<?php

namespace Botble\Ecommerce\Forms;

use Botble\Base\Forms\FormAbstract;
use Botble\Ecommerce\Http\Requests\AddShippingRegionRequest;
use Botble\Ecommerce\Models\Shipping;
use Botble\Ecommerce\Repositories\Interfaces\ShippingInterface;
use EcommerceHelper;

class AddShippingRegionForm extends FormAbstract
{
    /**
     * {@inheritDoc}
     */
    public function buildForm()
    {
        $existedCountries = app(ShippingInterface::class)->pluck('country');

        foreach ($existedCountries as &$existedCountry) {
            if (empty($existedCountry)) {
                $existedCountry = '';
            }
        }

        $countries = ['' => trans('plugins/ecommerce::shipping.all')] + EcommerceHelper::getAvailableCountries();

        $countries = array_diff_key($countries, array_flip($existedCountries));

        $this
            ->setupModel(new Shipping())
            ->setFormOptions([
                'template' => 'core/base::forms.form-content-only',
                'url'      => route('shipping_methods.region.create'),
            ])
            ->setTitle(trans('plugins/ecommerce::shipping.add_shipping_region'))
            ->setValidatorClass(AddShippingRegionRequest::class)
            ->withCustomFields()
            ->add('region', 'customSelect', [
                'label'      => trans('plugins/ecommerce::shipping.country'),
                'label_attr' => [
                    'class' => 'control-label required',
                ],
                'attr'       => [
                    'class' => 'select-country-search',
                ],
                'choices'    => $countries,
            ]);
    }
}
