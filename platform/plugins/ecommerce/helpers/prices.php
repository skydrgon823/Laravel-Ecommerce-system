<?php

use Carbon\Carbon;
use Illuminate\Support\Arr;

if (!function_exists('get_product_price')) {
    /**
     * @param array $priceData
     * @return array
     */
    function get_product_price(array $priceData): array
    {
        $defaultSaleType = Arr::get($priceData, 'default_sale_type', 'none');
        $defaultStartDate = Arr::get($priceData, 'default_start_date');
        $defaultEndDate = Arr::get($priceData, 'default_end_date');

        $saleType = Arr::get($priceData, 'sale_type', 'default');
        $startDate = Arr::get($priceData, 'start_date');
        $endDate = Arr::get($priceData, 'end_date');

        $price = Arr::get($priceData, 'price', 0);
        $salePrice = Arr::get($priceData, 'sale_price', 0);

        $priceInfo = [
            'start_date' => null,
            'end_date'   => null,
            'old_price'  => null,
        ];

        if ($saleType == 'default') {
            $saleType = $defaultSaleType;
            $startDate = $defaultStartDate;
            $endDate = $defaultEndDate;
        }

        if ($saleType == 'none' || !$salePrice) {
            $priceInfo['price'] = $price;
            return $priceInfo;
        }

        if ($saleType == 'always') {
            $priceInfo['price'] = min($price, $salePrice);
            $priceInfo['old_price'] = max($salePrice, $price);
        } elseif (is_product_on_sale($saleType, $startDate, $endDate)) {
            $priceInfo['price'] = min($price, $salePrice);
            $priceInfo['old_price'] = max($salePrice, $price);
            $priceInfo['start_date'] = $startDate;
            $priceInfo['end_date'] = $endDate;
        } else {
            $priceInfo['price'] = max($price, $salePrice);
        }

        return $priceInfo;
    }
}

if (!function_exists('get_sale_percentage')) {
    /**
     * @param float|int $price
     * @param float|int $salePrice
     * @param bool $abs
     * @param bool $appendSymbol
     * @return string
     */
    function get_sale_percentage($price, $salePrice, bool $abs = false, bool $appendSymbol = true): string
    {
        $symbol = $appendSymbol ? '%' : '';

        if (!$salePrice) {
            return 0 . $symbol;
        }

        $down = $price - $salePrice;
        $result = $price > 0 ? ceil(-($down / $price) * 100) : 0;

        if ($abs === true) {
            return abs($result) . $symbol;
        }

        return $result . $symbol;
    }
}

if (!function_exists('is_product_on_sale')) {
    /**
     * @param string $saleStatus
     * @param null $startDate
     * @param null $endDate
     * @return bool
     */
    function is_product_on_sale(string $saleStatus, $startDate = null, $endDate = null): bool
    {
        if ($saleStatus == 'none' || !$endDate) {
            return false;
        }

        if ($saleStatus == 'always') {
            return true;
        }

        $now = Carbon::now();

        $startDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate);

        if ($now >= $endDate || $startDate > $now) {
            return false;
        }

        return true;
    }
}
