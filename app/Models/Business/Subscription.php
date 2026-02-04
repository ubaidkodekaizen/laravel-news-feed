<?php

namespace App\Models\Business;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Business\Plan;
use App\Models\System\SubscriptionBilling;
use App\Models\Users\User;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'plan_id',
        'subscription_type',
        'subscription_amount',
        'start_date',
        'renewal_date',
        'status',
        'transaction_id',
        'receipt_data',
        'platform',
        'cancelled_at',
        'last_renewed_at',
        'renewal_count',
        'expires_at',
        'auto_renewing',
        'payment_state',
        'last_checked_at',
        'grace_period_ends_at',
        'renewal_reminder_sent_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     * Get all billing records for this subscription
     */
    public function billings(): HasMany
    {
        return $this->hasMany(SubscriptionBilling::class);
    }

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_date' => 'datetime',
        'renewal_date' => 'datetime',
        'expires_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'last_renewed_at' => 'datetime',
        'last_checked_at' => 'datetime',
        'grace_period_ends_at' => 'datetime',
        'renewal_reminder_sent_at' => 'datetime',
        'auto_renewing' => 'boolean',
    ];

    /**
     * Get total amount paid across all billings
     */
    public function getTotalPaidAttribute(): float
    {
        return (float) $this->billings()->where('status', 'success')->sum('amount');
    }
}
