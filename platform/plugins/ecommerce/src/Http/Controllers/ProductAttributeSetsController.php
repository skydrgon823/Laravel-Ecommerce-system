<?php

namespace Botble\Ecommerce\Http\Controllers;

use Assets;
use Botble\Base\Forms\FormBuilder;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Ecommerce\Forms\ProductAttributeSetForm;
use Botble\Ecommerce\Http\Requests\ProductAttributeSetsRequest;
use Botble\Ecommerce\Repositories\Interfaces\ProductAttributeSetInterface;
use Botble\Ecommerce\Repositories\Interfaces\ProductCategoryInterface;
use Botble\Ecommerce\Services\ProductAttributes\StoreAttributeSetService;
use Botble\Ecommerce\Tables\ProductAttributeSetsTable;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;

class ProductAttributeSetsController extends BaseController
{
    /**
     * @var ProductAttributeSetInterface
     */
    protected $productAttributeSetRepository;

    /**
     * @var ProductCategoryInterface
     */
    protected $productCategoryRepository;

    /**
     * ProductAttributesController constructor.
     * @param ProductAttributeSetInterface $productAttributeSetRepository
     * @param ProductCategoryInterface $productCategoryRepository
     */
    public function __construct(
        ProductAttributeSetInterface $productAttributeSetRepository,
        ProductCategoryInterface $productCategoryRepository
    ) {
        $this->productAttributeSetRepository = $productAttributeSetRepository;
        $this->productCategoryRepository = $productCategoryRepository;
    }

    /**
     * @param ProductAttributeSetsTable $dataTable
     * @return Factory|View
     * @throws Throwable
     */
    public function index(ProductAttributeSetsTable $dataTable)
    {
        page_title()->setTitle(trans('plugins/ecommerce::product-attributes.name'));

        return $dataTable->renderTable();
    }

    /**
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function create(FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('plugins/ecommerce::product-attributes.create'));

        Assets::addScripts(['spectrum', 'jquery-ui'])
            ->addStyles(['spectrum'])
            ->addStylesDirectly([
                asset('vendor/core/plugins/ecommerce/css/ecommerce-product-attributes.css'),
            ])
            ->addScriptsDirectly([
                asset('vendor/core/plugins/ecommerce/js/ecommerce-product-attributes.js'),
            ]);

        return $formBuilder->create(ProductAttributeSetForm::class)->renderForm();
    }

    /**
     * @param ProductAttributeSetsRequest $request
     * @param StoreAttributeSetService $service
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     * @throws Exception
     */
    public function store(
        ProductAttributeSetsRequest $request,
        StoreAttributeSetService $service,
        BaseHttpResponse $response
    ) {
        $productAttributeSet = $this->productAttributeSetRepository->getModel();

        $productAttributeSet = $service->execute($request, $productAttributeSet);

        return $response
            ->setPreviousUrl(route('product-attribute-sets.index'))
            ->setNextUrl(route('product-attribute-sets.edit', $productAttributeSet->id))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    /**
     * @param int $id
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function edit($id, FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('plugins/ecommerce::product-attributes.edit'));

        $productAttributeSet = $this->productAttributeSetRepository->findOrFail($id);

        Assets::addScripts(['spectrum', 'jquery-ui'])
            ->addStyles(['spectrum'])
            ->addStylesDirectly([
                'vendor/core/plugins/ecommerce/css/ecommerce-product-attributes.css',
            ])
            ->addScriptsDirectly([
                'vendor/core/plugins/ecommerce/js/ecommerce-product-attributes.js',
            ]);

        return $formBuilder
            ->create(ProductAttributeSetForm::class, ['model' => $productAttributeSet])
            ->renderForm();
    }

    /**
     * @param int $id
     * @param ProductAttributeSetsRequest $request
     * @param StoreAttributeSetService $service
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     * @throws Exception
     */
    public function update(
        $id,
        ProductAttributeSetsRequest $request,
        StoreAttributeSetService $service,
        BaseHttpResponse $response
    ) {
        $productAttributeSet = $this->productAttributeSetRepository->findOrFail($id);

        $service->execute($request, $productAttributeSet);

        return $response
            ->setPreviousUrl(route('product-attribute-sets.index'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    /**
     * @param int $id
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function destroy($id, BaseHttpResponse $response)
    {
        try {
            $productAttributeSet = $this->productAttributeSetRepository->findOrFail($id);
            $this->productAttributeSetRepository->delete($productAttributeSet);

            return $response->setMessage(trans('core/base::notices.delete_success_message'));
        } catch (Exception $exception) {
            return $response
                ->setError()
                ->setMessage($exception->getMessage());
        }
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     * @throws Exception
     */
    public function deletes(Request $request, BaseHttpResponse $response)
    {
        $ids = $request->input('ids');
        if (empty($ids)) {
            return $response
                ->setError()
                ->setMessage(trans('core/base::notices.no_select'));
        }

        foreach ($ids as $id) {
            $productAttributeSet = $this->productAttributeSetRepository->findOrFail($id);
            $this->productAttributeSetRepository->delete($productAttributeSet);
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
