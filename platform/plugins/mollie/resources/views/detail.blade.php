@if ($payment)
    <p>{{ trans('plugins/payment::payment.payment_id') }}: {{ $payment->id }}</p>
    <p>{{ trans('plugins/payment::payment.amount') }}: {{ $payment->amount->value }} {{ $payment->amount->currency }}</p>
    <p>{{ trans('plugins/payment::payment.amount_remaining') }}: {{ $payment->amountRemaining->value }} {{ $payment->amountRemaining->currency }}</p>
    <p>{{ trans('plugins/payment::payment.method_name') }}: {{ $payment->method }}</p>
    <p>{{ trans('plugins/payment::payment.status') }}: {{ $payment->status }}</p>
    <p>{{ trans('core/base::tables.created_at') }}: {{ now()->parse($payment->createdAt) }}</p>
    <p>{{ trans('plugins/payment::payment.paid_at') }}: {{ now()->parse($payment->paidAt) }}</p>

    @if ($payment->amount->value - $payment->amountRemaining->value)
        @php
            $amountRefunded = '';
            if ((float) $payment->amountRefunded->value) {
                $amountRefunded = ' (' . $payment->amountRefunded->value . ' ' . $payment->amountRefunded->currency . ')';
            }
            $refunds = $payment->refunds();
        @endphp
        <br />
        <h6 class="alert-heading">{{ trans('plugins/payment::payment.refunds.title') . $amountRefunded }}</h6>
        <hr class="m-0 mb-4">
        @foreach ($refunds as $refund)
            <div class="alert alert-warning" role="alert">
                <p>{{ trans('plugins/payment::payment.refunds.id') }}: {{ htmlspecialchars($refund->id) }}</p>
                <p>{{ trans('plugins/payment::payment.amount') }}: {{ $refund->amount->value }} {{ $refund->amount->currency }}</p>
                <p>{{ trans('plugins/payment::payment.refunds.description') }}: {{ $refund->description }}</p>
                <p>{{ trans('plugins/payment::payment.refunds.status') }}: {{ $refund->status }}</p>
                <p>{{ trans('plugins/payment::payment.refunds.create_time') }}: {{ now()->parse($refund->createdAt) }}</p>
            </div>
            <br />
        @endforeach
    @endif

    @include('plugins/payment::partials.view-payment-source')
@endif
