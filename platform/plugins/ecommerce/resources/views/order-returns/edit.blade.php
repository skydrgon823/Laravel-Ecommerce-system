@extends(BaseHelper::getAdminMasterLayoutTemplate())
@section('content')
    <div class="max-width-1036">
        <div class="flexbox-grid">
            <div class="flexbox-content">
                <div class="wrapper-content mb20">
                    <div class="pd-all-20">
                        <label class="title-product-main text-no-bold">{{ trans('plugins/ecommerce::order.order_information') }} </label>
                    </div>
                    <div class="pd-all-10-20 border-top-title-main">
                        <div class="clearfix">
                            <div class="table-wrapper p-none mb20 ps-relative">
                                <table class="table-normal">
                                    <tbody>
                                    @php
                                        $returnRequest->load(['items.product']);
                                        $totalAmount = $returnRequest->items->sum(function ($item) {
                                            return $item->qty * $item->price;
                                        });
                                    @endphp
                                    @foreach ($returnRequest->items as $returnRequestItem)
                                        @php
                                            $product = $returnRequestItem->product;
                                        @endphp
                                        @if ($product && $product->original_product)
                                            <tr>
                                                <td class="width-60-px min-width-60-px">
                                                    <div class="wrap-img"><img class="thumb-image thumb-image-cartorderlist" src="{{ RvMedia::getImageUrl($product->image ?: $product->original_product->image, 'thumb', false, RvMedia::getDefaultImage()) }}" alt="{{ $product->name }}"></div>
                                                </td>
                                                <td class="pl5 p-r5">
                                                    @if ($product->original_product->id)
                                                        <a target="_blank" href="{{ route('products.edit', $product->original_product->id) }}" title="{{ $returnRequestItem->product_name }}">{{ $returnRequestItem->product_name }}</a>
                                                    @else
                                                        <span>{{ $returnRequestItem->product_name }}</span>
                                                    @endif
                                                    <p>
                                                        <small>{{ $product->variation_attributes }}</small>
                                                    </p>
                                                    @if ($product->sku)
                                                        <p>{{ trans('plugins/ecommerce::order.sku') }} : <span>{{ $product->sku }}</span></p>
                                                    @endif
                                                </td>
                                                <td class="pl5 p-r5 width-100-px min-width-100-px text-end">
                                                    <span>{{ format_price($returnRequestItem->price) }}</span>
                                                </td>
                                                <td class="pl5 p-r5 width-20-px min-width-20-px text-center"> x</td>
                                                <td class="pl5 p-r5 width-30-px min-width-30-px text-start">
                                                    <span class="item-quantity text-danger">{{ $returnRequestItem->qty }}</span>
                                                </td>
                                                <td class="pl5 p-r5 width-100-px min-width-130-px text-end">{{ format_price($returnRequestItem->price * $returnRequestItem->qty) }}</td>
                                            </tr>
                                        @endif
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="flexbox-grid-default">
                            <div class="flexbox-auto-content"></div>
                            <div class="flexbox-auto-content">
                                <div class="table-wrapper">
                                    <table class="table-normal table-none-border">
                                        <tbody>
                                        <tr>
                                            <td colspan="3" class="text-end p-sm-r">
                                                {{ trans('plugins/ecommerce::order.total_return_amount') }}:
                                            </td>
                                            <td class="text-end p-r5">{{ format_price($totalAmount) }}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" class="text-end p-sm-r">
                                                {{ trans('plugins/ecommerce::order.status') }}:
                                            </td>
                                            <td class="text-end p-r5">{!! BaseHelper::clean($returnRequest->return_status->toHtml()) !!}</td>
                                        </tr>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @if($returnRequest->return_status != \Botble\Ecommerce\Enums\OrderReturnStatusEnum::COMPLETED || $returnRequest->return_status != \Botble\Ecommerce\Enums\OrderReturnStatusEnum::CANCELED )
                    <div class="wrapper-content mb20">
                        <div class="pd-all-20 p-none-b">
                            <label class="title-product-main text-no-bold">{{ trans('plugins/ecommerce::order.change_return_order_status') }}</label>
                        </div>
                        <div class="pd-all-10-20">
                            <form action="{{ route('order_returns.edit', $returnRequest->id) }}" method="POST">
                                <label class="text-title-field">{{ trans('plugins/ecommerce::order.status') }}</label>
                                {!! Form::customSelect('return_status', \Botble\Ecommerce\Enums\OrderReturnStatusEnum::labels(),$returnRequest->return_status, ['class'=> 'form-control']) !!}
                                <div class="mt15 mb15 text-end">
                                    <button type="button" class="btn btn-primary btn-update-order">{{ trans('plugins/ecommerce::order.update') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif
            </div>

            <div class="flexbox-content flexbox-right">
                <div class="wrapper-content mb20">
                    <div class="next-card-section p-none-b">
                        <div class="flexbox-grid-default">
                            <div class="flexbox-auto-content">
                                <label class="title-product-main"><strong>{{ trans('plugins/ecommerce::order.customer_label') }}</strong></label>
                            </div>
                            <div class="flexbox-auto-left">
                                <img class="width-30-px radius-cycle" width="40" src="{{ $returnRequest->customer->id ? $returnRequest->customer->avatar_url : $returnRequest->order->address->avatar_url }}" alt="{{ $returnRequest->order->address->name }}">
                            </div>
                        </div>
                    </div>
                    <div class="next-card-section border-none-t">
                        <ul class="ws-nm">
                            <li class="overflow-ellipsis">
                                <div class="mb5">
                                    <a class="hover-underline text-capitalize" href="#">{{ $returnRequest->customer->name ?: $returnRequest->order->address->name }}</a>
                                </div>
                                @if ($returnRequest->customer->id)
                                    <div><i class="fas fa-inbox mr5"></i><span>{{ $returnRequest->customer->orders()->count() }}</span> {{ trans('plugins/ecommerce::order.orders') }}</div>
                                @endif
                                <ul class="ws-nm text-infor-subdued">
                                    <li class="overflow-ellipsis"><a class="hover-underline" href="mailto:{{ $returnRequest->customer->email ?: $returnRequest->order->address->email }}">{{ $returnRequest->customer->email ?: $returnRequest->order->address->email }}</a></li>
                                    @if ($returnRequest->customer->id)
                                        <li><div>{{ trans('plugins/ecommerce::order.have_an_account_already') }}</div></li>
                                    @else
                                        <li><div>{{ trans('plugins/ecommerce::order.dont_have_an_account_yet') }}</div></li>
                                    @endif
                                </ul>
                            </li>
                        </ul>
                    </div>
                    <div class="next-card-section">
                        <ul class="ws-nm">
                            <li class="clearfix">
                                <div class="flexbox-grid-default">
                                    <div class="flexbox-auto-content">
                                        <label class="title-text-second"><strong>{{ trans('plugins/ecommerce::order.address') }}</strong></label>
                                    </div>
                                </div>
                            </li>
                            <li class="text-infor-subdued mt15">
                                <div>{{ $returnRequest->order->address->name }}</div>
                                <div>
                                    <a href="tel:{{ $returnRequest->order->address->phone }}">
                                        <span><i class="fa fa-phone-square cursor-pointer mr5"></i></span>
                                        <span>{{ $returnRequest->order->address->phone }}</span>
                                    </a>
                                </div>
                                <div>
                                    <div>{{ $returnRequest->order->address->address }}</div>
                                    <div>{{ $returnRequest->order->address->city_name }}</div>
                                    <div>{{ $returnRequest->order->address->state_name }}</div>
                                    <div>{{ $returnRequest->order->address->country_name }}</div>
                                    <div>
                                        <a target="_blank" class="hover-underline" href="https://maps.google.com/?q={{ $returnRequest->full_address }}">{{ trans('plugins/ecommerce::order.see_maps') }}</a>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="next-card-section">
                        <ul class="ws-nm">
                            <li class="clearfix">
                                <div class="flexbox-grid-default">
                                    <div class="flexbox-auto-content">
                                        <label class="title-text-second"><strong>{{ trans('plugins/ecommerce::order.return_reason') }}</strong></label>
                                    </div>
                                </div>
                            </li>
                            <li class="text-infor-subdued mt15 text-danger">
                                {{ $returnRequest->reason->label() }}
                            </li>
                        </ul>
                    </div>
                </div>

            </div>
            </div>
        </div>
    </div>
@stop
