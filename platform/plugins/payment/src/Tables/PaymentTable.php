<?php

namespace Botble\Payment\Tables;

use BaseHelper;
use Botble\Payment\Enums\PaymentStatusEnum;
use Botble\Payment\Repositories\Interfaces\PaymentInterface;
use Botble\Table\Abstracts\TableAbstract;
use Html;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class PaymentTable extends TableAbstract
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
     * PaymentTable constructor.
     * @param DataTables $table
     * @param UrlGenerator $urlGenerator
     * @param PaymentInterface $paymentRepository
     */
    public function __construct(DataTables $table, UrlGenerator $urlGenerator, PaymentInterface $paymentRepository)
    {
        parent::__construct($table, $urlGenerator);

        $this->repository = $paymentRepository;

        if (!Auth::user()->hasAnyPermission(['payment.show', 'payment.destroy'])) {
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
            ->editColumn('charge_id', function ($item) {
                return Html::link(route('payment.show', $item->id), Str::limit($item->charge_id, 20));
            })
            ->editColumn('checkbox', function ($item) {
                return $this->getCheckbox($item->id);
            })
            ->editColumn('customer_id', function ($item) {
                if ($item->customer_id && $item->customer_type && class_exists($item->customer_type)) {
                    return $item->customer->name;
                }

                if ($item->order && $item->order->address) {
                    return $item->order->address->name;
                }

                return '&mdash;';
            })
            ->editColumn('payment_channel', function ($item) {
                return $item->payment_channel->label();
            })
            ->editColumn('amount', function ($item) {
                return $item->amount . ' ' . $item->currency;
            })
            ->editColumn('created_at', function ($item) {
                return BaseHelper::formatDate($item->created_at);
            })
            ->editColumn('status', function ($item) {
                return $item->status->toHtml();
            })
            ->addColumn('operations', function ($item) {
                return $this->getOperations('payment.show', 'payment.destroy', $item);
            });

        return $this->toJson($data);
    }

    /**
     * @return mixed
     */
    public function query()
    {
        $query = $this->repository->getModel()->select([
            'id',
            'charge_id',
            'amount',
            'currency',
            'payment_channel',
            'created_at',
            'status',
            'order_id',
            'customer_id',
            'customer_type',
        ])->with(['customer']);

        if (method_exists($query->getModel(), 'order')) {
            $query->with(['customer', 'order']);
        }

        return $this->applyScopes($query);
    }

    /**
     * {@inheritDoc}
     */
    public function columns()
    {
        return [
            'id'              => [
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
            ],
            'charge_id'       => [
                'title' => trans('plugins/payment::payment.charge_id'),
                'class' => 'text-start',
            ],
            'customer_id'     => [
                'title' => trans('plugins/payment::payment.payer_name'),
                'class' => 'text-start',
            ],
            'amount'          => [
                'title' => trans('plugins/payment::payment.amount'),
                'class' => 'text-start',
            ],
            'payment_channel' => [
                'title' => trans('plugins/payment::payment.payment_channel'),
                'class' => 'text-start',
            ],
            'created_at'      => [
                'title' => trans('core/base::tables.created_at'),
                'width' => '100px',
            ],
            'status'          => [
                'title' => trans('core/base::tables.status'),
                'width' => '100px',
            ],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function bulkActions(): array
    {
        return $this->addDeleteAction(route('payment.deletes'), 'payment.destroy', parent::bulkActions());
    }

    /**
     * {@inheritDoc}
     */
    public function getBulkChanges(): array
    {
        return [
            'status'     => [
                'title'    => trans('core/base::tables.status'),
                'type'     => 'customSelect',
                'choices'  => PaymentStatusEnum::labels(),
                'validate' => 'required|in:' . implode(',', PaymentStatusEnum::values()),
            ],
            'charge_id'  => [
                'title'    => trans('plugins/payment::payment.charge_id'),
                'type'     => 'text',
                'validate' => 'required|max:120',
            ],
            'created_at' => [
                'title' => trans('core/base::tables.created_at'),
                'type'  => 'date',
            ],
        ];
    }
}
