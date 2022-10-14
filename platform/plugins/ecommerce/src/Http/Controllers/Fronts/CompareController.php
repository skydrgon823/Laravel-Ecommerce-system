<?php

namespace Botble\Ecommerce\Http\Controllers\Fronts;

use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Ecommerce\Models\Product;
use Botble\Ecommerce\Repositories\Interfaces\ProductAttributeSetInterface;
use Botble\Ecommerce\Repositories\Interfaces\ProductInterface;
use Cart;
use EcommerceHelper;
use Illuminate\Routing\Controller;
use Response;
use SeoHelper;
use Theme;

class CompareController extends Controller
{
    /**
     * @var ProductInterface
     */
    protected $productRepository;

    /**
     * CompareController constructor.
     * @param ProductInterface $productRepository
     */
    public function __construct(ProductInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * @return Response
     */
    public function index()
    {
        if (!EcommerceHelper::isCompareEnabled()) {
            abort(404);
        }

        SeoHelper::setTitle(__('Compare'));

        Theme::breadcrumb()
            ->add(__('Home'), route('public.index'))
            ->add(__('Compare'), route('public.compare'));

        $itemIds = collect(Cart::instance('compare')->content())
            ->sortBy([['updated_at', 'desc']])
            ->pluck('id');

        $products = collect();
        $attributeSets = collect();
        if ($itemIds->count()) {
            $products = $this->productRepository
                ->getProductsByIds($itemIds->toArray(), [
                    'take'      => 10,
                    'with'      => [
                        'slugable',
                        'variations',
                        'productCollections',
                        'variationAttributeSwatchesForProductList',
                    ],
                    'withCount' => EcommerceHelper::withReviewsCount(),
                ]);

            $attributeSets = app(ProductAttributeSetInterface::class)->getAllWithSelected($itemIds);
        }

        return Theme::scope(
            'ecommerce.compare',
            compact('products', 'attributeSets'),
            'plugins/ecommerce::themes.compare'
        )->render();
    }

    /**
     * @param int $productId
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function store($productId, BaseHttpResponse $response)
    {
        if (!EcommerceHelper::isCompareEnabled()) {
            abort(404);
        }

        $product = $this->productRepository->findOrFail($productId);

        $duplicates = Cart::instance('compare')->search(function ($cartItem) use ($productId) {
            return $cartItem->id == $productId;
        });

        if (!$duplicates->isEmpty()) {
            return $response
                ->setMessage(__(':product is already in your compare list!', ['product' => $product->name]))
                ->setError(true);
        }

        Cart::instance('compare')->add($productId, $product->name, 1, $product->front_sale_price)
            ->associate(Product::class);

        return $response
            ->setMessage(__('Added product :product to compare list successfully!', ['product' => $product->name]))
            ->setData(['count' => Cart::instance('compare')->count()]);
    }

    /**
     * @param int $productId
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function destroy($productId, BaseHttpResponse $response)
    {
        if (!EcommerceHelper::isCompareEnabled()) {
            abort(404);
        }

        $product = $this->productRepository->findOrFail($productId);

        Cart::instance('compare')->search(function ($cartItem, $rowId) use ($productId) {
            if ($cartItem->id == $productId) {
                Cart::instance('compare')->remove($rowId);
                return true;
            }
            return false;
        });

        return $response
            ->setMessage(__('Removed product :product from compare list successfully!', ['product' => $product->name]))
            ->setData(['count' => Cart::instance('compare')->count()]);
    }
}
