<?php

namespace Botble\Location\Http\Controllers;

use Assets;
use BaseHelper;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Location\Exports\CsvLocationExport;
use Botble\Location\Repositories\Interfaces\CityInterface;
use Botble\Location\Repositories\Interfaces\CountryInterface;
use Botble\Location\Repositories\Interfaces\StateInterface;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ExportController extends BaseController
{
    /**
     * @return Factory|Application|View
     */
    public function index(
        CountryInterface $countryRepository,
        StateInterface   $stateRepository,
        CityInterface    $cityRepository
    ) {
        page_title()->setTitle(trans('plugins/location::location.export_location'));

        Assets::addScriptsDirectly(['vendor/core/plugins/location/js/export.js']);

        $countryCount = $countryRepository->count();
        $stateCount = $stateRepository->count();
        $cityCount = $cityRepository->count();

        return view('plugins/location::export.index', compact('countryCount', 'stateCount', 'cityCount'));
    }

    /**
     * @return BinaryFileResponse
     */
    public function export()
    {
        BaseHelper::maximumExecutionTimeAndMemoryLimit();

        return (new CsvLocationExport())->download('exported_location.csv');
    }
}
