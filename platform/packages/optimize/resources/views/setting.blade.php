<div class="flexbox-annotated-section">
    <div class="flexbox-annotated-section-annotation">
        <div class="annotated-section-title pd-all-20">
            <h2>{{ trans('packages/optimize::optimize.settings.title') }}</h2>
        </div>
        <div class="annotated-section-description pd-all-20 p-none-t">
            <p class="color-note">{{ trans('packages/optimize::optimize.settings.description') }}</p>
        </div>
    </div>

    <div class="flexbox-annotated-section-content">
        <div class="wrapper-content pd-all-20">
            <div class="form-group mb-3">
                <label class="text-title-field"
                       for="optimize_page_speed_enable">{{ trans('packages/optimize::optimize.settings.enable') }}
                </label>
                <label class="me-2">
                    <input type="radio" name="optimize_page_speed_enable" class="setting-selection-option" data-target="#pagespeed-optimize-settings"
                           value="1"
                           @if (setting('optimize_page_speed_enable')) checked @endif>{{ trans('core/setting::setting.general.yes') }}
                </label>
                <label>
                    <input type="radio" name="optimize_page_speed_enable" class="setting-selection-option" data-target="#pagespeed-optimize-settings"
                           value="0"
                           @if (!setting('optimize_page_speed_enable')) checked @endif>{{ trans('core/setting::setting.general.no') }}
                </label>
            </div>

            <div id="pagespeed-optimize-settings" class="mb-4 border rounded-top rounded-bottom p-3 bg-light @if (!setting('optimize_page_speed_enable')) d-none @endif">
                <div class="form-group mb-3">
                    <input type="hidden" name="optimize_collapse_white_space" value="0">
                    <label>
                        <input type="checkbox" value="1" @if (setting('optimize_collapse_white_space', 0)) checked @endif name="optimize_collapse_white_space"> {{ trans('packages/optimize::optimize.collapse_white_space') }} </label>
                    {{ Form::helper(trans('packages/optimize::optimize.collapse_white_space_description')) }}
                </div>
                <div class="form-group mb-3">
                    <input type="hidden" name="optimize_elide_attributes" value="0">
                    <label>
                        <input type="checkbox" value="1" @if (setting('optimize_elide_attributes', 0)) checked @endif name="optimize_elide_attributes"> {{ trans('packages/optimize::optimize.elide_attributes') }} </label>
                    {{ Form::helper(trans('packages/optimize::optimize.elide_attributes_description')) }}
                </div>
                <div class="form-group mb-3">
                    <input type="hidden" name="optimize_inline_css" value="0">
                    <label>
                        <input type="checkbox" value="1" @if (setting('optimize_inline_css', 0)) checked @endif name="optimize_inline_css"> {{ trans('packages/optimize::optimize.inline_css') }} </label>
                    {{ Form::helper(trans('packages/optimize::optimize.inline_css_description')) }}
                </div>
                <div class="form-group mb-3">
                    <input type="hidden" name="optimize_insert_dns_prefetch" value="0">
                    <label>
                        <input type="checkbox" value="1" @if (setting('optimize_insert_dns_prefetch', 0)) checked @endif name="optimize_insert_dns_prefetch"> {{ trans('packages/optimize::optimize.insert_dns_prefetch') }} </label>
                    {{ Form::helper(trans('packages/optimize::optimize.insert_dns_prefetch_description')) }}
                </div>
                <div class="form-group mb-3">
                    <input type="hidden" name="optimize_remove_comments" value="0">
                    <label>
                        <input type="checkbox" value="1" @if (setting('optimize_remove_comments', 0)) checked @endif name="optimize_remove_comments"> {{ trans('packages/optimize::optimize.remove_comments') }} </label>
                    {{ Form::helper(trans('packages/optimize::optimize.remove_comments_description')) }}
                </div>
                <div class="form-group">
                    <input type="hidden" name="optimize_trim_urls" value="0">
                    <label>
                        <input type="checkbox" value="1" @if (setting('optimize_trim_urls', 0)) checked @endif name="optimize_trim_urls"> {{ trans('packages/optimize::optimize.trim_urls') }} </label>
                    {{ Form::helper(trans('packages/optimize::optimize.trim_urls_description')) }}
                </div>

                <div class="form-group">
                    <input type="hidden" name="optimize_remove_quotes" value="0">
                    <label>
                        <input type="checkbox" value="1" @if (setting('optimize_remove_quotes', 0)) checked @endif name="optimize_remove_quotes"> {{ trans('packages/optimize::optimize.remove_quotes') }} </label>
                    {{ Form::helper(trans('packages/optimize::optimize.remove_quotes_description')) }}
                </div>

                <div class="form-group">
                    <input type="hidden" name="optimize_defer_javascript" value="0">
                    <label>
                        <input type="checkbox" value="1" @if (setting('optimize_defer_javascript', 0)) checked @endif name="optimize_defer_javascript"> {{ trans('packages/optimize::optimize.defer_javascript') }} </label>
                    {{ Form::helper(trans('packages/optimize::optimize.defer_javascript_description')) }}
                </div>
            </div>
        </div>
    </div>
</div>
