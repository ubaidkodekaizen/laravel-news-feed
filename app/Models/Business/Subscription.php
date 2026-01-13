<?php

namespace App\Models\Business;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Business\Plan;

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
    ];

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }
}
