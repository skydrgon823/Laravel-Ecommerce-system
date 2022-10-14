<?php

namespace Botble\Ecommerce\Http\Controllers\Fronts;

use BaseHelper;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Base\Supports\Helper;
use Botble\Ecommerce\Http\Resources\ProductVariationResource;
use Botble\Ecommerce\Models\Brand;
use Botble\Ecommerce\Models\Product;
use Botble\Ecommerce\Models\ProductCategory;
use Botble\Ecommerce\Models\ProductTag;
use Botble\Ecommerce\Repositories\Interfaces\BrandInterface;
use Botble\Ecommerce\Repositories\Interfaces\OrderInterface;
use Botble\Ecommerce\Repositories\Interfaces\ProductAttributeSetInterface;
use Botble\Ecommerce\Repositories\Interfaces\ProductCategoryInterface;
use Botble\Ecommerce\Repositories\Interfaces\ProductInterface;
use Botble\Ecommerce\Repositories\Interfaces\ProductTagInterface;
use Botble\Ecommerce\Repositories\Interfaces\ProductVariationInterface;
use Botble\Ecommerce\Repositories\Interfaces\ProductVariationItemInterface;
use Botble\Ecommerce\Services\Products\GetProductService;
use Botble\SeoHelper\SeoOpenGraph;
use Botble\Slug\Repositories\Interfaces\SlugInterface;
use EcommerceHelper;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use ProductCategoryHelper;
use Response;
use RvMedia;
use SeoHelper;
use SlugHelper;
use Theme;
use Throwable;

class PublicProductController
{
    /**
     * @var ProductInterface
     */
    protected $productRepository;

    /**
     * @var ProductCategoryInterface
     */
    protected $productCategoryRepository;

    /**
     * @var ProductAttributeSetInterface
     */
    protected $productAttributeSetRepository;

    /**
     * @var BrandInterface
     */
    protected $brandRepository;

    /**
     * @var ProductVariationInterface
     */
    protected $productVariationRepository;

    /**
     * @var SlugInterface
     */
    protected $slugRepository;

    /**
     * PublicProductController constructor.
     * @param ProductInterface $productRepository
     * @param ProductCategoryInterface $productCategoryRepository
     * @param ProductAttributeSetInterface $productAttributeSet
     * @param BrandInterface $brandRepository
     * @param ProductVariationInterface $productVariationRepository
     * @param SlugInterface $slugRepository
     */
    public function __construct(
        ProductInterface             $productRepository,
        ProductCategoryInterface     $productCategoryRepository,
        ProductAttributeSetInterface $productAttributeSet,
        BrandInterface               $brandRepository,
        ProductVariationInterface    $productVariationRepository,
        SlugInterface                $slugRepository
    ) {
        $this->productRepository = $productRepository;
        $this->productCategoryRepository = $productCategoryRepository;
        $this->productAttributeSetRepository = $productAttributeSet;
        $this->brandRepository = $brandRepository;
        $this->productVariationRepository = $productVariationRepository;
        $this->slugRepository = $slugRepository;
    }

    /**
     * @param Request $request
     * @param GetProductService $productService
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse|Response
     * @throws Throwable
     */
    public function getProducts(Request $request, GetProductService $productService, BaseHttpResponse $response)
    {
        if (!EcommerceHelper::productFilterParamsValidated($request)) {
            return $response->setNextUrl(route('public.products'));
        }

        $query = $request->input('q');

        $with = [
            'slugable',
            'variations',
            'productLabels',
            'variationAttributeSwatchesForProductList',
            'productCollections',
        ];

        if (is_plugin_active('marketplace')) {
            $with = array_merge($with, ['store', 'store.slugable']);
        }

        $withCount = EcommerceHelper::withReviewsCount();

        if ($query && !$request->ajax()) {
            $products = $productService->getProduct($request, null, null, $with, $withCount);

            SeoHelper::setTitle(__('Search result for ":query"', compact('query')));

            Theme::breadcrumb()
                ->add(__('Home'), route('public.index'))
                ->add(__('Search'), route('public.products'));

            return Theme::scope(
                'ecommerce.search',
                compact('products', 'query'),
                'plugins/ecommerce::themes.search'
            )->render();
        }

        $products = $productService->getProduct($request, null, null, $with, $withCount);

        if ($request->ajax()) {
            return $this->ajaxFilterProductsResponse($products, $request, $response);
        }

        Theme::breadcrumb()
            ->add(__('Home'), route('public.index'))
            ->add(__('Products'), route('public.products'));

        SeoHelper::setTitle(__('Products'))->setDescription(__('Products'));

        do_action(PRODUCT_MODULE_SCREEN_NAME);

        return Theme::scope(
            'ecommerce.products',
            compact('products'),
            'plugins/ecommerce::themes.products'
        )->render();
    }

