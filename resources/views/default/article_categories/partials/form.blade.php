{!! Form::text('id')->type('hidden') !!}
{!! Form::text('parent_id')->type('hidden') !!}

<div class="form-group row">
    <label class="col">{{ Str::ucfirst(trans('ui-bootstrap5::word.is_visible_message')) }} </label>
    <div class="col-auto">

        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" name="is_visible">
            <label class="form-check-label"></label>
        </div>
    </div>
</div>

<div class="form-group breadcrumb-wrap">
    <label>{{ Str::title(trans('mpcs-article::word.attr.top_category'))}}</label>
    <div class="form-control">
    </div>
</div>

{!! Form::text('name', Str::ucfirst(trans('ui-bootstrap5::word.name')))->placeholder(Str::ucfirst(trans('ui-bootstrap5::word.name')))->wrapperAttrs(['class' => 'required']) !!}

{!! Form::select('type', Str::ucfirst(trans('mpcs-article::word.attr.list_type')), $types)->attrs(['data-type' => 'select-one'])->wrapperAttrs(['class' => 'required mb-3']) !!}

{!! Form::select('page_style', Str::ucfirst(trans('mpcs-article::word.attr.page_style')), $pageStyles)->attrs(['data-type' => 'select-one'])->wrapperAttrs(['class' => 'required mb-3']) !!}
{!! Form::text('per_page', Str::ucfirst(trans('mpcs-article::word.attr.per_page')))->type('number')->placeholder(Str::ucfirst(trans('mpcs-article::word.attr.per_page')))->wrapperAttrs(['class' => 'required']) !!}

{!! Form::text('description', Str::ucfirst(trans('ui-bootstrap5::word.description')))->placeholder(
    Str::ucfirst(trans('ui-bootstrap5::word.description')),
) !!}

<div class="form-group row">
    <label class="col">{{ Str::ucfirst(trans('mpcs-article::word.attr.is_thumbnail_size_message')) }} </label>
    <div class="col-auto">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" name="is_thumbnail_size">
            <label class="form-check-label"></label>
        </div>
    </div>
</div>
