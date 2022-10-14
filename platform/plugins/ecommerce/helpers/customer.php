<?php

use Botble\Ecommerce\Models\Address;
use Botble\Ecommerce\Repositories\Interfaces\AddressInterface;
use Botble\Ecommerce\Repositories\Interfaces\WishlistInterface;
use Illuminate\Support\Collection;

if (!function_exists('is_added_to_wishlist')) {
    /**
     * @param int $productId
     * @return bool
     */
    function is_added_to_wishlist(int $productId): bool
    {
        if (!auth('customer')->check()) {
            return false;
        }

        return app(WishlistInterface::class)->count([
                'product_id'  => $productId,
                'customer_id' => auth('customer')->id(),
            ]) > 0;
    }
}

if (!function_exists('count_customer_addresses')) {
    /**
     * @return int
     */
    function count_customer_addresses(): int
    {
        if (!auth('customer')->check()) {
            return 0;
        }

        return app(AddressInterface::class)->count(['customer_id' => auth('customer')->id()]);
    }
}

if (!function_exists('get_customer_addresses')) {
    /**
     * @return Collection
     */
    function get_customer_addresses(): Collection
    {
        if (!auth('customer')->check()) {
            return collect([]);
        }

        return app(AddressInterface::class)->advancedGet([
            'condition' => [
                'customer_id' => auth('customer')->id(),
            ],
            'order_by'  => [
                'is_default' => 'DESC',
            ],
        ]);
    }
}

if (!function_exists('get_default_customer_address')) {
    /**
     * @return Address
     */
    function get_default_customer_address(): ?Address
    {
        if (!auth('customer')->check()) {
            return null;
        }

        return app(AddressInterface::class)->getFirstBy([
            'is_default'  => 1,
            'customer_id' => auth('customer')->id(),
        ]);
    }
}
