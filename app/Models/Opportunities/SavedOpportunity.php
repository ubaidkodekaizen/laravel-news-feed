<?php

namespace App\Models\Opportunities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Opportunities\Opportunity;
use App\Models\Users\User;

class SavedOpportunity extends Model
{
    use HasFactory;

    protected $table = 'saved_opportunities';

    protected $fillable = [
        'user_id',
        'opportunity_id',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::created(function ($savedOpportunity) {
            // Increment saves count on opportunity
            $savedOpportunity->opportunity->incrementSavesCount();
        });

        static::deleted(function ($savedOpportunity) {
            // Decrement saves count on opportunity
            $savedOpportunity->opportunity->decrementSavesCount();
        });
    }

    /**
     * Get the user who saved this opportunity.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the opportunity that was saved.
     */
    public function opportunity(): BelongsTo
    {
        return $this->belongsTo(Opportunity::class);
    }

    /**
     * Scope: Get saved opportunities for a specific user.
     */
    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope: Get saved opportunities for a specific opportunity.
     */
    public function scopeForOpportunity($query, int $opportunityId)
    {
        return $query->where('opportunity_id', $opportunityId);
    }

    /**
     * Scope: Order by saved date (newest first).
     */
    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc');
    }
}
