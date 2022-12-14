<?php

namespace Mpcs\Article\Repositories;

use Mpcs\Core\Facades\Core;
use Mpcs\Bootstrap5\Facades\Bootstrap5;

use Mpcs\Article\Models\ArticleFile as Model;
use Mpcs\Core\Traits\RepositoryTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Exception;

class ArticleFileRepository implements ArticleFileRepositoryInterface
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
            'item_list' => ['id', 'name', 'is_visible'],
            'attribute_name' => trans('mpcs-article::word.attributes.article_file')
        ];
        $model = $this->model::search($enableRequestParam);
        return $this->getSelectFormatter($model, $isApiSelect, $apiSelectParams);
    }

    // Get all instances of model
    public function all()
    {
        $model = $this->model::search()->sortable();
        return $model->with($this->model::getDefaultLoadRelations())->paging();
    }

    // create a new record in the database
    public function create()
    {
        DB::beginTransaction();
        try {
            $articleFile = $this->request['upload_file'] ?? null;
            if (is_file($articleFile)) {
                $filename = round(microtime(true) * 1000) . "_" . Str::random(10) . "." . $articleFile->clientExtension();
                $this->model->original_name = $articleFile->getClientOriginalName();
                $this->model->name = $filename;
                $this->model->size = $articleFile->getSize();
                $this->model->mime = $articleFile->getClientMimeType();
                $this->model->save();
                $this->model->upload_disk->putFileAs($this->model->dir_path, $articleFile, $filename, 'public');

                // 첨부파일이 이미지파일일 경우 썸네일 형성
                if (getimagesize($articleFile)) {
                    Bootstrap5::generateThumb($this->model->root_dir, $this->model->name);
                }
            }
            DB::commit();
        } catch (Exception $e) {
            /* DB 트랜젝션 롤 */
            DB::rollback();
            throw $e;
        }

        return $this->model->loadRelations();
    }

    // update record in the database
    public function update($model)
    {
    }

    // show the record with the given id
    public function get($model)
    {
        return $model->loadRelations();
    }

    // remove record from the database
    public function delete($model)
    {
        return $model->delete();
    }

    /**
     * download
     *
     * @param  mixed $model
     * @return void
     */
    public function download($model)
    {
        $disk = $model->uploadDisk;

        if ($disk->exists($model->file_path)) {
            $model->download_count = $model->download_count + 1;
            $model->save();

            $headers = [
                'Content-Type' => $model->mime,
            ];

            return $disk->download($model->file_path, $model->caption, $headers);
        }

        abort(404, Core::trans('errors.message.not_found_resource'));
    }
}
