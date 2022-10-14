<?php

namespace Botble\Ecommerce\Repositories\Interfaces;

use Botble\Ecommerce\Models\Product;
use Botble\Support\Repositories\Interfaces\RepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface ProductInterface extends RepositoryInterface
{
    /**
     * @param string $query
     * @param int $paginate
     * @return mixed
     * @deprecated
     */
    public function getSearch($query, $paginate = 10);

    /**
     * @param Product $product
     * @return Collection
     */
    public function getRelatedProductAttributes($product);

    /**
     * @param array $params
     * @return LengthAwarePaginator|\Illuminate\Database\Eloquent\Collection|Model|Collection|null
     */
    public function getProducts(array $params);

    /**
     * @param array $params
     * @return LengthAwarePaginator|\Illuminate\Database\Eloquent\Collection|Collection|mixed
     */
    public function getProductsWithCategory(array $params);

    /**
     * @param array $params
     * @return LengthAwarePaginator|\Illuminate\Database\Eloquent\Collection|Collection|mixed
     */
    public function getOnSaleProducts(array $params);

    /**
     * @param int $configurableProductId
     * @param array $params
     * @return LengthAwarePaginator|\Illuminate\Database\Eloquent\Collection|Collection|mixed
     */
    public function getProductVariations($configurableProductId, array $params = []);

    /**
     * @param array $params
     * @return LengthAwarePaginator|\Illuminate\Database\Eloquent\Collection|Collection|mixed
     */
    public function getProductsByCollections(array $params);

    /**
     * @param array $params
     * @return LengthAwarePaginator|\Illuminate\Database\Eloquent\Collection|Collection|mixed
     */
    public function getProductByBrands(array $params);

    /**
     * @param array $params
     * @return LengthAwarePaginator|\Illuminate\Database\Eloquent\Collection|Collection|mixed
     */
    public function getProductByTags(array $params);

    /**
     * @param array $params
     * @return mixed
     */
    public function getProductsByCategories(array $params);

    /**
     * @param array $filters
     * @param array $params
     * @return LengthAwarePaginator|\Illuminate\Database\Eloquent\Collection|Collection|mixed
     */
    public function filterProducts(array $filters, array $params = []);

    /**
     * @param array $ids
     * @param array $params
     * @return mixed
     */
    public function getProductsByIds(array $ids, array $params = []);

    /**
     * @param int $customerId
     * @param array $params
     * @return mixed
     */
    public function getProductsWishlist(int $customerId, array $params = []);

    /**
     * @param int $customerId
     * @param array $params
     * @return mixed
     */
    public function getProductsRecentlyViewed(int $customerId, array $params = []);
}
