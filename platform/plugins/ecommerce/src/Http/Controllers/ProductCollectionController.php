<?php

namespace Botble\Ecommerce\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Forms\FormBuilder;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Ecommerce\Forms\ProductCollectionForm;
use Botble\Ecommerce\Http\Requests\ProductCollectionRequest;
use Botble\Ecommerce\Repositories\Interfaces\ProductCollectionInterface;
use Botble\Ecommerce\Tables\ProductCollectionTable;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;

class ProductCollectionController extends BaseController
{
    /**
     * @var ProductCollectionInterface
     */
    protected $productCollectionRepository;

    /**
     * ProductCollectionController constructor.
     * @param ProductCollectionInterface $productCollectionRepository
     */
    public function __construct(ProductCollectionInterface $productCollectionRepository)
    {
        $this->productCollectionRepository = $productCollectionRepository;
    }

    /**
     * @param ProductCollectionTable $dataTable
     * @return Factory|View
     * @throws Throwable
     */
    public function index(ProductCollectionTable $dataTable)
    {
        page_title()->setTitle(trans('plugins/ecommerce::product-collections.name'));

        return $dataTable->renderTable();
    }

    /**
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function create(FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('plugins/ecommerce::product-collections.create'));

        return $formBuilder->create(ProductCollectionForm::class)->renderForm();
    }

    /**
     * @param ProductCollectionRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function store(ProductCollectionRequest $request, BaseHttpResponse $response)
    {
        $productCollection = $this->productCollectionRepository->getModel();
        $productCollection->fill($request->input());

        $productCollection->slug = $this->productCollectionRepository->createSlug($request->input('slug'), 0);

        $productCollection = $this->productCollectionRepository->createOrUpdate($productCollection);

        event(new CreatedContentEvent(PRODUCT_COLLECTION_MODULE_SCREEN_NAME, $request, $productCollection));

        return $response
            ->setPreviousUrl(route('product-collections.index'))
            ->setNextUrl(route('product-collections.edit', $productCollection->id))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    /**
     * @param int $id
     * @param FormBuilder $formBuilder
     * @param Request $request
     * @return string
     */
    public function edit($id, FormBuilder $formBuilder, Request $request)
    {
        $productCollection = $this->productCollectionRepository->findOrFail($id);

        event(new BeforeEditContentEvent($request, $productCollection));

        page_title()->setTitle(trans('plugins/ecommerce::product-collections.edit') . ' "' . $productCollection->name . '"');

        return $formBuilder
            ->create(ProductCollectionForm::class, ['model' => $productCollection])
            ->remove('slug')
            ->renderForm();
    }

    /**
     * @param int $id
     * @param ProductCollectionRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function update($id, ProductCollectionRequest $request, BaseHttpResponse $response)
    {
        $productCollection = $this->productCollectionRepository->findOrFail($id);
        $productCollection->fill($request->input());

        $productCollection = $this->productCollectionRepository->createOrUpdate($productCollection);

        event(new UpdatedContentEvent(PRODUCT_COLLECTION_MODULE_SCREEN_NAME, $request, $productCollection));

        return $response
            ->setPreviousUrl(route('product-collections.index'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    /**
     * @param int $id
     * @param BaseHttpResponse $response
     * @param Request $request
     * @return BaseHttpResponse
     */
    public function destroy($id, BaseHttpResponse $response, Request $request)
    {
        $productCollection = $this->productCollectionRepository->findOrFail($id);

        try {
            $this->productCollectionRepository->delete($productCollection);

            event(new DeletedContentEvent(PRODUCT_COLLECTION_MODULE_SCREEN_NAME, $request, $productCollection));

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
            $productCollection = $this->productCollectionRepository->findOrFail($id);
            $this->productCollectionRepository->delete($productCollection);
            event(new DeletedContentEvent(PRODUCT_COLLECTION_MODULE_SCREEN_NAME, $request, $productCollection));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }

    /**
     * @param BaseHttpResponse $response
     * @return mixed
     */
    public function getListForSelect(BaseHttpResponse $response)
    {
        $productCollections = $this->productCollectionRepository
            ->getModel()
            ->select(['id', 'name'])
            ->get()
            ->toArray();

        return $response->setData($productCollections);
    }
}
