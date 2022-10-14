<?php

namespace Botble\Ecommerce\Events;

use Botble\Base\Events\Event;
use Botble\Ecommerce\Models\OrderReturn;
use Illuminate\Queue\SerializesModels;

class OrderReturnedEvent extends Event
{
    use SerializesModels;

    /**
     * @var OrderReturn
     */
    public $order;

    /**
     * @param OrderReturn $order
     */
    public function __construct(OrderReturn $order)
    {
        $this->order = $order;
    }
}
