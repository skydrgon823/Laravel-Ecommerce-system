<?php

namespace Botble\Ecommerce\Http\Controllers;

use Assets;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Forms\FormAbstract;
use Botble\Base\Forms\FormBuilder;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Ecommerce\Forms\ProductCategoryForm;
use Botble\Ecommerce\Http\Requests\ProductCategoryRequest;
use Botble\Ecommerce\Http\Resources\ProductCategoryResource;
use Botble\Ecommerce\Models\ProductCategory;
use Botble\Ecommerce\Repositories\Interfaces\ProductCategoryInterface;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Throwable;

class ProductCategoryController extends BaseController
{
    /**
     * @var ProductCategoryInterface
     */
    protected $productCategoryRepository;

    /**
     * ProductCategoryController constructor.
     * @param ProductCategoryInterface $productCategoryRepository
     */
    public function __construct(ProductCategoryInterface $productCategoryRepository)
    {
        $this->productCategoryRepository = $productCategoryRepository;
    }

    /**
     * @return BaseHttpResponse|Factory|View|string
     * @throws Throwable
     */
    public function index(FormBuilder $formBuilder, Request $request, BaseHttpResponse $response)
    {
        page_title()->setTitle(trans('plugins/ecommerce::product-categories.name'));

        $categories = $this->productCategoryRepository->getProductCategories([], ['slugable'], ['products']);

        if ($request->ajax()) {
            $data = view('core/base::forms.partials.tree-categories', $this->getOptions(compact('categories')))
                ->render();

            return $response->setData($data);
        }

        Assets::addStylesDirectly(['vendor/core/core/base/css/tree-category.css'])
            ->addScriptsDirectly(['vendor/core/core/base/js/tree-category.js']);

        $form = $formBuilder->create(ProductCategoryForm::class, ['template' => 'core/base::forms.form-tree-category']);
        $form = $this->setFormOptions($form, null, compact('categories'));

        return $form->renderForm();
    }

    /**
     * @param FormBuilder $formBuilder
     * @return BaseHttpResponse|string
     */
    public function create(FormBuilder $formBuilder, Request $request, BaseHttpResponse $response)
    {
        page_title()->setTitle(trans('plugins/ecommerce::product-categories.create'));

        if ($request->ajax()) {
            return $response->setData($this->getForm());
        }

        return $formBuilder->create(ProductCategoryForm::class)->renderForm();
    }

    /**
     * @param ProductCategoryRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function store(ProductCategoryRequest $request, BaseHttpResponse $response)
    {
        $productCategory = $this->productCategoryRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(PRODUCT_CATEGORY_MODULE_SCREEN_NAME, $request, $productCategory));

        if ($request->ajax()) {
            $productCategory = $this->productCategoryRepository->findOrFail($productCategory->id);

            if ($request->input('submit') == 'save') {
                $form = $this->getForm();
            } else {
                $form = $this->getForm($productCategory);
            }

            $response->setData([
                'model' => $productCategory,
                'form'  => $form
            ]);
        }

        return $response
                ->setPreviousUrl(route('product-categories.index'))
                ->setNextUrl(route('product-categories.edit', $productCategory->id))
                ->setMessage(trans('core/base::notices.create_success_message'));
    }

    /**
     * @param int $id
     * @param FormBuilder $formBuilder
     * @return BaseHttpResponse|string
     */
    public function edit($id, FormBuilder $formBuilder, Request $request, BaseHttpResponse $response)
    {
        $productCategory = $this->productCategoryRepository->findOrFail($id);

        if ($request->ajax()) {
            return $response->setData($this->getForm($productCategory));
        }

        page_title()->setTitle(trans('plugins/ecommerce::product-categories.edit') . ' "' . $productCategory->name . '"');

        return $formBuilder->create(ProductCategoryForm::class, ['model' => $productCategory])->renderForm();
    }

    /**
     * @param int $id
     * @param ProductCategoryRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function update($id, ProductCategoryRequest $request, BaseHttpResponse $response)
    {
        $productCategory = $this->productCategoryRepository->findOrFail($id);
        $productCategory->fill($request->input());

        $this->productCategoryRepository->createOrUpdate($productCategory);
        event(new UpdatedContentEvent(PRODUCT_CATEGORY_MODULE_SCREEN_NAME, $request, $productCategory));

        if ($request->ajax()) {
            $productCategory = $this->productCategoryRepository->findOrFail($id);

            if ($request->input('submit') == 'save') {
                $form = $this->getForm();
            } else {
                $form = $this->getForm($productCategory);
            }
            $response->setData([
                'model' => $productCategory,
                'form'  => $form
            ]);
        }

        return $response
                ->setPreviousUrl(route('product-categories.index'))
                ->setMessage(trans('core/base::notices.update_success_message'));
    }

    /**
     * @param Request $request
     * @param int $id
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function destroy(Request $request, $id, BaseHttpResponse $response)
    {
        try {
            $productCategory = $this->productCategoryRepository->findOrFail($id);

            $this->productCategoryRepository->delete($productCategory);
            event(new DeletedContentEvent(PRODUCT_CATEGORY_MODULE_SCREEN_NAME, $request, $productCategory));
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
            $productCategory = $this->productCategoryRepository->findOrFail($id);
            $this->productCategoryRepository->delete($productCategory);
            event(new DeletedContentEvent(PRODUCT_CATEGORY_MODULE_SCREEN_NAME, $request, $productCategory));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }

    /**
     * @param ProductCategory|null $model
     * @return string
     */
    private function getForm($model = null)
    {
        $options = ['template' => 'core/base::forms.form-no-wrap'];
        if ($model) {
            $options['model'] = $model;
        }

        $form = app(FormBuilder::class)->create(ProductCategoryForm::class, $options);

        $form = $this->setFormOptions($form, $model);

        return $form->renderForm();
    }

    /**
     * @param FormAbstract $form
     * @param ProductCategory|null $model
     * @param array $options
     * @return FormAbstract
     */
    private function setFormOptions($form, $model = null, $options = [])
    {
        if (!$model) {
            $form->setUrl(route('product-categories.create'));
        }

        if (!Auth::user()->hasPermission('product-categories.create') && !$model) {
            $class = $form->getFormOption('class');
            $form->setFormOption('class', $class . ' d-none');
        }

        $form->setFormOptions($this->getOptions($options));

        return $form;
    }

    /**
     * @param array $options
     * @return array
     */
    private function getOptions($options = [])
    {
        return array_merge([
            'canCreate'   => Auth::user()->hasPermission('product-categories.create'),
            'canEdit'     => Auth::user()->hasPermission('product-categories.edit'),
            'canDelete'   => Auth::user()->hasPermission('product-categories.destroy'),
            'createRoute' => 'product-categories.create',
            'editRoute'   => 'product-categories.edit',
            'deleteRoute' => 'product-categories.destroy',
        ], $options);
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function getSearch(Request $request, BaseHttpResponse $response)
    {
        $term = $request->input('search');

        $categories = $this->productCategoryRepository
                ->select(['id', 'name'])
                ->where('name', 'LIKE', '%' . $term . '%')
                ->paginate(10);

        $data = ProductCategoryResource::collection($categories);

        return $response->setData($data)->toApiResponse();
    }
}
