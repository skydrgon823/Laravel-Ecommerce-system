@if ($order->shipment && $order->shipment->note)
    <p><strong>{{ __('Delivery Notes:') }}</strong></p>
    <p style="color: #17a2b8 !important">{{ $order->shipment->note }}</p>
@endif
