{!! Form::open()
->get()
->attrs(['class' => 'search-form-wrap'])
->idPrefix('search_') !!}
<input type="text" style="display:none;"/>
<div class="row">
    <div class="col">
        {!! Form::text('_nested_name', 'Name')->placeholder(trans('ui-bootstrap5::word.name'))->wrapperAttrs(['class' => 'mb-2']) !!}
    </div>
</div>
<div class="row mt-1">
    <div class="col">
        <a href="{{ url()->current() }}" class="btn btn-secondary d-block">{{ trans('ui-bootstrap5::word.initialization') }}</a>
    </div>
    <div class="col">
        <button type="button" class="btn btn-primary d-block w-100 btn-crud-search">{{ trans('ui-bootstrap5::word.search') }}</button>
    </div>
</div>
{!! Form::close() !!}
