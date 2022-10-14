<?php

namespace Botble\Ecommerce\Http\Controllers;

use Assets;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Ecommerce\Http\Requests\StoreLocatorRequest;
use Botble\Ecommerce\Http\Requests\UpdatePrimaryStoreRequest;
use Botble\Ecommerce\Http\Requests\UpdateSettingsRequest;
use Botble\Ecommerce\Repositories\Interfaces\CurrencyInterface;
use Botble\Ecommerce\Repositories\Interfaces\StoreLocatorInterface;
use Botble\Ecommerce\Services\StoreCurrenciesService;
use Botble\Setting\Supports\SettingStore;
use EcommerceHelper;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Throwable;

class EcommerceController extends BaseController
{
    /**
     * @var StoreLocatorInterface
     */
    protected $storeLocatorRepository;

    /**
     * @var CurrencyInterface
     */
    protected $currencyRepository;

    /**
     * EcommerceController constructor.
     * @param StoreLocatorInterface $storeLocatorRepository
     * @param CurrencyInterface $currencyRepository
     */
    public function __construct(StoreLocatorInterface $storeLocatorRepository, CurrencyInterface $currencyRepository)
    {
        $this->storeLocatorRepository = $storeLocatorRepository;
        $this->currencyRepository = $currencyRepository;
    }

    /**
     * @return Factory|View
     */
    public function getSettings()
    {
        page_title()->setTitle(trans('plugins/ecommerce::ecommerce.basic_settings'));

        Assets::addScripts(['jquery-ui'])
            ->addScriptsDirectly([
                'vendor/core/plugins/ecommerce/js/currencies.js',
                'vendor/core/plugins/ecommerce/js/setting.js',
                'vendor/core/plugins/ecommerce/js/store-locator.js',
            ])
            ->addStylesDirectly([
                'vendor/core/plugins/ecommerce/css/ecommerce.css',
                'vendor/core/plugins/ecommerce/css/currencies.css',
            ]);

        if (EcommerceHelper::loadCountriesStatesCitiesFromPluginLocation()) {
            Assets::addScriptsDirectly('vendor/core/plugins/location/js/location.js');
        }

        $currencies = $this->currencyRepository
            ->getAllCurrencies()
            ->toArray();

        $storeLocators = $this->storeLocatorRepository->all();

        return view('plugins/ecommerce::settings.index', compact('currencies', 'storeLocators'));
    }

    /**
     * @return Factory|Application|View
     */
    public function getAdvancedSettings()
    {
        page_title()->setTitle(trans('plugins/ecommerce::ecommerce.advanced_settings'));

        Assets::addScripts(['jquery-ui'])
            ->addScriptsDirectly([
                'vendor/core/plugins/ecommerce/js/currencies.js',
                'vendor/core/plugins/ecommerce/js/setting.js',
            ])
            ->addStylesDirectly([
                'vendor/core/plugins/ecommerce/css/ecommerce.css',
            ]);

        if (EcommerceHelper::loadCountriesStatesCitiesFromPluginLocation()) {
            Assets::addScriptsDirectly('vendor/core/plugins/location/js/location.js');
        }

        return view('plugins/ecommerce::settings.advanced-settings');
    }

    /**
     * @return Factory|Application|View
     */
    public function getTrackingSettings()
    {
        page_title()->setTitle(trans('plugins/ecommerce::ecommerce.setting.tracking_settings'));

        Assets::addStylesDirectly([
            'vendor/core/plugins/ecommerce/css/ecommerce.css',
            'vendor/core/core/base/libraries/codemirror/lib/codemirror.css',
            'vendor/core/core/base/libraries/codemirror/addon/hint/show-hint.css',
            'vendor/core/packages/theme/css/custom-css.css',
        ])
            ->addScriptsDirectly([
                'vendor/core/plugins/ecommerce/js/setting.js',
                'vendor/core/core/base/libraries/codemirror/lib/codemirror.js',
                'vendor/core/core/base/libraries/codemirror/lib/javascript.js',
                'vendor/core/core/base/libraries/codemirror/addon/hint/show-hint.js',
                'vendor/core/core/base/libraries/codemirror/addon/hint/anyword-hint.js',
                'vendor/core/core/base/libraries/codemirror/addon/hint/javascript-hint.js',
                'vendor/core/packages/theme/js/custom-js.js',
            ]);

        return view('plugins/ecommerce::settings.tracking-settings');
    }

