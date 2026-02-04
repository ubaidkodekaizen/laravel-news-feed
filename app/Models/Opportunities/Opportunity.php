<?php

namespace App\Models\Opportunities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use App\Models\Opportunities\OpportunityProposal;
use App\Models\Opportunities\OpportunityRating;
use App\Models\Reference\Industry;
use App\Models\Users\User;

class Opportunity extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'slug',
        'title',
        'description',
        'category',
        'industry_id',
        'budget',
        'timeline',
        'deadline',
        'expires_at',
        'location',
        'work_type',
        'tags',
        'contact_email',
        'contact_phone',
        'contact_preference',
        'attachment_urls',
        'is_featured',
        'status',
        'views_count',
        'proposals_count',
        'saves_count',
    ];

    protected $casts = [
        'budget' => 'decimal:2',
        'tags' => 'array',
        'attachment_urls' => 'array',
        'is_featured' => 'boolean',
        'deadline' => 'datetime',
        'expires_at' => 'datetime',
        'views_count' => 'integer',
        'proposals_count' => 'integer',
        'saves_count' => 'integer',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($opportunity) {
            if (empty($opportunity->slug)) {
                $opportunity->slug = static::generateUniqueSlug($opportunity->title);
            }
            
            // Set default expiry date (30 days from creation) if not provided
            if (empty($opportunity->expires_at)) {
                $opportunity->expires_at = now()->addDays(30);
            }
        });

        static::updating(function ($opportunity) {
            // Regenerate slug if title changed
            if ($opportunity->isDirty('title') && empty($opportunity->slug)) {
                $opportunity->slug = static::generateUniqueSlug($opportunity->title, $opportunity->id);
            }
        });
    }

    /**
     * Generate a unique slug from title.
     */
    protected static function generateUniqueSlug(string $title, ?int $excludeId = null): string
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $counter = 1;

        while (static::where('slug', $slug)
            ->when($excludeId, fn($query) => $query->where('id', '!=', $excludeId))
            ->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Get the user who created the opportunity.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the industry for this opportunity.
     */
    public function industry(): BelongsTo
    {
        return $this->belongsTo(Industry::class);
    }

    /**
     * Get all proposals for this opportunity.
     */
    public function proposals(): HasMany
    {
        return $this->hasMany(OpportunityProposal::class);
    }

    /**
     * Get accepted proposal for this opportunity.
     */
    public function acceptedProposal(): HasMany
    {
        return $this->hasMany(OpportunityProposal::class)->where('status', 'accepted');
    }

    /**
     * Get shortlisted proposals for this opportunity.
     */
    public function shortlistedProposals(): HasMany
    {
        return $this->hasMany(OpportunityProposal::class)->where('status', 'shortlisted');
    }

    /**
     * Get all ratings for this opportunity.
     */
    public function ratings(): HasMany
    {
        return $this->hasMany(OpportunityRating::class);
    }

    /**
     * Get users who saved this opportunity.
     */
    public function savedByUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'saved_opportunities')
            ->withTimestamps();
    }

    /**
     * Check if opportunity is expired.
     */
    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * Check if opportunity is open for proposals.
     */
    public function isOpen(): bool
    {
        return $this->status === 'open' && !$this->isExpired();
    }

    /**
     * Check if deadline has passed.
     */
    public function isDeadlinePassed(): bool
    {
        return $this->deadline && $this->deadline->isPast();
    }

    /**
     * Increment views count.
     */
    public function incrementViewsCount(): void
    {
        $this->increment('views_count');
    }

    /**
     * Increment proposals count.
     */
    public function incrementProposalsCount(): void
    {
        $this->increment('proposals_count');
    }

    /**
     * Decrement proposals count.
     */
    public function decrementProposalsCount(): void
    {
        $this->decrement('proposals_count');
    }

    /**
     * Increment saves count.
     */
    public function incrementSavesCount(): void
    {
        $this->increment('saves_count');
    }

    /**
     * Decrement saves count.
     */
    public function decrementSavesCount(): void
    {
        $this->decrement('saves_count');
    }

    /**
     * Scope: Only open opportunities.
     */
    public function scopeOpen($query)
    {
        return $query->where('status', 'open')
            ->where(function ($q) {
                $q->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            });
    }

    /**
     * Scope: Only active (not expired) opportunities.
     */
    public function scopeActive($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('expires_at')
                ->orWhere('expires_at', '>', now());
        });
    }

    /**
     * Scope: Only featured opportunities.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope: Filter by category.
     */
    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope: Filter by status.
     */
    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope: Filter by work type.
     */
    public function scopeByWorkType($query, string $workType)
    {
        return $query->where('work_type', $workType);
    }

    /**
     * Scope: Filter by industry.
     */
    public function scopeByIndustry($query, int $industryId)
    {
        return $query->where('industry_id', $industryId);
    }

    /**
     * Scope: Filter by budget range.
     */
    public function scopeByBudgetRange($query, ?float $min = null, ?float $max = null)
    {
        if ($min !== null) {
            $query->where('budget', '>=', $min);
        }

        if ($max !== null) {
            $query->where('budget', '<=', $max);
        }

        return $query;
    }

    /**
     * Scope: Opportunities that need to be expired (past expiry date).
     */
    public function scopeNeedsExpiration($query)
    {
        return $query->where('status', 'open')
            ->where('expires_at', '<=', now());
    }
}
