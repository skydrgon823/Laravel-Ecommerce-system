<?php

namespace Botble\Faq\Forms;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Forms\FormAbstract;
use Botble\Faq\Http\Requests\FaqRequest;
use Botble\Faq\Models\Faq;
use Botble\Faq\Repositories\Interfaces\FaqCategoryInterface;

class FaqForm extends FormAbstract
{
    /**
     * {@inheritDoc}
     */
    public function buildForm()
    {
        $this
            ->setupModel(new Faq())
            ->setValidatorClass(FaqRequest::class)
            ->withCustomFields()
            ->add('category_id', 'customSelect', [
                'label'      => trans('plugins/faq::faq.category'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'class' => 'form-control select-full',
                ],
                'choices'    => ['' => trans('plugins/faq::faq.select_category')] + app(FaqCategoryInterface::class)->pluck('name', 'id'),
            ])
            ->add('question', 'text', [
                'label'      => trans('plugins/faq::faq.question'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'rows' => 4,
                ],
            ])
            ->add('answer', 'editor', [
                'label'      => trans('plugins/faq::faq.answer'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'rows' => 4,
                ],
            ])
            ->add('status', 'customSelect', [
                'label'      => trans('core/base::tables.status'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'class' => 'form-control select-full',
                ],
                'choices'    => BaseStatusEnum::labels(),
            ])
            ->setBreakFieldPoint('status');
    }
}
