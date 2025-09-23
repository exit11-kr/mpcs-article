<li data-crud-id="{{ $branch->id }}" data-parent-id="{{ $branch->parent_id }}" class="mb-1">
    <div
        class="list-group-item d-flex justify-content-between align-items-center p-1 border rounded {{ $branch->id == $currentCategory->id ? 'active' : '' }}">
        @php
            // 자식 중 공개 체크된 것만...
            $isVisibleBranch = $branch->allChildren->where('is_visible', true);
            $isVisibleBranchIds = $isVisibleBranch->pluck('id')->toArray();
            $isShow = in_array($currentCategory->id, $isVisibleBranchIds);
        @endphp
        @if ($isVisibleBranch->count() > 0)
            <div class="col-auto">
                <button class="btn btn-light py-0 px-1 align-middle" data-bs-toggle="collapse"
                    href="#branch_{{ $branch->id }}" aria-expanded="false" title="펼침/접힘">
                    <i class="mdi mdi-arrow-collapse-vertical"></i>
                </button>
            </div>
        @endif
        <div class="col ps-2">
            <span class="badge bg-dark">{{ $branch->id }}</span>
            <span data-name="name" class="setting-name breadcrumb-wrap">{{ $branch->name }}</span>
            @if ($isVisibleBranch->count() == 0)
                <small class="{{ $branch->id == $currentCategory->id ? 'text-white' : 'text-muted' }}">({{ $branch->articles->count() }})</small>
            @endif
        </div>
        <div class="col-auto">
            <span class="badge bg-light text-dark">{{ $branch->type_str }}</span>
            @if ($isVisibleBranch->count() == 0)
                <a href="{{ route(Bootstrap5::routePrefix() . '.articles.index', ['article_category_id' => $branch->id]) }}"
                    class="btn btn-info text-white py-0 px-1">
                    <i class="mdi mdi-format-list-bulleted"></i>
                </a>
            @endif
        </div>
    </div>
    @if ($isVisibleBranch->count() > 0)
        <ul class="pt-1 collapse {{ $isShow ? 'show' : '' }}" style="list-style: none" id="branch_{{ $branch->id }}">
            @forelse ($isVisibleBranch as $branch)
                @include(Article::theme('articles.partials.branch_categories'), [
                    'branch' => $branch,
                    'currentCategoryId' => $currentCategory->id,
                ])
            @empty
            @endforelse
        </ul>
    @endif
</li>
