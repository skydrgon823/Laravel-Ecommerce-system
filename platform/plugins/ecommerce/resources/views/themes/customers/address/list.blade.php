@extends(EcommerceHelper::viewPath('customers.master'))

@section('content')
    <h2 class="customer-page-title">{{ __('Address books') }}</h2>
    <div class="dashboard-address">
        <a class="add-address" href="{{ route('customer.address.create') }}"><i class="fa fa-plus"></i>
            <span>{{ __('Add a new address') }}</span></a>
        <div class="row">

            @foreach($addresses as $address)
                <div class="col-md-12 col-sm-12">
                    <div
                        class="panel panel-default dashboard-address-item @if ($address->is_default) is-address-default @endif">
                        <div class="panel-body">
                            <p class="name">{{ $address->name }} @if ($address->is_default) <span
                                    class="address-default">{{ __('Default') }}</span> @endif
                            </p>
                            <p class="address"><i class="fa fa-address-book"
                                                  aria-hidden="true"></i> {{ $address->address }}, {{ $address->city_name }}
                                , {{ $address->state_name }}, {{ $address->country_name }}@if (EcommerceHelper::isZipCodeEnabled()), {{ $address->zip_code }} @endif</p>
                            <p class="phone"><i class="fa fa-phone" aria-hidden="true"></i> {{ $address->phone }}</p>
                            <div class="action">
                                <div class="edit-customer-address">
                                    <a class="text-info"
                                       href="{{ route('customer.address.edit', $address->id) }}">{{ __('Edit') }}</a> |
                                    <a class="text-danger btn-trigger-delete-address"
                                       href="#" data-url="{{ route('customer.address.destroy', $address->id) }}">{{ __('Remove') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
    </div>

    <div class="modal fade" id="confirm-delete-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xs">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><strong>{{ __('Confirm delete') }}</strong></h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>{{ __('Do you really want to delete this address?') }}</p>
                </div>
                <div class="modal-footer">
                    <button class="btn--custom btn--rounded btn--outline btn--sm" type="button" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <button class="btn--custom btn--rounded btn--outline btn--sm btn-confirm-delete btn-outline-danger" type="submit">{{ __('Delete') }}</button>
                </div>
            </div>
        </div>
    </div><!-- /.modal -->
@endsection
