{!! Form::open(['url' => route('ecommerce.store-locators.update-primary-store')]) !!}
    <div class="next-form-section">
        <div class="next-form-grid">
            <div class="next-form-grid-cell">
                <label class="text-title-field">{{ trans('plugins/ecommerce::store-locator.primary_store_is') }}</label>
                {!!
                    Form::customSelect('primary_store_id', $storeLocators->pluck('name', 'id')->all(), ($storeLocators->where('is_primary', true)->first() ? $storeLocators->where('is_primary', true)->first()->id : null), [
                        'class' => 'form-control',
                    ])
                !!}
            </div>
        </div>
    </div>
{!! Form::close() !!}
