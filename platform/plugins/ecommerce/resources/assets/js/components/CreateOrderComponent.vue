<template>
    <div class="flexbox-grid no-pd-none">
        <div class="flexbox-content">
            <div class="wrapper-content">
                <div class="pd-all-20">
                    <label class="title-product-main text-no-bold">{{ __('order.order_information')}}</label>
                </div>
                <div class="pd-all-10-20 border-top-title-main">
                    <div class="clearfix">
                        <div class="table-wrapper p-none mb20 ps-relative z-index-4" v-if="child_products.length">
                            <table class="table-normal">
                                <tbody>
                                <tr v-for="variant in child_products">
                                    <td class="width-60-px min-width-60-px">
                                        <div class="wrap-img vertical-align-m-i">
                                            <img class="thumb-image" :src="variant.image_url"
                                                 :alt="variant.product_name">
                                        </div>
                                    </td>
                                    <td class="pl5 p-r5 min-width-200-px">
                                        <a class="hover-underline pre-line" :href="variant.product_link"
                                           target="_blank">{{ variant.product_name }}</a>
                                        <p class="type-subdued"
                                           v-if="variant.variation_items && variant.variation_items.length">
                                            <span v-for="(productItem, index) in variant.variation_items">
                                                {{ productItem.attribute_title }}
                                                <span v-if="index !== variant.variation_items.length - 1">/</span>
                                            </span>
                                        </p>
                                    </td>
                                    <td class="pl5 p-r5 width-100-px min-width-100-px text-center">
                                        <div class="dropup dropdown-priceOrderNew">
                                            <div class="inline_block dropdown">
                                                <a class="wordwrap hide-print">{{ variant.price }} {{ currency }}</a>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="pl5 p-r5 width-20-px min-width-20-px text-center"> x</td>
                                    <td class="pl5 p-r5 width-100-px min-width-100-px">
                                        <input class="next-input p-none-r" v-model="variant.select_qty" type="number"
                                               min="1" @change="handleChangeQuantity()">
                                    </td>
                                    <td class="pl5 p-r5 width-100-px min-width-100-px text-center">{{ variant.price }}
                                        {{ currency }}
                                    </td>
                                    <td class="pl5 p-r5 text-end width-20-px min-width-20-px">
                                        <a href="#" @click="handleRemoveVariant($event, variant)">
                                            <svg class="svg-next-icon svg-next-icon-size-12">
                                                <use xmlns:xlink="http://www.w3.org/1999/xlink"
                                                     xlink:href="#next-remove"></use>
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="box-search-advance product">
                            <div>
                                <input type="text" class="next-input textbox-advancesearch product"
                                       :placeholder="__('order.search_or_create_new_product')"
                                       @click="loadListProductsAndVariations()"
                                       @keyup="handleSearchProduct($event.target.value)">
                            </div>
                            <div class="panel panel-default"
                                 v-bind:class="{ active: list_products, hidden : hidden_product_search_panel }">
                                <div class="panel-body">
                                    <div class="box-search-advance-head" v-b-modal.add-product-item>
                                        <img width="30"
                                             src="/vendor/core/plugins/ecommerce/images/next-create-custom-line-item.svg" alt="icon">
                                        <span class="ml10">{{ __('order.create_a_new_product') }}</span>
                                    </div>
                                    <div class="list-search-data">
                                        <div class="has-loading" v-show="loading">
                                            <i class="fa fa-spinner fa-spin"></i>
                                        </div>
                                        <ul class="clearfix" v-show="!loading">
                                            <li v-for="product_item in list_products.data"
                                                v-bind:class="{ 'item-selectable' : !product_item.variations.length, 'item-not-selectable' : product_item.variations.length }"
                                                v-on="!product_item.variations.length ? { click : () => selectProductVariant(product_item) } : {}">
                                                <div class="wrap-img inline_block vertical-align-t float-start">
                                                    <img class="thumb-image"
                                                         :src="product_item.image_url"
                                                         :title="product_item.name" :alt="product_item.name">
                                                </div>
                                                <label class="inline_block ml10 mt10 ws-nm"
                                                       style="width:calc(100% - 50px);">{{
                                                    product_item.name }}
                                                    <span v-if="!product_item.variations.length">
                                                        <span v-if="product_item.is_out_of_stock" class="text-danger"><small>&nbsp;({{ __('order.out_of_stock') }})</small></span>
                                                        <span v-if="!product_item.is_out_of_stock && product_item.quantity > 0"><small>&nbsp;({{ product_item.quantity }} {{ __('order.products_available') }})</small></span>
                                                    </span>
                                                </label>
                                                <div v-if="product_item.variations.length">
                                                    <div class="clear"></div>
                                                    <ul>
                                                        <li class="clearfix product-variant"
                                                            v-for="variation in product_item.variations"
                                                            @click="selectProductVariant(product_item, variation)"
                                                            v-if="variation.variation_items.length">
                                                            <a class="color_green float-start">
                                                                <span v-for="(productItem, index) in variation.variation_items">
                                                                    {{ productItem.attribute_title }}
                                                                    <span v-if="index !== variation.variation_items.length - 1">/</span>
                                                                </span>
                                                            </a>
                                                            <span v-if="variation.is_out_of_stock" class="text-danger"><small>&nbsp;({{ __('order.out_of_stock') }})</small></span>
                                                            <span v-if="!variation.is_out_of_stock && variation.quantity > 0"><small>&nbsp;({{ variation.quantity }} {{ __('order.products_available') }})</small></span>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </li>
                                            <li v-if="list_products.data.length === 0">
                                                <span>{{ __('order.no_products_found')}}</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="panel-footer"
                                     v-if="list_products.next_page_url || list_products.prev_page_url">
                                    <div class="btn-group float-end">
                                        <button type="button"
                                                @click="loadListProductsAndVariations((list_products.prev_page_url ? list_products.current_page - 1 : list_products.current_page), true)"
                                                v-bind:class="{ 'btn btn-secondary': list_products.current_page !== 1, 'btn btn-secondary disable': list_products.current_page === 1}"
                                                :disabled="list_products.current_page === 1">
                                            <svg role="img"
                                                 class="svg-next-icon svg-next-icon-size-16 svg-next-icon-rotate-180">
                                                <use xmlns:xlink="http://www.w3.org/1999/xlink"
                                                     xlink:href="#next-chevron"></use>
                                            </svg>
                                        </button>
                                        <button type="button"
                                                @click="loadListProductsAndVariations((list_products.next_page_url ? list_products.current_page + 1 : list_products.current_page), true)"
                                                v-bind:class="{ 'btn btn-secondary': list_products.next_page_url, 'btn btn-secondary disable': !list_products.next_page_url }"
                                                :disabled="!list_products.next_page_url">
                                            <svg role="img" class="svg-next-icon svg-next-icon-size-16">
                                                <use xmlns:xlink="http://www.w3.org/1999/xlink"
                                                     xlink:href="#next-chevron"></use>
                                            </svg>
                                        </button>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="pd-all-10-20 p-none-t">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group mb-3">
                                <label class="text-title-field" for="txt-note">{{ __('order.note') }}</label>
                                <textarea class="ui-text-area textarea-auto-height" id="txt-note" rows="2"
                                          :placeholder="__('order.note_for_order')" v-model="note"></textarea>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="table-wrap">
                                <table class="table-normal table-none-border table-color-gray-text text-end">
                                    <tbody>
                                    <tr>
                                        <td class="color-subtext">{{ __('order.amount') }}</td>
                                        <td class="pl10">{{ child_sub_amount | formatPrice }} {{ currency }}</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <a href="#" v-b-modal.add-discounts class="hover-underline">
                                                <span v-if="!has_applied_discount"><i class="fa fa-plus-circle"></i> {{ __('order.add_discount') }}</span>
                                                <span v-if="has_applied_discount">{{ __('order.discount')}}</span>
                                            </a>
                                            <p class="mb0 font-size-12px"
                                               v-if="child_discount_description && has_applied_discount">{{
                                                child_discount_description }}</p>
                                        </td>
                                        <td class="pl10">{{ has_applied_discount ? child_discount_amount : 0 | formatPrice }} {{ currency }}</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <a href="#" v-b-modal.add-shipping class="hover-underline">
                                                <span v-if="!child_is_selected_shipping"><i
                                                        class="fa fa-plus-circle"></i> {{ __('order.add_shipping_fee') }}</span>
                                                <span v-if="child_is_selected_shipping">{{ __('order.shipping') }}</span>
                                            </a>
                                            <p class="mb0 font-size-12px" v-if="child_shipping_method_name">{{
                                                child_shipping_method_name }}</p>
                                        </td>
                                        <td class="pl10">{{ child_shipping_amount | formatPrice }} {{ currency }}</td>
                                    </tr>
                                    <tr class="text-no-bold">
                                        <td>{{ __('order.total_amount') }}</td>
                                        <td class="pl10">{{ child_total_amount | formatPrice }} {{ currency }}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="pd-all-10-20 border-top-color">
                    <div class="row">
                        <div class="col-12 col-sm-6 col-md-12 col-lg-6">
                            <div class="flexbox-grid-default mt5 mb5">
                                <div class="flexbox-auto-left p-r10">
                                    <i class="fa fa-credit-card fa-1-5 color-blue"></i>
                                </div>
                                <div class="flexbox-auto-content">
                                    <div class="text-upper ws-nm">{{ __('order.confirm_payment_and_create_order') }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-md-12 col-lg-6 text-end">
                            <button class="btn btn-primary" v-b-modal.make-paid
                                    :disabled="!child_product_ids.length">{{ __('order.paid') }}
                            </button>
                            <button class="btn btn-primary ml15" v-b-modal.make-pending
                                    :disabled="!child_product_ids.length || child_total_amount === 0">{{ __('order.pay_later') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="flexbox-content flexbox-right">
            <div class="wrapper-content mb20">
                <div v-if="!child_customer_id || !child_customer">
                    <div class="next-card-section">
                        <div class="flexbox-grid-default mb15">
                            <div class="flexbox-auto-content">
                                <label class="title-product-main">{{ __('order.customer_information') }}</label>
                            </div>
                        </div>
                        <div class="findcustomer">
                            <div class="box-search-advance customer">
                                <div>
                                    <input type="text" class="next-input textbox-advancesearch customer"
                                           @click="loadListCustomersForSearch()"
                                           @keyup="handleSearchCustomer($event.target.value)"
                                           :placeholder="__('order.search_or_create_new_customer')">
                                </div>
                                <div class="panel panel-default"
                                     v-bind:class="{ active: customers, hidden : hidden_customer_search_panel }">
                                    <div class="panel-body">
                                        <div class="box-search-advance-head" v-b-modal.add-customer>
                                            <div class="flexbox-grid-default flexbox-align-items-center">
                                                <div class="flexbox-auto-40">
                                                    <img width="30"
                                                         src="/vendor/core/plugins/ecommerce/images/next-create-customer.svg" alt="icon">
                                                </div>
                                                <div class="flexbox-auto-content-right">
                                                    <span>{{ __('order.create_new_customer') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="list-search-data">
                                            <div class="has-loading" v-show="loading">
                                                <i class="fa fa-spinner fa-spin"></i>
                                            </div>
                                            <ul class="clearfix" v-show="!loading">
                                                <li class="row" v-for="customer in customers.data"
                                                    @click="selectCustomer(customer)">
                                                    <div class="flexbox-grid-default flexbox-align-items-center">
                                                        <div class="flexbox-auto-40">
                                                            <div class="wrap-img inline_block vertical-align-t radius-cycle">
                                                                <img class="thumb-image radius-cycle"
                                                                     :src="customer.avatar_url" :alt="customer.name">
                                                            </div>
                                                        </div>
                                                        <div class="flexbox-auto-content-right">
                                                            <div class="overflow-ellipsis">{{ customer.name }}</div>
                                                            <div class="overflow-ellipsis">
                                                                <a :href="'mailto:' + customer.email">
                                                                    <span>{{ customer.email ? customer.email : '-' }}</span>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li v-if="customers.data.length === 0">
                                                    <span>{{ __('order.no_customer_found') }}</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>

                                    <div class="panel-footer"
                                         v-if="customers.next_page_url || customers.prev_page_url">
                                        <div class="btn-group float-end">
                                            <button type="button"
                                                    @click="loadListCustomersForSearch((customers.prev_page_url ? customers.current_page - 1 : customers.current_page), true)"
                                                    v-bind:class="{ 'btn btn-secondary': customers.current_page !== 1, 'btn btn-secondary disable': customers.current_page === 1}"
                                                    :disabled="customers.current_page === 1">
                                                <svg role="img"
                                                     class="svg-next-icon svg-next-icon-size-16 svg-next-icon-rotate-180">
                                                    <use xmlns:xlink="http://www.w3.org/1999/xlink"
                                                         xlink:href="#next-chevron"></use>
                                                </svg>
                                            </button>
                                            <button type="button"
                                                    @click="loadListCustomersForSearch((customers.next_page_url ? customers.current_page + 1 : customers.current_page), true)"
                                                    v-bind:class="{ 'btn btn-secondary': customers.next_page_url, 'btn btn-secondary disable': !customers.next_page_url }"
                                                    :disabled="!customers.next_page_url">
                                                <svg role="img" class="svg-next-icon svg-next-icon-size-16">
                                                    <use xmlns:xlink="http://www.w3.org/1999/xlink"
                                                         xlink:href="#next-chevron"></use>
                                                </svg>
                                            </button>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-if="child_customer_id && child_customer">
                    <div class="next-card-section p-none-b">
                        <div class="flexbox-grid-default">
                            <div class="flexbox-auto-content-left">
                                <label class="title-product-main">{{ __('order.customer') }}</label>
                            </div>
                            <div class="flexbox-auto-left">
                                <a href="#" data-bs-toggle="tooltip" data-placement="top" title="Delete customer"
                                   @click="removeCustomer()">
                                    <svg class="svg-next-icon svg-next-icon-size-12">
                                        <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#next-remove"></use>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="next-card-section border-none-t">
                        <ul class="ws-nm">
                            <li>
                                <img v-if="child_customer.avatar_url" class="width-60-px radius-cycle" :alt="child_customer.name"
                                     :src="child_customer.avatar_url">
                                <div class="pull-right color_darkblue mt20">
                                    <i class="fas fa-inbox"></i>
                                    <span>
                                        {{ child_customer_order_numbers }}
                                    </span>
                                    {{ __('order.orders') }}
                                </div>
                            </li>
                            <li class="mt10">
                                <a class="hover-underline text-capitalize" href="#">{{ child_customer.name }}</a>
                            </li>
                            <li>
                                <div class="flexbox-grid-default">
                                    <div class="flexbox-auto-content-left overflow-ellipsis">
                                        <a :href="'mailto:' + child_customer.email">
                                            <span>{{ child_customer.email ? child_customer.email : '-' }}</span>
                                        </a>
                                    </div>
                                    <div class="flexbox-auto-left">
                                        <a v-b-modal.edit-email>
                                            <span data-placement="top" data-bs-toggle="tooltip"
                                                  data-bs-original-title="Edit email">
                                                <svg class="svg-next-icon svg-next-icon-size-12">
                                                    <use xmlns:xlink="http://www.w3.org/1999/xlink"
                                                         xlink:href="#next-edit"></use>
                                                </svg>
                                            </span>
                                        </a>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="next-card-section">
                        <ul class="ws-nm">
                            <li class="clearfix">
                                <div class="flexbox-grid-default">
                                    <div class="flexbox-auto-content-left">
                                        <label class="title-text-second">{{ __('order.shipping_address')}}</label>
                                    </div>
                                    <div class="flexbox-auto-left">
                                        <a v-b-modal.edit-address>
                                                <span data-placement="top" title="Update address"
                                                      data-bs-toggle="tooltip">
                                                    <svg class="svg-next-icon svg-next-icon-size-12">
                                                        <use xmlns:xlink="http://www.w3.org/1999/xlink"
                                                             xlink:href="#next-edit"></use>
                                                    </svg>
                                                </span>
                                        </a>
                                    </div>
                                </div>
                            </li>
                            <li class="text-infor-subdued mt15">
                                <div v-if="child_customer_addresses.length > 1">
                                    <div class="ui-select-wrapper">
                                        <select class="ui-select" @change="selectCustomerAddress($event)">
                                            <option v-for="address_item in child_customer_addresses"
                                                    :value="address_item.id"
                                                    :selected="parseInt(address_item.id) === parseInt(customer_address.email)">
                                                {{ address_item.address + ', ' + address_item.city_name + ', ' +
                                                address_item.state_name + ', ' +
                                                address_item.country_name + (zip_code_enabled ? ', ' +
                                            address_item.zip_code : '') }}
                                            </option>
                                        </select>
                                        <svg class="svg-next-icon svg-next-icon-size-16">
                                            <use xmlns:xlink="http://www.w3.org/1999/xlink"
                                                 xlink:href="#select-chevron"></use>
                                        </svg>
                                    </div>
                                    <br>
                                </div>
                                <div>{{ child_customer_address.name }}</div>
                                <div>{{ child_customer_address.phone }}</div>
                                <div><a :href="'mailto:' + child_customer_address.email">{{ child_customer_address.email
                                    }}</a>
                                </div>
                                <div>{{ child_customer_address.address }}</div>
                                <div>{{ child_customer_address.city_name }}</div>
                                <div>{{ child_customer_address.state_name }}</div>
                                <div>{{ child_customer_address.country_name }}</div>
                                <div v-if="zip_code_enabled">{{ child_customer_address.zip_code }}</div>
                                <div>
                                    <a target="_blank" class="hover-underline"
                                       :href="'https://maps.google.com/?q=' + child_customer_address.address + ', ' + child_customer_address.city_name + ', ' + child_customer_address.state_name + ', ' + child_customer_address.country_name + (zip_code_enabled ? ', ' + child_customer_address.zip_code : '')">{{ __('order.see_on_maps') }}</a>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <!--/ko-->
                </div>
            </div>
        </div>

        <b-modal id="add-product-item" title="Add product" ok-title="Save" cancel-title="Cancel"
                 @shown="resetProductData()"
                 @ok="createProduct($event)">
            <div class="form-group mb15">
                <label class="text-title-field">{{ __('order.name') }}</label>
                <input type="text" class="next-input" v-model="product.name">
            </div>
            <div class="form-group mb15 row">
                <div class="col-6">
                    <label class="text-title-field">{{ __('order.price') }}</label>
                    <input type="text" class="next-input" v-model="product.price">
                </div>
                <div class="col-6">
                    <label class="text-title-field">{{ __('order.sku_optional') }}</label>
                    <input type="text" class="next-input" v-model="product.sku">
                </div>
            </div>
            <div class="form-group mb-3">
                <label class="next-label">
                    <input type="checkbox" class="hrv-checkbox" v-model="product.with_storehouse_management" value="1">
                    {{ __('order.with_storehouse_management') }}
                    </label>
            </div>
            <div class="row" v-show="product.with_storehouse_management">
                <div class="col-8">
                    <div class="form-group mb-3">
                        <label class="text-title-field">{{ __('order.quantity') }}</label>
                        <input type="number" min="1" class="next-input"
                               v-model="product.quantity">
                    </div>
                    <div class="form-group mb-3">
                        <label class="next-label">
                            <input type="checkbox" class="hrv-checkbox"
                                   v-model="product.allow_checkout_when_out_of_stock"
                                   value="1">
                            {{ __('order.allow_customer_checkout_when_this_product_out_of_stock') }}</label>
                    </div>
                </div>
            </div>
        </b-modal>

        <b-modal id="add-customer" :title="__('order.create_new_customer')" :ok-title="__('order.save')" :cancel-title="__('order.cancel')"
                 @shown="loadCountries()" @ok="createNewCustomer($event)">
            <div class="next-form-section">
                <div class="next-form-grid">
                    <div class="next-form-grid-cell">
                        <label class="text-title-field">{{ __('order.name') }}</label>
                        <input type="text" class="next-input" v-model="child_customer_address.name">
                    </div>
                    <div class="next-form-grid-cell">
                        <label class="text-title-field">{{ __('order.phone') }}</label>
                        <input type="text" class="next-input" v-model="child_customer_address.phone">
                    </div>
                </div>
                <div class="next-form-grid">
                    <div class="next-form-grid-cell">
                        <label class="text-title-field">{{ __('order.address') }}</label>
                        <input type="text" class="next-input" v-model="child_customer_address.address">
                    </div>
                    <div class="next-form-grid-cell">
                        <label class="text-title-field">{{ __('order.email') }}</label>
                        <input type="text" class="next-input" v-model="child_customer_address.email">
                    </div>
                </div>
                <div class="next-form-grid">
                    <div class="next-form-grid-cell">
                        <label class="text-title-field">{{ __('order.country') }}</label>
                        <div class="ui-select-wrapper">
                            <select class="ui-select" v-model="child_customer_address.country" @change="loadStates($event)">
                                <option v-for="(countryName, countryCode) in countries" :value="countryCode">
                                    {{ countryName }}
                                </option>
                            </select>
                            <svg class="svg-next-icon svg-next-icon-size-16">
                                <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#select-chevron"></use>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="next-form-grid">
                    <div class="next-form-grid-cell">
                        <label class="text-title-field">{{ __('order.state')}}</label>
                        <div class="ui-select-wrapper" v-if="use_location_data">
                            <select class="ui-select customer-address-state" v-model="child_customer_address.state" @change="loadCities($event)">
                                <option v-for="state in states" :value="state.id">
                                    {{ state.name }}
                                </option>
                            </select>
                            <svg class="svg-next-icon svg-next-icon-size-16">
                                <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#select-chevron"></use>
                            </svg>
                        </div>
                        <input type="text" class="next-input customer-address-state" v-if="!use_location_data"
                               v-model="child_customer_address.state">
                    </div>
                    <div class="next-form-grid-cell">
                        <label class="text-title-field">{{ __('order.city') }}</label>
                        <div class="ui-select-wrapper" v-if="use_location_data">
                            <select class="ui-select customer-address-city" v-model="child_customer_address.city">
                                <option v-for="city in cities" :value="city.id">
                                    {{ city.name }}
                                </option>
                            </select>
                            <svg class="svg-next-icon svg-next-icon-size-16">
                                <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#select-chevron"></use>
                            </svg>
                        </div>
                        <input type="text" class="next-input customer-address-city" v-if="!use_location_data"
                               v-model="child_customer_address.city">
                    </div>
                </div>
                <div class="next-form-grid" v-if="zip_code_enabled">
                    <div class="next-form-grid-cell">
                        <label class="text-title-field">{{ __('order.zip_code') }}</label>
                        <input type="text" class="next-input" v-model="child_customer_address.zip_code">
                    </div>
                </div>
            </div>
        </b-modal>

        <b-modal id="add-discounts" title="Add discount" :ok-title="__('order.add_discount')" :cancel-title="__('order.close')"
                 @ok="handleAddDiscount($event)">
            <div class="next-form-section">
                <div class="next-form-grid">
                    <div class="next-form-grid-cell">
                        <label class="text-title-field">{{ __('order.discount_based_on') }}</label>
                        <div class="flexbox-grid-default">
                            <div class="flexbox-auto-left">
                                <div class="flexbox-input-group">
                                    <button value="amount" class="item-group btn btn-secondary btn-active"
                                            v-bind:class="{ active : discount_type === 'amount' }"
                                            @click="changeDiscountType($event)">
                                        {{ currency ? currency : '$' }}
                                    </button>&nbsp;
                                    <button value="percentage"
                                            class="item-group border-radius-right-none btn btn-secondary btn-active"
                                            v-bind:class="{ active : discount_type === 'percentage' }"
                                            @click="changeDiscountType($event)">
                                        %
                                    </button>&nbsp;
                                </div>
                            </div>
                            <div class="flexbox-auto-content">
                                <div class="next-input--stylized border-radius-left-none">
                                    <input class="next-input next-input--invisible"
                                           v-model="discount_custom_value">
                                    <span class="next-input-add-on next-input__add-on--after">{{ discount_type_unit }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="next-form-grid">
                    <div class="next-form-grid-cell">
                        <label class="text-title-field">{{ __('order.or_coupon_code') }}</label>
                        <div class="next-input--stylized" v-bind:class="{ 'field-has-error' : has_invalid_coupon }">
                            <input class="next-input next-input--invisible" v-model="coupon_code">
                        </div>
                    </div>
                </div>
                <div class="next-form-grid">
                    <div class="next-form-grid-cell">
                        <label class="text-title-field">{{ __('order.description') }}</label>
                        <input :placeholder="__('order.discount_description')" class="next-input"
                               v-model="child_discount_description">
                    </div>
                </div>
            </div>
        </b-modal>

        <b-modal id="add-shipping" :title="__('order.shipping_fee')" :ok-title="__('order.update')" :cancel-title="__('order.close')"
                 @shown="loadAvailableShippingMethods()" @ok="selectShippingMethod($event)">
            <div class="next-form-section">
                <div class="ui-layout__item mb15 p-none-important" v-if="!child_customer_id">
                    <div class="ui-banner ui-banner--status-info">
                        <div class="ui-banner__ribbon">
                            <svg class="svg-next-icon svg-next-icon-size-20">
                                <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#alert-circle"></use>
                            </svg>
                        </div>
                        <div class="ui-banner__content">
                            <h2 class="ui-banner__title">{{ __('order.how_to_select_configured_shipping') }}</h2>
                            <div class="ws-nm">{{ __('order.please_add_customer_information_with_the_complete_shipping_address_to_see_the_configured_shipping_rates') }}.
                            </div>
                        </div>
                    </div>
                </div>
                <div class="next-form-grid">
                    <div class="next-form-grid-cell">
                        <label class="next-label">
                            <input type="radio" class=" hrv-radio" value="free-shipping" name="shipping_type"
                                   v-model="shipping_type">
                            {{ __('order.free_shipping') }}</label>
                    </div>
                </div>
                <div class="next-form-grid">
                    <div class="next-form-grid-cell">
                        <label class="next-label">
                            <input type="radio" class=" hrv-radio" value="custom" name="shipping_type"
                                   v-model="shipping_type" checked="checked">
                            {{ __('order.custom') }}</label>
                    </div>
                </div>
                <div class="next-form-grid">
                    <div class="next-form-grid-cell">
                        <div class="ui-select-wrapper">
                            <select class="ui-select">
                                <option :value="shipping_method_key"
                                        v-for="(shipping_method, shipping_method_key) in shipping_methods"
                                        :selected="shipping_method_key === (shipping_method + ';' + shipping_option + ';' + shipping_amount)">
                                    {{ shipping_method.name + ' - ' + shipping_method.price }}
                                </option>
                            </select>
                            <svg class="svg-next-icon svg-next-icon-size-16">
                                <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#select-chevron"></use>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </b-modal>

        <b-modal id="edit-email" :title="__('order.update_email')" :ok-title="__('order.update')" :cancel-title="__('order.close')"
                 @ok="updateCustomerEmail($event)">
            <div class="next-form-section">
                <div class="next-form-grid">
                    <div class="next-form-grid-cell">
                        <label class="text-title-field">{{ __('order.email') }}</label>
                        <input class="next-input" v-model="customer.email">
                    </div>
                </div>
            </div>
        </b-modal>

        <b-modal id="edit-address" :title="__('order.update_address')" :ok-title="__('order.save')" :cancel-title="__('order.cancel')"
                 @shown="loadCountries()" @ok="updateOrderAddress($event)">
            <div class="next-form-section">
                <div class="next-form-grid">
                    <div class="next-form-grid-cell">
                        <label class="text-title-field">{{ __('order.name') }}</label>
                        <input type="text" class="next-input customer-address-name"
                               v-model="child_customer_address.name">
                    </div>
                    <div class="next-form-grid-cell">
                        <label class="text-title-field">{{ __('order.phone') }}</label>
                        <input type="text" class="next-input customer-address-phone"
                               v-model="child_customer_address.phone">
                    </div>
                </div>
                <div class="next-form-grid">
                    <div class="next-form-grid-cell">
                        <label class="text-title-field">{{ __('order.address') }}</label>
                        <input type="text" class="next-input customer-address-address"
                               v-model="child_customer_address.address">
                    </div>
                    <div class="next-form-grid-cell">
                        <label class="text-title-field">{{ __('order.email') }}</label>
                        <input type="text" class="next-input customer-address-email"
                               v-model="child_customer_address.email">
                    </div>
                </div>
                <div class="next-form-grid">
                    <div class="next-form-grid-cell">
                        <label class="text-title-field">{{ __('order.country') }}</label>
                        <div class="ui-select-wrapper">
                            <select class="ui-select customer-address-country" v-model="child_customer_address.country" @change="loadStates($event)">
                                <option v-for="(countryName, countryCode) in countries" :value="countryCode">
                                    {{ countryName }}
                                </option>
                            </select>
                            <svg class="svg-next-icon svg-next-icon-size-16">
                                <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#select-chevron"></use>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="next-form-grid">
                    <div class="next-form-grid-cell">
                        <label class="text-title-field">{{ __('order.state')}}</label>
                        <div class="ui-select-wrapper" v-if="use_location_data">
                            <select class="ui-select customer-address-state" v-model="child_customer_address.state" @change="loadCities($event)">
                                <option v-for="state in states" :value="state.id">
                                    {{ state.name }}
                                </option>
                            </select>
                            <svg class="svg-next-icon svg-next-icon-size-16">
                                <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#select-chevron"></use>
                            </svg>
                        </div>
                        <input type="text" class="next-input customer-address-state" v-if="!use_location_data"
                               v-model="child_customer_address.state">
                    </div>
                    <div class="next-form-grid-cell">
                        <label class="text-title-field">{{ __('order.city') }}</label>
                        <div class="ui-select-wrapper" v-if="use_location_data">
                            <select class="ui-select customer-address-city" v-model="child_customer_address.city">
                                <option v-for="city in cities" :value="city.id">
                                    {{ city.name }}
                                </option>
                            </select>
                            <svg class="svg-next-icon svg-next-icon-size-16">
                                <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#select-chevron"></use>
                            </svg>
                        </div>
                        <input type="text" class="next-input customer-address-city" v-if="!use_location_data"
                               v-model="child_customer_address.city">
                    </div>
                </div>
                <div class="next-form-grid" v-if="zip_code_enabled">
                    <div class="next-form-grid-cell">
                        <label class="text-title-field">{{ __('order.zip_code')}}</label>
                        <input type="text" class="next-input customer-address-zip-code"
                               v-model="child_customer_address.zip_code">
                    </div>
                </div>
            </div>
        </b-modal>

        <b-modal id="make-paid" :title="__('order.confirm_payment_is_paid_for_this_order')" :ok-title="__('order.create_order')" :cancel-title="__('order.close')"
                 @ok="createOrder($event, true)">
            <div class="note note-warning">
                {{ __('order.payment_status_of_the_order_is_paid_once_the_order_has_been_created_you_cannot_change_the_payment_method_or_status') }}.
            </div>
            <p>{{ __('order.select_payment_method') }}</p>
            <div class="ui-select-wrapper mb15 next-input--is-focused">
                <select class="ui-select" v-model="child_payment_method">
                    <option value="cod">{{ __('order.cash_on_delivery_cod') }}</option>
                    <option value="bank_transfer">{{ __('order.bank_transfer') }}</option>
                </select>
                <svg class="svg-next-icon svg-next-icon-size-16">
                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#select-chevron"></use>
                </svg>
            </div>
            <br/>
            <p>{{ __('order.paid_amount') }} : <span>{{ child_total_amount | formatPrice }} {{ currency }}</span></p>
        </b-modal>

        <b-modal id="make-pending" :title="__('order.confirm_that_payment_for_this_order_will_be_paid_later')" :ok-title="__('order.create_order')" :cancel-title="__('order.close')"
                 @ok="createOrder($event)">
            <div class="note note-warning">
                {{ __('order.payment_status_of_the_order_is_pending_once_the_order_has_been_created_you_cannot_change_the_payment_method_or_status') }}.
            </div>
            <div class="ui-select-wrapper mb15 next-input--is-focused">
                <select class="ui-select" v-model="child_payment_method">
                    <option value="cod">{{ __('order.cash_on_delivery_cod') }}</option>
                    <option value="bank_transfer">{{ __('order.bank_transfer') }}</option>
                </select>
                <svg class="svg-next-icon svg-next-icon-size-16">
                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#select-chevron"></use>
                </svg>
            </div>
            <br/>
            <p>{{ __('order.pending_amount') }} : <span>{{ child_total_amount | formatPrice }} {{ currency }}</span></p>
        </b-modal>

    </div>
</template>

<script>
    export default {
        props: {
            products: {
                type: Array,
                default: () => [],
            },
            product_ids: {
                type: Array,
                default: () => [],
            },
            customer_id: {
                type: Number,
                default: () => null,
            },
            customer: {
                type: Object,
                default: () => {
                    return {
                        email: 'guest@example.com',
                    };
                },
            },
            customer_addresses: {
                type: Array,
                default: () => [],
            },
            customer_address: {
                type: Object,
                default: () => ({
                    name: null,
                    email: null,
                    address: null,
                    phone: null,
                    country: 'AF',
                    state: null,
                    city: null,
                    zip_code: null,
                }),
            },
            customer_order_numbers: {
                type: Number,
                default: () => 0,
            },
            sub_amount: {
                type: Number,
                default: () => 0,
            },
            total_amount: {
                type: Number,
                default: () => 0,
            },
            discount_amount: {
                type: Number,
                default: () => 0,
            },
            discount_description: {
                type: String,
                default: () => null,
            },
            shipping_amount: {
                type: Number,
                default: () => 0,
            },
            shipping_method: {
                type: String,
                default: () => 'default',
            },
            shipping_option: {
                type: String,
                default: () => '',
            },
            is_selected_shipping: {
                type: Boolean,
                default: () => false,
            },
            shipping_method_name: {
                type: String,
                default: () => 'Default',
            },
            payment_method: {
                type: String,
                default: () => 'cod',
            },
            currency: {
                type: String,
                default: () => null,
                required: true
            },
            zip_code_enabled: {
                type: Number,
                default: () => 0,
                required: true
            },
            use_location_data: {
                type: Number,
                default: () => 0,
            },
        },
        data: function () {
            return {
                list_products: {
                    data: [],
                },
                hidden_product_search_panel: true,
                loading: false,
                note: null,
                customers: {
                    data: [],
                },
                hidden_customer_search_panel: true,
                customer_keyword: null,
                countries: [],
                states: [],
                cities: [],
                shipping_type: 'custom',
                product: {
                    name: null,
                    price: 0,
                    sku: null,
                    with_storehouse_management: false,
                    allow_checkout_when_out_of_stock: false,
                    quantity: 0,
                },
                shipping_methods: {
                    'default': {
                        name: 'Default',
                        price: 0,
                    }
                },
                discount_type_unit: this.currency,
                discount_type: 'amount',
                coupon_code: null,
                child_discount_description: this.discount_description,
                has_invalid_coupon: false,
                has_applied_discount: this.discount_amount > 0,
                discount_custom_value: 0,
                child_customer: this.customer,
                child_customer_id: this.customer_id,
                child_customer_order_numbers: this.customer_order_numbers,
                child_customer_addresses: this.customer_addresses,
                child_customer_address: this.customer_address,
                child_products: this.products,
                child_product_ids: this.product_ids,
                child_sub_amount: this.sub_amount,
                child_total_amount: this.total_amount,
                child_discount_amount: this.discount_amount,
                child_shipping_amount: this.shipping_amount,
                child_shipping_method: this.shipping_method,
                child_shipping_option: this.shipping_option,
                child_shipping_method_name: this.shipping_method_name,
                child_is_selected_shipping: this.is_selected_shipping,
                child_payment_method: this.payment_method,
            }
        },
        mounted: function () {
            let context = this;
            $(document).on('click', 'body', e => {
                let container = $('.box-search-advance');

                if (!container.is(e.target) && container.has(e.target).length === 0) {
                    context.hidden_customer_search_panel = true;
                    context.hidden_product_search_panel = true;
                }
            });
        },
        methods: {
            loadListCustomersForSearch: function (page = 1, force = false) {
                let context = this;
                context.hidden_customer_search_panel = false;
                $('.textbox-advancesearch.customer').closest('.box-search-advance.customer').find('.panel').addClass('active');
                if (_.isEmpty(context.customers.data) || force) {
                    context.loading = true;
                    axios
                        .get(route('customers.get-list-customers-for-search', {
                            keyword: context.customer_keyword,
                            page: page
                        }))
                        .then(res => {
                            context.customers = res.data.data;
                            context.loading = false;
                        })
                        .catch(res => {
                            Botble.handleError(res.response.data);
                        });
                }
            },
            handleSearchCustomer: function (value) {
                if (value !== this.customer_keyword) {
                    let context = this;
                    this.customer_keyword = value;
                    setTimeout(() => {
                        context.loadListCustomersForSearch(1, true);
                    }, 500);
                }
            },
            loadListProductsAndVariations: function (page = 1, force = false) {
                let context = this;
                context.hidden_product_search_panel = false;
                $('.textbox-advancesearch.product').closest('.box-search-advance.product').find('.panel').addClass('active');
                if (_.isEmpty(context.list_products.data) || force) {
                    context.loading = true;
                    axios
                        .get(route('products.get-all-products-and-variations', {
                            keyword: context.product_keyword,
                            page: page
                        }))
                        .then(res => {
                            context.list_products = res.data.data;
                            context.loading = false;
                        })
                        .catch(res => {
                            Botble.handleError(res.response.data);
                        });
                }
            },
            handleSearchProduct: function (value) {
                if (value !== this.product_keyword) {
                    let context = this;
                    this.product_keyword = value;
                    setTimeout(() => {
                        context.loadListProductsAndVariations(1, true);
                    }, 500);
                }
            },
            selectProductVariant: function (product, variation = null) {
                if ((!_.isEmpty(variation) && variation.is_out_of_stock) || (_.isEmpty(variation) && product.is_out_of_stock)) {
                    Botble.showError(__('order.cant_select_out_of_stock_product'));
                    return false;
                }

                if (!_.isEmpty(variation)) {
                    if (!_.includes(this.child_product_ids, variation.product_id)) {
                        let productItem = variation;
                        productItem.product_name = product.name;
                        productItem.image_url = product.image_url;
                        productItem.price = variation.price;
                        productItem.product_link = product.product_link;
                        productItem.select_qty = 1;
                        this.child_products.push(productItem);
                        this.child_product_ids.push(variation.product_id);
                    }
                } else if (!_.includes(this.child_product_ids, product.id)) {
                    let productItem = product;
                    productItem.product_name = product.name;
                    productItem.image_url = product.image_url;
                    productItem.price = product.price;
                    productItem.product_link = product.product_link;
                    productItem.select_qty = 1;
                    this.child_products.push(productItem);
                    this.child_product_ids.push(product.id);
                }
                this.hidden_product_search_panel = true;
            },
            selectCustomer: function (customer) {
                this.child_customer = customer;
                this.child_customer_id = customer.id;

                this.loadCustomerAddress(this.child_customer_id);

                this.getOrderNumbers();
            },
            removeCustomer: function () {
                this.child_customer = this.customer;
                this.child_customer_id = null;
                this.child_customer_addresses = [];
                this.child_customer_address = {
                    name: null,
                    email: null,
                    address: null,
                    phone: null,
                    country: 'AF',
                    state: null,
                    city: null,
                    zip_code: null,
                };
                this.child_customer_order_numbers = 0;
            },
            handleRemoveVariant: function ($event, variant) {
                $event.preventDefault();
                if (variant.product_id) {
                    this.child_product_ids = _.reject(this.child_product_ids, item => {
                        return item === variant.product_id;
                    });

                    this.child_products = _.reject(this.child_products, item => {
                        return item.product_id === variant.product_id;
                    });
                } else {
                    this.child_product_ids = _.reject(this.child_product_ids, item => {
                        return item === variant.id;
                    });

                    this.child_products = _.reject(this.child_products, item => {
                        return item.id === variant.id;
                    });
                }
            },
            loadCountries: function () {
                let context = this;
                if (_.isEmpty(context.countries)) {
                    axios
                        .get(route('ajax.countries.list'))
                        .then(res => {
                            context.countries = res.data.data;
                        })
                        .catch(res => {
                            Botble.handleError(res.response.data);
                        });
                }
            },
            loadStates: function ($event) {
                let context = this;
                axios
                    .get(route('ajax.states-by-country', {country_id: $event.target.value}))
                    .then(res => {
                        context.states = res.data.data;
                    })
                    .catch(res => {
                        Botble.handleError(res.response.data);
                    });
            },
            loadCities: function ($event) {
                let context = this;
                axios
                    .get(route('ajax.cities-by-state', {state_id: $event.target.value}))
                    .then(res => {
                        context.cities = res.data.data;
                    })
                    .catch(res => {
                        Botble.handleError(res.response.data);
                    });
            },
            createOrder: function ($event, paid = false) {
                $event.preventDefault();
                $($event.target).find('.btn-primary').addClass('button-loading');
                let context = this;

                let products = [];
                _.each(this.child_products, function (item) {
                    products.push({
                        id: (item.configurable_product_id ? item.product_id : item.id),
                        quantity: item.select_qty
                    });
                });

                axios
                    .post(route('orders.create'), {
                        products: products,
                        payment_status: paid ? 'completed' : 'pending',
                        payment_method: this.child_payment_method,
                        shipping_method: this.child_shipping_method,
                        shipping_option: this.child_shipping_option,
                        shipping_amount: this.child_shipping_amount,
                        discount_amount: this.child_discount_amount,
                        discount_description: this.child_discount_description,
                        coupon_code: this.coupon_code,
                        customer_id: this.child_customer_id,
                        note: this.note,
                        amount: this.child_sub_amount,
                        customer_address: this.child_customer_address,
                    })
                    .then(res => {
                        let data = res.data.data;
                        if (res.data.error) {
                            Botble.showError(res.data.message);
                            $($event.target).find('.btn-primary').removeClass('button-loading');
                        } else {
                            Botble.showSuccess(res.data.message);
                            if (paid) {
                                context.$root.$emit('bv::hide::modal', 'make-paid');
                            } else {
                                context.$root.$emit('bv::hide::modal', 'make-pending');
                            }

                            setTimeout(() => {
                                window.location.href = route('orders.edit', data.id);
                            }, 1000);
                        }
                    })
                    .catch(res => {
                        Botble.handleError(res.response.data);
                        $($event.target).find('.btn-primary').removeClass('button-loading');
                    });
            },
            createProduct: function ($event) {
                $event.preventDefault();
                $($event.target).find('.btn-primary').addClass('button-loading');
                let context = this;

                axios
                    .post(route('products.create-product-when-creating-order'), context.product)
                    .then(res => {
                        if (res.data.error) {
                            Botble.showError(res.data.message);
                            $($event.target).find('.btn-primary').removeClass('button-loading');
                        } else {

                            context.product = res.data.data;

                            context.list_products = {
                                data: [],
                            };

                            let productItem = context.product;
                            productItem.product_name = context.product.name;
                            productItem.image_url = context.product.image_url;
                            productItem.price = context.product.price;
                            productItem.product_link = context.product.product_link;

                            context.child_products.push(productItem);
                            context.child_product_ids.push(context.product.id);

                            context.hidden_product_search_panel = true;

                            Botble.showSuccess(res.data.message);

                            context.$root.$emit('bv::hide::modal', 'add-product-item');
                        }
                    })
                    .catch(res => {
                        $($event.target).find('.btn-primary').removeClass('button-loading');
                        Botble.handleError(res.response.data);
                    });
            },
            updateCustomerEmail: function ($event) {
                $event.preventDefault();
                $($event.target).find('.btn-primary').addClass('button-loading');

                let context = this;

                axios
                    .post(route('customers.update-email', context.child_customer.id), {
                        email: context.child_customer.email,
                    })
                    .then(res => {
                        if (res.data.error) {
                            Botble.showError(res.data.message);
                            $($event.target).find('.btn-primary').removeClass('button-loading');
                        } else {
                            Botble.showSuccess(res.data.message);

                            context.$root.$emit('bv::hide::modal', 'edit-email')
                        }
                        $($event.target).find('.btn-primary').removeClass('button-loading');
                    })
                    .catch(res => {
                        Botble.handleError(res.response.data);
                        $($event.target).find('.btn-primary').removeClass('button-loading');
                    });
            },
            updateOrderAddress: function ($event) {
                $event.preventDefault();

                if (this.customer) {

                    let $modal = $(event.target).closest('.modal-dialog');

                    $($event.target).find('.btn-primary').addClass('button-loading');

                    this.child_customer_address.name = $modal.find('.customer-address-name').val();
                    this.child_customer_address.email = $modal.find('.customer-address-email').val();
                    this.child_customer_address.phone = $modal.find('.customer-address-phone').val();
                    this.child_customer_address.address = $modal.find('.customer-address-address').val();
                    this.child_customer_address.city = $modal.find('.customer-address-city').val();
                    this.child_customer_address.state = $modal.find('.customer-address-state').val();
                    this.child_customer_address.country = $modal.find('.customer-address-country').val();
                    this.child_customer_address.zip_code = $modal.find('.customer-address-zip-code').val();

                    let context = this;

                    setTimeout(() => {
                        $($event.target).find('.btn-primary').removeClass('button-loading');
                        context.$root.$emit('bv::hide::modal', 'edit-address');
                    }, 500);
                }
            },
            createNewCustomer: function ($event) {
                $event.preventDefault();
                let context = this;

                $($event.target).find('.btn-primary').addClass('button-loading');

                axios
                    .post(route('customers.create-customer-when-creating-order'), {
                        customer_id: context.child_customer_id,
                        name: context.child_customer_address.name,
                        email: context.child_customer_address.email,
                        phone: context.child_customer_address.phone,
                        address: context.child_customer_address.address,
                        country: context.child_customer_address.country,
                        state: context.child_customer_address.state,
                        city: context.child_customer_address.city,
                        zip_code: context.child_customer_address.zip_code,
                    })
                    .then(res => {
                        if (res.data.error) {
                            Botble.showError(res.data.message);
                            $($event.target).find('.btn-primary').removeClass('button-loading');
                        } else {
                            context.child_customer_address = res.data.data.address;
                            context.child_customer = res.data.data.customer;
                            context.child_customer_id = context.child_customer.id;

                            context.customers = {
                                data: [],
                            };

                            Botble.showSuccess(res.data.message);

                            context.$root.$emit('bv::hide::modal', 'add-customer');
                        }

                        $($event.target).find('.btn-primary').removeClass('button-loading');
                    })
                    .catch(res => {
                        Botble.handleError(res.response.data);
                        $($event.target).find('.btn-primary').removeClass('button-loading');
                    });
            },
            selectCustomerAddress: function (event) {
                let context = this;
                _.each(this.child_customer_addresses, (item) => {
                    if (parseInt(item.id) === parseInt(event.target.value)) {
                        context.child_customer_address = item;
                    }
                });
            },
            getOrderNumbers: function () {
                let context = this;
                axios
                    .get(route('customers.get-customer-order-numbers', context.child_customer_id))
                    .then(res => {
                        context.child_customer_order_numbers = res.data.data;
                    })
                    .catch(res => {
                        Botble.handleError(res.response.data);
                    });
            },
            loadCustomerAddress: function () {
                let context = this;
                axios
                    .get(route('customers.get-customer-addresses', context.child_customer_id))
                    .then(res => {
                        context.child_customer_addresses = res.data.data;
                        if (!_.isEmpty(context.child_customer_addresses)) {
                            context.child_customer_address = _.first(context.child_customer_addresses);
                        }
                    })
                    .catch(res => {
                        Botble.handleError(res.response.data);
                    });
            },
            selectShippingMethod: function ($event) {
                $event.preventDefault();
                let context = this;
                $($event.target).find('.btn-primary').addClass('button-loading');

                context.child_is_selected_shipping = true;

                if (context.shipping_type === 'free-shipping') {
                    context.child_shipping_method_name = 'Free shipping';
                    context.child_shipping_amount = 0;
                } else {
                    let selected_shipping = $($event.target).find('.ui-select').val();
                    if (!_.isEmpty(selected_shipping)) {
                        selected_shipping = selected_shipping.split(';');
                        context.child_shipping_method = selected_shipping[0].trim();
                        context.child_shipping_option = selected_shipping[1].trim();
                        context.child_shipping_amount = parseFloat(selected_shipping[2].trim());
                        context.child_shipping_method_name = $($event.target).find('.ui-select option:selected').data('name')
                    }
                }

                setTimeout(function () {
                    $($event.target).find('.btn-primary').removeClass('button-loading');
                    context.$root.$emit('bv::hide::modal', 'add-shipping');
                }, 500);
            },
            loadAvailableShippingMethods: function () {
                let context = this;
                axios
                    .get(route('orders.get-available-shipping-methods', {
                        address: context.child_customer_address.address,
                        country: context.child_customer_address.country,
                        state: context.child_customer_address.state,
                        city: context.child_customer_address.city,
                        zip_code: context.child_customer_address.zip_code,
                        products: context.child_product_ids,
                    }))
                    .then(res => {
                        context.shipping_methods = res.data.data;
                    })
                    .catch(res => {
                        Botble.handleError(res.response.data);
                    });
            },
            changeDiscountType: function (event) {
                if ($(event.target).val() === 'amount') {
                    this.discount_type_unit = this.currency;
                } else {
                    this.discount_type_unit = '%';
                }
                this.discount_type = $(event.target).val();
            },
            handleAddDiscount: function (event) {
                event.preventDefault();
                let context = this;

                context.has_applied_discount = true;

                context.has_invalid_coupon = false;

                let button = $(event.target).find('.btn-primary');

                button.addClass('button-loading').prop('disabled', true);

                if (!_.isEmpty(context.coupon_code)) {
                    axios
                        .post(route('orders.apply-coupon-when-creating-order'), {
                            coupon_code: context.coupon_code,
                            country: context.child_customer_address.country,
                            shipping_amount: context.child_shipping_amount,
                            product_ids: context.child_product_ids,
                            customer_id: context.child_customer_id,
                            sub_total: context.child_sub_amount,
                        })
                        .then(res => {
                            if (res.data.error) {
                                Botble.showError(res.data.message);
                                button.removeClass('button-loading');
                                context.has_invalid_coupon = true;
                            } else {
                                context.child_discount_amount = res.data.data.discount_amount;
                                if (res.data.data.is_free_shipping) {
                                    context.child_shipping_amount = 0;
                                }
                                Botble.showSuccess(res.data.message);
                            }
                            button.removeClass('button-loading');

                            context.$root.$emit('bv::hide::modal', 'add-discounts');
                        })
                        .catch(res => {
                            Botble.handleError(res.response.data);
                            button.removeClass('button-loading');
                        });
                } else {
                    if (this.discount_type === 'percentage') {
                        context.child_discount_amount = context.child_total_amount * context.discount_custom_value / 100;
                    } else {
                        context.child_discount_amount = context.discount_custom_value;
                    }

                    setTimeout(function () {
                        button.removeClass('button-loading').prop('disabled', false);
                        context.$root.$emit('bv::hide::modal', 'add-discounts');
                    }, 500);
                }
            },
            calculateAmount: function (products) {
                let context = this;
                context.child_sub_amount = 0;
                _.each(products, function (item) {
                    context.child_sub_amount += parseFloat(item.price) * parseInt(item.select_qty);
                });
                context.child_total_amount = parseFloat(context.child_sub_amount) - parseFloat(context.child_discount_amount) + parseFloat(context.child_shipping_amount);
                if (context.child_total_amount < 0) {
                    context.child_total_amount = 0;
                }
            },
            handleChangeQuantity: function () {
                this.calculateAmount(this.child_products);
            },
            resetProductData: function () {
                this.product = {
                    name: null,
                    price: 0,
                    sku: null,
                    with_storehouse_management: false,
                    allow_checkout_when_out_of_stock: false,
                    quantity: 0,
                };
            }
        },
        watch: {
            'child_products': function (value) {
                this.calculateAmount(value);
            },
            'child_discount_amount': function (value) {
                let context = this;
                context.child_total_amount = parseFloat(context.child_sub_amount) - parseFloat(value) + parseFloat(context.child_shipping_amount);
            },
            'child_shipping_amount': function (value) {
                let context = this;
                context.child_total_amount = parseFloat(context.child_sub_amount) - parseFloat(context.child_discount_amount) + parseFloat(value);
            },
            'shipping_type': function (value) {
                if (value === 'free-shipping') {
                    this.child_shipping_amount = 0;
                }
            },
        }
    }
</script>
