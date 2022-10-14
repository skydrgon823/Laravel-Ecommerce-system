<?php

namespace Botble\Ecommerce\Tables\Reports;

use BaseHelper;
use Botble\Ecommerce\Repositories\Interfaces\ProductInterface;
use Botble\Payment\Enums\PaymentStatusEnum;
use Botble\Table\Abstracts\TableAbstract;
use EcommerceHelper;
use Html;
use Illuminate\Contracts\Routing\UrlGenerator;
use Yajra\DataTables\DataTables;

class TopSellingProductsTable extends TableAbstract
{
    /**
     * @var string
     */
    protected $type = self::TABLE_TYPE_SIMPLE;

    /**
     * @var string
     */
    protected $view = 'core/table::simple-table';

    /**
     * TopSellingProductsTable constructor.
     * @param DataTables $table
     * @param UrlGenerator $urlGenerator
     * @param ProductInterface $productRepository
     */
    public function __construct(
        DataTables $table,
        UrlGenerator $urlGenerator,
        ProductInterface $productRepository
    ) {
        parent::__construct($table, $urlGenerator);
        $this->repository = $productRepository;
    }

    /**
     * {@inheritDoc}
     */
    public function ajax()
    {
        $data = $this->table
            ->eloquent($this->query())
            ->editColumn('id', function ($item) {
                if (!$item->is_variation) {
                    return $item->id;
                }

                return $item->original_product->id;
            })
            ->editColumn('name', function ($item) {
                if (!$item->is_variation) {
                    return Html::link($item->url, BaseHelper::clean($item->name), ['target' => '_blank']);
                }

                $attributeText = $item->variation_attributes;

                return Html::link($item->original_product->url, BaseHelper::clean($item->original_product->name), ['target' => '_blank'])
                        ->toHtml() . ' ' . Html::tag('small', $attributeText);
            });

        return $this->toJson($data);
    }

    /**
     * @return mixed
     */
    public function query()
    {
        [$startDate, $endDate] = EcommerceHelper::getDateRangeInReport(request());

        $query = $this->repository->getModel()
            ->join('ec_order_product', 'ec_products.id', '=', 'ec_order_product.product_id')
            ->join('ec_orders', 'ec_orders.id', '=', 'ec_order_product.order_id')
            ->join('payments', 'payments.order_id', '=', 'ec_orders.id')
            ->where('payments.status', PaymentStatusEnum::COMPLETED)
            ->whereDate('ec_orders.created_at', '>=', $startDate)
            ->whereDate('ec_orders.created_at', '<=', $endDate)
            ->select([
                'ec_products.id as id',
                'ec_products.is_variation as is_variation',
                'ec_products.name as name',
                'ec_order_product.qty as qty',
            ])
            ->orderBy('ec_order_product.qty', 'DESC')
            ->limit(10);

        return $this->applyScopes($query);
    }

    /**
     * {@inheritDoc}
     */
    public function columns()
    {
        return [
            'id'   => [
                'title'     => trans('plugins/ecommerce::order.product_id'),
                'width'     => '80px',
                'orderable' => false,
                'class'     => 'no-sort text-center',
            ],
            'name' => [
                'title'     => trans('plugins/ecommerce::reports.product_name'),
                'orderable' => false,
                'class'     => 'text-start no-sort',
            ],
            'qty'  => [
                'title'     => trans('plugins/ecommerce::reports.quantity'),
                'orderable' => false,
                'class'     => 'text-center no-sort',
                'width'     => '60px',
            ],
        ];
    }
}
