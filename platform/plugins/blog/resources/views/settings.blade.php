<div class="flexbox-annotated-section">
    <div class="flexbox-annotated-section-annotation">
        <div class="annotated-section-title pd-all-20">
            <h2>{{ trans('plugins/blog::base.settings.title') }}</h2>
        </div>
        <div class="annotated-section-description pd-all-20 p-none-t">
            <p class="color-note">{{ trans('plugins/blog::base.settings.description') }}</p>
        </div>
    </div>

    <div class="flexbox-annotated-section-content">
        <div class="wrapper-content pd-all-20">
            <div class="form-group mb-3">
                <div class="form-group mb-3">
                    <input type="hidden" name="blog_post_schema_enabled" value="0">
                    <label>
                        <input type="checkbox" value="1" @if (setting('blog_post_schema_enabled', 1)) checked @endif name="blog_post_schema_enabled">
                        {{ trans('plugins/blog::base.settings.enable_blog_post_schema') }}
                    </label>
                    <span class="help-ts">{{ trans('plugins/blog::base.settings.enable_blog_post_schema_description') }}</span>
                </div>
                <div class="form-group">
                    <label class="text-title-field"
                           for="blog_post_schema_type">{{ trans('plugins/blog::base.settings.schema_type') }}
                    </label>
                    <div class="ui-select-wrapper">
                        <select name="blog_post_schema_type" class="ui-select" id="blog_post_schema_type">
                            @foreach(['NewsArticle', 'News', 'Article', 'BlogPosting'] as $type)
                                <option value="{{ $type }}" @if (setting('blog_post_schema_type', 'NewsArticle') === $type) selected @endif>
                                    {{ $type }}
                                </option>
                            @endforeach
                        </select>
                        <svg class="svg-next-icon svg-next-icon-size-16">
                            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#select-chevron"></use>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
