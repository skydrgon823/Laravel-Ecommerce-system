<div class="form-group mb-3">
    <label class="text-title-field" for="payment_cod_minimum_amount">{{ trans('plugins/ecommerce::ecommerce.setting.payment_method_cod_minimum_amount', ['currency' => get_application_currency()->title]) }}</label>
    {!! Form::number('payment_cod_minimum_amount', setting('payment_cod_minimum_amount', 0), ['class' => 'form-control']) !!}
</div>
