@extends(BaseHelper::getAdminMasterLayoutTemplate())

@section('content')
    <div class="container">
        <div class="row">
            <div class="group flexbox-annotated-section">
                <div class="col-md-3">
                    <h4>{{ trans('plugins/payment::payment.payment_methods') }}</h4>
                    <p>{{ trans('plugins/payment::payment.payment_methods_description') }}</p>
                </div>
                <div class="col-md-9">
                    @php do_action(BASE_ACTION_META_BOXES, 'top', new \Botble\Payment\Models\Payment) @endphp

                    <div class="wrapper-content pd-all-20">
                        {!! Form::open(['route' => 'payments.settings']) !!}
                        <div class="form-group mb-3">
                            <label for="default_payment_method">{{ trans('plugins/payment::payment.default_payment_method') }}</label>
                            {!! Form::customSelect('default_payment_method', \Botble\Payment\Enums\PaymentMethodEnum::labels(), setting('default_payment_method', Botble\Payment\Enums\PaymentMethodEnum::COD)) !!}
                        </div>
                        <button type="button" class="btn btn-info button-save-payment-settings">{{ trans('core/base::forms.save') }}</button>
                        {!! Form::close() !!}
                    </div>

                    <br>

                    {!! apply_filters(PAYMENT_METHODS_SETTINGS_PAGE, null) !!}

                    <div class="table-responsive">
                     <table class="table payment-method-item">

                            <tbody><tr class="border-pay-row">
                                <td class="border-pay-col"><i class="fa fa-theme-payments"></i></td>
                                <td style="width: 20%;">
                                    <span>{{ trans('plugins/payment::payment.payment_methods') }}</span>
                                </td>
                                <td class="border-right">
                                    <ul>
                                        <li>
                                            <p>{{ trans('plugins/payment::payment.payment_methods_instruction') }}</p>
                                        </li>
                                    </ul>
                                </td>
                            </tr>

                            @php $codStatus = setting('payment_cod_status'); @endphp
                            <tr class="bg-white">
                                <td colspan="3">
                                    <div class="float-start" style="margin-top: 5px;">
                                        <div class="payment-name-label-group">
                                            @if ($codStatus != 0)<span class="payment-note v-a-t">{{ trans('plugins/payment::payment.use') }}:</span>@endif <label class="ws-nm inline-display method-name-label">{{ setting('payment_cod_name', \Botble\Payment\Enums\PaymentMethodEnum::COD()->label()) }}</label>
                                        </div>
                                    </div>
                                    <div class="float-end">
                                        <a class="btn btn-secondary toggle-payment-item edit-payment-item-btn-trigger @if ($codStatus == 0) hidden @endif">{{ trans('plugins/payment::payment.edit') }}</a>
                                        <a class="btn btn-secondary toggle-payment-item save-payment-item-btn-trigger @if ($codStatus == 1) hidden @endif">{{ trans('plugins/payment::payment.settings') }}</a>
                                    </div>
                                </td>
                            </tr>
                            <tr class="paypal-online-payment payment-content-item hidden">
                                <td class="border-left" colspan="3">
                                    {!! Form::open() !!}
                                    {!! Form::hidden('type', 'cod', ['class' => 'payment_type']) !!}
                                    <div class="col-sm-12 mt-2">
                                        <div class="well bg-white">
                                            <div class="form-group mb-3">
                                                <label class="text-title-field" for="payment_cod_name">{{ trans('plugins/payment::payment.method_name') }}</label>
                                                <input type="text" class="next-input" name="payment_cod_name" id="payment_cod_name" data-counter="400" value="{{ setting('payment_cod_name', \Botble\Payment\Enums\PaymentMethodEnum::COD()->label()) }}">
                                            </div>
                                            <div class="form-group mb-3">
                                                <label class="text-title-field" for="payment_cod_description">{{ trans('plugins/payment::payment.payment_method_description') }}</label>
                                                {!! Form::editor('payment_cod_description', setting('payment_cod_description')) !!}
                                            </div>
                                            {!! apply_filters(PAYMENT_METHOD_SETTINGS_CONTENT, null, 'cod') !!}
                                        </div>
                                    </div>
                                    <div class="col-12 bg-white text-end">
                                        <button class="btn btn-warning disable-payment-item @if ($codStatus == 0) hidden @endif" type="button">{{ trans('plugins/payment::payment.deactivate')  }}</button>
                                        <button class="btn btn-info save-payment-item btn-text-trigger-save @if ($codStatus == 1) hidden @endif" type="button">{{ trans('plugins/payment::payment.activate') }}</button>
                                        <button class="btn btn-info save-payment-item btn-text-trigger-update @if ($codStatus == 0) hidden @endif" type="button">{{ trans('plugins/payment::payment.update') }}</button>
                                    </div>
                                    {!! Form::close() !!}
                                </td>
                            </tr>
                            </tbody>

                            @php $bankTransferStatus = setting('payment_bank_transfer_status'); @endphp
                            <tbody class="border-none-t">
                            <tr class="bg-white">
                                <td colspan="3">
                                    <div class="float-start" style="margin-top: 5px;">
                                        <div class="payment-name-label-group">
                                            @if ($bankTransferStatus != 0) <span class="payment-note v-a-t">{{ trans('plugins/payment::payment.use') }}:</span>@endif <label class="ws-nm inline-display method-name-label">{{ setting('payment_bank_transfer_name', \Botble\Payment\Enums\PaymentMethodEnum::BANK_TRANSFER()->label()) }}</label>
                                        </div>
                                    </div>
                                    <div class="float-end">
                                        <a class="btn btn-secondary toggle-payment-item edit-payment-item-btn-trigger @if ($bankTransferStatus == 0) hidden @endif">{{ trans('plugins/payment::payment.edit') }}</a>
                                        <a class="btn btn-secondary toggle-payment-item save-payment-item-btn-trigger @if ($bankTransferStatus == 1) hidden @endif">{{ trans('plugins/payment::payment.settings') }}</a>
                                    </div>
                                </td>
                            </tr>
                            <tr class="paypal-online-payment payment-content-item hidden">
                                <td class="border-left" colspan="3">
                                    {!! Form::open() !!}
                                    {!! Form::hidden('type', 'bank_transfer', ['class' => 'payment_type']) !!}
                                    <div class="col-sm-12 mt-2">
                                        <div class="well bg-white">
                                            <div class="form-group mb-3">
                                                <label class="text-title-field" for="payment_bank_transfer_name">{{ trans('plugins/payment::payment.method_name') }}</label>
                                                <input type="text" class="next-input" name="payment_bank_transfer_name" id="payment_bank_transfer_name" data-counter="400" value="{{ setting('payment_bank_transfer_name', \Botble\Payment\Enums\PaymentMethodEnum::BANK_TRANSFER()->label()) }}">
                                            </div>
                                            <div class="form-group mb-3">
                                                <label class="text-title-field" for="payment_bank_transfer_description">{{ trans('plugins/payment::payment.payment_method_description') }}</label>
                                                {!! Form::editor('payment_bank_transfer_description', setting('payment_bank_transfer_description')) !!}
                                            </div>
                                            {!! apply_filters(PAYMENT_METHOD_SETTINGS_CONTENT, null, 'bank_transfer') !!}
                                        </div>
                                    </div>
                                    <div class="col-12 bg-white text-end">
                                        <button class="btn btn-warning disable-payment-item @if ($bankTransferStatus == 0) hidden @endif" type="button">{{ trans('plugins/payment::payment.deactivate') }}</button>
                                        <button class="btn btn-info save-payment-item btn-text-trigger-save @if ($bankTransferStatus == 1) hidden @endif" type="button">{{ trans('plugins/payment::payment.activate') }}</button>
                                        <button class="btn btn-info save-payment-item btn-text-trigger-update @if ($bankTransferStatus == 0) hidden @endif" type="button">{{ trans('plugins/payment::payment.update') }}</button>
                                    </div>
                                    {!! Form::close() !!}
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @php do_action(BASE_ACTION_META_BOXES, 'main', new \Botble\Payment\Models\Payment) @endphp
            <div class="group">
                <div class="col-md-3">

                </div>
                <div class="col-md-9">
                    @php do_action(BASE_ACTION_META_BOXES, 'advanced', new \Botble\Payment\Models\Payment) @endphp
                </div>
            </div>
        </div>
    </div>
    {!! Form::modalAction('confirm-disable-payment-method-modal', trans('plugins/payment::payment.deactivate_payment_method'), 'info', trans('plugins/payment::payment.deactivate_payment_method_description'), 'confirm-disable-payment-method-button', trans('plugins/payment::payment.agree')) !!}
@stop
