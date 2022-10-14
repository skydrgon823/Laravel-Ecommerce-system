<?php

namespace Botble\Ecommerce\Repositories\Eloquent;

use Botble\Ecommerce\Repositories\Interfaces\DiscountInterface;
use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;
use Carbon\Carbon;
use Illuminate\Database\Query\Builder;

class DiscountRepository extends RepositoriesAbstract implements DiscountInterface
{
    /**
     * {@inheritDoc}
     */
    public function getAvailablePromotions(array $with = [], bool $forProductSingle = false)
    {
        $data = $this->model
            ->where('type', 'promotion')
            ->where('start_date', '<=', Carbon::now())
            ->where(function ($query) {
                /**
                 * @var Builder $query
                 */
                return $query
                    ->whereNull('end_date')
                    ->orWhere('end_date', '>=', Carbon::now());
            })
            ->where(function ($query) use ($forProductSingle) {
                /**
                 * @var Builder $query
                 */
                return $query
                    ->whereIn('target', ['all-orders', 'amount-minimum-order'])
                    ->orWhere(function ($sub) use ($forProductSingle) {
                        $compare = '>';

                        if ($forProductSingle) {
                            $compare = '=';
                        }

                        /**
                         * @var Builder $sub
                         */
                        return $sub
                            ->whereIn('target', ['customer', 'group-products', 'specific-product', 'product-variant'])
                            ->where('product_quantity', $compare, 1);
                    });
            });

        if ($with) {
            $data = $data->with($with);
        }

        return $this->applyBeforeExecuteQuery($data)->get();
    }

    /**
     * {@inheritDoc}
     */
    public function getProductPriceBasedOnPromotion(array $productIds = [], array $productCollectionIds = [])
    {
        $data = $this->model
            ->where('type', 'promotion')
            ->where('start_date', '<=', Carbon::now())
            ->where(function ($query) use ($productIds, $productCollectionIds) {
                /**
                 * @var Builder $query
                 */
                return $query
                    ->where(function ($sub) use ($productIds) {
                        /**
                         * @var Builder $sub
                         */
                        return $sub
                            ->whereIn('target', ['specific-product', 'product-variant'])
                            ->whereHas('products', function ($whereHas) use ($productIds) {
                                return $whereHas->whereIn('ec_discount_products.product_id', $productIds);
                            });
                    })
                    ->orWhere(function ($sub) use ($productCollectionIds) {
                        /**
                         * @var Builder $sub
                         */
                        return $sub
                            ->where('target', 'group-products')
                            ->whereHas('productCollections', function ($whereHas) use ($productCollectionIds) {
                                return $whereHas->whereIn('ec_discount_product_collections.product_collection_id', $productCollectionIds);
                            });
                    })
                    ->orWhere(function ($sub) {
                        /**
                         * @var Builder $sub
                         */
                        return $sub
                            ->where('target', 'customer')
                            ->whereHas('customers', function ($whereHas) {
                                $customerId = auth('customer')->check() ? auth('customer')->id() : -1;

                                return $whereHas->where('ec_discount_customers.customer_id', $customerId);
                            });
                    });
            })
            ->where(function ($query) {
                /**
                 * @var Builder $query
                 */
                return $query
                    ->whereNull('end_date')
                    ->orWhere('end_date', '>=', Carbon::now());
            })
            ->where('product_quantity', 1)
            ->select('ec_discounts.*');

        return $this->applyBeforeExecuteQuery($data, true)->get();
    }
}
