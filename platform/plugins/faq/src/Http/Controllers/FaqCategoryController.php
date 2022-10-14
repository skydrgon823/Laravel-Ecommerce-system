<?php

namespace Botble\Faq\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Faq\Http\Requests\FaqCategoryRequest;
use Botble\Faq\Repositories\Interfaces\FaqCategoryInterface;
use Botble\Base\Http\Controllers\BaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Exception;
use Botble\Faq\Tables\FaqCategoryTable;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Faq\Forms\FaqCategoryForm;
use Botble\Base\Forms\FormBuilder;
use Illuminate\Contracts\View\View;
use Throwable;

class FaqCategoryController extends BaseController
{
    /**
     * @var FaqCategoryInterface
     */
    protected $faqCategoryRepository;

    /**
     * FaqCategoryController constructor.
     * @param FaqCategoryInterface $faqCategoryRepository
     */
    public function __construct(FaqCategoryInterface $faqCategoryRepository)
    {
        $this->faqCategoryRepository = $faqCategoryRepository;
    }

    /**
     * @param FaqCategoryTable $table
     * @return JsonResponse|View
     * @throws Throwable
     */
    public function index(FaqCategoryTable $table)
    {
        page_title()->setTitle(trans('plugins/faq::faq-category.name'));

        return $table->renderTable();
    }

    /**
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function create(FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('plugins/faq::faq-category.create'));

        return $formBuilder->create(FaqCategoryForm::class)->renderForm();
    }

    /**
     * @param FaqCategoryRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function store(FaqCategoryRequest $request, BaseHttpResponse $response)
    {
        $faqCategory = $this->faqCategoryRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(FAQ_CATEGORY_MODULE_SCREEN_NAME, $request, $faqCategory));

        return $response
            ->setPreviousUrl(route('faq_category.index'))
            ->setNextUrl(route('faq_category.edit', $faqCategory->id))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    /**
     * @param $id
     * @param Request $request
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function edit($id, FormBuilder $formBuilder, Request $request)
    {
        $faqCategory = $this->faqCategoryRepository->findOrFail($id);

        event(new BeforeEditContentEvent($request, $faqCategory));

        page_title()->setTitle(trans('plugins/faq::faq-category.edit') . ' "' . $faqCategory->name . '"');

        return $formBuilder->create(FaqCategoryForm::class, ['model' => $faqCategory])->renderForm();
    }

    /**
     * @param $id
     * @param FaqCategoryRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function update($id, FaqCategoryRequest $request, BaseHttpResponse $response)
    {
        $faqCategory = $this->faqCategoryRepository->findOrFail($id);

        $faqCategory->fill($request->input());

        $this->faqCategoryRepository->createOrUpdate($faqCategory);

        event(new UpdatedContentEvent(FAQ_CATEGORY_MODULE_SCREEN_NAME, $request, $faqCategory));

        return $response
            ->setPreviousUrl(route('faq_category.index'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    /**
     * @param Request $request
     * @param $id
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function destroy(Request $request, $id, BaseHttpResponse $response)
    {
        try {
            $faqCategory = $this->faqCategoryRepository->findOrFail($id);

            $this->faqCategoryRepository->delete($faqCategory);

            event(new DeletedContentEvent(FAQ_CATEGORY_MODULE_SCREEN_NAME, $request, $faqCategory));

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
            $faqCategory = $this->faqCategoryRepository->findOrFail($id);
            $this->faqCategoryRepository->delete($faqCategory);
            event(new DeletedContentEvent(FAQ_CATEGORY_MODULE_SCREEN_NAME, $request, $faqCategory));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
