<?php

namespace Botble\Ecommerce\Services;

use Botble\Ecommerce\Repositories\Eloquent\CurrencyRepository;
use Botble\Ecommerce\Repositories\Interfaces\CurrencyInterface;
use CurrencyHelper;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class StoreCurrenciesService
{
    /**
     * @var CurrencyRepository
     */
    protected $currencyRepository;

    /**
     * StoreCurrenciesService constructor.
     * @param CurrencyInterface $currency
     */
    public function __construct(CurrencyInterface $currency)
    {
        $this->currencyRepository = $currency;
    }

    /**
     * @param array $currencies
     * @param array $deletedCurrencies
     * @return array
     * @throws Exception
     */
    public function execute(array $currencies, array $deletedCurrencies): array
    {
        $validated = Validator::make(
            $currencies,
            [
                '*.title'  => 'required|string|' . Rule::in(CurrencyHelper::currencyCodes()),
                '*.symbol' => 'required|string',
            ],
            [
                '*.title.in' => trans('plugins/ecommerce::currency.invalid_currency_name', [
                    'currencies' => implode(', ', CurrencyHelper::currencyCodes())
                ]),
            ],
            [
                '*.title'  => trans('plugins/ecommerce::currency.invalid_currency_name'),
                '*.symbol' => trans('plugins/ecommerce::currency.symbol'),
            ]
        );

        if ($validated->fails()) {
            return [
                'error'   => true,
                'message' => $validated->getMessageBag()->first(),
            ];
        }

        if ($deletedCurrencies) {
            $this->currencyRepository->deleteBy([
                ['id', 'IN', $deletedCurrencies],
            ]);
        }

        foreach ($currencies as $item) {
            if (!$item['title'] || !$item['symbol']) {
                continue;
            }

            $item['title'] = substr(strtoupper($item['title']), 0, 3);
            $item['decimals'] = (int)$item['decimals'];
            $item['decimals'] = $item['decimals'] < 10 ? $item['decimals'] : 2;

            if (count($currencies) == 1) {
                $item['is_default'] = 1;
            }

            $currency = $this->currencyRepository->findById($item['id']);

            if (!$currency) {
                $this->currencyRepository->create($item);
            } else {
                $currency->fill($item);
                $this->currencyRepository->createOrUpdate($currency);
            }
        }

        return [
            'error' => false,
        ];
    }
}
