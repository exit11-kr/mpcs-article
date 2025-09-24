{!! Form::text('id')->type('hidden') !!}
{!! Form::text('type')->type('hidden') !!}

<div class="row h-100">
    <div class="col-12 col-sm-4 col-lg-3">

        @if (class_exists('PushSse') && config('mpcspushsse.enabled') && $formType === 'create')
            <div class="form-group row">
                <label class="col">{{ Str::ucfirst(trans('ui-bootstrap5::word.is_push_notification_message')) }}
                </label>
                <div class="col-auto">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_push_notification">
                        <label class="form-check-label"></label>
                    </div>
                </div>
            </div>
        @endif

        <div class="form-group row">
            <label class="col">
                {{ Str::title(trans('mpcs-article::word.attr.is_header_notice')) }}
            </label>
            <div class="col-auto">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="is_header_notice" value="1">
                    <label class="form-check-label"></label>
                </div>
            </div>
        </div>

        {!! Form::select('article_category_id', trans('mpcs-article::word.attr.categories'), $article_categories)->attrs(['data-type' => 'select-one'])->placeholder(trans('mpcs-article::word.attr.categories')) !!}
        {!! Form::text('released_at', trans('mpcs-article::word.attr.released_at'))->attrs(['data-type' => 'data-picker-datetime'])->placeholder(trans('mpcs-article::word.attr.released_at'))->wrapperAttrs(['class' => 'required']) !!}


        @if (Article::useTag())
            {!! Form::text('tag_list', trans('mpcs-article::word.attr.tags'))->attrs(['data-type' => 'select-tag'])->placeholder(trans('mpcs-article::word.attr.tags')) !!}
        @endif

        {{-- 이미지 업로드 --}}
        @if (Article::useThumbnail())
            <div data-type="cropper-image-upload">
                <div class="row">
                    <label for="image" class="col">
                        {{ trans('mpcs-article::word.attr.thumbnail') }}
                        <button type="button" class="btn p-0" data-bs-container="body" data-bs-toggle="popover"
                            data-bs-placement="top" title="이미지 규격"
                            data-bs-content="{{ config('mpcsarticle.thumbnail.width') ?? 512 }}px * {{ config('mpcsarticle.thumbnail.height') ?? 512 }}px 이미지 사이즈에 최적화 되어 있습니다.">
                            <i class="mdi mdi-information"></i>
                        </button>
                    </label>
                    <div class="col-auto">
                        <button type="button" class="btn btn-info align-middle btn-select"
                            data-width="{{ $currentCategory->is_thumbnail_size ? config('mpcsarticle.thumbnail.width') ?? 512 : '' }}"
                            data-height="{{ $currentCategory->is_thumbnail_size ? config('mpcsarticle.thumbnail.height') ?? 512 : '' }}">
                            <i class="mdi mdi-cloud-upload me-1"></i>
                            {{ trans('ui-bootstrap5::word.button.choose_a_image_file') }}
                        </button>
                    </div>
                </div>
                <div class="row mt-2 justify-content-center">
                    <div class="col-auto" style="max-width: 250px">
                        <img src="{{ Bootstrap5::noImage() }}" class="cropped-image img-thumbnail"
                            data-default-src="{{ Bootstrap5::noImage() }}" data-crud-edit-name="small_image_url"
                            data-crud-edit-type="image" data-crud-edit-type="image">
                        <input type="file" class="d-none" accept=".png,.jpg,.gif" />
                        <input type="hidden" name="thumbnail" />
                    </div>
                </div>
            </div>
        @endif
        <div class="form-group mt-3">
            <label>{{ trans('mpcs-article::word.attr.attachments') }}</label>
            <ul class="list-group" data-crud-show-template-id="script-template-edit-article_files"
                data-crud-edit-type="template-html" data-crud-edit-name="article_files">
            </ul>
            <div data-type="file-upload-multiple" data-relation-name="article_files[]"
                data-endpoint-url="/{{ Core::getConfig('url_prefix') }}/article_files" data-item-limit="5"
                data-size-limit="10240000">
            </div>
        </div>

    </div>
    <div class="col-12 col-sm-8 col-lg">
        {!! Form::text('title', trans('mpcs-article::word.attr.title'))->placeholder(trans('mpcs-article::word.attr.title'))->wrapperAttrs(['class' => 'required']) !!}
        {!! Form::textarea('summary', trans('mpcs-article::word.attr.summary'))->placeholder(
            trans('mpcs-article::word.attr.summary'),
        ) !!}
        {{-- 에디터 --}}
        <div data-type="editor" style="height: calc(100vh - 320px)">
            <div class="editor-wrap" data-crud-edit-type="editor"
                data-crud-edit-name="{{ config('mpcsui.editor_is_mode_wysiwyg') ? 'html' : 'markdown' }}"></div>
            <input type="hidden" data-get-lang="markdown" name="markdown">
            <input type="hidden" data-get-lang="html" name="html">
        </div>
    </div>
</div>
