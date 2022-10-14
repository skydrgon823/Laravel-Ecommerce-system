<?php

namespace Botble\Location\Forms;

use Assets;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Forms\FormAbstract;
use Botble\Location\Http\Requests\CityRequest;
use Botble\Location\Models\City;
use Botble\Location\Repositories\Interfaces\CountryInterface;
use Botble\Location\Repositories\Interfaces\StateInterface;

class CityForm extends FormAbstract
{
    /**
     * @var CountryInterface
     */
    protected $countryRepository;

    /**
     * @var StateInterface
     */
    protected $stateRepository;

    /**
     * @param CountryInterface $countryRepository
     * @param StateInterface $stateRepository
     */
    public function __construct(CountryInterface $countryRepository, StateInterface $stateRepository)
    {
        parent::__construct();

        $this->countryRepository = $countryRepository;
        $this->stateRepository = $stateRepository;
    }

    /**
     * {@inheritDoc}
     */
    public function buildForm()
    {
        Assets::addScriptsDirectly('vendor/core/plugins/location/js/location.js');

        $countries = $this->countryRepository->pluck('countries.name', 'countries.id');

        $states = [];
        if ($this->getModel()) {
            $states = $this->stateRepository->pluck(
                'states.name',
                'states.id',
                [['country_id', '=', $this->model->country_id]]
            );
        }

        $this
            ->setupModel(new City())
            ->setValidatorClass(CityRequest::class)
            ->withCustomFields()
            ->add('name', 'text', [
                'label'      => trans('core/base::forms.name'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'placeholder'  => trans('core/base::forms.name_placeholder'),
                    'data-counter' => 120,
                ],
            ])
            ->add('country_id', 'customSelect', [
                'label'      => trans('plugins/location::city.country'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'id'        => 'country_id',
                    'class'     => 'form-control select-search-full',
                    'data-type' => 'country',
                ],
                'choices'    => [0 => trans('plugins/location::city.select_country')] + $countries,
            ])
            ->add('state_id', 'customSelect', [
                'label'      => trans('plugins/location::city.state'),
                'label_attr' => ['class' => 'control-label'],
                'attr'       => [
                    'id'        => 'state_id',
                    'data-url'  => route('ajax.states-by-country'),
                    'class'     => 'form-control select-search-full',
                    'data-type' => 'state',
                ],
                'choices'    => ($this->getModel()->state_id ?
                        [
                            $this->model->state->id => $this->model->state->name,
                        ]
                        :
                        [0 => trans('plugins/location::city.select_state')]) + $states,
            ])
            ->add('order', 'number', [
                'label'         => trans('core/base::forms.order'),
                'label_attr'    => ['class' => 'control-label'],
                'attr'          => [
                    'placeholder' => trans('core/base::forms.order_by_placeholder'),
                ],
                'default_value' => 0,
            ])
            ->add('is_default', 'onOff', [
                'label'         => trans('core/base::forms.is_default'),
                'label_attr'    => ['class' => 'control-label'],
                'default_value' => false,
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