    /**
     * @param string $slug
     * @param Request $request
     * @return Response|RedirectResponse
     */
    public function getProduct($slug, Request $request)
    {
        $slug = $this->slugRepository->getFirstBy([
            'key'            => $slug,
            'reference_type' => Product::class,
            'prefix'         => SlugHelper::getPrefix(Product::class),
        ]);

        if (!$slug) {
            abort(404);
        }

        $condition = [
            'ec_products.id'     => $slug->reference_id,
            'ec_products.status' => BaseStatusEnum::PUBLISHED,
        ];

        if (Auth::check() && $request->input('preview')) {
            Arr::forget($condition, 'ec_products.status');
        }

        $product = get_products([
            'condition' => $condition,
            'take'      => 1,
            'with'      => [
                'slugable',
                'tags',
                'tags.slugable',
                'categories',
                'categories.slugable',
            ],
            'withCount' => EcommerceHelper::withReviewsCount(),
        ]);

        if (!$product) {
            abort(404);
        }

        if ($product->slugable->key !== $slug->key) {
            return redirect()->to($product->url);
        }

        SeoHelper::setTitle($product->name)->setDescription($product->description);

        $meta = new SeoOpenGraph();
        if ($product->image) {
            $meta->setImage(RvMedia::getImageUrl($product->image));
        }
        $meta->setDescription($product->description);
        $meta->setUrl($product->url);
        $meta->setTitle($product->name);

        SeoHelper::setSeoOpenGraph($meta);

        Helper::handleViewCount($product, 'viewed_product');
        EcommerceHelper::handleCustomerRecentlyViewedProduct($product);

        Theme::breadcrumb()
            ->add(__('Home'), route('public.index'))
            ->add(__('Products'), route('public.products'));

        $category = $product->categories->sortByDesc('id')->first();

        if ($category) {
            if ($category->parents->count()) {
                foreach ($category->parents->reverse() as $parentCategory) {
                    Theme::breadcrumb()->add($parentCategory->name, $parentCategory->url);
                }
            }

            Theme::breadcrumb()->add($category->name, $category->url);
        }

        Theme::breadcrumb()->add($product->name, $product->url);

        admin_bar()
            ->registerLink(
                trans('plugins/ecommerce::products.edit_this_product'),
                route('products.edit', $product->id)
            );

        do_action(BASE_ACTION_PUBLIC_RENDER_SINGLE, PRODUCT_MODULE_SCREEN_NAME, $product);

        [$productImages, $productVariation, $selectedAttrs] = EcommerceHelper::getProductVariationInfo($product);

        return Theme::scope(
            'ecommerce.product',
            compact('product', 'selectedAttrs', 'productImages', 'productVariation'),
            'plugins/ecommerce::themes.product'
        )
            ->render();
    }

    /**
     * @param string $slug
     * @param Request $request
     * @param ProductTagInterface $tagRepository
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse|RedirectResponse|Response
     * @throws Throwable
     */
    public function getProductTag(
        $slug,
        Request $request,
        ProductTagInterface $tagRepository,
        BaseHttpResponse $response
    ) {
        $slug = $this->slugRepository->getFirstBy([
            'key'            => $slug,
            'reference_type' => ProductTag::class,
            'prefix'         => SlugHelper::getPrefix(ProductTag::class),
        ]);

        if (!$slug) {
            abort(404);
        }

        $condition = [
            'ec_product_categories.id'     => $slug->reference_id,
            'ec_product_categories.status' => BaseStatusEnum::PUBLISHED,
        ];

        if (Auth::check() && $request->input('preview')) {
            Arr::forget($condition, 'ec_product_categories.status');
        }

        $tag = $tagRepository->getFirstBy(['id' => $slug->reference_id], ['*'], ['slugable', 'products']);

        if (!$tag) {
            abort(404);
        }

        if ($tag->slugable->key !== $slug->key) {
            return redirect()->to($tag->url);
        }

        if (!EcommerceHelper::productFilterParamsValidated($request)) {
            return $response->setNextUrl($tag->url);
        }

        $withCount = EcommerceHelper::withReviewsCount();

        $products = $this->productRepository->getProductByTags([
            'product_tag' => [
                'by'       => 'id',
                'value_in' => [$tag->id],
            ],
            'paginate'    => [
                'per_page'      => (int)theme_option('number_of_products_per_page', 12),
                'current_paged' => (int)$request->input('page', 1),
            ],
            'with'        => [
                'slugable',
                'variations',
                'productLabels',
                'variationAttributeSwatchesForProductList',
                'productCollections',
            ],
            'withCount'   => $withCount,
        ]);

        if ($request->ajax()) {
            return $this->ajaxFilterProductsResponse($products, $request, $response);
        }

        SeoHelper::setTitle($tag->name)->setDescription($tag->description);

        $meta = new SeoOpenGraph();
        $meta->setDescription($tag->description);
        $meta->setUrl($tag->url);
        $meta->setTitle($tag->name);

        SeoHelper::setSeoOpenGraph($meta);

        Theme::breadcrumb()
            ->add(__('Home'), route('public.index'))
            ->add(__('Products'), route('public.products'))
            ->add($tag->name, $tag->url);

        do_action(BASE_ACTION_PUBLIC_RENDER_SINGLE, PRODUCT_TAG_MODULE_SCREEN_NAME, $tag);

        return Theme::scope(
            'ecommerce.product-tag',
            compact('tag', 'products'),
            'plugins/ecommerce::themes.product-tag'
        )->render();
    }

