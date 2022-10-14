<?php

namespace Botble\Ecommerce\Http\Controllers;

use Assets;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Ecommerce\Enums\OrderStatusEnum;
use Botble\Ecommerce\Repositories\Interfaces\CustomerInterface;
use Botble\Ecommerce\Repositories\Interfaces\OrderInterface;
use Botble\Ecommerce\Repositories\Interfaces\ProductInterface;
use Carbon\Carbon;
use EcommerceHelper;
use Botble\Ecommerce\Tables\Reports\RecentOrdersTable;
use Botble\Ecommerce\Tables\Reports\TopSellingProductsTable;
use Botble\Payment\Enums\PaymentStatusEnum;
use Carbon\CarbonPeriod;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Throwable;

class ReportController extends BaseController
{
    /**
     * @var OrderInterface
     */
    protected $orderRepository;

    /**
     * @var ProductInterface
     */
    protected $productRepository;

    /**
     * @var CustomerInterface
     */
    protected $customerRepository;

    /**
     * EcommerceReportController constructor.
     * @param OrderInterface $order
     * @param ProductInterface $product
     * @param CustomerInterface $customer
     */
    public function __construct(
        OrderInterface $order,
        ProductInterface $product,
        CustomerInterface $customer
    ) {
        $this->orderRepository = $order;
        $this->productRepository = $product;
        $this->customerRepository = $customer;
    }

