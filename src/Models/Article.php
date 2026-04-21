<?php

namespace Mpcs\Article\Models;

use Illuminate\Database\Eloquent\Model;
use Mpcs\Core\Traits\ModelTrait;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentTaggable\Taggable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Mpcs\Bootstrap5\Traits\ResponsiveImageTrait;
use Illuminate\Support\Str;
use Mpcs\Core\Facades\Core;

class Article extends Model
{
    use SoftDeletes, Sluggable, Taggable, ModelTrait, ResponsiveImageTrait;

    protected $table = 'articles';
    protected $guarded = ['id'];
    protected static $m_params = [
        'default_load_relations' => ['articleCategory', 'articleFiles', 'tags', 'user'],
        'column_maps' => [
            // date : {컬럼명}
            'from' => 'released_at',
            'to' => 'released_at',
        ]
    ];
    // $sortable 정의시 정렬기능을 제공할 필드는 필수 기입
    public $sortable = ['id', 'title', 'view_count', 'released_at', 'user_id'];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i',
        'updated_at' => 'datetime:Y-m-d H:i',
        'deleted_at' => 'datetime:Y-m-d H:i',
        'released_at' => 'datetime:Y-m-d H:i',
        'status_released' => 'boolean',
    ];

    protected $appends = [
        'status_released',
        'thumb_image_url',
        'small_image_url',
        'medium_image_url',
        'large_image_url',
        'image_aspect_ratio',
        'preview_text',
    ];

    private $uploadDisk;
    private $imageRootDir;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->uploadDisk = Storage::disk('upload');
        $this->imageRootDir = 'articles';
    }

    /**
     * boot
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        static::setMemberParams(self::$m_params);
    }


    /**
     * articleCategories
     *
     * @return void
     */
    public function articleCategory()
    {
        return $this->belongsTo(ArticleCategory::class, 'article_category_id');
    }

    /**
     * articleFiles
     *
     * @return void
     */
    public function articleFiles()
    {
        return $this->hasMany(ArticleFile::class, 'article_id');
    }

    /**
     * user
     *
     * @return void
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }


    /**
     * getPreviewTextAttribute
     *
     * @return void
     */
    public function getPreviewTextAttribute()
    {
        $preview_text = $this->summary ?? strip_tags($this->html);
        return Str::limit(strip_tags($preview_text), 100, ' ...');
    }

    /**
     * getStatusReleasedAttribute
     *
     * @return void
     */
    public function getStatusReleasedAttribute()
    {
        $now = Carbon::now()->format('Y-m-d H:i:s');
        $released = $this->attributes['released_at'];
        return ($released && $released <= $now);
    }

    /**
     * getViewCountAttribute
     *
     * @return void
     */
    public function getViewCountAttribute()
    {
        return $this->attributes['view_count'] ?? 0;
    }

    /**
     * setReleasedAtAttribute
     *
     * @param  mixed $date
     * @return void
     */
    public function setReleasedAtAttribute($date)
    {
        $this->attributes['released_at'] = empty($date) ? null : Carbon::parse($date);
    }

    /**
     * scopeReleased
     *
     * @param  mixed $query
     * @return void
     */
    public function scopeReleased($query)
    {
        $now = Carbon::now()->format('Y-m-d H:i:s');
        return $query->where('released_at', '<=', $now);
    }

    /**
     * getUploadDiskAttribute
     *
     * @return void
     */
    public function getUploadDiskAttribute()
    {
        return $this->uploadDisk;
    }

    /**
     * sluggable
     *
     * @return void
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title',
                'method' => function ($string, $separator) {
                    return preg_replace('/[^0-9a-zA-Z가-힣ㄱ-ㅎㅏ]+/i', $separator, $string);
                },
                'onUpdate'  => true
            ]
        ];
    }

    /**
     * responsiveImagable
     *
     * @return void
     */
    public function responsiveImagable()
    {
        return $this->thumbnail ? $this->attributes['thumbnail'] : null;
    }

    /**
     * scopeCustom
     *
     * @return void
     */
    public function scopeCustom($query, $params)
    {
        $user = Core::user();
        $isReleased = false;

        if (isset($params['__released'])) {
            $released = $params['__released'];
            if ($released === "true") {
                $isReleased = true;
            }
        }

        // 게시판 수정/관리 권한 없을 시 릴리즈된 글만 조회
        if (!($user && ($user->isAdministrator() || ($user->cans(['article.edit', 'article.manage']) === true)))) {
            $isReleased = true;
        }

        if ($isReleased) {
            $query->released();
        }

        if (request('sort') === 'status_released') {
            $direction = request('direction', 'asc') === 'asc' ? 'asc' : 'desc';
            $now = Carbon::now()->format('Y-m-d H:i:s');

            $query->orderBy('is_header_notice', 'desc')->orderByRaw("
            CASE
                WHEN released_at IS NOT NULL AND released_at <= ? THEN 1
                ELSE 0
            END {$direction}
        ", [$now]);
        }
    }
}
