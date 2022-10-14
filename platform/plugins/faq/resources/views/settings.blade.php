<div class="flexbox-annotated-section">
    <div class="flexbox-annotated-section-annotation">
        <div class="annotated-section-title pd-all-20">
            <h2>{{ trans('plugins/faq::faq.settings.title') }}</h2>
        </div>
        <div class="annotated-section-description pd-all-20 p-none-t">
            <p class="color-note">{{ trans('plugins/faq::faq.settings.description') }}</p>
        </div>
    </div>

    <div class="flexbox-annotated-section-content">
        <div class="wrapper-content pd-all-20">
            <div class="form-group mb-3">
                <div class="form-group mb-3">
                    <input type="hidden" name="enable_faq_schema" value="0">
                    <label>
                        <input type="checkbox"  value="1" @if (setting('enable_faq_schema', 0)) checked @endif name="enable_faq_schema">
                        {{ trans('plugins/faq::faq.settings.enable_faq_schema') }}
                    </label>
                    <span class="help-ts">{{ trans('plugins/faq::faq.settings.enable_faq_schema_description') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
