<?php

namespace Botble\Ecommerce\Repositories\Eloquent;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Ecommerce\Repositories\Interfaces\FlashSaleInterface;
use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;

class FlashSaleRepository extends RepositoriesAbstract implements FlashSaleInterface
{
    /**
     * {@inheritDoc}
     */
    public function getAvailableFlashSales(array $with = [])
    {
        $data = $this->model
            ->where('status', BaseStatusEnum::PUBLISHED)
            ->notExpired()
            ->latest();

        if ($with) {
            $data = $data->with($with);
        }

        return $this->applyBeforeExecuteQuery($data)->get();
    }
}