    /**
     * @param string $slug
     * @param Request $request
     * @param ProductCategoryInterface $categoryRepository
     * @param GetProductService $getProductService
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse|RedirectResponse|Response
     * @throws Throwable
     */
    public function getProductCategory(
        $slug,
        Request $request,
        ProductCategoryInterface $categoryRepository,
        GetProductService $getProductService,
        BaseHttpResponse $response
    ) {
        $slug = $this->slugRepository->getFirstBy([
            'key'            => $slug,
            'reference_type' => ProductCategory::class,
            'prefix'         => SlugHelper::getPrefix(ProductCategory::class),
        ]);

        if (!$slug) {
            abort(404);
        }

        $condition = [
            'ec_product_categories.id'     => $slug->reference_id,
            'ec_product_categories.status' => BaseStatusEnum::PUBLISHED,
        ];

        if (Auth::check() && $request->input('preview')) {
            Arr::forget($condition, 'ec_product_categories.status');
        }

        $category = $categoryRepository->getFirstBy($condition, ['*'], ['slugable']);

        if (!$category) {
            abort(404);
        }

        if ($category->slugable->key !== $slug->key) {
            return redirect()->to($category->url);
        }

        if (!EcommerceHelper::productFilterParamsValidated($request)) {
            return $response->setNextUrl($category->url);
        }

        $with = [
            'slugable',
            'variations',
            'productLabels',
            'variationAttributeSwatchesForProductList',
            'productCollections',
        ];

        if (is_plugin_active('marketplace')) {
            $with = array_merge($with, ['store', 'store.slugable']);
        }

        $request->merge(['categories' => $category->getChildrenIds($category, [$category->id])]);

        $products = $getProductService->getProduct($request, null, null, $with, EcommerceHelper::withReviewsCount());

        SeoHelper::setTitle($category->name)->setDescription($category->description);

        $meta = new SeoOpenGraph();
        if ($category->image) {
            $meta->setImage(RvMedia::getImageUrl($category->image));
        }
        $meta->setDescription($category->description);
        $meta->setUrl($category->url);
        $meta->setTitle($category->name);

        SeoHelper::setSeoOpenGraph($meta);

        Theme::breadcrumb()
            ->add(__('Home'), route('public.index'))
            ->add(__('Products'), route('public.products'));

        if ($category->parents->count()) {
            foreach ($category->parents->reverse() as $parentCategory) {
                Theme::breadcrumb()->add($parentCategory->name, $parentCategory->url);
            }
        }

        Theme::breadcrumb()->add($category->name, $category->url);

        do_action(BASE_ACTION_PUBLIC_RENDER_SINGLE, PRODUCT_CATEGORY_MODULE_SCREEN_NAME, $category);

        if ($request->ajax()) {
            return $this->ajaxFilterProductsResponse($products, $request, $response);
        }

        return Theme::scope(
            'ecommerce.product-category',
            compact('category', 'products'),
            'plugins/ecommerce::themes.product-category'
        )->render();
    }

