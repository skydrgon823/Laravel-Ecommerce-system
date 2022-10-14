@if ($refund)
    @php
        $dataRequest = Arr::get($refund, '_data_request');
        $refundRefId = Arr::get($refund, 'refund_ref_id');
    @endphp
    <div class="alert alert-warning" role="alert" >
        <div class="d-flex justify-content-between">
            <p>{{ trans('plugins/payment::payment.refunds.id') }}: <strong>{{ $refundRefId }}</strong></p>
            @if ($refundRefId)
                <a class="get-refund-detail d-block"
                    data-element="#{{ $refundRefId }}"
                    data-url="{{ route('payment.refund-detail', [$paymentModel->id, $refundRefId]) }}">
                    <i class="fas fa-sync-alt"></i>
                </a>
            @endif
        </div>
        <p>{{ trans('plugins/payment::payment.amount') }}: {{ Arr::get($dataRequest, 'refund_amount') }} {{ Arr::get($dataRequest, 'currency') }}</p>
        <p>{{ trans('plugins/payment::payment.refunds.status') }}: {{ Arr::get($refund, 'status') }}</p>
        @if (Arr::has($refund, 'initiated_on'))
            <p>{{ trans('core/base::tables.created_at') }}: {{ now()->parse(Arr::get($refund, 'initiated_on')) }}</p>
        @endif
        @if (Arr::has($refund, 'refunded_on'))
            <p>{{ trans('plugins/payment::payment.refunds.refunded_at') }}: {{ now()->parse(Arr::get($refund, 'refunded_on')) }}</p>
        @endif
        @if ($errorReason = Arr::get($refund, 'errorReason'))
            <p class="text-danger">{{ trans('plugins/payment::payment.refunds.error_message') }}: {{ $errorReason }}</p>
        @endif
    </div>
    <br />
@endif