    /**
     * @param UpdateSettingsRequest $request
     * @param BaseHttpResponse $response
     * @param StoreCurrenciesService $service
     * @param SettingStore $settingStore
     * @return BaseHttpResponse
     * @throws Exception
     */
    public function postSettings(
        UpdateSettingsRequest  $request,
        BaseHttpResponse       $response,
        StoreCurrenciesService $service,
        SettingStore           $settingStore
    ) {
        foreach ($request->except([
            '_token',
            'currencies',
            'deleted_currencies',
        ]) as $settingKey => $settingValue) {
            $settingStore->set(EcommerceHelper::getSettingPrefix() . $settingKey, $settingValue);
        }

        $settingStore->save();

        $primaryStore = $this->storeLocatorRepository->getFirstBy(['is_primary' => 1]);

        if (!$primaryStore) {
            $primaryStore = $this->storeLocatorRepository->getModel();
            $primaryStore->is_primary = true;
            $primaryStore->is_shipping_location = true;
        }

        $primaryStore->name = $primaryStore->name ?? $request->input(
            'store_name',
            trans('plugins/ecommerce::store-locator.default_store')
        );
        $primaryStore->phone = $request->input('store_phone');
        $primaryStore->email = $primaryStore->email ?? get_admin_email()->first();
        $primaryStore->address = $request->input('store_address');
        $primaryStore->country = $request->input('store_country');
        $primaryStore->state = $request->input('store_state');
        $primaryStore->city = $request->input('store_city');
        $this->storeLocatorRepository->createOrUpdate($primaryStore);

        $currencies = json_decode($request->input('currencies'), true) ?: [];

        if (!$currencies) {
            return $response
                ->setNextUrl(route('ecommerce.settings'))
                ->setError()
                ->setMessage(trans('plugins/ecommerce::currency.require_at_least_one_currency'));
        }

        $deletedCurrencies = json_decode($request->input('deleted_currencies', []), true) ?: [];

        $response
            ->setNextUrl(route('ecommerce.settings'));

        $storedCurrencies = $service->execute($currencies, $deletedCurrencies);

        if ($storedCurrencies['error']) {
            return $response
                ->setError()
                ->setMessage($storedCurrencies['message']);
        }

        return $response
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @param SettingStore $settingStore
     * @return BaseHttpResponse
     */
    public function postAdvancedSettings(
        Request          $request,
        BaseHttpResponse $response,
        SettingStore     $settingStore
    ) {
        foreach ($request->except([
            '_token',
            'available_countries',
        ]) as $settingKey => $settingValue) {
            $settingStore->set(EcommerceHelper::getSettingPrefix() . $settingKey, $settingValue);
        }

        $settingStore->set(
            EcommerceHelper::getSettingPrefix() . 'available_countries',
            json_encode($request->input('available_countries'))
        );

        $settingStore->save();

        return $response
            ->setNextUrl(route('ecommerce.advanced-settings'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @param SettingStore $settingStore
     * @return BaseHttpResponse
     */
    public function postTrackingSettings(
        Request          $request,
        BaseHttpResponse $response,
        SettingStore     $settingStore
    ) {
        foreach ($request->except([
            '_token',
        ]) as $settingKey => $settingValue) {
            $settingStore->set(EcommerceHelper::getSettingPrefix() . $settingKey, $settingValue);
        }

        $settingStore->save();

        return $response
            ->setNextUrl(route('ecommerce.tracking-settings'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    /**
     * @param int $id
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     * @throws Throwable
     */
    public function getStoreLocatorForm(BaseHttpResponse $response, $id = null)
    {
        $locator = null;
        if ($id) {
            $locator = $this->storeLocatorRepository->findOrFail($id);
        }

        return $response->setData(view('plugins/ecommerce::settings.store-locator-item', compact('locator'))->render());
    }

    /**
     * @param int $id
     * @param StoreLocatorRequest $request
     * @param BaseHttpResponse $response
     * @param SettingStore $settingStore
     * @return BaseHttpResponse
     */
    public function postUpdateStoreLocator(
        $id,
        StoreLocatorRequest $request,
        BaseHttpResponse $response,
        SettingStore $settingStore
    ) {
        $request->merge([
            'is_shipping_location' => $request->has('is_shipping_location'),
        ]);

        $locator = $this->storeLocatorRepository->createOrUpdate($request->input(), compact('id'));

        if ($locator->is_primary) {
            $prefix = EcommerceHelper::getSettingPrefix();

            $settingStore
                ->set([
                    $prefix . 'store_phone'   => $locator->phone,
                    $prefix . 'store_address' => $locator->address,
                    $prefix . 'store_country' => $locator->country,
                    $prefix . 'store_state'   => $locator->state,
                    $prefix . 'store_city'    => $locator->city,
                ])
                ->save();
        }

        return $response->setMessage(trans('core/base::notices.update_success_message'));
    }

    /**
     * @param StoreLocatorRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function postCreateStoreLocator(StoreLocatorRequest $request, BaseHttpResponse $response)
    {
        $request->merge([
            'is_primary'           => false,
            'is_shipping_location' => $request->has('is_shipping_location'),
        ]);

        $this->storeLocatorRepository->createOrUpdate($request->input());

        return $response->setMessage(trans('core/base::notices.create_success_message'));
    }

    /**
     * @param int $id
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     * @throws Exception
     */
    public function postDeleteStoreLocator($id, BaseHttpResponse $response)
    {
        $this->storeLocatorRepository->deleteBy(compact('id'));

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }

    /**
     * @param UpdatePrimaryStoreRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function postUpdatePrimaryStore(UpdatePrimaryStoreRequest $request, BaseHttpResponse $response)
    {
        $this->storeLocatorRepository->update([['id', '!=', 0]], ['is_primary' => false]);
        $this->storeLocatorRepository->createOrUpdate(
            [
                'is_primary' => true,
            ],
            [
                'id' => $request->input('primary_store_id'),
            ]
        );

        return $response->setMessage(trans('core/base::notices.update_success_message'));
    }

    /**
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function ajaxGetCountries(BaseHttpResponse $response)
    {
        return $response->setData(EcommerceHelper::getAvailableCountries());
    }
}
