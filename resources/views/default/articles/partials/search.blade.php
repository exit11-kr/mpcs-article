{!! Form::open()->get()->attrs(['class' => 'search-form-wrap'])->idPrefix('search_') !!}
<div class="row">
    <div class="col">
        {!! Form::text('type')->type('hidden') !!}
        {{-- {!! Form::select(
            'article_category_id',
            'Categories',
            Arr::prepend($article_categories, trans('mpcs-article::word.attr.categories'), ''),
        )->attrs(['data-type' => 'select-one']) !!} --}}
        {!! Form::text('title', 'Title')->placeholder(trans('mpcs-article::word.attr.title')) !!}
        {!! Form::text('html', 'Html')->placeholder(trans('mpcs-article::word.attr.content')) !!}
        @php
            use Carbon\Carbon;
            $today = Carbon::now()->format('Y-m-d');
            $oneMonthAgo = Carbon::today()->subMonth()->format('Y-m-d');
        @endphp
        <div class="input-group mb-3">
            <input data-type="data-picker-date" type="text" name="created_at__from" class="form-control"
                placeholder="{{ trans('ui-bootstrap5::word.created_at') }}"
                value="{{ old('created_at__from', $oneMonthAgo) }}" style="cursor: pointer;">
            <span class="input-group-text">~</span>
            <input data-type="data-picker-date" type="text" name="created_at__to" class="form-control"
                placeholder="{{ trans('ui-bootstrap5::word.created_at') }}"
                value="{{ old('created_at__to', $today) }}" style="cursor: pointer;">
        </div>
    </div>
</div>
<div class="row mt-1">
    <div class="col">
        <a href="{{ url()->current() }}"
            class="ui-btn ghost default w-100">{{ Str::title(trans('ui-bootstrap5::word.button.initialization')) }}</a>
    </div>
    <div class="col">
        <button type="button"
            class="ui-btn filled default w-100 btn-crud-search">{{ Str::title(trans('ui-bootstrap5::word.button.search')) }}</button>
    </div>
</div>
{!! Form::close() !!}
