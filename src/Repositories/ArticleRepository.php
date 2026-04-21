<?php

namespace Mpcs\Article\Repositories;

use Exception;
use Mpcs\Core\Facades\Core;
use Mpcs\Bootstrap5\Facades\Bootstrap5;
use Mpcs\Article\Models\Article as Model;
use Mpcs\Article\Models\ArticleFile;
use Illuminate\Support\Facades\DB;
use Mpcs\Core\Traits\RepositoryTrait;
use Illuminate\Support\Carbon;

class ArticleRepository implements ArticleRepositoryInterface
{
    use RepositoryTrait;

    public function __construct(Model $model)
    {
        $this->repositoryInit($model);
    }

    // Get all instances of model
    public function select($enableRequestParam = false, $isApiSelect = false)
    {
        $apiSelectParams = [
            // id, name [,'is_visible']
            'item_list' => ['id', 'title'],
            'attribute_name' => trans('mpcs-article::word.attr.article')
        ];
        $model = $this->model::search($enableRequestParam);

        return $this->getSelectFormatter($model, $isApiSelect, $apiSelectParams);
    }

    // Get all instances of model
    public function all()
    {
        $sort = request('sort');
        $direction = request('direction', 'asc') === 'asc' ? 'asc' : 'desc';
        $model = $this->model::search();

        if ($sort === 'status_released') {
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $model = $model
                ->orderBy('is_header_notice', 'desc')
                ->orderByRaw("
                CASE
                    WHEN released_at IS NOT NULL AND released_at <= ? THEN 1
                    ELSE 0
                END {$direction}
            ", [$now]);
        } else {
            $model = $model->orderBy('is_header_notice', 'desc')->sortable();
        }

        return $model->with($this->model::getDefaultLoadRelations())->paging()->onEachSide(2);
    }

    // create a new record in the database
    public function create()
    {
        DB::beginTransaction();
        try {
            $this->model->article_category_id = $this->request['article_category_id'];
            $this->model->title = $this->request['title'];
            $this->model->summary = $this->request['summary'] ?? null;
            $this->model->markdown = $this->request['markdown'] ?? null;
            $this->model->html = $this->request['html'] ?? null;
            $this->model->is_header_notice = $this->request['is_header_notice'] ?? 0;
            $this->model->released_at = $this->request['released_at'];
            $this->model->user_id = Core::user()->id;

            /* 이미지 Base64 방식 저장 */
            $requestImage = $this->request['thumbnail'] ?? null;
            if ($requestImage) {
                if (!empty($requestImage)) {
                    if (substr($requestImage, 0, 10) === "data:image") {
                        $base64ToFile = Bootstrap5::base64ToFile($requestImage, $this->model->upload_disk, $this->model->image_root_dir);
                        if ($base64ToFile) {
                            $this->model->thumbnail = $base64ToFile;
                        }
                    } else {
                        $this->model->thumbnail = $requestImage;
                    }
                    Bootstrap5::generateThumb($this->model->image_root_dir, $this->model->thumbnail);
                }
            }

            if ($this->model->save()) {
                $this->model->retag($this->request['tag_list'] ?? []);

                if (isset($this->request['article_files'])) {
                    ArticleFile::whereIn('id', $this->request['article_files'])->update(['article_id' => $this->model->id]);
                }
            }

            DB::commit();
        } catch (Exception $e) {
            /* DB 트랜젝션 롤 */
            DB::rollback();
            throw $e;
        }

        // PushSse 클래스가 있는지 확인
        if (class_exists('Mpcs\PushSse\Facades\PushSse') && config('mpcspushsse.enabled')) {
            // PushSse 클래스가 있으면 푸시 전송

            $category = $this->model->articleCategory;
            $categoryName = $category->name ?? null;

            $pushMessage = $this->model->title;
            if ($categoryName) {
                $pushMessage = "[" . $categoryName . "] " . $pushMessage;
            }

            $is_push_notification = $this->request['is_push_notification'] ?? false;
            $is_push_notification = $is_push_notification ? true : false;
            // $uidxs = [];
            // array_push($uidxs, Core::user()->uidx);
            \PushSse::sseQueue($pushMessage, trans('mpcs-article::word.attr.push_title'), [
                'is_private' => false,
                'notification' => $is_push_notification,
                'pushed_at' => $this->model->released_at,
                'params' => json_encode([
                    'id'    => $this->model->id,
                    'title' => $this->model->title,
                    'summary' => $this->model->summary,
                    'article_category_id' => $this->model->article_category_id,
                    'released_at' => $this->model->released_at,
                ]),
                // 'uuids' => $uidxs,
            ], "pushSseArticle");
        }

        return $this->model->loadRelations();
    }

    // update record in the database
    public function update($model)
    {
        DB::beginTransaction();
        try {
            $model->article_category_id = $this->request['article_category_id'];
            $model->title = $this->request['title'];
            $model->summary = $this->request['summary'] ?? $model->summary;
            $model->markdown = $this->request['markdown'] ?? $model->content;
            $model->html = $this->request['html'] ?? $model->html;
            $model->is_header_notice = $this->request['is_header_notice'] ?? 0;
            $model->released_at = $this->request['released_at'];
            $model->user_id = Core::user()->id;

            /* 이미지 Base64 방식 저장 */
            $requestImage = $this->request['thumbnail'] ?? $model->thumbnail;
            if ($requestImage) {
                if (substr($requestImage, 0, 10) === "data:image") {
                    $base64ToFile = Bootstrap5::base64ToFile($requestImage, $model->upload_disk, $model->image_root_dir);
                    if ($base64ToFile) {
                        $model->thumbnail = $base64ToFile;
                    }
                } else {
                    $model->thumbnail = $requestImage;
                }
                Bootstrap5::generateThumb($model->image_root_dir, $model->thumbnail);
            }

            if ($model->save()) {
                $model->retag($this->request['tag_list'] ?? []);

                if (isset($this->request['delete_article_files'])) {
                    ArticleFile::whereIn('id', $this->request['delete_article_files'])->update(['article_id' => null]);
                }

                if (isset($this->request['article_files'])) {
                    ArticleFile::whereIn('id', $this->request['article_files'])->update(['article_id' => $model->id]);
                }
            }

            DB::commit();
        } catch (Exception $e) {
            /* DB 트랜젝션 롤 */
            DB::rollback();
            throw $e;
        }

        return $model->loadRelations();
    }

    // show the record with the given id
    public function get($model)
    {
        // 관리자가 아닐 경우, 뷰카운트 추가
        if (!Core::user()) {
            $model->increment('view_count');
        }
        return $model->loadRelations();
    }

    // remove record from the database
    public function delete($model)
    {
        return $model->delete();
    }
}
