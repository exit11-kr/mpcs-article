@extends(Bootstrap5::theme('layouts.crud'))

{{-- 브라우저 타이틀 --}}
@section('app_title', Str::ucfirst(trans('mpcs-article::menu.article_categories')))

{{-- 목록 서브타이틀 --}}
@section('crud_subtitle', Str::ucfirst(trans('mpcs-article::menu.article_categories')))


{{-- 검색폼 영역 --}}
@section('crud_search')
    @component(Bootstrap5::theme('components.aside_crud_search'))
        @include(Article::theme('article_categories.partials.search'))
    @endcomponent
@endsection


{{-- 헤더 버튼 그룹 --}}
@section('crud_button_group')
    <button id="btnSaveNestedOrder" type="button" class="btn btn-danger">
        <i class="mdi mdi-content-save-all mr-2"></i>
        <span class="d-none d-sm-inline"> {{ trans('ui-bootstrap5::word.button.save_order') }} </span>
    </button>
    <button class="btn-crud-create btn btn-primary font-weight-bold">
        <i class="mdi mdi-plus-circle-outline mr-1"></i>
       {{ Str::ucfirst(trans('ui-bootstrap5::word.create')) }}
    </button>
@endsection



{{-- CRUD 모달 폼 영역 --}}
@section('crud_form')
    {{-- 생성 --}}
    @component(Bootstrap5::theme('components.modal_crud_create'))
        {!! Form::open()->idPrefix('create_') !!}
        @include(Article::theme('article_categories.partials.form'))
        {!! Form::close() !!}
    @endcomponent

    {{-- 수정 --}}
    @component(Bootstrap5::theme('components.modal_crud_edit'))
        {!! Form::open()->idPrefix('edit_') !!}
        @include(Article::theme('article_categories.partials.form'))
        {!! Form::close() !!}
    @endcomponent

@endsection

@push('after_app_src_scripts')
    <script src="/vendor/mpcs/bootstrap5/js/crud.js"></script>
@endpush

{{-- CURD 스크립트 추가 --}}
@push('after_app_scripts')
    <script>
        window.CRUD.sortable(undefined, {
            chidrenOffsetElement: 'li',
        });
    </script>
@endpush
        