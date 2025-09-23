<table class="table table-sm table-borderless table-hover align-middle mb-0 w-100 crud-table-responsive">
    <thead class="thead-light">
        <tr class="border-bottom">
            <th class="text-center min-width-rem-4">
                @sortablelink('id', 'ID')
            </th>
            <th class="text-center min-width-rem-4">
                {{ trans('mpcs-article::word.attr.is_header_notice') }}
            </th>
            <th class="text-center min-width-rem-4">
                {{ trans('mpcs-article::word.attr.status') }}
            </th>
            <th class="text-center">
                @sortablelink('title', trans('mpcs-article::word.attr.title'))
            </th>
            <th class="text-center min-width-rem-6">
                {{ trans('mpcs-article::word.attr.writer') }}
            </th>
            <th class="text-center min-width-rem-8">
                @sortablelink('view_count', trans('mpcs-article::word.attr.view_count'))
            </th>
            <th class="text-center min-width-rem-10">
                @sortablelink('released_at', trans('mpcs-article::word.attr.released_at'))
            </th>
            <th class="text-center min-width-rem-6">
                {{ trans('ui-bootstrap5::word.actions') }}
            </th>
        </tr>
    </thead>
    <tbody class="crud-list">
        @forelse($datas as $data)
            <tr data-crud-id="{{ $data->id }}" class="border-bottom">
                <td data-header-title='ID' class="text-md-center">
                    {{ $data->id }}
                </td>
                <td data-header-title='{{ trans('mpcs-article::word.attr.is_header_notice') }}' class="text-start">
                    <span class="badge bg-{{ $data->is_header_notice ? 'info' : '' }}">
                        {{ $data->is_header_notice ? trans('mpcs-article::word.attr.is_header_notice') : '' }}
                    </span>
                </td>
                <td data-header-title='{{ trans('mpcs-article::word.attr.status') }}' class="text-start">
                    <span class="badge bg-{{ $data->status_released ? 'success' : 'warning' }}">
                        {{ $data->status_released ? trans('mpcs-article::word.attr.released') : trans('mpcs-article::word.attr.nonrelease') }}
                    </span>
                </td>
                <td data-header-title='{{ trans('mpcs-article::word.attr.title') }}' class="text-start">
                    <div class="row no-gutters align-items-center">
                        @if (Article::useThumbnail() && $data->thumbnail)
                            <div class="col-auto mr-2">
                                <button type="button" class="btn p-0" data-bs-toggle="popover" data-bs-html="true"
                                    data-bs-content='<img class="img-fluid"
                                    src="{{ $data->small_image_url }}" alt="{{ $data->title }}">'>
                                    <img class="img-thumbnail" style="width: 40px; height: 40px;"
                                        src="{{ $data->thumb_image_url }}" alt="{{ $data->title }}">
                                </button>
                            </div>
                        @endif
                        <div class="col">
                            @if (isset($data->articleCategories))
                                <p data-header-title='categories' class="mb-1">
                                    @forelse($data->articleCategories as $category)
                                        <span class="badge bg-info">
                                            {{ $category->nested_str }}
                                        </span>
                                    @empty
                                    @endforelse
                                </p>
                            @endif
                            <p class="mb-0 text-md-center">
                                <span> {{ $data->title }} </span> <small
                                    class="d-md-none">({{ $data->view_count }})</small>
                                @if ($data->summary)
                                    <button type="button" class="btn p-0" data-bs-container="body"
                                        data-bs-toggle="popover" data-bs-placement="top" title="{{ $data->title }}"
                                        data-bs-content="{{ $data->summary }}">
                                        <i class="mdi mdi-information"></i>
                                    </button>
                                @endif
                            </p>
                            @if (Article::useTag() && isset($data->tags))
                                <p data-header-title='tags' class="mt-1 mb-0">
                                    @forelse($data->tags as $tag)
                                        <span class="badge rounded-pill border border-light text-dark">
                                            #{{ $tag->name }}
                                        </span>
                                    @empty
                                    @endforelse
                                </p>
                            @endif
                        </div>
                    </div>
                </td>
                <td data-header-title='{{ trans('mpcs-article::word.attr.writer') }}' class="text-center">
                    {{ $data->user->name }}
                </td>
                <td data-header-title='{{ trans('mpcs-article::word.attr.view_count') }}' class="text-center">
                    {{ number_format($data->view_count) }}
                </td>
                <td data-header-title='{{ trans('mpcs-article::word.attr.released_at') }}' class="">
                    {{ $data->released_at }}
                </td>
                <td class="crud-td-actions text-end text-md-center">
                    @can('view', $data)
                        <button class="btn-crud-show ui-btn small outline icon-only"
                            title="{{ trans('ui-bootstrap5::word.button.show') }}">
                            <i class="mdi mdi-eye"></i>
                        </button>
                    @endcan
                    @can('delete', $data)
                        <button class="btn-crud-delete ui-btn small outline icon-only"
                            title="{{ trans('ui-bootstrap5::word.button.delete') }}">
                            <i class="mdi mdi-trash-can"></i>
                        </button>
                    @endcan
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="crud-td-actions text-center">
                    {{ trans('ui-bootstrap5::word.crud.none_data') }}
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

@isset($datas)
    <div class="mt-3 d-flex justify-content-center">
        {{ $datas->render(Bootstrap5::theme('partials.paginator')) }}
    </div>
@endisset
