<?php

namespace Botble\Location\Exports;

use Botble\Location\Repositories\Interfaces\CountryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Language;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CsvLocationExport implements FromCollection, WithHeadings
{
    use Exportable;

    /**
     * @var Collection
     */
    protected $results;

    /**
     * @var int
     */
    protected $totalRow;

    public function __construct()
    {
        $supportedLocales = [];
        $defaultLanguage = null;

        if (defined('LANGUAGE_MODULE_SCREEN_NAME')) {
            $defaultLanguage = Language::getDefaultLanguage(['lang_code'])->lang_code;

            $supportedLocales = Language::getSupportedLocales();
        }

        $with = [
            'states',
            'states.cities',
        ];

        if (count($supportedLocales)) {
            $with = [
                'translations',
                'states',
                'states.cities',
                'states.translations',
                'states.cities.translations',
            ];
        }

        $countries = app(CountryInterface::class)->all($with);

        $locations = [];

        foreach ($countries as $country) {
            $countryData = [
                'name'         => $country->name,
                'slug'         => $country->slug ?: Str::slug($country->name),
                'abbreviation' => '',
                'state'        => '',
                'country'      => '',
                'import_type'  => 'country',
                'status'       => $country->status,
                'order'        => $country->order,
                'nationality'  => $country->nationality,
            ];

            foreach ($supportedLocales as $properties) {
                if ($properties['lang_code'] != $defaultLanguage) {
                    $countryData['name_' . $properties['lang_code']] = $country->translations->where('lang_code', $properties['lang_code'])->get('name');
                }
            }

            $locations[] = $countryData;

            foreach ($country->states as $state) {
                $stateData = [
                    'name'         => $state->name,
                    'slug'         => $state->slug ?: Str::slug($state->name),
                    'abbreviation' => $state->abbreviation,
                    'state'        => '',
                    'country'      => $country->name,
                    'import_type'  => 'state',
                    'status'       => $state->status,
                    'order'        => $state->order,
                    'nationality'  => '',
                ];

                foreach ($supportedLocales as $properties) {
                    if ($properties['lang_code'] != $defaultLanguage) {
                        $stateData['name_' . $properties['lang_code']] = $state->translations->where('lang_code', $properties['lang_code'])->get('name');
                    }
                }

                $locations[] = $stateData;

                foreach ($state->cities as $city) {
                    $cityData = [
                        'name'         => $city->name,
                        'slug'         => $city->slug ?: Str::slug($state->name),
                        'abbreviation' => '',
                        'state'        => $state->name,
                        'country'      => $city->country->name,
                        'import_type'  => 'city',
                        'status'       => $city->status,
                        'order'        => $city->order,
                        'nationality'  => '',
                    ];

                    foreach ($supportedLocales as $properties) {
                        if ($properties['lang_code'] != $defaultLanguage) {
                            $stateData['name_' . $properties['lang_code']] = $city->translations->where('lang_code', $properties['lang_code'])->get('name');
                        }
                    }

                    $locations[] = $cityData;
                }
            }
        }

        $this->results = collect($locations);
        $this->totalRow = $this->results->count() + 1;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        $headings = [
            'name'         => 'Name', // 1 => A
            'slug'         => 'Slug', // 2 => B
            'abbreviation' => 'Abbreviation', // 3 => C
            'state'        => 'State', // 4 => D
            'country'      => 'Country', // 5 => E
            'import_type'  => 'Import Type', // 6 => F
            'status'       => 'Status', // 7 => G
            'order'        => 'Order', // 8 => H
            'nationality'  => 'Nationality', // 9 => I
        ];

        if (defined('LANGUAGE_MODULE_SCREEN_NAME')) {
            $defaultLanguage = Language::getDefaultLanguage(['lang_code'])->lang_code;

            $supportedLocales = Language::getSupportedLocales();
            foreach ($supportedLocales as $properties) {
                if ($properties['lang_code'] != $defaultLanguage) {
                    $headings['name_' . $properties['lang_code']] = 'Name (' .  strtoupper($properties['lang_code']) . ')';
                }
            }
        }

        return $headings;
    }

    /**
     * @return Collection
     */
    public function collection(): Collection
    {
        return $this->results;
    }
}
