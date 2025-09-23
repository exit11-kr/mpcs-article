<li data-crud-id="{{ $branch->id }}" data-parent-id="{{ $branch->parent_id }}" class="mt-1">
    <div class="d-flex justify-content-between align-items-top">
        <div class="col-auto">
            <button class="btn btn-sm btn-icon p-0 mt-1" data-bs-toggle="collapse" href="#branch_{{ $branch->id }}"
                aria-expanded="false">
                <i class="mdi {{ $branch->allChildren->count() > 0 ? 'mdi-arrow-collapse-vertical' : '' }}"></i>
            </button>
        </div>
        <div class="col-auto">
            <button type="button" class="btn btn-sm btn-icon p-0 mt-1 handle">
                <i class="mdi mdi-drag-vertical"></i>
            </button>
        </div>
        <div class="col p-1 border rounded">
            <div class="d-flex justify-content-between align-items-center">
                <div class="col-auto">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="list_checked_visible"
                            {{ $branch->is_visible ? 'checked' : '' }}>
                        <label class="form-check-label"></label>
                    </div>
                </div>
                <div class="col">
                    <div class="d-inline-block breadcrumb-wrap">
                        <span data-name="id" class="setting-id badge bg-dark mr-2">{{ $branch->id }}</span>
                        <span data-name="name" class="setting-name">{{ $branch->name }}</span>
                    </div>
                    @if ($branch->allChildren->count() > 0)
                        <small class="text-muted">
                            ({{ $branch->allChildren->count() }})
                        </small>
                    @endif
                    @if ($branch->description)
                        <button type="button" class="btn p-0" data-bs-container="body" data-bs-toggle="popover"
                            data-bs-placement="top" title="{{ $branch->name }}"
                            data-bs-content="{{ $branch->description }}">
                            <i class="mdi mdi-information"></i>
                        </button>
                    @endif
                </div>
                <div class="col-auto">
                    <button type="button" class="btn-crud-edit ui-btn small outline icon-only py-0 px-1 align-middle">
                        <i class="mdi mdi-pencil"></i>
                    </button>
                    <button type="button" class="btn-child-create btn btn-primary text-white py-0 px-1 align-middle">
                        <i class="mdi mdi-timeline-plus"></i>
                    </button>
                </div>
            </div>
            <ul class="p-1 collapse nested-sortable" style="list-style: none" id="branch_{{ $branch->id }}">
                @forelse ($branch->allChildren as $branch)
                    @include(Article::theme('article_categories.partials.branch'), $branch)
                @empty
                @endforelse
            </ul>
        </div>
    </div>
</li>
    