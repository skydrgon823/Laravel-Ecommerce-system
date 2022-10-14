<?php

namespace Botble\Ecommerce\Http\Controllers;

use Assets;
use BaseHelper;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Ecommerce\Exports\TemplateProductExport;
use Botble\Ecommerce\Http\Requests\BulkImportRequest;
use Botble\Ecommerce\Http\Requests\ProductRequest;
use Botble\Ecommerce\Imports\ProductImport;
use Botble\Ecommerce\Imports\ValidateProductImport;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class BulkImportController extends BaseController
{
    /**
     * @var ProductImport
     */
    protected $productImport;

    /**
     * @var ProductImport
     */
    protected $validateProductImport;

    /**
     * @param ProductImport $productImport
     * @param ValidateProductImport $validateProductImport
     */
    public function __construct(ProductImport $productImport, ValidateProductImport $validateProductImport)
    {
        $this->productImport = $productImport;
        $this->validateProductImport = $validateProductImport;
    }

    /**
     * @return Factory|Application|View
     */
    public function index()
    {
        page_title()->setTitle(trans('plugins/ecommerce::bulk-import.name'));

        Assets::addScriptsDirectly(['vendor/core/plugins/ecommerce/js/bulk-import.js']);

        $template = new TemplateProductExport('xlsx');
        $headings = $template->headings();
        $data = $template->collection();
        $rules = $template->rules();

        return view('plugins/ecommerce::bulk-import.index', compact('data', 'headings', 'rules'));
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

        $this->validateProductImport
            ->setValidatorClass(new ProductRequest())
            ->import($file);

        if ($this->validateProductImport->failures()->count()) {
            $data = [
                'total_failed' => $this->validateProductImport->failures()->count(),
                'total_error'  => $this->validateProductImport->errors()->count(),
                'failures'     => $this->validateProductImport->failures(),
            ];

            $message = trans('plugins/ecommerce::bulk-import.import_failed_description');

            return $response
                ->setError()
                ->setData($data)
                ->setMessage($message);
        }

        $this->productImport
            ->setValidatorClass(new ProductRequest())
            ->setImportType($request->input('type'))
            ->import($file); // Start import

        $data = [
            'total_success' => $this->productImport->successes()->count(),
            'total_failed'  => $this->productImport->failures()->count(),
            'total_error'   => $this->productImport->errors()->count(),
            'failures'      => $this->productImport->failures(),
            'successes'     => $this->productImport->successes(),
        ];

        $message = trans('plugins/ecommerce::bulk-import.imported_successfully');

        $result = trans('plugins/ecommerce::bulk-import.results', [
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

        return (new TemplateProductExport($extension))->download('template_products_import.' . $extension);
    }
}
