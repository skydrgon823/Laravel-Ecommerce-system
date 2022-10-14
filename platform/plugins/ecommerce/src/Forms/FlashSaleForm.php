<?php

namespace Botble\Ecommerce\Forms;

use Assets;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Forms\FormAbstract;
use Botble\Ecommerce\Http\Requests\FlashSaleRequest;
use Botble\Ecommerce\Models\FlashSale;
use Carbon\Carbon;

class FlashSaleForm extends FormAbstract
{
    /**
     * {@inheritDoc}
     */
    public function buildForm()
    {
        Assets::addScriptsDirectly('vendor/core/plugins/ecommerce/js/flash-sale.js')
            ->addStylesDirectly(['vendor/core/plugins/ecommerce/css/ecommerce.css'])
            ->addScripts([
                'blockui',
                'input-mask',
            ]);

        $this
            ->setupModel(new FlashSale())
            ->setValidatorClass(FlashSaleRequest::class)
            ->withCustomFields()
            ->add('name', 'text', [
                'label'      => trans('core/base::forms.name'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'placeholder'  => trans('core/base::forms.name_placeholder'),
                    'data-counter' => 120,
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
            ->add('end_date', 'text', [
                'label'         => __('End date'),
                'label_attr'    => ['class' => 'control-label required'],
                'attr'          => [
                    'class'            => 'form-control datepicker',
                    'data-date-format' => 'yyyy/mm/dd',
                ],
                'default_value' => Carbon::now()->addDay()->format('Y/m/d'),
            ])
            ->addMetaBoxes([
                'products' => [
                    'title'    => trans('plugins/ecommerce::flash-sale.products'),
                    'content'  => view('plugins/ecommerce::flash-sales.products', [
                        'flashSale' => $this->getModel(),
                        'products'  => $this->getModel()->id ? $this->getModel()->products : collect([]),
                    ]),
                    'priority' => 0,
                ],
            ])
            ->setBreakFieldPoint('status');
    }
}
