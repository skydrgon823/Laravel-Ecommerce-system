<?php

namespace Botble\Ecommerce\Http\Controllers;

use Assets;
use BaseHelper;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Ecommerce\Exports\CsvProductExport;
use Botble\Ecommerce\Repositories\Interfaces\ProductInterface;
use Botble\Ecommerce\Repositories\Interfaces\ProductVariationInterface;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ExportController extends BaseController
{
    /**
     * @var ProductInterface
     */
    protected $productRepository;

    /**
     * @var ProductVariationInterface
     */
    protected $productVariationRepository;

    /**
     * @param ProductInterface $productRepository
     * @param ProductVariationInterface $productVariationRepository
     */
    public function __construct(
        ProductInterface $productRepository,
        ProductVariationInterface $productVariationRepository
    ) {
        $this->productRepository = $productRepository;
        $this->productVariationRepository = $productVariationRepository;
    }

    /**
     * @return Factory|Application|View
     */
    public function products()
    {
        page_title()->setTitle(trans('plugins/ecommerce::export.products.name'));

        Assets::addScriptsDirectly(['vendor/core/plugins/ecommerce/js/export.js']);

        $totalProduct = $this->productRepository->count(['is_variation' => 0]);
        $totalVariation = $this->productVariationRepository
            ->getModel()
            ->whereHas('product')
            ->whereHas('configurableProduct', function ($query) {
                $query->where('is_variation', 0);
            })
            ->count();

        return view('plugins/ecommerce::export.products', compact('totalProduct', 'totalVariation'));
    }

    /**
     * @return BinaryFileResponse
     */
    public function exportProducts()
    {
        BaseHelper::maximumExecutionTimeAndMemoryLimit();

        return (new CsvProductExport())->download('export_products.csv');
    }
}
