<template>
    <div class="flexbox-grid no-pd-none">
        <div class="flexbox-content">
            <div class="wrapper-content">
                <div class="pd-all-20 ws-nm">
                    <label class="title-product-main text-no-bold"><span
                            v-if="!is_promotion">{{ __('discount.create_coupon_code')}}</span><span v-if="is_promotion">{{ __('discount.create_discount_promotion') }}</span></label>
                    <a href="#" class="btn-change-link float-end" v-on:click="generateCouponCode($event)"
                       v-show="!is_promotion">{{ __('discount.generate_coupon_code')}}</a>
                    <div class="form-group mt15 mb0">
                        <input type="text" class="next-input coupon-code-input" name="code" v-model="code"
                               v-show="!is_promotion">
                        <input type="text" class="next-input" name="title" v-model="title" v-show="is_promotion"
                               :placeholder="__('discount.enter_promotion_name')">
                        <p class="type-subdued mt5 mb0" v-show="!is_promotion">{{ __('discount.customers_will_enter_this_coupon_code_when_they_checkout')}}.</p>
                    </div>
                </div>
                <div class="pd-all-20 border-top-color">
                    <label class="title-product-main text-no-bold block-display">{{ __('discount.select_type_of_discount') }}</label>
                    <div class="ui-select-wrapper width-200-px-rsp-768 mt15">
                        <select class="ui-select" id="select-promotion" name="type" v-model="type"
                                @change="changeDiscountType()">
                            <option value="coupon">{{ __('discount.coupon_code')}}</option>
                            <option value="promotion">{{ __('discount.promotion')}}</option>
                        </select>
                        <svg class="svg-next-icon svg-next-icon-size-16">
                            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#select-chevron"></use>
                        </svg>
                    </div>
                    <div class="form-group mt15 mb0" v-show="!is_promotion">
                        <label class="next-label">
                            <input type="checkbox" class="hrv-checkbox" value="1" name="can_use_with_promotion"
                                   v-model="can_use_with_promotion">
                            <span class="pre-line">{{ __('discount.can_be_used_with_promotion')}}</span>
                        </label>
                    </div>
                    <div class="form-group mb0 mt15" v-show="!is_promotion">
                        <label>
                            <input type="checkbox" class="hrv-checkbox" name="is_unlimited" value="1"
                                   v-model="is_unlimited">{{ __('discount.unlimited_coupon')}}
                        </label>
                    </div>
                    <div class="form-group mb0 mt15" v-show="!is_promotion && !is_unlimited">
                        <label class="text-title-field">{{ __('discount.enter_number') }}</label>
                        <div class="limit-input-group">
                            <input type="text" class="form-control pl5 p-r5" name="quantity" v-model="quantity"
                                   autocomplete="off" :disabled="is_unlimited">
                        </div>
                    </div>
                </div>
                <div class="pd-all-20 border-top-color">
                    <label class="title-product-main text-no-bold block-display">{{ __('discount.coupon_type') }}</label>
                    <div class="form-inline form-group discount-input mt15 mb0 ws-nm">
                        <div class="ui-select-wrapper inline_block mb5" style="min-width: 200px;">
                            <select id="discount-type-option" name="type_option" class="ui-select" v-model="type_option"
                                    @change="handleChangeTypeOption()">
                                <option value="amount">{{ currency }}</option>
                                <option value="percentage">{{ __('discount.percentage_discount')}}</option>
                                <option value="shipping" v-if="!is_promotion">{{ __('discount.free_shipping')}}</option>
                                <option value="same-price">{{ __('discount.same_price') }}</option>
                            </select>
                            <svg class="svg-next-icon svg-next-icon-size-16">
                                <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#select-chevron"></use>
                            </svg>
                        </div>
                        <span class="lb-dis"> <span>{{ value_label }}</span></span>
                        <div class="inline width20-rsp-768 mb5">
                            <div class="next-input--stylized">
                                <input type="text" class="next-input next-input--invisible" name="value"
                                       v-model="discount_value" autocomplete="off" placeholder="0">
                                <span class="next-input-add-on next-input__add-on--after">{{ discountUnit }}</span>
                            </div>
                        </div>
                        <span class="lb-dis" v-show="type_option !== 'shipping' && type_option"> {{ __('discount.apply_for') }}</span>
                        <div v-show="type_option !== 'shipping' && type_option">
                            <div class="ui-select-wrapper inline_block mb5 min-width-150-px" style="margin-right: 10px;"
                                 @change="handleChangeTarget()">
                                <select id="select-offers" class="ui-select" name="target" v-model="target">
                                    <option value="all-orders" v-if="type_option !== 'same-price'">{{ __('discount.all_orders') }}
                                    </option>
                                    <option value="amount-minimum-order" v-if="type_option !== 'same-price'">{{ __('discount.order_amount_from')}}
                                    </option>
                                    <option value="group-products">{{ __('discount.product_collection')}}</option>
                                    <option value="specific-product">{{ __('discount.product')}}</option>
                                    <option value="customer" v-if="type_option !== 'same-price'">{{ __('discount.customer')}}</option>
                                    <option value="product-variant">{{ __('discount.variant') }}</option>
                                </select>
                                <svg class="svg-next-icon svg-next-icon-size-16">
                                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#select-chevron"></use>
                                </svg>
                            </div>
                            <div class="inline mb5" id="div-select-collection"
                                 v-if="target === 'group-products' && type_option !== 'shipping'"
                                 style="margin-right: 10px;">

                                <div class="ui-select-wrapper" style="min-width: 200px;">
                                    <select name="product_collections" class="ui-select"
                                            v-model="product_collection_id">
                                        <option v-for="product_collection in product_collections"
                                                :value="product_collection.id">{{ product_collection.name }}
                                        </option>
                                    </select>
                                    <svg class="svg-next-icon svg-next-icon-size-16">
                                        <use xmlns:xlink="http://www.w3.org/1999/xlink"
                                             xlink:href="#select-chevron"></use>
                                    </svg>
                                </div>
                            </div>
                            <div class="inline mb5" id="div-select-product"
                                 v-if="target === 'specific-product' && type_option !== 'shipping'"
                                 style="margin-right: 10px;">
                                <div class="box-search-advance product" style="min-width: 310px;">
                                    <input type="text" class="next-input textbox-advancesearch"
                                           @click="loadListProductsForSearch(0)"
                                           @keyup="handleSearchProduct(0, $event.target.value)" :placeholder="__('discount.search_product')">
                                    <div class="panel panel-default"
                                         :class="{ active: products, hidden: hidden_product_search_panel }">
                                        <div class="panel-body">
                                            <div class="list-search-data">
                                                <div class="has-loading" v-show="loading">
                                                    <i class="fa fa-spinner fa-spin"></i>
                                                </div>
                                                <ul class="clearfix" v-show="!loading">
                                                    <li v-for="product in products.data" @click="handleSelectProducts(product)">
                                                        <div class="wrap-img inline_block vertical-align-t">
                                                            <img class="thumb-image"
                                                                 :src="product.image_url"
                                                                 :title="product.name" :alt="product.name">
                                                        </div>
                                                        <label class="inline_block ml10 mt10 ws-nm"
                                                               style="width:calc(100% - 50px); cursor: pointer;">
                                                            {{ product.name }}</label>
                                                    </li>
                                                    <li v-if="products.data.length === 0">
                                                        <span>{{ __('discount.no_products_found') }}</span>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="panel-footer"
                                             v-if="products.next_page_url || products.prev_page_url">
                                            <div class="btn-group float-end">
                                                <button type="button"
                                                        @click="loadListProductsForSearch(0, (products.prev_page_url ? products.current_page - 1 : products.current_page), true)"
                                                        :class="{ 'btn btn-secondary': products.current_page !== 1, 'btn btn-secondary disable': products.current_page === 1}"
                                                        :disabled="products.current_page === 1">
                                                    <svg role="img"
                                                         class="svg-next-icon svg-next-icon-size-16 svg-next-icon-rotate-180">
                                                        <use xmlns:xlink="http://www.w3.org/1999/xlink"
                                                             xlink:href="#next-chevron"></use>
                                                    </svg>
                                                </button>
                                                <button type="button"
                                                        @click="loadListProductsForSearch(0, (products.next_page_url ? products.current_page + 1 : products.current_page), true)"
                                                        :class="{ 'btn btn-secondary': products.next_page_url, 'btn btn-secondary disable': !products.next_page_url }"
                                                        :disabled="!products.next_page_url">
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

                            <div class="inline mb5" id="div-select-customer"
                                 v-if="target === 'customer' && type_option !== 'shipping'">
                                <div class="box-search-advance customer" style="min-width: 310px;">
                                    <div>
                                        <input type="text" class="next-input textbox-advancesearch customer"
                                               @click="loadListCustomersForSearch()"
                                               @keyup="handleSearchCustomer($event.target.value)"
                                               :placeholder="__('discount.search_customer')">
                                    </div>
                                    <div class="panel panel-default"
                                         v-bind:class="{ active: customers, hidden : hidden_customer_search_panel }">
                                        <div class="panel-body">
                                            <div class="list-search-data">
                                                <div class="has-loading" v-show="loading">
                                                    <i class="fa fa-spinner fa-spin"></i>
                                                </div>
                                                <ul class="clearfix" v-show="!loading">
                                                    <li class="row" v-for="customer in customers.data"
                                                        @click="handleSelectCustomers(customer)">
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
                                                        <span>{{ __('discount.no_customer_found') }}</span>
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

                            <div class="inline mb5" id="div-select-product-variant"
                                 v-if="target === 'product-variant' && type_option !== 'shipping'"
                                 style="margin-right: 10px;">
                                <div class="box-search-advance product" style="min-width: 310px;">
                                    <input type="text" class="next-input textbox-advancesearch"
                                           @click="loadListProductsForSearch()"
                                           @keyup="handleSearchProduct(1, $event.target.value)" placeholder="Search product">
                                    <div class="panel panel-default"
                                         :class="{ active: variants, hidden: hidden_product_search_panel }">
                                        <div class="panel-body">
                                            <div class="list-search-data">
                                                <div class="has-loading" v-show="loading">
                                                    <i class="fa fa-spinner fa-spin"></i>
                                                </div>
                                                <ul class="clearfix" v-show="!loading">
                                                    <li v-for="product_variant in variants.data"
                                                        v-if="product_variant.variations.length">
                                                        <div class="wrap-img inline_block vertical-align-t">
                                                            <img class="thumb-image"
                                                                 :src="product_variant.image_url"
                                                                 :title="product_variant.name" :alt="product_variant.name">
                                                        </div>
                                                        <label class="inline_block ml10 mt10 ws-nm"
                                                               style="width:calc(100% - 50px); cursor: pointer;">{{
                                                            product_variant.name }}</label>
                                                        <div class="clear"></div>
                                                        <ul>
                                                            <li class="clearfix product-variant"
                                                                v-for="variation in product_variant.variations"
                                                                @click="handleSelectVariants(product_variant, variation)">
                                                                <a class="color_green float-start">
                                                                    <span v-for="(variantItem, index) in variation.variation_items">
                                                                        {{ variantItem.attribute_title }}
                                                                        <span v-if="index !== variation.variation_items.length - 1">/</span>
                                                                    </span>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </li>
                                                    <li v-if="variants.data.length === 0">
                                                        <span>{{ __('discount.no_products_found') }}</span>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="panel-footer"
                                             v-if="variants.next_page_url || variants.prev_page_url">
                                            <div class="btn-group float-end">
                                                <button type="button"
                                                        @click="loadListProductsForSearch(1, (variants.prev_page_url ? variants.current_page - 1 : variants.current_page), true)"
                                                        :class="{ 'btn btn-secondary': variants.current_page !== 1, 'btn btn-secondary disable': variants.current_page === 1}"
                                                        :disabled="variants.current_page === 1">
                                                    <svg role="img"
                                                         class="svg-next-icon svg-next-icon-size-16 svg-next-icon-rotate-180">
                                                        <use xmlns:xlink="http://www.w3.org/1999/xlink"
                                                             xlink:href="#next-chevron"></use>
                                                    </svg>
                                                </button>
                                                <button type="button"
                                                        @click="loadListProductsForSearch(1, (variants.next_page_url ? variants.current_page + 1 : variants.current_page), true)"
                                                        :class="{ 'btn btn-secondary': variants.next_page_url, 'btn btn-secondary disable': !variants.next_page_url }"
                                                        :disabled="!variants.next_page_url">
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

                            <div class="inline mb5"
                                 v-if="!is_promotion && (target === 'group-products' || target === 'specific-product' || target === 'product-variant') && type_option === 'amount'">
                                <div class="ui-select-wrapper">
                                    <select class="ui-select" name="discount_on" v-model="discount_on">
                                        <option value="per-order">{{ __('discount.one_time_per_order') }}</option>
                                        <option value="per-every-item">{{ __('discount.one_time_per_product_in_cart') }}</option>
                                    </select>
                                    <svg class="svg-next-icon svg-next-icon-size-16">
                                        <use xmlns:xlink="http://www.w3.org/1999/xlink"
                                             xlink:href="#select-chevron"></use>
                                    </svg>
                                </div>
                            </div>
                            <div class="inline width-150-px mb5"
                                 v-if="target === 'amount-minimum-order' && type_option !== 'shipping'">
                                <div class="next-input--stylized">
                                    <input type="text" class="next-input next-input--invisible"
                                           v-model="min_order_price" name="min_order_price">
                                    <span class="next-input-add-on next-input__add-on--after">{{ currency }}</span>
                                </div>
                            </div>
                        </div>
                        <div style="margin: 10px 0;" v-show="is_promotion">
                            <span class="lb-dis">  {{ __('discount.number_of_products') }}: </span>
                            <input type="text" class="form-control width-100-px p-none-r" name="product_quantity"
                                   id="product-quantity" v-model="product_quantity">
                        </div>
                    </div>

                    <div class="clearfix" v-if="selected_variants.length && target === 'product-variant'">
                        <input type="hidden" v-model="selected_variant_ids" name="variants">
                        <div class="mt20"><label class="text-title-field">{{ __('discount.selected_products')}}:</label></div>
                        <div class="table-wrapper p-none mt10 mb20 ps-relative">
                            <table class="table-normal">
                                <tbody>
                                <tr v-for="variant in selected_variants">
                                    <td class="width-60-px min-width-60-px">
                                        <div class="wrap-img vertical-align-m-i">
                                            <img class="thumb-image"
                                              :src="variant.image_url"
                                              :title="variant.product_name"
                                              :alt="variant.product_name" />
                                        </div>
                                    </td>
                                    <td class="pl5 p-r5 min-width-200-px">
                                        <a class="hover-underline pre-line" :href="variant.product_link"
                                           target="_blank">{{ variant.product_name }}</a>
                                        <p class="type-subdued">
                                            <span v-for="(variantItem, index) in variant.variation_items">
                                                {{ variantItem.attribute_title }}
                                                <span v-if="index !== variant.variation_items.length - 1">/</span>
                                            </span>
                                        </p>
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
                    </div>

                    <div class="clearfix" v-if="selected_products.length && target === 'specific-product'">
                        <input type="hidden" v-model="selected_product_ids" name="products">
                        <div class="mt20"><label class="text-title-field">{{ __('discount.selected_products')}}:</label></div>
                        <div class="table-wrapper p-none mt10 mb20 ps-relative">
                            <table class="table-normal">
                                <tbody>
                                <tr v-for="product in selected_products">
                                    <td class="width-60-px min-width-60-px">
                                        <div class="wrap-img vertical-align-m-i"><img class="thumb-image"
                                                                                      :src="product.image_url"
                                                                                      :title="product.name" :alt="product.name" />
                                        </div>
                                    </td>
                                    <td class="pl5 p-r5 min-width-200-px">
                                        <a class="hover-underline pre-line" :href="product.product_link"
                                           target="_blank">{{ product.name }}</a>
                                    </td>
                                    <td class="pl5 p-r5 text-end width-20-px min-width-20-px">
                                        <a href="#" @click="handleRemoveProduct($event, product)">
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
                        <!--/ko-->
                    </div>

                    <div class="clearfix" v-if="selected_customers.length && target === 'customer'">
                        <input type="hidden" v-model="selected_customer_ids" name="customers">
                        <div class="mt20"><label class="text-title-field">{{ __('discount.selected_customers')}}:</label></div>
                        <div class="table-wrapper p-none mt10 mb20 ps-relative">
                            <table class="table-normal">
                                <tbody>
                                <tr v-for="customer in selected_customers">
                                    <td class="width-60-px min-width-60-px">
                                        <div class="wrap-img vertical-align-m-i"><img class="thumb-image"
                                                                                      :src="customer.avatar_url"
                                                                                      :title="customer.name" :alt="customer.name">
                                        </div>
                                    </td>
                                    <td class="pl5 p-r5 min-width-200-px">
                                        <a class="hover-underline pre-line" :href="customer.customer_link"
                                           target="_blank">{{ customer.name }}</a>
                                    </td>
                                    <td class="pl5 p-r5 text-end width-20-px min-width-20-px">
                                        <a href="#" @click="handleRemoveCustomer($event, customer)">
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
                    </div>
                </div>
            </div>
        </div>
        <div class="flexbox-content flexbox-right">
            <div class="wrapper-content">
                <div class="pd-all-20">
                    <label class="title-product-main text-no-bold">{{ __('discount.time') }}</label>
                </div>
                <div class="pd-all-10-20 form-group mb0 date-time-group">
                    <label class="text-title-field">{{ __('discount.start_date')}}</label>
                    <div class="next-field__connected-wrapper z-index-9">
                        <div class="input-group date form_datetime form_datetime bs-datetime">
                            <input type="text" placeholder="dd-mm-yyyy" data-date-format="dd-mm-yyyy" name="start_date" v-model="start_date"
                                   class="next-field--connected next-input z-index-9 datepicker" autocomplete="off">
                            <span class="input-group-prepend">
                                <button class="btn default" type="button">
                                    <span class="fa fa-fw fa-calendar"></span>
                                </button>
                            </span>
                        </div>
                        <div class="input-group">
                            <input type="text" placeholder="hh:mm" name="start_time" v-model="start_time"
                                   class="next-field--connected next-input z-index-9 time-picker timepicker timepicker-24">
                            <span class="input-group-prepend">
                                <button class="btn default" type="button">
                                    <i class="fa fa-clock"></i>
                                </button>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="pd-all-10-20 form-group mb0 date-time-group">
                    <label class="text-title-field">{{ __('discount.end_date')}}</label>
                    <div class="next-field__connected-wrapper z-index-9">
                        <div class="input-group date form_datetime form_datetime bs-datetime">
                            <input type="text" placeholder="dd-mm-yyyy" data-date-format="dd-mm-yyyy" name="end_date" v-model="end_date"
                                   class="next-field--connected next-input z-index-9 datepicker"
                                   :disabled="unlimited_time">
                            <span class="input-group-prepend">
                                <button class="btn default" type="button">
                                    <span class="fa fa-fw fa-calendar"></span>
                                </button>
                            </span>
                        </div>
                        <div class="input-group">
                            <input type="text" placeholder="hh:mm" name="end_time" v-model="end_time"
                                   class="next-field--connected next-input z-index-9 time-picker timepicker timepicker-24"
                                   :disabled="unlimited_time">
                            <span class="input-group-prepend">
                                <button class="btn default" type="button">
                                    <i class="fa fa-clock"></i>
                                </button>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="pd-all-10-20">
                    <label class="next-label disable-input-date-discount">
                        <input type="checkbox" class="hrv-checkbox" name="unlimited_time" value="1"
                               v-model="unlimited_time">{{ __('discount.never_expired')}}
                    </label>
                </div>
            </div>

            <br>
            <div class="wrapper-content">
                <div class="pd-all-20">
                    <button type="submit" class="btn btn-primary">{{ __('discount.save') }}</button>
                </div>
            </div>
        </div>
    </div>

