<ul>
    @foreach($payments->payments as $payment)
        <li>
            @include('plugins/paypal::detail', compact('payment'))
        </li>
    @endforeach
</ul>