    /**
     * @param int $id
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function getProductVariation($id, Request $request, BaseHttpResponse $response)
    {
        $attributes = $request->input('attributes', []);

        if (empty($attributes)) {
            return $response
                ->setError()
                ->setMessage(__('Not available'));
        }

        $variation = $this->productVariationRepository->getVariationByAttributes($id, $attributes);

        $product = null;

        if ($variation) {
            $product = $this->productRepository->getProductVariations($id, [
                'condition' => [
                    'ec_product_variations.id' => $variation->id,
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
                    'original_products.images as original_images',
                    'ec_products.height',
                    'ec_products.weight',
                    'ec_products.wide',
                    'ec_products.length',
                ],
                'take'      => 1,
            ]);

            if ($product) {
                if ($product->images) {
                    $images = $product->images;

                    if (get_ecommerce_setting('how_to_display_product_variation_images') == 'variation_images_and_main_product_images') {
                        $images = array_merge($images, json_decode($product->original_images));
                    }

                    $product->image_with_sizes = rv_get_image_list($images, [
                        'origin',
                        'thumb',
                    ]);
                } else {
                    $originalImages = json_decode($product->original_images);
                    $product->image_with_sizes = rv_get_image_list($originalImages, [
                        'origin',
                        'thumb',
                    ]);
                }
            }
        }

        if ($product) {
            if ($product->isOutOfStock()) {
                $product->errorMessage = __('Out of stock');
            }

            if (!$product->with_storehouse_management || $product->quantity < 1) {
                $product->successMessage = __('In stock');
            } elseif ($product->quantity) {
                if (EcommerceHelper::showNumberOfProductsInProductSingle()) {
                    if ($product->quantity != 1) {
                        $product->successMessage = __(':number products available', ['number' => $product->quantity]);
                    } else {
                        $product->successMessage = __(':number product available', ['number' => $product->quantity]);
                    }
                } else {
                    $product->successMessage = __('In stock');
                }
            }

            $originalProduct = $product->original_product;
        } else {
            $originalProduct = $this->productRepository->advancedGet([
                'condition' => [
                    'ec_products.id'     => $id,
                    'ec_products.status' => BaseStatusEnum::PUBLISHED,
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
                    'ec_products.height',
                    'ec_products.weight',
                    'ec_products.wide',
                    'ec_products.length',
                ],
                'take'      => 1,
            ]);

            if ($originalProduct) {
                if ($originalProduct->images) {
                    $originalProduct->image_with_sizes = rv_get_image_list($originalProduct->images, [
                        'origin',
                        'thumb',
                    ]);
                }

                $originalProduct->errorMessage = __('Please select attributes');
            }
        }

        if (!$originalProduct) {
            return $response->setError()->setMessage(__('Not available'));
        }

        $productAttributes = $this->productRepository->getRelatedProductAttributes($originalProduct)->sortBy('order');

        $attributeSets = $originalProduct->productAttributeSets()->orderBy('order')->get();

        $productVariations = app(ProductVariationInterface::class)->allBy([
            'configurable_product_id' => $originalProduct->id,
        ]);

        $productVariationsInfo = app(ProductVariationItemInterface::class)
            ->getVariationsInfo($productVariations->pluck('id')->toArray());

        $variationInfo = $productVariationsInfo;

        $unavailableAttributeIds = [];
        $variationNextIds = [];
        foreach ($attributeSets as $key => $set) {
            if ($key != 0) {
                $variationInfo = $productVariationsInfo
                    ->where('attribute_set_id', $set->id)
                    ->whereIn('variation_id', $variationNextIds);
            }
            [$variationNextIds, $unavailableAttributeIds] = handle_next_attributes_in_product(
                $productAttributes->where('attribute_set_id', $set->id),
                $productVariationsInfo,
                $set->id,
                $attributes,
                $key,
                $variationNextIds,
                $variationInfo,
                $unavailableAttributeIds
            );
        }

        if (!$product) {
            $product = $originalProduct;
        }

        $product->unavailableAttributeIds = $unavailableAttributeIds;

        return $response
            ->setData(new ProductVariationResource($product));
    }

    /**
     * @param string $slug
     * @param Request $request
     * @param GetProductService $getProductService
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse|RedirectResponse|Response
     * @throws Throwable
     */
    public function getBrand($slug, Request $request, GetProductService $getProductService, BaseHttpResponse $response)
    {
        $slug = $this->slugRepository->getFirstBy([
            'key'            => $slug,
            'reference_type' => Brand::class,
            'prefix'         => SlugHelper::getPrefix(Brand::class),
        ]);

        if (!$slug) {
            abort(404);
        }

        $brand = $this->brandRepository->getFirstBy(['id' => $slug->reference_id], ['*'], ['slugable']);

        if (!$brand) {
            abort(404);
        }

        if ($brand->slugable->key !== $slug->key) {
            return redirect()->to($brand->url);
        }

        if (!EcommerceHelper::productFilterParamsValidated($request)) {
            return $response->setNextUrl($brand->url);
        }

        $products = $getProductService->getProduct(
            $request,
            null,
            $brand->id,
            [
                'slugable',
                'variations',
                'productLabels',
                'variationAttributeSwatchesForProductList',
                'productCollections',
            ],
            EcommerceHelper::withReviewsCount()
        );

        if ($request->ajax()) {
            return $this->ajaxFilterProductsResponse($products, $request, $response);
        }

        SeoHelper::setTitle($brand->name)->setDescription($brand->description);

        Theme::breadcrumb()->add(__('Home'), route('public.index'))->add($brand->name, $brand->url);

        $meta = new SeoOpenGraph();
        if ($brand->logo) {
            $meta->setImage(RvMedia::getImageUrl($brand->logo));
        }
        $meta->setDescription($brand->description);
        $meta->setUrl($brand->url);
        $meta->setTitle($brand->name);

        SeoHelper::setSeoOpenGraph($meta);

        do_action(BASE_ACTION_PUBLIC_RENDER_SINGLE, BRAND_MODULE_SCREEN_NAME, $brand);

        return Theme::scope('ecommerce.brand', compact('brand', 'products'), 'plugins/ecommerce::themes.brand')
            ->render();
    }

