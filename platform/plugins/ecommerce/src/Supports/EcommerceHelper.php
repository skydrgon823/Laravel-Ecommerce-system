<?php

namespace Botble\Ecommerce\Supports;

use BaseHelper;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Supports\Helper;
use Botble\Ecommerce\Enums\OrderStatusEnum;
use Botble\Ecommerce\Enums\ProductTypeEnum;
use Botble\Ecommerce\Models\Customer;
use Botble\Ecommerce\Models\Product;
use Botble\Ecommerce\Repositories\Interfaces\ProductInterface;
use Botble\Ecommerce\Repositories\Interfaces\ProductVariationInterface;
use Botble\Ecommerce\Repositories\Interfaces\ReviewInterface;
use Botble\Location\Repositories\Interfaces\CityInterface;
use Botble\Location\Repositories\Interfaces\CountryInterface;
use Botble\Location\Repositories\Interfaces\StateInterface;
use Carbon\Carbon;
use Cart;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Theme;

class EcommerceHelper
{
    /**
     * @return bool
     */
    public function isCartEnabled(): bool
    {
        return get_ecommerce_setting('shopping_cart_enabled', 1) == 1;
    }

    /**
     * @return bool
     */
    public function isWishlistEnabled(): bool
    {
        return get_ecommerce_setting('wishlist_enabled', 1) == 1;
    }

    /**
     * @return bool
     */
    public function isCompareEnabled(): bool
    {
        return get_ecommerce_setting('compare_enabled', 1) == 1;
    }

    /**
     * @return bool
     */
    public function isReviewEnabled(): bool
    {
        return get_ecommerce_setting('review_enabled', 1) == 1;
    }

    /**
     * @return bool
     */
    public function isOrderTrackingEnabled(): bool
    {
        return get_ecommerce_setting('order_tracking_enabled', 1) == 1;
    }

    /**
     * @param bool $isConvertToKB
     * @return int
     */
    public function reviewMaxFileSize(bool $isConvertToKB = false): int
    {
        $size = (int)get_ecommerce_setting('review_max_file_size', 2);

        if (!$size) {
            $size = 2;
        } elseif ($size > 1024) {
            $size = 1024;
        }

        return $isConvertToKB ? $size * 1024 : $size;
    }

    /**
     * @return int
     */
    public function reviewMaxFileNumber(): int
    {
        $number = (int)get_ecommerce_setting('review_max_file_number', 6);

        if (!$number) {
            $number = 1;
        } elseif ($number > 100) {
            $number = 100;
        }

        return $number;
    }

    /**
     * @param int $productId
     * @param int $reviewsCount
     * @return Collection
     */
    public function getReviewsGroupedByProductId(int $productId, int $reviewsCount = 0): Collection
    {
        if ($reviewsCount) {
            $reviews = app(ReviewInterface::class)->getGroupedByProductId($productId);
        } else {
            $reviews = collect([]);
        }

        $results = collect([]);
        for ($i = 5; $i >= 1; $i--) {
            if ($reviewsCount) {
                $review = $reviews->firstWhere('star', $i);
                $starCount = $review ? $review->star_count : 0;
                if ($starCount > 0) {
                    $starCount = $starCount / $reviewsCount * 100;
                }
            } else {
                $starCount = 0;
            }

            $results[] = [
                'star'    => $i,
                'count'   => $starCount,
                'percent' => ((int)($starCount * 100)) / 100,
            ];
        }

        return $results;
    }

    /**
     * @return bool
     */
    public function isQuickBuyButtonEnabled(): bool
    {
        return get_ecommerce_setting('enable_quick_buy_button', 1) == 1;
    }

    /**
     * @return string
     */
    public function getQuickBuyButtonTarget(): string
    {
        return get_ecommerce_setting('quick_buy_target_page', 'checkout');
    }

    /**
     * @return bool
     */
    public function isZipCodeEnabled(): bool
    {
        return get_ecommerce_setting('zip_code_enabled', '0') == 1;
    }

    /**
     * @return bool
     */
    public function isBillingAddressEnabled(): bool
    {
        return get_ecommerce_setting('billing_address_enabled', '0') == 1;
    }

    /**
     * @return bool
     */
    public function isDisplayProductIncludingTaxes(): bool
    {
        if (!$this->isTaxEnabled()) {
            return false;
        }

        return get_ecommerce_setting('display_product_price_including_taxes', '0') == 1;
    }

