<?php

namespace Botble\AuditLog\Models;

use Botble\ACL\Models\User;
use Botble\Base\Models\BaseModel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\MassPrunable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Query\Builder;

class AuditHistory extends BaseModel
{
    use MassPrunable;

    /**
     * @var string
     */
    protected $table = 'audit_histories';

    /**
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /**
     * @var array
     */
    protected $fillable = [
        'user_agent',
        'ip_address',
        'module',
        'action',
        'user_id',
        'reference_user',
        'reference_id',
        'reference_name',
        'type',
        'request',
    ];

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withDefault();
    }

    /**
     * @return Builder
     */
    public function prunable()
    {
        return $this->whereDate('created_at', '>', Carbon::now()->subDays(30)->toDateString());
    }
}
