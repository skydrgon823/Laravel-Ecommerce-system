<?php

namespace Botble\Faq\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Base\Traits\HasDeleteManyItemsTrait;
use Botble\Faq\Http\Requests\FaqRequest;
use Botble\Faq\Repositories\Interfaces\FaqInterface;
use Botble\Base\Http\Controllers\BaseController;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Botble\Faq\Tables\FaqTable;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Faq\Forms\FaqForm;
use Botble\Base\Forms\FormBuilder;
use Illuminate\Contracts\View\View;
use Throwable;

class FaqController extends BaseController
{
    use HasDeleteManyItemsTrait;

    /**
     * @var FaqInterface
     */
    protected $faqRepository;

    /**
     * FaqController constructor.
     * @param FaqInterface $faqRepository
     */
    public function __construct(FaqInterface $faqRepository)
    {
        $this->faqRepository = $faqRepository;
    }

    /**
     * @param FaqTable $table
     * @return JsonResponse|View
     * @throws Throwable
     */
    public function index(FaqTable $table)
    {
        page_title()->setTitle(trans('plugins/faq::faq.name'));

        return $table->renderTable();
    }

    /**
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function create(FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('plugins/faq::faq.create'));

        return $formBuilder->create(FaqForm::class)->renderForm();
    }

    /**
     * @param FaqRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function store(FaqRequest $request, BaseHttpResponse $response)
    {
        $faq = $this->faqRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(FAQ_MODULE_SCREEN_NAME, $request, $faq));

        return $response
            ->setPreviousUrl(route('faq.index'))
            ->setNextUrl(route('faq.edit', $faq->id))
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
        $faq = $this->faqRepository->findOrFail($id);

        event(new BeforeEditContentEvent($request, $faq));

        page_title()->setTitle(trans('plugins/faq::faq.edit') . ' "' . $faq->question . '"');

        return $formBuilder->create(FaqForm::class, ['model' => $faq])->renderForm();
    }

    /**
     * @param int $id
     * @param FaqRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function update($id, FaqRequest $request, BaseHttpResponse $response)
    {
        $faq = $this->faqRepository->findOrFail($id);

        $faq->fill($request->input());

        $this->faqRepository->createOrUpdate($faq);

        event(new UpdatedContentEvent(FAQ_MODULE_SCREEN_NAME, $request, $faq));

        return $response
            ->setPreviousUrl(route('faq.index'))
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
            $faq = $this->faqRepository->findOrFail($id);

            $this->faqRepository->delete($faq);

            event(new DeletedContentEvent(FAQ_MODULE_SCREEN_NAME, $request, $faq));

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
        return $this->executeDeleteItems($request, $response, $this->faqRepository, FAQ_MODULE_SCREEN_NAME);
    }
}
