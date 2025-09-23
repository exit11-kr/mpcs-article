<div id="asideNavCategory" class="aside-category panel-wrap mb-2">
    <div class="panel-heading d-flex align-items-start justify-content-between">
        <h4 class="h4">
            {{ Str::title(trans('mpcs-article::word.attr.categories')) }}
        </h4>
        <button class="btn btn-search-expend" type="button" data-bs-toggle="collapse"
            data-bs-target="#articleCategoriesWrap">
            <i class="mdi mdi-chevron-up"></i>
        </button>
    </div>
    <div id="articleCategoriesWrap" class="panel-body p-1 collapse show">
        <ul class="nested-list-group">
            @forelse($categories as $branch)
                @include(Article::theme('articles.partials.branch_categories'), [
                    'branch' => $branch,
                    'currentCategoryId' => $currentCategory->id,
                ])
            @empty
            @endforelse
        </ul>
    </div>
</div>
