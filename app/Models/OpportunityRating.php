<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OpportunityRating extends Model
{
    use HasFactory;

    protected $table = 'opportunity_ratings';

    protected $fillable = [
        'opportunity_id',
        'rated_by_user_id',
        'rated_user_id',
        'rating',
        'comment',
    ];

    protected $casts = [
        'rating' => 'integer',
    ];

    /**
     * Get the opportunity this rating belongs to.
     */
    public function opportunity(): BelongsTo
    {
        return $this->belongsTo(Opportunity::class);
    }

    /**
     * Get the user who gave this rating.
     */
    public function ratedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'rated_by_user_id');
    }

    /**
     * Get the user who was rated.
     */
    public function ratedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'rated_user_id');
    }

    /**
     * Scope: Filter by rating value.
     */
    public function scopeByRating($query, int $rating)
    {
        return $query->where('rating', $rating);
    }

    /**
     * Scope: Filter by minimum rating.
     */
    public function scopeMinRating($query, int $minRating)
    {
        return $query->where('rating', '>=', $minRating);
    }

    /**
     * Scope: Order by rating (highest first).
     */
    public function scopeHighestRating($query)
    {
        return $query->orderBy('rating', 'desc');
    }

    /**
     * Scope: Order by rating (lowest first).
     */
    public function scopeLowestRating($query)
    {
        return $query->orderBy('rating', 'asc');
    }

    /**
     * Scope: Order by created date (newest first).
     */
    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc');
    }
}
