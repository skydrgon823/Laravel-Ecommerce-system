<li>{{ $address->name }}</li>
@if ($address->phone)
    <li>
        <a href="tel:{{ $address->phone }}">
            <span><i class="fa fa-phone-square cursor-pointer mr5"></i></span>
            <span>{{ $address->phone }}</span>
        </a>
    </li>
@endif
<li>
    @if ($address->email)
        <div><a href="mailto:{{ $address->email }}">{{ $address->email }}</a></div>
    @endif
    @if ($address->address)
        <div>{{ $address->address }}</div>
    @endif
    @if ($address->city)
        <div>{{ $address->city_name }}</div>
    @endif
    @if ($address->state)
        <div>{{ $address->state_name }}</div>
    @endif
    @if ($address->country_name)
        <div>{{ $address->country_name }}</div>
    @endif
    @if (EcommerceHelper::isZipCodeEnabled() && $address->zip_code)
        <div>{{ $address->zip_code }}</div>
    @endif
    <div>
        <a target="_blank" class="hover-underline" href="https://maps.google.com/?q={{ $address->address }}, {{ $address->city_name }}, {{ $address->state_name }}, {{ $address->country_name }}@if (EcommerceHelper::isZipCodeEnabled()), {{ $address->zip_code }} @endif">{{ trans('plugins/ecommerce::order.see_on_maps') }}</a>
    </div>
</li>