    /**
     * @param mixed $products
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     * @throws Throwable
     */
    protected function ajaxFilterProductsResponse($products, Request $request, BaseHttpResponse $response)
    {
        $total = $products->total();
        $message = $total > 1 ? __(':total Products found', compact('total')) : __(
            ':total Product found',
            compact('total')
        );

        $view = Theme::getThemeNamespace('views.ecommerce.includes.product-items');

        if (!view()->exists($view)) {
            $view = 'plugins/ecommerce::themes.includes.product-items';
        }

        $additional = [
            'breadcrumb' => view()->exists(Theme::getThemeNamespace() . '::partials.breadcrumbs') ? Theme::partial('breadcrumbs') : Theme::breadcrumb()
                ->render(),
        ];

        $categoryTree = Theme::getThemeNamespace() . '::views.ecommerce.includes.categories';

        if (view()->exists($categoryTree)) {
            $categoriesRequest = $request->input('categories', []);

            if ($categoriesRequest) {
                $categories = $this->productCategoryRepository
                    ->getModel()
                    ->whereIn('id', $categoriesRequest)
                    ->where('status', BaseStatusEnum::PUBLISHED)
                    ->with(['slugable', 'children:id,name,parent_id', 'children.slugable'])
                    ->orderBy('parent_id', 'ASC')
                    ->limit(1)
                    ->get();
            } else {
                $categories = ProductCategoryHelper::getAllProductCategories()
                    ->where('status', BaseStatusEnum::PUBLISHED)
                    ->whereIn('parent_id', [0, null])
                    ->loadMissing(['slugable', 'children:id,name,parent_id', 'children.slugable']);
            }

            $urlCurrent = URL::current();

            $categoryTreeView = view($categoryTree, compact('categories', 'categoriesRequest', 'urlCurrent'))->render();

            $additional['category_tree'] = $categoryTreeView;
        }

        return $response
            ->setData(view($view, compact('products'))->render())
            ->setAdditional($additional)
            ->setMessage($message);
    }

    /**
     * @param Request $request
     * @param OrderInterface $orderRepository
     * @return Response
     */
    public function getOrderTracking(Request $request, OrderInterface $orderRepository)
    {
        if (!EcommerceHelper::isOrderTrackingEnabled()) {
            abort(404);
        }

        $code = BaseHelper::removeSpecialCharacters(str_replace('#', '', $request->input('order_id')));

        SeoHelper::setTitle(__('Order tracking :code', ['code' => $code ? ' #' . $code : '']));

        Theme::breadcrumb()
            ->add(__('Home'), route('public.index'))
            ->add(__('Order tracking :code', ['code' => $code ? ' #' . $code : '']), route('public.orders.tracking', $code));

        $orderId = get_order_id_from_order_code('#' . $code);

        $order = null;
        if ($orderId) {
            $order = $orderRepository
                ->getModel()
                ->where('ec_orders.id', $orderId)
                ->join('ec_order_addresses', 'ec_order_addresses.order_id', '=', 'ec_orders.id')
                ->where('ec_order_addresses.email', $request->input('email'))
                ->with(['address', 'payment', 'products'])
                ->select('ec_orders.*')
                ->first();
        }

        return Theme::scope('ecommerce.order-tracking', compact('order'), 'plugins/ecommerce::themes.order-tracking')
            ->render();
    }
}
