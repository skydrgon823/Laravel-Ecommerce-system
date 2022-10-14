<?php

namespace Botble\Ecommerce\Repositories\Eloquent;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Ecommerce\Repositories\Interfaces\ReviewInterface;
use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;
use Illuminate\Support\Facades\DB;

class ReviewRepository extends RepositoriesAbstract implements ReviewInterface
{
    /**
     * {@inheritDoc}
     */
    public function getGroupedByProductId($productId)
    {
        $data = $this->model
            ->select([DB::raw('COUNT(star) as star_count'), 'star'])
            ->where([
                'product_id' => $productId,
                'status'     => BaseStatusEnum::PUBLISHED
            ])
            ->groupBy('star');

        return $this->applyBeforeExecuteQuery($data)->get();
    }
}
