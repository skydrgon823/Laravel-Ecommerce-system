<strong>{{ trans('plugins/payment::payment.payment_details') }}: </strong>
@include('plugins/stripe::detail', compact('payment'))
