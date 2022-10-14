<?php

namespace Botble\Location\Http\Controllers;

use Assets;
use BaseHelper;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Base\Supports\Helper;
use Botble\Location\Exports\TemplateLocationExport;
use Botble\Location\Http\Requests\BulkImportRequest;
use Botble\Location\Http\Requests\LocationImportRequest;
use Botble\Location\Imports\LocationImport;
use Botble\Location\Imports\ValidateLocationImport;
use Botble\Location\Location;
use Botble\Location\Repositories\Interfaces\CountryInterface;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class BulkImportController extends BaseController
{
    /**
     * @var LocationImport
     */
    protected $locationImport;

    /**
     * @var LocationImport
     */
    protected $validateLocationImport;

    /**
     * BulkImportController constructor.
     * @param LocationImport $locationImport
     * @param ValidateLocationImport $validateLocationImport
     */
    public function __construct(LocationImport $locationImport, ValidateLocationImport $validateLocationImport)
    {
        $this->locationImport = $locationImport;
        $this->validateLocationImport = $validateLocationImport;
    }

    /**
     * @return Application|Factory|View
     */
    public function index()
    {
        page_title()->setTitle(trans('plugins/location::bulk-import.name'));

        Assets::addScriptsDirectly(['vendor/core/plugins/location/js/bulk-import.js']);

        return view('plugins/location::bulk-import.index');
    }

    /**
     * @param BulkImportRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function postImport(BulkImportRequest $request, BaseHttpResponse $response)
    {
        BaseHelper::maximumExecutionTimeAndMemoryLimit();

        $file = $request->file('file');

        $this->validateLocationImport
            ->setValidatorClass(new LocationImportRequest())
            ->setImportType($request->input('type'))
            ->import($file);

        if ($this->validateLocationImport->failures()->count()) {
            $data = [
                'total_failed' => $this->validateLocationImport->failures()->count(),
                'total_error'  => $this->validateLocationImport->errors()->count(),
                'failures'     => $this->validateLocationImport->failures(),
            ];

            $message = trans('plugins/location::bulk-import.import_failed_description');

            return $response
                ->setError()
                ->setData($data)
                ->setMessage($message);
        }

        $this->locationImport
            ->setValidatorClass(new LocationImportRequest())
            ->setImportType($request->input('type'))
            ->import($file);

        $data = [
            'total_success' => $this->locationImport->successes()->count(),
            'total_failed'  => $this->locationImport->failures()->count(),
            'total_error'   => $this->locationImport->errors()->count(),
            'failures'      => $this->locationImport->failures(),
            'successes'     => $this->locationImport->successes(),
        ];

        $message = trans('plugins/location::bulk-import.imported_successfully');

        $result = trans('plugins/location::bulk-import.results', [
            'success' => $data['total_success'],
            'failed'  => $data['total_failed'],
        ]);

        return $response->setData($data)->setMessage($message . ' ' . $result);
    }

    /**
     * @param Request $request
     * @return BinaryFileResponse
     */
    public function downloadTemplate(Request $request)
    {
        $extension = $request->input('extension');
        $extension = $extension == 'csv' ? $extension : Excel::XLSX;

        return (new TemplateLocationExport($extension))->download('template_locations_import.' . $extension);
    }

    /**
     * @param Location $location
     * @param BaseHttpResponse $response
     * @param CountryInterface $countryRepository
     * @return BaseHttpResponse
     */
    public function ajaxGetAvailableRemoteLocations(Location $location, BaseHttpResponse $response, CountryInterface $countryRepository)
    {
        $remoteLocations = $location->getRemoteAvailableLocations();

        $availableLocations = $countryRepository->pluck('code');

        $listCountries = Helper::countries();

        $locations = [];

        foreach ($remoteLocations as $location) {
            $location = strtoupper($location);

            if (in_array($location, $availableLocations)) {
                continue;
            }

            foreach ($listCountries as $key => $country) {
                if ($location === strtoupper($key)) {
                    $locations[$location] = $country;
                }
            }
        }

        $locations = array_unique($locations);

        return $response
            ->setData(view('plugins/location::partials.available-remote-locations', compact('locations'))->render());
    }

    /**
     * @param string $countryCode
     * @param Location $location
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function importLocationData(string $countryCode, Location $location, BaseHttpResponse $response)
    {
        $result = $location->downloadRemoteLocation($countryCode);

        return $response
            ->setError($result['error'])
            ->setMessage($result['message']);
    }
}