</template>

<style>
    .date-time-group .invalid-feedback {
        display: none !important;
    }
</style>

<script>
    let moment = require('moment');

    export default {
        data: () => {
            return {
                title: null,
                code: null,
                is_promotion: false,
                type: 'coupon',
                is_unlimited: 1,
                quantity: 0,
                unlimited_time: 1,
                start_date: moment().format('DD-MM-Y'),
                start_time: '00:00',
                end_date: moment().format('DD-MM-Y'),
                end_time: '23:59',
                type_option: 'amount',
                discount_value: null,
                target: 'all-orders',
                can_use_with_promotion: false,
                value_label: 'Discount',
                variants: {
                    data: [],
                },
                selected_variants: [],
                selected_variant_ids: [],
                hidden_product_search_panel: true,
                product_collection_id: null,
                product_collections: [],
                discount_on: 'per-order',
                min_order_price: null,
                product_quantity: 1,
                products: {
                    data: [],
                },
                selected_products: [],
                selected_product_ids: [],
                product_keyword: null,
                customers: {
                    data: [],
                },
                selected_customers: [],
                selected_customer_ids: [],
                customer_keyword: null,
                hidden_customer_search_panel: true,
                loading: false,
                discountUnit: '$'
            }
        },
        props: {
            currency: {
                type: String,
                default: () => null,
                required: true
            },
        },
        mounted: function () {
            let context = this;
            $(document).on('click', 'body', e => {
                let container = $('.box-search-advance');

                if (!container.is(e.target) && container.has(e.target).length === 0) {
                    context.hidden_product_search_panel = true;
                    context.hidden_customer_search_panel = true;
                }
            });

            this.discountUnit = this.currency;
        },
        methods: {
            generateCouponCode: function (event) {
                event.preventDefault();
                let context = this;
                axios
                    .post(route('discounts.generate-coupon'))
                    .then(res => {
                        context.code = res.data.data;
                        context.title = null;
                        $('.coupon-code-input').closest('div').find('.invalid-feedback').remove();
                    })
                    .catch(res => {
                        Botble.handleError(res.response.data);
                    });
            },
            changeDiscountType: function () {
                let context = this;
                if (context.type === 'coupon') {
                    context.is_promotion = false;
                    context.code = context.title;
                    context.title = null;
                } else {
                    context.is_promotion = true;
                    context.title = context.code;
                    context.code = null;
                }
            },
            handleChangeTypeOption: function () {
                let context = this;

                context.discountUnit = this.currency;
                context.value_label = __('discount.discount');

                switch (context.type_option) {
                    case 'amount':
                        context.target = 'all-orders';
                        break;
                    case 'percentage':
                        context.target = 'all-orders';
                        context.discountUnit = '%';
                        break;
                    case 'shipping':
                        context.value_label = __('discount.when_shipping_fee_less_than');
                        break;
                    case 'same-price':
                        context.target = 'group-products';
                        context.value_label = __('discount.is');
                        context.getListProductCollections();
                        break;
                }
            },
            loadListProductsForSearch: function (include_variation = 1, page = 1, force = false) {
                let context = this;
                context.hidden_product_search_panel = false;
                $('.textbox-advancesearch').closest('.box-search-advance').find('.panel').removeClass('hidden');
                if (_.isEmpty(context.variants.data) || _.isEmpty(context.products.data) || force) {
                    context.loading = true;
                    axios
                        .get(route('products.get-list-products-for-select', {
                            keyword: context.product_keyword,
                            include_variation: include_variation,
                            page: page
                        }))
                        .then(res => {
                            if (include_variation) {
                                context.variants = res.data.data;
                            } else {
                                context.products = res.data.data;
                            }

                            context.loading = false;
                        })
                        .catch(res => {
                            Botble.handleError(res.response.data);
                        });
                }
            },
            handleSearchProduct: function (include_variation = 1, value) {
                if (value !== this.product_keyword) {
                    let context = this;
                    this.product_keyword = value;
                    setTimeout(() => {
                        context.loadListProductsForSearch(include_variation, 1, true);
                    }, 500);
                }
            },
            handleChangeTarget: function () {
                let context = this;
                switch (context.target) {
                    case 'group-products':
                        context.getListProductCollections();
                        break;
                    case 'specific-product':
                        context.selected_variant_ids = [];
                        context.selected_customers = [];
                        context.selected_customer_ids = [];
                        break;
                    case 'product-variant':
                        context.selected_products = [];
                        context.selected_product_id = [];
                        context.selected_customers = [];
                        context.selected_customer_ids = [];
                        break;
                    case 'customer':
                        context.selected_products = [];
                        context.selected_product_ids = [];
                        context.selected_variant_ids = [];
                        break;
                }
            },
            getListProductCollections: function () {
                let context = this;
                if (_.isEmpty(context.product_collections)) {
                    context.loading = true;
                    axios
                        .get(route('product-collections.get-list-product-collections-for-select'))
                        .then(res => {
                            context.product_collections = res.data.data;
                            if (!_.isEmpty(res.data.data)) {
                                context.product_collection_id = _.first(res.data.data).id;
                            }
                            context.loading = false;
                        })
                        .catch(res => {
                            Botble.handleError(res.response.data);
                        });
                }
            },
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
            handleSelectProducts: function (item) {
                if (!_.includes(this.selected_product_ids, item.id)) {
                    item.product_link = route('products.edit', item.id);
                    this.selected_products.push(item);
                    this.selected_product_ids.push(item.id);
                }
                this.hidden_product_search_panel = true;
            },
            handleRemoveProduct: function ($event, currentItem) {
                $event.preventDefault();
                this.selected_product_ids = _.reject(this.selected_product_ids, (item) => {
                    return item === currentItem.id;
                });

                this.selected_products = _.reject(this.selected_products, (item) => {
                    return item.id === currentItem.id;
                });
            },
            handleSelectCustomers: function (item) {
                if (!_.includes(this.selected_customer_ids, item.id)) {
                    item.customer_link = route('customers.edit', item.id);
                    this.selected_customers.push(item);
                    this.selected_customer_ids.push(item.id);
                }
                this.hidden_customer_search_panel = true;
            },
            handleRemoveCustomer: function ($event, currentItem) {
                $event.preventDefault();
                this.selected_customer_ids = _.reject(this.selected_customer_ids, (item) => {
                    return item === currentItem.id;
                });

                this.selected_customers = _.reject(this.selected_customers, (item) => {
                    return item.id === currentItem.id;
                });
            },
            handleSelectVariants: function (productVariant, variation) {
                if (!_.includes(this.selected_variant_ids, variation.product_id)) {
                    let variantItem = variation;
                    variantItem.product_name = productVariant.name;
                    variantItem.image_url = productVariant.image_url;
                    variantItem.product_link = route('products.edit', variation.configurable_product_id);
                    this.selected_variants.push(variantItem);
                    this.selected_variant_ids.push(variation.product_id);
                }
                this.hidden_product_search_panel = true;
            },
            handleRemoveVariant: function ($event, variant) {
                $event.preventDefault();
                this.selected_variant_ids = _.reject(this.selected_variant_ids, (item) => {
                    return item === variant.product_id;
                });

                this.selected_variants = _.reject(this.selected_variants, (item) => {
                    return item.product_id === variant.product_id;
                });
            }
        }
    }
</script>
