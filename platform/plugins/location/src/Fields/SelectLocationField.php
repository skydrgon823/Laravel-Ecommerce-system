<?php

namespace Botble\Location\Fields;

use Assets;
use Kris\LaravelFormBuilder\Fields\FormField;
use Kris\LaravelFormBuilder\Form;
use Botble\Location\Repositories\Interfaces\StateInterface;
use Botble\Location\Repositories\Interfaces\CountryInterface;
use Botble\Location\Repositories\Interfaces\CityInterface;
use Html;
use Illuminate\Support\Arr;

class SelectLocationField extends FormField
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
     * @var CityInterface
     */
    protected $cityRepository;

    /**
     * @var array
     */
    protected $locationKeys = [];

    /**
     * @param string $name
     * @param string $type
     * @param Form $parent
     * @param array $options
     */
    public function __construct(
        $name,
        $type,
        Form $parent,
        array $options = []
    ) {
        $default = [
            'country' => 'country_id',
            'state'   => 'state_id',
            'city'    => 'city_id',
        ];
        $this->locationKeys = array_filter(array_merge($default, Arr::get($options, 'locationKeys', [])));

        $this->name       = $name;
        $this->type       = $type;
        $this->parent     = $parent;
        $this->formHelper = $this->parent->getFormHelper();

        $this->setTemplate();
        $this->setDefaultOptions($options);
        $this->setupValue();
        $this->initFilters();

        $this->countryRepository = app(CountryInterface::class);
        $this->stateRepository   = app(StateInterface::class);
        $this->cityRepository    = app(CityInterface::class);

        Assets::addScriptsDirectly('vendor/core/plugins/location/js/location.js');
    }

    /**
     * Get config from the form.
     *
     * @return mixed
     */
    private function getConfig($key = null, $default = null)
    {
        return $this->parent->getConfig($key, $default);
    }

    /**
     * Set the template property on the object.
     *
     * @return void
     */
    private function setTemplate()
    {
        $this->template = $this->getConfig($this->getTemplate(), $this->getTemplate());
    }

    /**
     * Setup the value of the form field.
     *
     * @return void
     */
    protected function setupValue()
    {
        $values = $this->getOption($this->valueProperty);
        foreach ($this->locationKeys as $k => $v) {
            $value = Arr::get($values, $k);
            if ($value === null) {
                $value = old($v, $this->getModelValueAttribute($this->parent->getModel(), $v));
            }

            $values[$k] = $value;
        }
        $this->setValue($values);
    }

    /**
     * Get options of country
     *
     * @return array
     */
    public function getCountryOptions()
    {
        $countryKey = Arr::get($this->locationKeys, 'country');
        $countries = $this->countryRepository->pluck('name', 'id');
        $value = Arr::get($this->getValue(), 'country');
        $attr = array_merge($this->getOption('attr', []), [
            'id'        => $countryKey,
            'class'     => 'form-control select-search-full',
            'data-type' => 'country'
        ]);

        return array_merge([
            'label'       => trans('plugins/location::city.country'),
            'attr'        => $attr,
            'choices'     => ['' => trans('plugins/location::city.select_country')] + $countries,
            'selected'    => $value,
            'empty_value' => null,
        ], $this->getOption('attrs.country', []));
    }

    /**
     * Get options of state
     *
     * @return array
     */
    public function getStateOptions()
    {
        $states = [];
        $stateKey = Arr::get($this->locationKeys, 'state');
        $countryId = Arr::get($this->getValue(), 'country');
        $value = Arr::get($this->getValue(), 'state');
        if ($countryId) {
            $states = $this->stateRepository->pluck('name', 'id', [['country_id', '=', $countryId]]);
        }

        $attr = array_merge($this->getOption('attr', []), [
            'id'        => $stateKey,
            'data-url'  => route('ajax.states-by-country'),
            'class'     => 'form-control select-search-full',
            'data-type' => 'state'
        ]);

        return array_merge([
            'label'    => trans('plugins/location::city.state'),
            'attr'     => $attr,
            'choices'  => ['' => trans('plugins/location::city.select_state')] + $states,
            'selected' => $value,
            'empty_value' => null,
        ], $this->getOption('attrs.state', []));
    }

    /**
     * Get options of city
     *
     * @return array
     */
    public function getCityOptions()
    {
        $cities = [];
        $cityKey = Arr::get($this->locationKeys, 'city');
        $stateId = Arr::get($this->getValue(), 'state');
        $value = Arr::get($this->getValue(), 'city');
        if ($stateId) {
            $cities = $this->cityRepository->pluck('name', 'id', [['state_id', '=', $stateId]]);
        }

        $attr = array_merge($this->getOption('attr', []), [
            'id'        => $cityKey,
            'data-url'  => route('ajax.cities-by-state'),
            'class'     => 'form-control select-search-full',
            'data-type' => 'city'
        ]);

        return array_merge([
            'label'       => trans('plugins/location::city.city'),
            'attr'        => $attr,
            'choices'     => ['' => trans('plugins/location::city.select_city')] + $cities,
            'selected'    => $value,
            'empty_value' => null,
        ], $this->getOption('attrs.city', []));
    }

    /**
     * Render the field.
     *
     * @param array $options
     * @param bool  $showLabel
     * @param bool  $showField
     * @param bool  $showError
     * @return string
     */
    public function render(array $options = [], $showLabel = true, $showField = true, $showError = true)
    {
        $html = '';

        $this->prepareOptions($options);

        if ($showField) {
            $this->rendered = true;
        }

        if (!$this->needsLabel()) {
            $showLabel = false;
        }

        if ($showError) {
            $showError = $this->parent->haveErrorsEnabled();
        }

        $data = $this->getRenderData();
        $values = $this->getValue();

        foreach ($this->locationKeys as $k => $v) {
            $value        = Arr::get($values, $v);
            $defaultValue = Arr::get($this->getDefaultValue(), $v);
            // Override default value with value
            $options = [];
            switch ($k) {
                case 'country':
                    $options = $this->getCountryOptions();
                    break;
                case 'state':
                    $options = $this->getStateOptions();
                    break;
                case 'city':
                    $options = $this->getCityOptions();
                    break;
            }

            $options = array_merge($this->options, $options);

            $html .= $this->formHelper->getView()->make(
                $this->getViewTemplate(),
                $data + [
                    'name'                => $v,
                    'nameKey'             => $v,
                    'type'                => $this->type,
                    'options'             => $options,
                    'showLabel'           => $showLabel,
                    'showField'           => $showField,
                    'showError'           => $showError,
                    'errorBag'            => $this->parent->getErrorBag(),
                    'translationTemplate' => $this->parent->getTranslationTemplate(),
                ]
            )->render();
        }

        return Html::tag('div', $html, ['class' => ($this->getOption('wrapperClassName') ?: 'row g-1') . ' select-location-fields']) ;
    }

    /**
     * {@inheritDoc}
     */
    protected function getTemplate()
    {
        return 'core/base::forms.fields.custom-select';
    }
}
