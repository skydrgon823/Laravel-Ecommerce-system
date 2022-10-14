<?php

namespace Botble\Ecommerce\Listeners;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Ecommerce\Repositories\Interfaces\BrandInterface;
use Botble\Ecommerce\Repositories\Interfaces\ProductCategoryInterface;
use Botble\Ecommerce\Repositories\Interfaces\ProductInterface;
use Botble\Ecommerce\Repositories\Interfaces\ProductTagInterface;
use SiteMapManager;

class RenderingSiteMapListener
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
     * @var BrandInterface
     */
    protected $brandRepository;

    /**
     * @var ProductTagInterface
     */
    protected $tagRepository;

    /**
     * RenderingSiteMapListener constructor.
     * @param ProductInterface $productRepository
     * @param ProductCategoryInterface $productCategoryRepository
     * @param BrandInterface $brandRepository
     * @param ProductTagInterface $tagRepository
     */
    public function __construct(
        ProductInterface $productRepository,
        ProductCategoryInterface $productCategoryRepository,
        BrandInterface $brandRepository,
        ProductTagInterface $tagRepository
    ) {
        $this->tagRepository = $tagRepository;
        $this->productRepository = $productRepository;
        $this->productCategoryRepository = $productCategoryRepository;
        $this->brandRepository = $brandRepository;
    }

    /**
     * Handle the event.
     *
     * @return void
     */
    public function handle()
    {
        SiteMapManager::add(route('public.products'), '2020-06-29 00:00:00', '1', 'monthly');
        SiteMapManager::add(route('public.cart'), '2020-06-29 00:00:00', '1', 'monthly');

        $products = $this->productRepository->getModel()
            ->with('slugable')
            ->where('status', BaseStatusEnum::PUBLISHED)
            ->where('is_variation', 0)
            ->orderBy('created_at', 'desc')
            ->get();

        foreach ($products as $product) {
            if (!$product->slugable) {
                continue;
            }

            SiteMapManager::add($product->url, $product->updated_at, '0.8');
        }

        $tags = $this->tagRepository->getModel()
            ->with('slugable')
            ->where('status', BaseStatusEnum::PUBLISHED)
            ->orderBy('created_at', 'desc')
            ->get();

        foreach ($tags as $tag) {
            if (!$tag->slugable) {
                continue;
            }

            SiteMapManager::add($tag->url, $tag->updated_at, '0.3', 'weekly');
        }

        $productCategories = $this->productCategoryRepository->getModel()
            ->with('slugable')
            ->where('status', BaseStatusEnum::PUBLISHED)
            ->orderBy('created_at', 'desc')
            ->get();

        foreach ($productCategories as $productCategory) {
            if (!$productCategory->slugable) {
                continue;
            }

            SiteMapManager::add($productCategory->url, $productCategory->updated_at, '0.6');
        }

        $brands = $this->brandRepository->getModel()
            ->with('slugable')
            ->where('status', BaseStatusEnum::PUBLISHED)
            ->orderBy('created_at', 'desc')
            ->get();

        foreach ($brands as $brand) {
            if (!$brand->slugable) {
                continue;
            }

            SiteMapManager::add($brand->url, $brand->updated_at, '0.6');
        }
    }
}
