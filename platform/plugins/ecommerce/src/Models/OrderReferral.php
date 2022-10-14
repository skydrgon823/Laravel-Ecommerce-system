<?php

namespace Botble\Ecommerce\Models;

use Botble\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderReferral extends BaseModel
{
    /**
     * @var string
     */
    protected $table = 'ec_order_referrals';

    /**
     * @var array
     */
    protected $fillable = [
        'ip',
        'landing_domain',
        'landing_page',
        'landing_params',
        'referral',
        'gclid',
        'fclid',
        'utm_source',
        'utm_campaign',
        'utm_medium',
        'utm_term',
        'utm_content',
        'referrer_url',
        'referrer_domain',
        'order_id',
    ];

    /**
     * @return BelongsTo
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class)->withDefault();
    }
}