    /**
     * @return bool
     */
    public function isTaxEnabled(): bool
    {
        return get_ecommerce_setting('ecommerce_tax_enabled', 1) == 1;
    }

    /**
     * @return array
     */
    public function getAvailableCountries(): array
    {
        $countries = ['' => __('Select country...')];

        if ($this->loadCountriesStatesCitiesFromPluginLocation()) {
            $selectedCountries = app(CountryInterface::class)
                ->getModel()
                ->where('status', BaseStatusEnum::PUBLISHED)
                ->orderBy('order', 'ASC')
                ->orderBy('name', 'ASC')
                ->pluck('name', 'id')
                ->all();

            if (!empty($selectedCountries)) {
                return $countries + $selectedCountries;
            }
        }

        try {
            $selectedCountries = json_decode(get_ecommerce_setting('available_countries'), true);
        } catch (Exception $exception) {
            $selectedCountries = [];
        }

        if (empty($selectedCountries)) {
            return $countries + Helper::countries();
        }

        foreach (Helper::countries() as $key => $item) {
            if (in_array($key, $selectedCountries)) {
                $countries[$key] = $item;
            }
        }

        return $countries;
    }

    /**
     * @param int|string $countryId
     * @return array
     */
    public function getAvailableStatesByCountry($countryId): array
    {
        if (!$this->loadCountriesStatesCitiesFromPluginLocation()) {
            return [];
        }

        $condition = [
            'status' => BaseStatusEnum::PUBLISHED,
        ];

        if ($this->isUsingInMultipleCountries()) {
            $condition['country_id'] = $countryId;
        }

        return app(StateInterface::class)
            ->getModel()
            ->where($condition)
            ->orderBy('order', 'ASC')
            ->orderBy('name', 'ASC')
            ->pluck('name', 'id')
            ->all();
    }

    /**
     * @param int|string $stateId
     * @return array
     */
    public function getAvailableCitiesByState($stateId): array
    {
        if (!$this->loadCountriesStatesCitiesFromPluginLocation()) {
            return [];
        }

        return app(CityInterface::class)
            ->getModel()
            ->where('status', BaseStatusEnum::PUBLISHED)
            ->where('state_id', $stateId)
            ->orderBy('order', 'ASC')
            ->orderBy('name', 'ASC')
            ->pluck('name', 'id')
            ->all();
    }

    /**
     * @return array
     */
    public function getSortParams(): array
    {
        $sort = [
            'default_sorting' => __('Default'),
            'date_asc'        => __('Oldest'),
            'date_desc'       => __('Newest'),
            'price_asc'       => __('Price: low to high'),
            'price_desc'      => __('Price: high to low'),
            'name_asc'        => __('Name: A-Z'),
            'name_desc'       => __('Name : Z-A'),
        ];

        if ($this->isReviewEnabled()) {
            $sort += [
                'rating_asc'  => __('Rating: low to high'),
                'rating_desc' => __('Rating: high to low'),
            ];
        }

        return $sort;
    }

    /**
     * @return array
     */
    public function getShowParams(): array
    {
        return apply_filters('ecommerce_number_of_products_display_options', [
            12 => 12,
            24 => 24,
            36 => 36,
        ]);
    }

    /**
     * @return float
     */
    public function getMinimumOrderAmount(): float
    {
        return (float)get_ecommerce_setting('minimum_order_amount', 0);
    }

    /**
     * @return bool
     */
    public function isEnabledGuestCheckout(): bool
    {
        return get_ecommerce_setting('enable_guest_checkout', 1) == 1;
    }

    /**
     * @return bool
     */
    public function showNumberOfProductsInProductSingle(): bool
    {
        return get_ecommerce_setting('show_number_of_products', 1) == 1;
    }

    /**
     * @return bool
     */
    public function showOutOfStockProducts(): bool
    {
        return get_ecommerce_setting('show_out_of_stock_products', 1) == 1;
    }

    /**
     * @param Request $request
     * @return array
     */
    public function getDateRangeInReport(Request $request): array
    {
        $startDate = Carbon::now()->subDays(29);
        $endDate = Carbon::now();

        if ($request->input('date_from')) {
            try {
                $startDate = Carbon::now()->createFromFormat('Y-m-d', $request->input('date_from'));
            } catch (Exception $exception) {
            }

            if (!$startDate) {
                $startDate = Carbon::now()->subDays(29);
            }
        }

        if ($request->input('date_to')) {
            try {
                $endDate = Carbon::now()->createFromFormat('Y-m-d', $request->input('date_to'));
            } catch (Exception $ex) {
            }

            if (!$endDate) {
                $endDate = Carbon::now();
            }
        }

        if ($endDate->gt(Carbon::now())) {
            $endDate = Carbon::now();
        }

        if ($startDate->gt($endDate)) {
            $startDate = Carbon::now()->subDays(29);
        }

        $predefinedRange = $request->input('predefined_range', trans('plugins/ecommerce::reports.ranges.last_30_days'));

        return [$startDate, $endDate, $predefinedRange];
    }

