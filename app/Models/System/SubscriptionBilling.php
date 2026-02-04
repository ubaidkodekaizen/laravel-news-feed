<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Business\Subscription;
use App\Models\Users\User;

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
     * 
     * @param array $data Event data
     * @return array ['billing' => SubscriptionBilling, 'wasCreated' => bool]
     */
    public static function createEvent(array $data): array
    {
        // Set billing_date from event_date if not provided (for backward compatibility)
        if (!isset($data['billing_date']) && isset($data['event_date'])) {
            $data['billing_date'] = $data['event_date'];
        }

        // Normalize event_date and billing_date for comparison
        $eventDate = $data['event_date'] ?? $data['billing_date'];
        if ($eventDate instanceof \Carbon\Carbon) {
            $eventDate = $eventDate->format('Y-m-d');
        }

        // Check if event already exists - use multiple criteria to prevent duplicates
        $query = self::where('subscription_id', $data['subscription_id'])
            ->where('event_type', $data['event_type'])
            ->where(function($q) use ($eventDate) {
                $q->where('event_date', $eventDate)
                  ->orWhere('billing_date', $eventDate);
            });

        // For cancelled/expired/status_changed events, also check status_to to differentiate
        // This allows tracking multiple status changes on the same date if needed
        if (isset($data['status_to'])) {
            $query->where('status_to', $data['status_to']);
        } else {
            // For events without status_to, ensure we don't have duplicates
            $query->whereNull('status_to');
        }

        $existing = $query->first();

        if ($existing) {
            // Record already exists - return it without creating duplicate
            return ['billing' => $existing, 'wasCreated' => false];
        }

        // Ensure event_date is set properly
        if (!isset($data['event_date'])) {
            $data['event_date'] = $eventDate;
        }
        if (!isset($data['billing_date'])) {
            $data['billing_date'] = $eventDate;
        }

        return ['billing' => self::create($data), 'wasCreated' => true];
    }

    /**
     * Get the subscription that owns this billing record
     */
    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class);
    }

    /**
     * Get the user that owns this billing record
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
