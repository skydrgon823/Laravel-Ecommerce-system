<?php

namespace Botble\Ecommerce\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Forms\FormBuilder;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Ecommerce\Forms\ProductTagForm;
use Botble\Ecommerce\Http\Requests\ProductTagRequest;
use Botble\Ecommerce\Repositories\Interfaces\ProductTagInterface;
use Botble\Ecommerce\Tables\ProductTagTable;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;

class ProductTagController extends BaseController
{
    /**
     * @var ProductTagInterface
     */
    protected $productTagRepository;

    /**
     * @param ProductTagInterface $productTagRepository
     */
    public function __construct(ProductTagInterface $productTagRepository)
    {
        $this->productTagRepository = $productTagRepository;
    }

    /**
     * @param ProductTagTable $table
     * @return Factory|View
     * @throws Throwable
     */
    public function index(ProductTagTable $table)
    {
        page_title()->setTitle(trans('plugins/ecommerce::product-tag.name'));

        return $table->renderTable();
    }

    /**
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function create(FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('plugins/ecommerce::product-tag.create'));

        return $formBuilder->create(ProductTagForm::class)->renderForm();
    }

    /**
     * @param ProductTagRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function store(ProductTagRequest $request, BaseHttpResponse $response)
    {
        $productTag = $this->productTagRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(PRODUCT_TAG_MODULE_SCREEN_NAME, $request, $productTag));

        return $response
            ->setPreviousUrl(route('product-tag.index'))
            ->setNextUrl(route('product-tag.edit', $productTag->id))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    /**
     * @param int $id
     * @param Request $request
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function edit($id, FormBuilder $formBuilder, Request $request)
    {
        $productTag = $this->productTagRepository->findOrFail($id);

        event(new BeforeEditContentEvent($request, $productTag));

        page_title()->setTitle(trans('plugins/ecommerce::product-tag.edit') . ' "' . $productTag->name . '"');

        return $formBuilder->create(ProductTagForm::class, ['model' => $productTag])->renderForm();
    }

    /**
     * @param int $id
     * @param ProductTagRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function update($id, ProductTagRequest $request, BaseHttpResponse $response)
    {
        $productTag = $this->productTagRepository->findOrFail($id);

        $productTag->fill($request->input());

        $this->productTagRepository->createOrUpdate($productTag);

        event(new UpdatedContentEvent(PRODUCT_TAG_MODULE_SCREEN_NAME, $request, $productTag));

        return $response
            ->setPreviousUrl(route('product-tag.index'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    /**
     * @param int $id
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function destroy(Request $request, $id, BaseHttpResponse $response)
    {
        try {
            $productTag = $this->productTagRepository->findOrFail($id);

            $this->productTagRepository->delete($productTag);

            event(new DeletedContentEvent(PRODUCT_TAG_MODULE_SCREEN_NAME, $request, $productTag));

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
            $productTag = $this->productTagRepository->findOrFail($id);
            $this->productTagRepository->delete($productTag);
            event(new DeletedContentEvent(PRODUCT_TAG_MODULE_SCREEN_NAME, $request, $productTag));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }

    /**
     * Get list tags in db
     *
     * @return array
     */
    public function getAllTags()
    {
        return $this->productTagRepository->pluck('name');
    }
}
