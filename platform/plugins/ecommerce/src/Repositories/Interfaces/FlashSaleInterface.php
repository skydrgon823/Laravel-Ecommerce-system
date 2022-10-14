<?php

namespace Botble\Ecommerce\Repositories\Interfaces;

use Botble\Support\Repositories\Interfaces\RepositoryInterface;

interface FlashSaleInterface extends RepositoryInterface
{
    /**
     * @param array $with
     * @return mixed
     */
    public function getAvailableFlashSales(array $with = []);
}
