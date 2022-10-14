<table class="table mb0 box-table-shipping input-shipping-sync-wrapper box-table-shipping-item-{{ $rule ? $rule->id : 0 }}">
    <tbody>
        @if ($rule)
            <tr class="border-top-shipping-detail">
                <td>
                    <a class="hover-underline text-start btn-trigger-show-shipping-detail label-rule-item-name" href="#" title="{{ $rule->name }}">{{ $rule->name }}</a>
                </td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>
                    <span class="rule-to-value-missing @if ($rule->to) hidden @endif">{{ trans('plugins/ecommerce::shipping.greater_than') }}</span>
                    <span><span class="from-value-label">{{ human_price_text($rule->from, get_application_currency()) }}</span><span class="unit-item-label">{{ $rule->type === 'base_on_price' ? get_application_currency()->symbol : ecommerce_weight_unit() }}</span></span>&nbsp;

                    <span class="rule-to-value-wrap @if (!$rule->to) hidden @endif">- <span><span class="to-value-label">{{ human_price_text($rule->to, get_application_currency()) }}</span> <span class="unit-item-label">{{ $rule->type === 'base_on_price' ? get_application_currency()->symbol : ecommerce_weight_unit() }}</span></span></span>
                </td>
                <td class="text-end">
                    <label><span><span class="rule-price-item">{{ human_price_text($rule->price ?? 0, get_application_currency()) }}</span> {{ get_application_currency()->symbol }}</span> </label>
                </td>
            </tr>
        @endif
        <tr class="shipping-detail-information">
            <td colspan="2" class="border-none p-none-important">
                <form action="{{ $rule ? route('shipping_methods.region.rule.update', $rule->id) : route('shipping_methods.region.rule.create') }}">
                    @if ($rule)
                    <div class="panel panel-default bg-aliceBlue content-box hidden">
                        <div class="panel-body">
                    @endif
                            <div class="form-group mb-3">
                                <label class="text-title-field">{{ trans('plugins/ecommerce::shipping.shipping_rule_name') }}</label>
                                <input type="text" name="name" class="next-input input-sync-text-item" data-target=".label-rule-item-name" value="{{ $rule ? $rule->name : null }}">
                            </div>
                            <div class="flexbox-grid-default">
                                <div class="flexbox-content-no-padding">
                                    <div class="form-group mb-3">
                                        <label class="text-title-field">{{ trans('plugins/ecommerce::shipping.type') }}</label>
                                        <div class="ui-select-wrapper">
                                            <select class="select-rule-type ui-select" name="type">
                                                <option value="base_on_price" data-text="{{ trans('plugins/ecommerce::shipping.base_on_price') }}" data-unit="{{ get_application_currency()->symbol }}" @if ($rule && $rule->type === 'base_on_price') selected @endif>{{ trans('plugins/ecommerce::shipping.base_on_price') }}</option>
                                                <option value="base_on_weight" data-text="{{ trans('plugins/ecommerce::shipping.base_on_weight', ['unit' => ecommerce_weight_unit()]) }}" data-unit="{{ ecommerce_weight_unit() }}" @if ($rule && $rule->type === 'base_on_weight') selected @endif>{{ trans('plugins/ecommerce::shipping.base_on_weight', ['unit' => ecommerce_weight_unit()]) }}</option>
                                            </select>
                                            <svg class="svg-next-icon svg-next-icon-size-16">
                                                <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#select-chevron"></use>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                                <div class="flexbox-content-no-padding pl15">
                                    <div class="form-group mb-3">
                                        <label class="text-title-field rule-from-to-label">{{ $rule && $rule->type === 'base_on_weight' ? trans('plugins/ecommerce::shipping.base_on_weight', ['unit' => ecommerce_weight_unit()]) : trans('plugins/ecommerce::shipping.base_on_price') }}</label>
                                        <div class="flexbox-grid-default flexbox-align-items-center">
                                            <div class="flexbox-auto-content">
                                                <div class="next-input--stylized">
                                                    <span class="next-input-add-on next-input__add-on--before unit-item-price-label @if ($rule && $rule->type !== 'base_on_price') hidden @endif">{{ get_application_currency()->symbol }}</span>
                                                    <input type="text" name="from" class="next-input input-mask-number next-input--invisible input-sync-item" data-target=".from-value-label" value="{{ $rule ? $rule->from : 0 }}">
                                                </div>
                                            </div>
                                            <div class="flexbox-auto-left pl5 p-r5">
                                                <span class="inline">—</span>
                                            </div>
                                            <div class="flexbox-auto-content">
                                                <div class="next-input--stylized">
                                                    <span class="next-input-add-on next-input__add-on--before unit-item-price-label @if ($rule && $rule->type !== 'base_on_price') hidden @endif">{{ get_application_currency()->symbol }}</span>
                                                    <input type="text" name="to" class="next-input input-mask-number next-input--invisible input-sync-item input-to-value-field" data-target=".to-value-label" value="{{ $rule && $rule->to != 0 ? $rule->to : null }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="flexbox-grid-default">
                                <div class="flexbox-content-no-padding">
                                    <div class="form-group mb-3">
                                        <label class="text-title-field">{{ trans('plugins/ecommerce::shipping.shipping_fee') }}</label>
                                        <div class="next-input--stylized">
                                            <span class="next-input-add-on next-input__add-on--before">{{ get_application_currency()->symbol }}</span>
                                            <input type="text" name="price" class="next-input input-mask-number next-input--invisible input-sync-item base-price-rule-item" data-thousands-separator="{{ EcommerceHelper::getThousandSeparatorForInputMask() }}" data-decimal-separator="{{ EcommerceHelper::getDecimalSeparatorForInputMask() }}" data-target=".rule-price-item" value="{{ $rule ? $rule->price : 0 }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="flexbox-content-no-padding pl15"></div>
                            </div>
                        </div>
                        @if ($rule)
                            <div class="panel-footer overflow-hidden">
                                    <div class="float-start">
                                        <button class="btn btn-secondary btn-destroy btn-confirm-delete-price-item-modal-trigger" data-name="{{ $rule->name }}" data-id="{{ $rule->id }}">{{ trans('plugins/ecommerce::shipping.delete') }}</button>
                                    </div>
                                    <div class="float-end inline">
                                        <button type="button" class="btn btn-secondary click-cancel">{{ trans('plugins/ecommerce::shipping.cancel') }}</button>
                                        <button class="btn btn-primary btn-save-rule">{{ trans('plugins/ecommerce::shipping.save') }}</button>
                                    </div>
                                </div>
                            </div>
                        @endif
                </form>
            </td>
        </tr>
    </tbody>
</table>