    /**
     * @return string
     */
    public function getSettingPrefix(): ?string
    {
        return config('plugins.ecommerce.general.prefix');
    }

    /**
     * @return bool
     */
    public function isPhoneFieldOptionalAtCheckout(): bool
    {
        return get_ecommerce_setting('make_phone_field_at_the_checkout_optional', 0) == 1;
    }

    /**
     * @return bool
     */
    public function isEnableEmailVerification(): bool
    {
        return get_ecommerce_setting('verify_customer_email', 0) == 1;
    }

    /**
     * @return bool
     */
    public function disableOrderInvoiceUntilOrderConfirmed(): bool
    {
        return get_ecommerce_setting('disable_order_invoice_until_order_confirmed', 0) == 1;
    }

    /**
     * @return string
     */
    public function getPhoneValidationRule(): string
    {
        $rule = BaseHelper::getPhoneValidationRule();

        if ($this->isPhoneFieldOptionalAtCheckout()) {
            return 'nullable|' . $rule;
        }

        return 'required|' . $rule;
    }

    /**
     * @param Product $product
     * @param int $star
     * @param int $perPage
     * @return Collection
     */
    public function getProductReviews(Product $product, int $star = 0, int $perPage = 10)
    {
        $condition = [
            'ec_reviews.status' => BaseStatusEnum::PUBLISHED,
        ];

        if ($star && $star >= 1 && $star <= 5) {
            $condition['ec_reviews.star'] = $star;
        }

        $ids = [$product->id];
        if ($product->variations->count()) {
            $ids = array_merge($ids, $product->variations->pluck('product_id')->toArray());
        }

        $reviews = app(ReviewInterface::class)
            ->getModel()
            ->select(['ec_reviews.*', 'ec_orders.created_at as order_created_at'])
            ->where($condition);

        if ($product->variations->count()) {
            $reviews
                ->whereHas('product.variations', function ($query) use ($ids) {
                    $query->whereIn('ec_product_variations.product_id', $ids);
                });
        } else {
            $reviews->where('ec_reviews.product_id', $product->id);
        }

        return $reviews
            ->leftJoin('ec_orders', function ($join) use ($ids) {
                $join
                    ->on('ec_orders.user_id', 'ec_reviews.customer_id')
                    ->where('ec_orders.status', OrderStatusEnum::COMPLETED)
                    ->join('ec_order_product', function ($join) use ($ids) {
                        $join
                            ->on('ec_order_product.order_id', 'ec_orders.id')
                            ->whereIn('ec_order_product.product_id', $ids);
                    });
            })
            ->with(['user'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage)
            ->onEachSide(1)
            ->appends(['star' => $star]);
    }

    /**
     * @return string
     */
    public function getThousandSeparatorForInputMask(): string
    {
        return ',';
    }

    /**
     * @return string
     */
    public function getDecimalSeparatorForInputMask(): string
    {
        return '.';
    }

    /**
     * @return array
     */
    public function withReviewsCount(): array
    {
        $withCount = [];
        if ($this->isReviewEnabled()) {
            $withCount = [
                'reviews',
                'reviews as reviews_avg' => function ($query) {
                    $query->select(DB::raw('avg(star)'));
                },
            ];
        }

        return $withCount;
    }

    /**
     * @return bool
     */
    public function loadCountriesStatesCitiesFromPluginLocation(): bool
    {
        if (!is_plugin_active('location')) {
            return false;
        }

        return get_ecommerce_setting('load_countries_states_cities_from_location_plugin', 0) == 1;
    }

    /**
     * @param $countryId
     * @return string|null
     */
    public function getCountryNameById($countryId): ?string
    {
        if (!$countryId) {
            return null;
        }

        if ($this->loadCountriesStatesCitiesFromPluginLocation()) {
            $countryName = app(CountryInterface::class)
                ->getModel()
                ->where('id', $countryId)
                ->value('name');

            if (!empty($countryName)) {
                return $countryName;
            }
        }

        return Helper::getCountryNameByCode($countryId);
    }

    /**
     * @param string|null $countryCode
     * @return array
     */
    public function getStates(?string $countryCode): array
    {
        if (!$countryCode || !$this->loadCountriesStatesCitiesFromPluginLocation()) {
            return [];
        }

        return app(StateInterface::class)
            ->getModel()
            ->whereHas('country', function ($query) use ($countryCode) {
                return $query->where('code', $countryCode);
            })
            ->where('status', BaseStatusEnum::PUBLISHED)
            ->orderBy('order', 'ASC')
            ->orderBy('name', 'ASC')
            ->pluck('name', 'id')
            ->all();
    }

    /**
     * @param int|string $stateId
     * @return array
     */
    public function getCities($stateId): array
    {
        if (!$stateId || !$this->loadCountriesStatesCitiesFromPluginLocation()) {
            return [];
        }

        return app(StateInterface::class)
            ->getModel()
            ->where('state_id', $stateId)
            ->where('status', BaseStatusEnum::PUBLISHED)
            ->orderBy('order', 'ASC')
            ->orderBy('name', 'ASC')
            ->pluck('name', 'id')
            ->all();
    }

    /**
     * @return bool
     */
    public function isUsingInMultipleCountries(): bool
    {
        return count($this->getAvailableCountries()) > 2;
    }

    /**
     * @return string|int
     */
    public function getFirstCountryId()
    {
        return Arr::first(array_filter(array_keys($this->getAvailableCountries())));
    }

    /**
     * @return array
     */
    public function getCustomerAddressValidationRules(): array
    {
        $rules = [
            'name'    => 'required|min:3|max:120',
            'email'   => 'email|nullable|max:60|min:6',
            'state'   => 'required|max:120',
            'city'    => 'required|max:120',
            'address' => 'required|max:120',
            'phone'   => $this->getPhoneValidationRule(),
        ];

        if ($this->isUsingInMultipleCountries()) {
            $rules['country'] = 'required|' . Rule::in(array_keys($this->getAvailableCountries()));
        }

        if ($this->isZipCodeEnabled()) {
            $rules['zip_code'] = 'required|max:20';
        }

        return $rules;
    }

    /**
     * @return bool
     */
    public function isEnabledCustomerRecentlyViewedProducts(): bool
    {
        return get_ecommerce_setting('enable_customer_recently_viewed_products', 1) == 1;
    }

    /**
     * @return int
     */
    public function maxCustomerRecentlyViewedProducts(): int
    {
        return (int)get_ecommerce_setting('max_customer_recently_viewed_products', 24);
    }

    /**
     * @param Product $product
     * @return $this
     */
    public function handleCustomerRecentlyViewedProduct(Product $product): self
    {
        if (!$this->isEnabledCustomerRecentlyViewedProducts()) {
            return $this;
        }

        $max = $this->maxCustomerRecentlyViewedProducts();

        if (!auth('customer')->check()) {
            $instance = Cart::instance('recently_viewed');

            $first = $instance->search(function ($cartItem) use ($product) {
                return $cartItem->id == $product->id;
            })->first();

            if ($first) {
                $instance->update($first->rowId, 1);
            } else {
                $instance->add($product->id, $product->name, 1, $product->front_sale_price)->associate(Product::class);
            }

            if ($max) {
                $content = collect($instance->content());
                if ($content->count() > $max) {
                    $content
                        ->sortBy([['updated_at', 'desc']])
                        ->skip($max)
                        ->each(function ($cartItem) use ($instance) {
                            $instance->remove($cartItem->rowId);
                        });
                }
            }
        } else {
            /**
             * @var Customer $customer
             */
            $customer = auth('customer')->user();
            $viewedProducts = $customer->viewedProducts;
            $exists = $viewedProducts->firstWhere('id', $product->id);

            $removedIds = [];

            if ($max) {
                if ($exists) {
                    $max -= 1;
                }

                if ($viewedProducts->count() >= $max) {
                    $filtered = $viewedProducts;
                    if ($exists) {
                        $filtered = $filtered->filter(function ($item) use ($product) {
                            return $item->id != $product->id;
                        });
                    }

                    $removedIds += $filtered->skip($max - 1)->pluck('id')->toArray();
                }
            }

            if ($exists) {
                $removedIds[] = $product->id;
            }

            if ($removedIds) {
                $customer->viewedProducts()->detach($removedIds);
            }

            $customer->viewedProducts()->attach([$product->id]);
        }

        return $this;
    }

    /**
     * @param Product $product
     * @return array
     */
    public function getProductVariationInfo(Product $product): array
    {
        $productImages = $product->images;

        $productVariation = $product;

        $selectedAttrs = [];

        $productVariationRepository = app(ProductVariationInterface::class);

        if ($product->variations()->count()) {
            if ($product->is_variation) {
                $product = $product->original_product;
                $selectedAttrs = $productVariationRepository->getAttributeIdsOfChildrenProduct($product->id);
                if (count($productImages) == 0) {
                    $productImages = $product->images;
                }
            } else {
                $selectedAttrs = $product->defaultVariation->productAttributes;
            }

            $selectedAttrIds = array_unique($selectedAttrs->pluck('id')->toArray());

            $variationDefault = $productVariationRepository->getVariationByAttributes($product->id, $selectedAttrIds);

            if ($variationDefault) {
                $productVariation = app(ProductInterface::class)->getProductVariations($product->id, [
                    'condition' => [
                        'ec_product_variations.id' => $variationDefault->id,
                        'original_products.status' => BaseStatusEnum::PUBLISHED,
                    ],
                    'select'    => [
                        'ec_products.id',
                        'ec_products.name',
                        'ec_products.quantity',
                        'ec_products.price',
                        'ec_products.sale_price',
                        'ec_products.allow_checkout_when_out_of_stock',
                        'ec_products.with_storehouse_management',
                        'ec_products.stock_status',
                        'ec_products.images',
                        'ec_products.sku',
                        'ec_products.description',
                        'ec_products.is_variation',
                    ],
                    'take'      => 1,
                ]);
            }
        }

        return [$productImages, $productVariation, $selectedAttrs];
    }

    /**
     * @return array
     */
    public function getProductsSearchBy(): array
    {
        $setting = get_ecommerce_setting('search_products_by');

        if (empty($setting)) {
            return ['name', 'sku', 'description'];
        }

        if (is_array($setting)) {
            return $setting;
        }

        return json_decode($setting, true);
    }

    /**
     * @param int|float $weight
     * @return int|float
     */
    public function validateOrderWeight($weight)
    {
        return max($weight, config('plugins.ecommerce.order.default_order_weight'));
    }

    /**
     * @return bool
     */
    public function isFacebookPixelEnabled(): bool
    {
        return get_ecommerce_setting('facebook_pixel_enabled', 0) == 1;
    }

    /**
     * @return bool
     */
    public function isGoogleTagManagerEnabled(): bool
    {
        return get_ecommerce_setting('google_tag_manager_enabled', 0) == 1;
    }

    /**
     * @return int
     */
    public function getReturnableDays(): int
    {
        return (int)get_ecommerce_setting('returnable_days', 30);
    }

    /**
     * @return int
     */
    public static function canCustomReturnProductQty(): int
    {
        return get_ecommerce_setting('can_custom_return_product_quantity', 0);
    }

    /**
     * @param Collection $products
     * @return bool
     */
    public function isAvailableShipping(Collection $products): bool
    {
        if (!$this->isEnabledSupportDigitalProducts()) {
            return true;
        }

        $count = $this->countDigitalProducts($products);

        return !$count || $products->count() != $count;
    }

    /**
     * @param Collection $products
     * @return int
     */
    public function countDigitalProducts(Collection $products): int
    {
        if (!$this->isEnabledSupportDigitalProducts()) {
            return 0;
        }

        return $products->where('product_type', ProductTypeEnum::DIGITAL)->count();
    }

    /**
     * @return bool
     */
    public function isEnabledSupportDigitalProducts(): bool
    {
        return !!get_ecommerce_setting('is_enabled_support_digital_products', 0);
    }

    /**
     * @param $request
     * @return bool
     */
    public function productFilterParamsValidated($request): bool
    {
        $validator = Validator::make($request->input(), [
            'q'          => 'nullable|string|max:255',
            'max_price'  => 'nullable|numeric',
            'min_price'  => 'nullable|numeric',
            'attributes' => 'nullable|array',
            'categories' => 'nullable|array',
            'tags'       => 'nullable|array',
            'brands'     => 'nullable|array',
            'sort-by'    => 'nullable|string',
        ]);

        return !$validator->fails();
    }

    /**
     * @param string $view
     * @return string
     */
    public function viewPath(string $view): string
    {
        $themeView = Theme::getThemeNamespace() . '::views.ecommerce.' . $view;

        if (view()->exists($themeView)) {
            return $themeView;
        }

        return 'plugins/ecommerce::themes.' . $view;
    }
}
