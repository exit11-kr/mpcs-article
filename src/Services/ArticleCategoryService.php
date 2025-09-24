<?php

namespace Mpcs\Article\Services;

use Mpcs\Core\Facades\Core;
use Mpcs\Article\Repositories\ArticleCategoryRepositoryInterface as RepositoryInterface;
use Mpcs\Core\Traits\ServiceTrait;

class ArticleCategoryService
{
    use ServiceTrait;

    protected $configs = [
        // resourceAbilityMap policy 추가 : 기본 crud 이외 액션 추가
        'resource_ability_map' => [
            'list' => 'viewAny',
        ],
        // resourceMethodsWithoutModels 모델을 사용하지 않는 policy method 추가
        'resource_methods_without_models' => [
            'list'
        ],
    ];

    public function __construct(RepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        return $this->repository->all();
    }

    /**
     * edit
     *
     * @param  mixed $model
     * @return void
     */
    public function edit($model)
    {
        return $this->repository->get($model);
    }

    /**
     * edit
     *
     * @param  mixed $model
     * @return void
     */
    public function show($model)
    {
        return $this->repository->get($model);
    }

    /**
     * store
     *
     * @return void
     */
    public function store()
    {
        $model = $this->repository->create();

        if ($model) {
            return $model;
        }

        Core::crudAbort();
    }

    /**
     * update
     *
     * @param  mixed $model
     * @return void
     */
    public function update($model)
    {
        if (request()->has('list_checked_visible')) {
            $model = $this->repository->updateVisible($model);
        } else {
            $model = $this->repository->update($model);
        }

        if ($model) {
            return $model;
        }

        Core::crudAbort();
    }

    /**
     * destroy
     *
     * @return void
     */
    public function destroy($model)
    {
        $id = $model->id;
        if ($this->repository->delete($model)) {
            return [
                'id' => $id,
            ];
        }
        Core::crudAbort();
    }

    /**
     * saveOrder
     *
     * @return void
     */
    public function saveOrder()
    {
        if ($this->repository->saveOrder()) {
            return [
                'title' => "순서 저장 성공",
                'message' => "메뉴 순서가 저장되었습니다.",
                'status' => 200,
            ];
        }

        Core::crudAbort();
    }
}
