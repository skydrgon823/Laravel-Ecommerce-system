<?php

use Botble\Ecommerce\Facades\CurrencyFacade;
use Botble\Ecommerce\Models\Currency;
use Botble\Ecommerce\Repositories\Interfaces\CurrencyInterface;
use Botble\Ecommerce\Supports\CurrencySupport;
use Illuminate\Support\Collection;

if (!function_exists('format_price')) {
    /**
     * @param float|int|null $price
     * @param Currency|null|string $currency
     * @param bool $withoutCurrency
     * @param bool $useSymbol
     * @return string
     */
    function format_price($price, $currency = null, bool $withoutCurrency = false, bool $useSymbol = true): string
    {
        if ($currency) {
            if (!$currency instanceof Currency) {
                $currency = app(CurrencyInterface::class)->getFirstBy(['id' => $currency]);
            }

            if (!$currency) {
                return human_price_text($price, $currency);
            }

            if ($currency->id != get_application_currency_id() && $currency->exchange_rate > 0) {
                $currentCurrency = get_application_currency();

                if ($currentCurrency->is_default) {
                    $price = $price / $currency->exchange_rate;
                } else {
                    $price = $price / $currency->exchange_rate * $currentCurrency->exchange_rate;
                }

                $currency = $currentCurrency;
            }
        } else {
            $currency = get_application_currency();

            if (!$currency) {
                return human_price_text($price, $currency);
            }

            if (!$currency->is_default && $currency->exchange_rate > 0) {
                $price = $price * $currency->exchange_rate;
            }
        }

        if ($withoutCurrency) {
            return $price;
        }

        if ($useSymbol && $currency->is_prefix_symbol) {
            $space = get_ecommerce_setting('add_space_between_price_and_currency', 0) == 1 ? ' ' : null;

            return $currency->symbol . $space . human_price_text($price, $currency);
        }

        return human_price_text($price, $currency, ($useSymbol ? $currency->symbol : $currency->title));
    }
}

if (!function_exists('human_price_text')) {
    /**
     * @param float|null|mixed $price
     * @param Currency|null|string $currency
     * @param string $priceUnit
     * @return string
     */
    function human_price_text($price, $currency, string $priceUnit = ''): string
    {
        $numberAfterDot = ($currency instanceof Currency) ? $currency->decimals : 0;

        if (config('plugins.ecommerce.general.display_big_money_in_million_billion')) {
            if ($price >= 1000000 && $price < 1000000000) {
                $price = round($price / 1000000, 2) + 0;
                $priceUnit = __('million') . ' ' . $priceUnit;
                $numberAfterDot = strlen(substr(strrchr($price, '.'), 1));
            } elseif ($price >= 1000000000) {
                $price = round($price / 1000000000, 2) + 0;
                $priceUnit = __('billion') . ' ' . $priceUnit;
                $numberAfterDot = strlen(substr(strrchr($price, '.'), 1));
            }
        }

        if (is_numeric($price)) {
            $price = preg_replace('/[^0-9,.]/s', '', $price);
        }

        $decimalSeparator = get_ecommerce_setting('decimal_separator', '.');

        if ($decimalSeparator == 'space') {
            $decimalSeparator = ' ';
        }

        $thousandSeparator = get_ecommerce_setting('thousands_separator', ',');

        if ($thousandSeparator == 'space') {
            $thousandSeparator = ' ';
        }

        $price = number_format(
            $price,
            (int)$numberAfterDot,
            $decimalSeparator,
            $thousandSeparator
        );

        $space = get_ecommerce_setting('add_space_between_price_and_currency', 0) == 1 ? ' ' : null;

        return $price . $space . ($priceUnit ?: '');
    }
}

if (!function_exists('get_current_exchange_rate')) {
    /**
     * @param null $currency
     */
    function get_current_exchange_rate($currency = null)
    {
        if (!$currency) {
            $currency = get_application_currency();
        } elseif (!$currency instanceof Currency) {
            $currency = app(CurrencyInterface::class)->getFirstBy(['id' => $currency]);
        }

        if (!$currency->is_default && $currency->exchange_rate > 0) {
            return $currency->exchange_rate;
        }

        return 1;
    }
}

if (!function_exists('cms_currency')) {
    /**
     * @return CurrencySupport
     */
    function cms_currency(): CurrencySupport
    {
        return CurrencyFacade::getFacadeRoot();
    }
}

if (!function_exists('get_all_currencies')) {
    /**
     * @return Collection
     */
    function get_all_currencies(): Collection
    {
        return cms_currency()->currencies();
    }
}

if (!function_exists('get_application_currency')) {
    /**
     * @return Currency|null
     */
    function get_application_currency(): ?Currency
    {
        $currency = cms_currency()->getApplicationCurrency();

        if (is_in_admin() || !$currency) {
            $currency = cms_currency()->getDefaultCurrency();
        }

        return $currency;
    }
}

if (!function_exists('get_application_currency_id')) {
    /**
     * @return int|null
     */
    function get_application_currency_id(): ?int
    {
        return get_application_currency()->id;
    }
}
