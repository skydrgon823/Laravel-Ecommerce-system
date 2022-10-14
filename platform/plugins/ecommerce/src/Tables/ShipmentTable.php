<?php

namespace Botble\Ecommerce\Tables;

use BaseHelper;
use Botble\Ecommerce\Enums\ShippingStatusEnum;
use Botble\Ecommerce\Repositories\Interfaces\ShipmentInterface;
use Botble\Table\Abstracts\TableAbstract;
use Html;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class ShipmentTable extends TableAbstract
{
    /**
     * @var bool
     */
    protected $hasActions = true;

    /**
     * @var bool
     */
    protected $hasFilter = true;

    /**
     * @param DataTables $table
     * @param UrlGenerator $urlGenerator
     * @param ShipmentInterface $repository
     */
    public function __construct(DataTables $table, UrlGenerator $urlGenerator, ShipmentInterface $repository)
    {
        parent::__construct($table, $urlGenerator);

        $this->repository = $repository;

        if (!Auth::user()->hasAnyPermission(['orders.edit'])) {
            $this->hasOperations = false;
            $this->hasActions = false;
        }
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
            ->editColumn('order_id', function ($item) {
                if (!Auth::user()->hasPermission('orders.edit')) {
                    return get_order_code($item->order_id);
                }

                return Html::link(route('orders.edit', $item->id), get_order_code($item->order_id) . ' <i class="fa fa-external-link-alt"></i>', ['target' => '_blank'], null, false);
            })
            ->editColumn('user_id', function ($item) {
                return BaseHelper::clean($item->order->user->name ?: $item->order->address->name);
            })
            ->editColumn('price', function ($item) {
                return format_price($item->price);
            })
            ->editColumn('created_at', function ($item) {
                return BaseHelper::formatDate($item->created_at);
            })
            ->editColumn('status', function ($item) {
                return BaseHelper::clean($item->status->toHtml());
            })
            ->editColumn('cod_status', function ($item) {
                if (!(float)$item->cod_amount) {
                    return Html::tag('span', trans('plugins/ecommerce::shipping.not_available'), ['class' => 'label-info status-label'])
                        ->toHtml();
                }

                return BaseHelper::clean($item->cod_status->toHtml());
            })
            ->addColumn('operations', function ($item) {
                return $this->getOperations('ecommerce.shipments.edit', null, $item);
            })
            ->filter(function ($query) {
                $keyword = $this->request->input('search.value');
                if ($keyword) {
                    return $query
                        ->whereHas('order.address', function ($subQuery) use ($keyword) {
                            return $subQuery->where('ec_order_addresses.name', 'LIKE', '%' . $keyword . '%');
                        });
                }

                return $query;
            });

        return $this->toJson($data);
    }

    /**
     * {@inheritDoc}
     */
    public function query()
    {
        $query = $this->repository->getModel()->select([
            'id',
            'order_id',
            'user_id',
            'price',
            'status',
            'cod_status',
            'created_at',
        ]);

        return $this->applyScopes($query);
    }

    /**
     * {@inheritDoc}
     */
    public function columns()
    {
        return [
            'id'         => [
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
                'class' => 'text-start',
            ],
            'order_id'   => [
                'title' => trans('plugins/ecommerce::shipping.order_id'),
                'class' => 'text-center',
            ],
            'user_id' => [
                'title' => trans('plugins/ecommerce::order.customer_label'),
                'class' => 'text-start',
            ],
            'price'   => [
                'title' => trans('plugins/ecommerce::shipping.shipping_amount'),
                'class' => 'text-center',
            ],
            'status'     => [
                'title' => trans('core/base::tables.status'),
                'class' => 'text-center',
            ],
            'cod_status'   => [
                'title' => trans('plugins/ecommerce::shipping.cod_status'),
                'class' => 'text-center',
            ],
            'created_at' => [
                'title' => trans('core/base::tables.created_at'),
                'width' => '100px',
                'class' => 'text-start',
            ],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function getBulkChanges(): array
    {
        return [
            'status'     => [
                'title'    => trans('core/base::tables.status'),
                'type'     => 'select',
                'choices'  => ShippingStatusEnum::labels(),
                'validate' => 'required|in:' . implode(',', ShippingStatusEnum::values()),
            ],
            'created_at' => [
                'title' => trans('core/base::tables.created_at'),
                'type'  => 'date',
            ],
        ];
    }
}
