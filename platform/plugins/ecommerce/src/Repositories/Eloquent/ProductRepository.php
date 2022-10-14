<?php

namespace Botble\Ecommerce\Repositories\Eloquent;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Ecommerce\Models\ProductAttribute;
use Botble\Ecommerce\Repositories\Interfaces\ProductInterface;
use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;
use Carbon\Carbon;
use EcommerceHelper;
use Eloquent;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Language;

class ProductRepository extends RepositoriesAbstract implements ProductInterface
{
    /**
     * {@inheritDoc}
     */
    public function getSearch($keyword, $paginate = 10)
    {
        return $this->filterProducts([
            'keyword'  => $keyword,
            'paginate' => [
                'per_page'      => $paginate,
                'current_paged' => 1,
            ],
        ]);
    }

    /**
     * @return Eloquent|\Illuminate\Database\Eloquent\Builder|Model
     */
    protected function exceptOutOfStockProducts()
    {
        return $this->model->notOutOfStock();
    }

    /**
     * {@inheritDoc}
     */
    public function getRelatedProductAttributes($product)
    {
        try {
            $data = ProductAttribute::join(
                'ec_product_variation_items',
                'ec_product_variation_items.attribute_id',
                '=',
                'ec_product_attributes.id'
            )
                ->join(
                    'ec_product_variations',
                    'ec_product_variation_items.variation_id',
                    '=',
                    'ec_product_variations.id'
                )
                ->where('configurable_product_id', $product->id)
                ->where('ec_product_attributes.status', BaseStatusEnum::PUBLISHED)
                ->select('ec_product_attributes.*')
                ->distinct();

            return $this->applyBeforeExecuteQuery($data)->get();
        } catch (Exception $exception) {
            return collect([]);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getProducts(array $params)
    {
        $params = array_merge([
            'condition' => [
                'status'       => BaseStatusEnum::PUBLISHED,
                'is_variation' => 0,
            ],
            'order_by'  => [
                'order'      => 'ASC',
                'created_at' => 'DESC',
            ],
            'take'      => null,
            'paginate'  => [
                'per_page'      => null,
                'current_paged' => 1,
            ],
            'with'      => [],
            'withCount' => [],
            'withAvg'   => [],
        ], $params);

        return $this->filterProducts([], $params);
    }

    /**
     * {@inheritDoc}
     */
    public function getProductsWithCategory(array $params)
    {
        $params = array_merge([
            'categories' => [
                'by'       => 'id',
                'value_in' => [],
            ],
            'condition'  => [
                'ec_products.status'       => BaseStatusEnum::PUBLISHED,
                'ec_products.is_variation' => 0,
            ],
            'order_by'   => [
                'ec_products.order'      => 'ASC',
                'ec_products.created_at' => 'DESC',
            ],
            'take'       => null,
            'paginate'   => [
                'per_page'      => null,
                'current_paged' => 1,
            ],
            'select'     => [
                'ec_products.*',
                'base_category.id as category_id',
                'base_category.name as category_name',
            ],
            'with'       => [],
        ], $params);

        $filters = ['categories' => $params['categories']['value_in']];

        Arr::forget($params, 'categories');

        return $this->filterProducts($filters, $params);
    }

    /**
     * {@inheritDoc}
     */
    public function getOnSaleProducts(array $params)
    {
        $this->model = $this->originalModel;

        $params = array_merge([
            'condition' => [
                'status'       => BaseStatusEnum::PUBLISHED,
                'is_variation' => 0,
            ],
            'order_by'  => [
                'order'      => 'ASC',
                'created_at' => 'DESC',
            ],
            'take'      => null,
            'paginate'  => [
                'per_page'      => null,
                'current_paged' => 1,
            ],
            'with'      => [],
        ], $params);

        $this->model = $this->model
            ->where(function ($query) {
                /**
                 * @var Builder $query
                 */
                return $query
                    ->where(function ($subQuery) {
                        /**
                         * @var Builder $subQuery
                         */
                        return $subQuery
                            ->where('sale_type', 0)
                            ->where('sale_price', '>', 0);
                    })
                    ->orWhere(function ($subQuery) {
                        /**
                         * @var Builder $subQuery
                         */
                        return $subQuery
                            ->where(function ($sub) {
                                /**
                                 * @var Builder $sub
                                 */
                                return $sub
                                    ->where('sale_type', 1)
                                    ->where('start_date', '<>', null)
                                    ->where('end_date', '<>', null)
                                    ->where('start_date', '<=', Carbon::now())
                                    ->where('end_date', '>=', Carbon::today());
                            })
                            ->orWhere(function ($sub) {
                                /**
                                 * @var Builder $sub
                                 */
                                return $sub
                                    ->where('sale_type', 1)
                                    ->where('start_date', '<>', null)
                                    ->where('start_date', '<=', Carbon::now())
                                    ->whereNull('end_date');
                            });
                    });
            });

        $this->exceptOutOfStockProducts();

        return $this->advancedGet($params);
    }

    /**
     * {@inheritDoc}
     */
    public function getProductVariations($configurableProductId, array $params = [])
    {
        $this->model = $this->model
            ->join('ec_product_variations', function ($join) use ($configurableProductId) {
                /**
                 * @var JoinClause $join
                 */
                return $join
                    ->on('ec_product_variations.product_id', '=', 'ec_products.id')
                    ->where('ec_product_variations.configurable_product_id', $configurableProductId);
            })
            ->join(
                'ec_products as original_products',
                'ec_product_variations.configurable_product_id',
                '=',
                'original_products.id'
            );

        $params = array_merge([
            'select' => [
                'ec_products.*',
                'ec_product_variations.id as variation_id',
                'ec_product_variations.configurable_product_id as configurable_product_id',
                'original_products.images as original_images',
            ],
        ], $params);

        return $this->advancedGet($params);
    }

    /**
     * {@inheritDoc}
     */
    public function getProductsByCollections(array $params)
    {
        $params = array_merge([
            'collections' => [
                'by'       => 'id',
                'value_in' => [],
            ],
            'condition'   => [
                'ec_products.status'       => BaseStatusEnum::PUBLISHED,
                'ec_products.is_variation' => 0,
            ],
            'order_by'    => [
                'ec_products.order'      => 'ASC',
                'ec_products.created_at' => 'DESC',
            ],
            'take'        => null,
            'paginate'    => [
                'per_page'      => null,
                'current_paged' => 1,
            ],
            'select'      => [
                'ec_products.*',
            ],
            'with'        => [],
            'withCount'   => [],
        ], $params);

        $filters = ['collections' => $params['collections']['value_in']];

        Arr::forget($params, 'categories');

        return $this->filterProducts($filters, $params);
    }

    /**
     * {@inheritDoc}
     */
    public function getProductByBrands(array $params)
    {
        $params = array_merge([
            'brand_id'  => null,
            'condition' => [
                'status'       => BaseStatusEnum::PUBLISHED,
                'is_variation' => 0,
            ],
            'order_by'  => [
                'order'      => 'ASC',
                'created_at' => 'DESC',
            ],
            'take'      => null,
            'paginate'  => [
                'per_page'      => null,
                'current_paged' => 1,
            ],
            'select'    => [
                '*',
            ],
            'with'      => [

            ],
        ], $params);

        $filters = ['brands' => (array)$params['brand_id']];

        Arr::forget($params, 'brand_id');

        return $this->filterProducts($filters, $params);
    }

    /**
     * {@inheritDoc}
     */
    public function getProductsByCategories(array $params)
    {
        $params = array_merge([
            'categories' => [
                'by'       => 'id',
                'value_in' => [],
            ],
            'condition'  => [
                'ec_products.status'       => BaseStatusEnum::PUBLISHED,
                'ec_products.is_variation' => 0,
            ],
            'order_by'   => [
                'ec_products.order'      => 'ASC',
                'ec_products.created_at' => 'DESC',
            ],
            'take'       => null,
            'paginate'   => [
                'per_page'      => null,
                'current_paged' => 1,
            ],
            'select'     => [
                'ec_products.*',
            ],
            'with'       => [],
            'withCount'  => [],
        ], $params);

        $filters = ['categories' => $params['categories']['value_in']];

        Arr::forget($params, 'categories');

        return $this->filterProducts($filters, $params);
    }

    /**
     * {@inheritDoc}
     */
    public function getProductByTags(array $params)
    {
        $params = array_merge([
            'product_tag' => [
                'by'       => 'id',
                'value_in' => [],
            ],
            'condition'   => [
                'ec_products.status'       => BaseStatusEnum::PUBLISHED,
                'ec_products.is_variation' => 0,
            ],
            'order_by'    => [
                'ec_products.order'      => 'ASC',
                'ec_products.created_at' => 'DESC',
            ],
            'take'        => null,
            'paginate'    => [
                'per_page'      => null,
                'current_paged' => 1,
            ],
            'select'      => [
                'ec_products.*',
            ],
            'with'        => [],
            'withCount'   => [],
        ], $params);

        $filters = ['tags' => $params['product_tag']['value_in']];

        Arr::forget($params, 'product_tag');

        return $this->filterProducts($filters, $params);
    }

    /**
     * {@inheritDoc}
     */
    public function filterProducts(array $filters, array $params = [])
    {
        $filters = array_merge([
            'keyword'                => null,
            'min_price'              => null,
            'max_price'              => null,
            'categories'             => [],
            'tags'                   => [],
            'brands'                 => [],
            'attributes'             => [],
            'collections'            => [],
            'count_attribute_groups' => null,
        ], $filters);

        $isUsingDefaultCurrency = get_application_currency_id() == cms_currency()->getDefaultCurrency()->id;

        if ($filters['min_price'] && !$isUsingDefaultCurrency) {
            $filters['min_price'] = (float)$filters['min_price'] / get_current_exchange_rate();
        }

        if ($filters['max_price'] && !$isUsingDefaultCurrency) {
            $filters['max_price'] = (float)$filters['max_price'] / get_current_exchange_rate();
        }

        $params = array_merge([
            'condition' => [
                'ec_products.status'       => BaseStatusEnum::PUBLISHED,
                'ec_products.is_variation' => 0,
            ],
            'order_by'  => Arr::get($filters, 'order_by'),
            'take'      => null,
            'paginate'  => [
                'per_page'      => null,
                'current_paged' => 1,
            ],
            'select'    => [
                'ec_products.*',
                'products_with_final_price.final_price',
            ],
            'with'      => [],
            'withCount' => [],
        ], $params);

        $this->model = $this->originalModel;

        $now = Carbon::now();

        $this->model = $this->model
            ->distinct()
            ->join(DB::raw('
                (
                    SELECT DISTINCT
                        `ec_products`.id,
                        CASE
                            WHEN (
                                ec_products.sale_type = 0 AND
                                ec_products.sale_price <> 0
                            ) THEN ec_products.sale_price
                            WHEN (
                                ec_products.sale_type = 0 AND
                                ec_products.sale_price = 0
                            ) THEN ec_products.price
                            WHEN (
                                ec_products.sale_type = 1 AND
                                (
                                    ec_products.start_date > ' . esc_sql($now) . ' OR
                                    ec_products.end_date < ' . esc_sql($now) . '
                                )
                            ) THEN ec_products.price
                            WHEN (
                                ec_products.sale_type = 1 AND
                                ec_products.start_date <= ' . esc_sql($now) . ' AND
                                ec_products.end_date >= ' . esc_sql($now) . '
                            ) THEN ec_products.sale_price
                            WHEN (
                                ec_products.sale_type = 1 AND
                                ec_products.start_date IS NULL AND
                                ec_products.end_date >= ' . esc_sql($now) . '
                            ) THEN ec_products.sale_price
                            WHEN (
                                ec_products.sale_type = 1 AND
                                ec_products.start_date <= ' . esc_sql($now) . ' AND
                                ec_products.end_date IS NULL
                            ) THEN ec_products.sale_price
                            ELSE ec_products.price
                        END AS final_price
                    FROM `ec_products`
                ) AS products_with_final_price
            '), function ($join) {
                return $join->on('products_with_final_price.id', '=', 'ec_products.id');
            });

        $keyword = $filters['keyword'];
        if ($keyword && is_string($keyword)) {
            $searchProductsBy = EcommerceHelper::getProductsSearchBy();
            $isPartial = get_ecommerce_setting('search_products_exactly_by_name', 0) != 1;

            if (is_plugin_active('language') && is_plugin_active('language-advanced') && Language::getCurrentLocale() != Language::getDefaultLocale()) {
                $this->model = $this->model
                    ->where(function ($query) use ($keyword, $searchProductsBy, $isPartial) {
                        $hasWhere = false;

                        if (in_array('sku', $searchProductsBy)) {
                            $query
                                ->where(function ($subQuery) use ($keyword) {
                                    $subQuery->addSearch('ec_products.sku', $keyword, false);
                                });

                            $hasWhere = true;
                        }


                        if (in_array('name', $searchProductsBy) || in_array('description', $searchProductsBy)) {
                            $function = $hasWhere ? 'orWhereHas' : 'whereHas';
                            $hasWhere = true;

                            $query
                                ->{$function}('translations', function ($query) use ($keyword, $searchProductsBy, $isPartial) {
                                    $query->where(function ($subQuery) use ($keyword, $searchProductsBy, $isPartial) {
                                        if (in_array('name', $searchProductsBy)) {
                                            $subQuery->addSearch('name', $keyword, $isPartial);
                                        }

                                        if (in_array('description', $searchProductsBy)) {
                                            $subQuery->addSearch('description', $keyword, false);
                                        }
                                    });
                                });
                        }

                        if (in_array('tag', $searchProductsBy)) {
                            $function = $hasWhere ? 'orWhereHas' : 'whereHas';
                            $hasWhere = true;

                            $query->{$function}('tags', function ($query) use ($keyword) {
                                $query->where(function ($subQuery) use ($keyword) {
                                    $subQuery->addSearch('name', $keyword, false);
                                });
                            });
                        }

                        if (in_array('brand', $searchProductsBy)) {
                            $function = $hasWhere ? 'orWhereHas' : 'whereHas';
                            $hasWhere = true;

                            $query->{$function}('brand.translations', function ($query) use ($keyword) {
                                $query->where(function ($subQuery) use ($keyword) {
                                    $subQuery->addSearch('name', $keyword, false);
                                });
                            });
                        }

                        if (in_array('variation_sku', $searchProductsBy)) {
                            $function = $hasWhere ? 'orWhereHas' : 'whereHas';

                            $query->{$function}('variations.product', function ($query) use ($keyword) {
                                $query->where(function ($subQuery) use ($keyword) {
                                    $subQuery->addSearch('sku', $keyword, false);
                                });
                            });
                        }
                    });
            } else {
                $this->model = $this->model
                    ->where(function ($query) use ($keyword, $searchProductsBy, $isPartial) {
                        $hasWhere = false;

                        if (in_array('name', $searchProductsBy) || in_array('sku', $searchProductsBy) || in_array('description', $searchProductsBy)) {
                            $query
                                ->where(function ($subQuery) use ($keyword, $searchProductsBy, $isPartial) {
                                    if (in_array('name', $searchProductsBy)) {
                                        $subQuery->addSearch('ec_products.name', $keyword, $isPartial);
                                    }

                                    if (in_array('sku', $searchProductsBy)) {
                                        $subQuery->addSearch('ec_products.sku', $keyword, false);
                                    }

                                    if (in_array('description', $searchProductsBy)) {
                                        $subQuery->addSearch('ec_products.description', $keyword, false);
                                    }
                                });

                            $hasWhere = true;
                        }

                        if (in_array('tag', $searchProductsBy)) {
                            $function = $hasWhere ? 'orWhereHas' : 'whereHas';
                            $hasWhere = true;

                            $query->{$function}('tags', function ($query) use ($keyword) {
                                $query->where(function ($subQuery) use ($keyword) {
                                    $subQuery->addSearch('name', $keyword, false);
                                });
                            });
                        }

                        if (in_array('brand', $searchProductsBy)) {
                            $function = $hasWhere ? 'orWhereHas' : 'whereHas';
                            $hasWhere = true;

                            $query->{$function}('brand', function ($query) use ($keyword) {
                                $query->where(function ($subQuery) use ($keyword) {
                                    $subQuery->addSearch('name', $keyword, false);
                                });
                            });
                        }

                        if (in_array('variation_sku', $searchProductsBy)) {
                            $function = $hasWhere ? 'orWhereHas' : 'whereHas';

                            $query->{$function}('variations.product', function ($query) use ($keyword) {
                                $query->where(function ($subQuery) use ($keyword) {
                                    $subQuery->addSearch('sku', $keyword, false);
                                });
                            });
                        }
                    });
            }
        }

        // Filter product by min price and max price
        if ($filters['min_price'] !== null || $filters['max_price'] !== null) {
            $this->model = $this->model
                ->where(function ($query) use ($filters) {
                    /**
                     * @var Builder $query
                     */
                    $priceMin = (float)Arr::get($filters, 'min_price');
                    $priceMax = (float)Arr::get($filters, 'max_price');

                    if ($priceMin != null) {
                        $query = $query->where('products_with_final_price.final_price', '>=', $priceMin);
                    }

                    if ($priceMax != null) {
                        $query = $query->where('products_with_final_price.final_price', '<=', $priceMax);
                    }

                    return $query;
                });
        }

        // Filter product by categories
        $filters['categories'] = array_filter($filters['categories']);
        if ($filters['categories']) {
            $this->model = $this->model
                ->whereHas('categories', function ($query) use ($filters) {
                    /**
                     * @var Builder $query
                     */
                    return $query
                        ->whereIn('ec_product_category_product.category_id', $filters['categories']);
                });
        }

        // Filter product by tags
        $filters['tags'] = array_filter($filters['tags']);
        if ($filters['tags']) {
            $this->model = $this->model
                ->whereHas('tags', function ($query) use ($filters) {
                    /**
                     * @var Builder $query
                     */
                    return $query
                        ->whereIn('ec_product_tag_product.tag_id', $filters['tags']);
                });
        }

        // Filter product by collections
        $filters['collections'] = array_filter($filters['collections']);
        if ($filters['collections']) {
            $this->model = $this->model
                ->whereHas('productCollections', function ($query) use ($filters) {
                    /**
                     * @var Builder $query
                     */
                    return $query
                        ->whereIn('ec_product_collection_products.product_collection_id', $filters['collections']);
                });
        }

        // Filter product by brands
        $filters['brands'] = array_filter($filters['brands']);
        if ($filters['brands']) {
            $this->model = $this->model
                ->whereIn('ec_products.brand_id', $filters['brands']);
        }

        // Filter product by attributes
        $filters['attributes'] = array_filter($filters['attributes']);
        if ($filters['attributes']) {
            foreach ($filters['attributes'] as &$attributeId) {
                $attributeId = (int) $attributeId;
            }

            $this->model = $this->model
                ->join(
                    DB::raw('
                    (
                        SELECT DISTINCT
                            ec_product_variations.id,
                            ec_product_variations.configurable_product_id,
                            COUNT(ec_product_variation_items.attribute_id) AS count_attr

                        FROM ec_product_variation_items

                        INNER JOIN ec_product_variations ON ec_product_variations.id = ec_product_variation_items.variation_id

                        WHERE ec_product_variation_items.attribute_id IN (' . implode(',', $filters['attributes']) . ')

                        GROUP BY
                            ec_product_variations.id,
                            ec_product_variations.configurable_product_id
                    ) AS t2'),
                    function ($join) use ($filters) {
                        /**
                         * @var JoinClause $join
                         */
                        $join = $join->on('t2.configurable_product_id', '=', 'ec_products.id');

                        if ($filters['count_attribute_groups'] > 1) {
                            $join = $join->on('t2.count_attr', '=', DB::raw($filters['count_attribute_groups']));
                        }

                        return $join;
                    }
                );
        }

        if (!Arr::get($params, 'include_out_of_stock_products')) {
            $this->exceptOutOfStockProducts();
        }

        return $this->advancedGet($params);
    }

    /**
     * {@inheritDoc}
     */
    public function getProductsByIds(array $ids, array $params = [])
    {
        $this->model = $this->originalModel;

        $params = array_merge([
            'condition'  => [
                'ec_products.status'       => BaseStatusEnum::PUBLISHED,
                'ec_products.is_variation' => 0,
            ],
            'paginate'   => [
                'per_page'      => null,
                'current_paged' => 1,
            ],
        ], $params);

        $this->model = $this->model
            ->whereIn('id', $ids);

        if (config('database.default') == 'mysql') {
            $idsOrdered = implode(',', $ids);
            if (!empty($idsOrdered)) {
                $this->model = $this->model->orderByRaw("FIELD(id, $idsOrdered)");
            }
        }

        return $this->advancedGet($params);
    }

    /**
     * {@inheritDoc}
     */
    public function getProductsWishlist(int $customerId, array $params = [])
    {
        $this->model = $this->originalModel;

        $params = array_merge([
            'condition' => [
                'ec_products.status'       => BaseStatusEnum::PUBLISHED,
                'ec_products.is_variation' => 0,
            ],
            'paginate'  => [
                'per_page'      => null,
                'current_paged' => 1,
            ],
            'with'      => ['slugable'],
            'order_by'  => ['ec_wish_lists.updated_at' => 'desc'],
            'select'    => ['ec_products.*']
        ], $params);

        $this->model = $this->model
            ->join('ec_wish_lists', 'ec_wish_lists.product_id', 'ec_products.id')
            ->where('ec_wish_lists.customer_id', $customerId);

        return $this->advancedGet($params);
    }

    /**
     * {@inheritDoc}
     */
    public function getProductsRecentlyViewed(int $customerId, array $params = [])
    {
        $this->model = $this->originalModel;

        $params = array_merge([
            'condition' => [
                'ec_products.status'       => BaseStatusEnum::PUBLISHED,
                'ec_products.is_variation' => 0,
            ],
            'paginate'  => [
                'per_page'      => null,
                'current_paged' => 1,
            ],
            'with'      => ['slugable'],
            'order_by'  => ['ec_customer_recently_viewed_products.id' => 'desc'],
            'select'    => ['ec_products.*']
        ], $params);

        $this->model = $this->model
            ->join('ec_customer_recently_viewed_products', 'ec_customer_recently_viewed_products.product_id', 'ec_products.id')
            ->where('ec_customer_recently_viewed_products.customer_id', $customerId);

        return $this->advancedGet($params);
    }
}
