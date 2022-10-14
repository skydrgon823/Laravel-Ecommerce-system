<?php

namespace Botble\Ecommerce\Repositories\Eloquent;

use Botble\Ecommerce\Repositories\Interfaces\BrandInterface;
use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;

class BrandRepository extends RepositoriesAbstract implements BrandInterface
{
    /**
     * {@inheritDoc}
     */
    public function getAll(array $condition = [])
    {
        $data = $this->model
            ->where($condition)
            ->orderBy('is_featured', 'DESC')
            ->orderBy('name', 'ASC');

        return $this->applyBeforeExecuteQuery($data)->get();
    }
}
