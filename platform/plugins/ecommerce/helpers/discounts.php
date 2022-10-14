<?php

use Botble\Ecommerce\Models\Discount;
use Botble\Ecommerce\Models\DiscountCustomer;
use Botble\Ecommerce\Models\DiscountProduct;
use Botble\Ecommerce\Models\DiscountProductCollection;

if (!function_exists('get_discount_description')) {
    /**
     * @param Discount $discount
     * @return string
     */
    function get_discount_description(Discount $discount)
    {
        switch ($discount->type_option) {
            case 'shipping':
                if ($discount->target) {
                    $description = __('Free shipping to') . ' <strong>' . $discount->target . '</strong>';
                } else {
                    $description = __('Free shipping for all orders');
                }

                $description .= ' ' . __('when shipping fee less than or equal') . ' ' . format_price($discount->value);
                break;
            case 'same-price':
                $description = __('Same fee') . ' ' . format_price($discount->value) . ' ';
                switch ($discount->target) {
                    case 'group-products':
                        $collections = DiscountProductCollection::where('discount_id', $discount->id)
                            ->join(
                                'ec_product_collections',
                                'ec_product_collections.id',
                                '=',
                                'ec_discount_product_collections.product_collection_id'
                            )
                            ->pluck('ec_product_collections.name')
                            ->all();

                        $description .= __('for all product in collection') . ' ' . implode(', ', $collections);
                        break;
                    default:
                        $description .= __('for all products in order');
                        break;
                }
                break;
            default:
                if ($discount->type_option === 'percentage') {
                    $description = __('Discount') . ' ' . $discount->value . '% ';
                } else {
                    $description = __('Discount') . ' ' . format_price($discount->value) . ' ';
                }

                switch ($discount->target) {
                    case 'amount-minimum-order':
                        $description .= __('for order with amount from') . ' ' . format_price($discount->min_order_price);
                        break;
                    case 'specific-product':
                        $products = DiscountProduct::where('discount_id', $discount->id)
                            ->join('ec_products', 'ec_products.id', '=', 'ec_discount_products.product_id')
                            ->where('ec_products.is_variation', 0)
                            ->pluck('ec_products.name')
                            ->all();

                        $description .= __('for product(s)') . ' ' . implode(', ', array_unique($products));
                        break;
                    case 'customer':
                        $customers = DiscountCustomer::where('discount_id', $discount->id)
                            ->join('ec_customers', 'ec_customers.id', '=', 'ec_discount_customers.customer_id')
                            ->pluck('ec_customers.name')
                            ->all();

                        $description .= __('for customer(s)') . ' ' . implode(', ', $customers);
                        break;
                    case 'group-products':
                        $collections = DiscountProductCollection::where('discount_id', $discount->id)
                            ->join(
                                'ec_product_collections',
                                'ec_product_collections.id',
                                '=',
                                'ec_discount_product_collections.product_collection_id'
                            )
                            ->pluck('ec_product_collections.name')
                            ->all();

                        $description .= __('for all products in collection') . ' ' . implode(', ', $collections);
                        break;
                    case 'product-variant':
                        $products = DiscountProduct::where('discount_id', $discount->id)
                            ->join('ec_products', 'ec_products.id', '=', 'ec_discount_products.product_id')
                            ->pluck('ec_products.name')
                            ->all();

                        $description .= __('for product(s) variant') . ' ' . implode(', ', array_unique($products));
                        break;
                    default:
                        $description .= __('for all orders');
                        break;
                }
        }

        return $description;
    }
}
