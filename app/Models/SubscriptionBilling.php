<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubscriptionBilling extends Model
{
    protected $fillable = [
        'subscription_id',
        'user_id',
        'platform',
        'event_type',
        'event_date',
        'billing_date',
        'amount',
        'transaction_id',
        'status',
        'status_from',
        'status_to',
        'notes',
        'metadata',
    ];

    protected $casts = [
        'event_date' => 'date',
        'billing_date' => 'date',
        'amount' => 'decimal:2',
        'metadata' => 'array',
    ];

    // Event types constants
    const EVENT_CREATED = 'created';
    const EVENT_RENEWED = 'renewed';
    const EVENT_CANCELLED = 'cancelled';
    const EVENT_EXPIRED = 'expired';
    const EVENT_SUSPENDED = 'suspended';
    const EVENT_REACTIVATED = 'reactivated';
    const EVENT_STATUS_CHANGED = 'status_changed';
    const EVENT_BILLING = 'billing';

    /**
     * Create or get existing event record (prevents duplicates)
     */
    public static function createEvent(array $data): self
    {
        // Set billing_date from event_date if not provided (for backward compatibility)
        if (!isset($data['billing_date']) && isset($data['event_date'])) {
            $data['billing_date'] = $data['event_date'];
        }

        // Check if event already exists
        $query = self::where('subscription_id', $data['subscription_id'])
            ->where('event_type', $data['event_type'])
            ->where('event_date', $data['event_date'] ?? $data['billing_date']);

        if (isset($data['status_to'])) {
            $query->where('status_to', $data['status_to']);
        }

        $existing = $query->first();

        if ($existing) {
            return $existing;
        }

        return self::create($data);
    }

    /**
     * Get the subscription that owns this billing record
     */
    public function subscription(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Business\Subscription::class);
    }

    /**
     * Get the user that owns this billing record
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
