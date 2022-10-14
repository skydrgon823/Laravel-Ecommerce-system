<?php

namespace Botble\Ecommerce\Tables;

use BaseHelper;
use Botble\Ecommerce\Enums\OrderReturnStatusEnum;
use Botble\Ecommerce\Repositories\Interfaces\OrderReturnInterface;
use Botble\Ecommerce\Repositories\Interfaces\OrderReturnItemInterface;
use Botble\Ecommerce\Supports\OrderReturnHelper;
use Botble\Table\Abstracts\TableAbstract;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class OrderReturnTable extends TableAbstract
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
     * @var OrderReturnItemInterface
     */
    protected $orderReturnItemRepository;

    /**
     * OrderTable constructor.
     * @param DataTables $table
     * @param UrlGenerator $urlGenerator
     * @param OrderReturnInterface $orderReturnRepository
     * @param OrderReturnItemInterface $orderReturnItemRepository
     */
    public function __construct(DataTables $table, UrlGenerator $urlGenerator, OrderReturnInterface $orderReturnRepository, OrderReturnItemInterface $orderReturnItemRepository)
    {
        parent::__construct($table, $urlGenerator);

        $this->repository = $orderReturnRepository;
        $this->orderReturnItemRepository = $orderReturnItemRepository;

        if (!Auth::user()->hasPermission('orders.edit')) {
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
            ->editColumn('return_status', function ($item) {
                return BaseHelper::clean($item->return_status->toHtml());
            })
            ->editColumn('reason', function ($item) {
                return BaseHelper::clean($item->reason->toHtml());
            })
            ->editColumn('order_id', function ($item) {
                return BaseHelper::clean(get_order_code($item->order_id));
            })
            ->editColumn('user_id', function ($item) {
                if (!$item->customer->name) {
                    return '&mdash;';
                }

                return BaseHelper::clean($item->customer->name);
            })
            ->editColumn('created_at', function ($item) {
                return BaseHelper::formatDate($item->created_at);
            });


        $data = $data
            ->addColumn('operations', function ($item) {
                return $this->getOperations('order_returns.edit', 'order_returns.destroy', $item);
            })
            ->filter(function ($query) {
                $keyword = $this->request->input('search.value');
                if ($keyword) {
                    return $query
                        ->whereHas('items', function ($subQuery) use ($keyword) {
                            return $subQuery->where('product_name', 'LIKE', '%' . $keyword . '%');
                        })->orWhereHas('customer', function ($subQuery) use ($keyword) {
                            return $subQuery->where('name', 'LIKE', '%' . $keyword . '%');
                        });
                }

                return $query;
            });

        return $this->toJson($data);
    }

    /**
     * @return mixed
     */
    public function query()
    {
        $query = $this->repository->getModel()
            ->select([
                'id',
                'order_id',
                'user_id',
                'reason',
                'order_status',
                'return_status',
                'created_at'
            ])
            ->with(['customer', 'order','items'])
            ->withCount('items')
            ->orderBy('id', 'desc');

        return $this->applyScopes($query);
    }

    /**
     * {@inheritDoc}
     */
    public function columns()
    {
        return [
            'id'      => [
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
                'class' => 'text-start',
            ],
            'order_id' => [
                'title' => trans('plugins/ecommerce::order.order_id'),
                'class' => 'text-start',
            ],
            'user_id' => [
                'title' => trans('plugins/ecommerce::order.customer_label'),
                'class' => 'text-start',
            ],
            'items_count'  => [
                'title' => trans('plugins/ecommerce::order.order_return_items_count'),
                'class' => 'text-center',
            ],
            'return_status'          => [
                'title' => trans('core/base::tables.status'),
                'class' => 'text-center',
            ],
            'created_at'      => [
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
            'return_status'     => [
                'title'    => trans('core/base::tables.status'),
                'type'     => 'select',
                'choices'  => OrderReturnStatusEnum::labels(),
                'validate' => 'required|in:' . implode(',', OrderReturnStatusEnum::values()),
            ],
            'created_at' => [
                'title' => trans('core/base::tables.created_at'),
                'type'  => 'date',
            ],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function getDefaultButtons(): array
    {
        return [
            'export',
            'reload',
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function bulkActions(): array
    {
        return $this->addDeleteAction(route('order_returns.deletes'), 'order_returns.destroy', parent::bulkActions());
    }

    /**
     * {@inheritDoc}
     */
    public function saveBulkChangeItem($item, string $inputKey, ?string $inputValue)
    {
        if ($inputKey === 'status' && $inputValue == OrderReturnStatusEnum::CANCELED) {
            OrderReturnHelper::cancelReturnOrder($item);

            return $item;
        }

        return parent::saveBulkChangeItem($item, $inputKey, $inputValue);
    }
}
