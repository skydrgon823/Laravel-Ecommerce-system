<?php

namespace Botble\Ecommerce\Repositories\Interfaces;

use Botble\Support\Repositories\Interfaces\RepositoryInterface;

interface BrandInterface extends RepositoryInterface
{
    /**
     * @param array $condition
     * @return mixed
     */
    public function getAll(array $condition = []);
}
