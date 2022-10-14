<?php

namespace Botble\Ecommerce\Tables\Reports;

use BaseHelper;
use Botble\Ecommerce\Repositories\Interfaces\OrderInterface;
use Botble\Table\Abstracts\TableAbstract;
use EcommerceHelper;
use Html;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class RecentOrdersTable extends TableAbstract
{
    /**
     * @var string
     */
    protected $type = self::TABLE_TYPE_SIMPLE;

    /**
     * @var int
     */
    protected $defaultSortColumn = 0;

    /**
     * @var string
     */
    protected $view = 'core/table::simple-table';

    /**
     * RecentOrdersTable constructor.
     * @param DataTables $table
     * @param UrlGenerator $urlGenerator
     * @param OrderInterface $orderRepository
     */
    public function __construct(DataTables $table, UrlGenerator $urlGenerator, OrderInterface $orderRepository)
    {
        parent::__construct($table, $urlGenerator);

        $this->repository = $orderRepository;
    }

    /**
     * {@inheritDoc}
     */
    public function ajax()
    {
        $data = $this->table
            ->eloquent($this->query())
            ->editColumn('checkbox', function ($item) {
                return $this->getCheckbox($item->id);
            })
            ->editColumn('id', function ($item) {
                if (!Auth::user()->hasPermission('orders.edit')) {
                    return get_order_code($item->id);
                }

                return Html::link(route('orders.edit', $item->id), get_order_code($item->id));
            })
            ->editColumn('status', function ($item) {
                return BaseHelper::clean($item->status->toHtml());
            })
            ->editColumn('payment_status', function ($item) {
                return BaseHelper::clean($item->payment->status->label() ?: '&mdash;');
            })
            ->editColumn('payment_method', function ($item) {
                return BaseHelper::clean($item->payment->payment_channel->label() ?: '&mdash;');
            })
            ->editColumn('amount', function ($item) {
                return format_price($item->amount);
            })
            ->editColumn('shipping_amount', function ($item) {
                return format_price($item->shipping_amount);
            })
            ->editColumn('user_id', function ($item) {
                return BaseHelper::clean($item->user->name ?: $item->address->name);
            })
            ->editColumn('created_at', function ($item) {
                return BaseHelper::formatDate($item->created_at);
            });

        return $this->toJson($data);
    }

    /**
     * {@inheritDoc}
     */
    public function query()
    {
        [$startDate, $endDate] = EcommerceHelper::getDateRangeInReport(request());

        $query = $this->repository->getModel()
            ->select([
                'id',
                'status',
                'user_id',
                'created_at',
                'amount',
                'tax_amount',
                'shipping_amount',
                'payment_id',
            ])
            ->with(['user', 'payment'])
            ->where('is_finished', 1)
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->orderBy('created_at', 'desc')
            ->limit(10);

        return $this->applyScopes($query);
    }

    /**
     * {@inheritDoc}
     */
    public function columns()
    {
        $columns = [
            'id'             => [
                'title'     => trans('core/base::tables.id'),
                'width'     => '20px',
                'class'     => 'text-start no-sort',
                'orderable' => false,
            ],
            'user_id'        => [
                'title'     => trans('plugins/ecommerce::order.customer_label'),
                'class'     => 'text-start',
                'orderable' => false,
            ],
            'amount'         => [
                'title'     => trans('plugins/ecommerce::order.amount'),
                'class'     => 'text-center',
                'orderable' => false,
            ],
            'payment_method' => [
                'name'      => 'payment_id',
                'title'     => trans('plugins/ecommerce::order.payment_method'),
                'class'     => 'text-center',
                'orderable' => false,
            ],
            'payment_status' => [
                'name'      => 'payment_id',
                'title'     => trans('plugins/ecommerce::order.payment_status_label'),
                'class'     => 'text-center',
                'orderable' => false,
            ],
            'status'         => [
                'title'     => trans('core/base::tables.status'),
                'class'     => 'text-center',
                'orderable' => false,
            ],
            'created_at'     => [
                'title'     => trans('core/base::tables.created_at'),
                'width'     => '100px',
                'class'     => 'text-start',
                'orderable' => false,
            ],
        ];

        return $columns;
    }
}
