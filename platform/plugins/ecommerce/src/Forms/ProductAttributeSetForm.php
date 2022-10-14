<?php

namespace Botble\Ecommerce\Forms;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Forms\FormAbstract;
use Botble\Ecommerce\Http\Requests\ProductAttributeSetsRequest;
use Botble\Ecommerce\Models\ProductAttributeSet;
use Language;

class ProductAttributeSetForm extends FormAbstract
{
    /**
     * {@inheritDoc}
     */
    public function buildForm()
    {
        $displayLayout = [
            'dropdown' => trans('plugins/ecommerce::product-attribute-sets.dropdown_swatch'),
            'visual'   => trans('plugins/ecommerce::product-attribute-sets.visual_swatch'),
            'text'     => trans('plugins/ecommerce::product-attribute-sets.text_swatch'),
        ];

        $attributes = [];

        if ($this->getModel()) {
            $attributes = $this->getModel()->attributes;
        }

        $isNotDefaultLanguage = defined('LANGUAGE_ADVANCED_MODULE_SCREEN_NAME') &&
            $this->request->input('ref_lang') &&
            $this->request->input('ref_lang') != Language::getDefaultLocaleCode();

        $this
            ->setupModel(new ProductAttributeSet())
            ->setValidatorClass(ProductAttributeSetsRequest::class)
            ->setFormOption('class', 'update-attribute-set-form')
            ->withCustomFields()
            ->add('title', 'text', [
                'label'      => trans('core/base::forms.title'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'data-counter' => 120,
                ],
            ])
            ->add('slug', 'text', [
                'label'      => trans('core/base::forms.slug'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'data-counter' => 120,
                ],
            ])
            ->add('use_image_from_product_variation', 'onOff', [
                'label'         => trans('plugins/ecommerce::product-attribute-sets.use_image_from_product_variation'),
                'label_attr'    => ['class' => 'control-label'],
                'default_value' => false,
            ])
            ->add('status', 'customSelect', [
                'label'      => trans('core/base::tables.status'),
                'label_attr' => ['class' => 'control-label required'],
                'choices'    => BaseStatusEnum::labels(),
            ])
            ->add('display_layout', 'customSelect', [
                'label'      => trans('plugins/ecommerce::product-attribute-sets.display_layout'),
                'label_attr' => ['class' => 'control-label required'],
                'choices'    => $displayLayout,
            ])
            ->add('is_searchable', 'onOff', [
                'label'         => trans('plugins/ecommerce::product-attribute-sets.searchable'),
                'label_attr'    => ['class' => 'control-label'],
                'default_value' => false,
            ])
            ->add('is_comparable', 'onOff', [
                'label'         => trans('plugins/ecommerce::product-attribute-sets.comparable'),
                'label_attr'    => ['class' => 'control-label'],
                'default_value' => false,
            ])
            ->add('is_use_in_product_listing', 'onOff', [
                'label'         => trans('plugins/ecommerce::product-attribute-sets.use_in_product_listing'),
                'label_attr'    => ['class' => 'control-label'],
                'default_value' => false,
            ])
            ->add('order', 'number', [
                'label'         => trans('core/base::forms.order'),
                'label_attr'    => ['class' => 'control-label'],
                'attr'          => [
                    'placeholder' => trans('core/base::forms.order_by_placeholder'),
                ],
                'default_value' => 0,
            ])
            ->setBreakFieldPoint('status')
            ->addMetaBoxes([
                'attributes_list' => [
                    'title'   => trans('plugins/ecommerce::product-attribute-sets.attributes_list'),
                    'content' => view(
                        'plugins/ecommerce::product-attributes.sets.list',
                        compact('attributes', 'isNotDefaultLanguage')
                    )->render(),
                ],
            ]);
    }
}
