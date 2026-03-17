<?php

namespace Mpcs\Article\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Mpcs\Article\Facades\Article as Facade;
use Mpcs\Core\Traits\ControllerTrait;
use Mpcs\Article\Http\Requests\ArticleRequest as Request;
use Mpcs\Article\Services\ArticleService as Service;
use Mpcs\Article\Models\Article as Model;
use Mpcs\Core\Facades\Core;
use Mpcs\Article\Http\Resources\Article as Resource;
use Mpcs\Article\Http\Resources\ArticleCollection as ResourceCollection;

class ArticleController extends Controller
{
    use ControllerTrait;
    protected $service;

    public function __construct(Service $service)
    {
        $this->service = $service;
        $this->controllerTraitInit();
    }

    /**
     * index
     * @param  mixed $request
     * @return view
     */
    public function index(Request $request)
    {
        // 모델 조회시 옵션설정(페이징여부, 검색조건)
        $perPage = $request->input('_per_page', Facade::getPerPage());
        $this->addOption('_per_page', $perPage);
        return new ResourceCollection($this->service->index());
    }

    /**
     * edit
     *
     * @param  mixed $article
     * @return void
     */
    public function edit(Model $article)
    {
        return new Resource($this->service->edit($article));
    }

    /**
     * show
     *
     * @param  mixed $article
     * @return void
     */
    public function show(Model $article)
    {
        return new Resource($this->service->show($article));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return new Resource($this->service->store());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Model $article)
    {
        return new Resource($this->service->update($article));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Model $article)
    {
        return Core::responseJson($this->service->destroy($article));
    }
}