    /**
     * @param Request $request
     * @param TopSellingProductsTable $topSellingProductsTable
     * @param RecentOrdersTable $recentOrdersTable
     * @return BaseHttpResponse|Factory|View
     */
    public function getIndex(
        Request $request,
        TopSellingProductsTable $topSellingProductsTable,
        RecentOrdersTable $recentOrdersTable,
        BaseHttpResponse $response
    ) {
        page_title()->setTitle(trans('plugins/ecommerce::reports.name'));

        Assets::addScriptsDirectly([
                'vendor/core/plugins/ecommerce/libraries/daterangepicker/daterangepicker.js',
                'vendor/core/plugins/ecommerce/libraries/apexcharts-bundle/dist/apexcharts.min.js',
                'vendor/core/plugins/ecommerce/js/report.js',
            ])
            ->addStylesDirectly([
                'vendor/core/plugins/ecommerce/libraries/daterangepicker/daterangepicker.css',
                'vendor/core/plugins/ecommerce/libraries/apexcharts-bundle/dist/apexcharts.css',
                'vendor/core/plugins/ecommerce/css/report.css',
            ]);

        [$startDate, $endDate] = EcommerceHelper::getDateRangeInReport($request);

        $count = compact('startDate', 'endDate');

        $revenues = $this->orderRepository
            ->getModel()
            ->select([
                DB::raw('SUM(COALESCE(payments.amount, 0) - COALESCE(payments.refunded_amount, 0)) as revenue'),
                'payments.status',
            ])
            ->join('payments', 'payments.id', '=', 'ec_orders.payment_id')
            ->whereIn('payments.status', [PaymentStatusEnum::COMPLETED, PaymentStatusEnum::PENDING])
            ->whereDate('payments.created_at', '>=', $startDate)
            ->whereDate('payments.created_at', '<=', $endDate)
            ->groupBy('payments.status')
            ->get();

        $revenueCompleted = $revenues->firstWhere('status', PaymentStatusEnum::COMPLETED);
        $revenuePending = $revenues->firstWhere('status', PaymentStatusEnum::PENDING);

        $count['revenues'] = [
            [
                'label'     => PaymentStatusEnum::COMPLETED()->label(),
                'value'     => $revenueCompleted ? $revenueCompleted->revenue : 0,
                'status'    => true,
                'color'     => '#80bc00',
            ],
            [
                'label'     => PaymentStatusEnum::PENDING()->label(),
                'value'     => $revenuePending ? $revenuePending->revenue : 0,
                'status'    => false,
                'color'     => '#E91E63',
            ]
        ];
        $count['orders'] = $this->orderRepository
            ->getModel()
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->count();

        $count['products'] = $this->productRepository
            ->getModel()
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->where([
                'status'       => BaseStatusEnum::PUBLISHED,
                'is_variation' => false,
            ])
            ->count();

        $count['customers'] = $this->customerRepository
            ->getModel()
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->count();

        if ($request->ajax()) {
            return $response->setData([
                'html' => view('plugins/ecommerce::reports.partials.content', compact('count'))->render()
            ]);
        }

        $topSellingProducts = $topSellingProductsTable
            ->setAjaxUrl(route('ecommerce.report.top-selling-products'));

        $recentOrders = $recentOrdersTable->setAjaxUrl(route('ecommerce.report.recent-orders'));

        return view('plugins/ecommerce::reports.index', compact('count', 'topSellingProducts', 'recentOrders'));
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     * @throws Throwable
     */
    public function getRevenue(Request $request, BaseHttpResponse $response)
    {
        [$startDate, $endDate] = EcommerceHelper::getDateRangeInReport($request);

        $revenues = $this->orderRepository->getRevenueData($startDate, $endDate);

        $series = [];
        $dates = [];
        $earningSales = collect([]);
        $period = CarbonPeriod::create($startDate, $endDate);

        $colors = ['#fcb800', '#80bc00'];

        $data = [
            'name' => get_application_currency()->title,
            'data' => collect([]),
        ];

        foreach ($period as $date) {
            $value = $revenues
                ->where('date', $date->format('Y-m-d'))
                ->sum('revenue');
            $data['data'][] = $value;
        }

        $earningSales[] = [
            'text'  => trans('plugins/ecommerce::reports.items_earning_sales', [
                'value' => format_price($data['data']->sum()),
            ]),
            'color' => Arr::get($colors, $earningSales->count(), Arr::first($colors)),
        ];
        $series[] = $data;

        foreach ($period as $date) {
            $dates[] = $date->format('Y-m-d');
        }

        $colors = $earningSales->pluck('color');

        return $response->setData(compact('dates', 'series', 'earningSales', 'colors'));
    }

    /**
     * @param TopSellingProductsTable $topSellingProductsTable
     * @return JsonResponse|View
     * @throws Throwable
     */
    public function getTopSellingProducts(TopSellingProductsTable $topSellingProductsTable)
    {
        return $topSellingProductsTable->renderTable();
    }

    /**
     * @param RecentOrdersTable $recentOrdersTable
     * @return JsonResponse|View
     * @throws Throwable
     */
    public function getRecentOrders(RecentOrdersTable $recentOrdersTable)
    {
        return $recentOrdersTable->renderTable();
    }

    /**
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     * @throws Throwable
     */
    public function getDashboardWidgetGeneral(BaseHttpResponse $response)
    {
        $startOfMonth = Carbon::now()->startOfMonth()->toDateString();
        $today = Carbon::now()->toDateString();

        $processingOrders = $this->orderRepository
            ->getModel()
            ->where('status', OrderStatusEnum::PENDING)
            ->whereDate('created_at', '>=', $startOfMonth)
            ->whereDate('created_at', '<=', $today)
            ->count();

        $completedOrders = $this->orderRepository
            ->getModel()
            ->where('status', OrderStatusEnum::COMPLETED)
            ->whereDate('created_at', '>=', $startOfMonth)
            ->whereDate('created_at', '<=', $today)
            ->count();

        $revenue = $this->orderRepository->countRevenueByDateRange($startOfMonth, $today);

        $lowStockProducts = $this->productRepository
            ->getModel()
            ->where('with_storehouse_management', 1)
            ->where('quantity', '<', 2)
            ->where('quantity', '>', 0)
            ->count();

        $outOfStockProducts = $this->productRepository
            ->getModel()
            ->where('with_storehouse_management', 1)
            ->where('quantity', '<', 1)
            ->count();

        return $response
            ->setData(
                view(
                    'plugins/ecommerce::reports.widgets.general',
                    compact(
                        'processingOrders',
                        'revenue',
                        'completedOrders',
                        'outOfStockProducts',
                        'lowStockProducts'
                    )
                )->render()
            );
    }
}
