<?php

namespace Botble\Ecommerce\Events;

use Botble\ACL\Models\User;
use Botble\Base\Events\Event;
use Botble\Ecommerce\Models\Order;
use Illuminate\Queue\SerializesModels;

class OrderPaymentConfirmedEvent extends Event
{
    use SerializesModels;

    /**
     * @var Order
     */
    public $order;

    /**
     * @var User
     */
    public $confirmedBy;

    /**
     * @param Order $order
     * @param User $confirmedBy
     */
    public function __construct(Order $order, User $confirmedBy)
    {
        $this->order = $order;
        $this->confirmedBy = $confirmedBy;
    }
}
