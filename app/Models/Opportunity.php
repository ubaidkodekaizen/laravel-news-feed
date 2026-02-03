<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use App\Models\Reference\Industry;

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
        'budget_min',
        'budget_max',
        'budget_type',
        'payment_terms',
        'timeline',
        'start_date',
        'deadline',
        'expires_at',
        'location',
        'work_type',
        'required_skills',
        'preferred_experience',
        'team_size',
        'tags',
        'deliverables',
        'contact_email',
        'contact_phone',
        'contact_preference',
        'nda_required',
        'reference_required',
        'attachment_urls',
        'priority',
        'is_featured',
        'status',
        'views_count',
        'proposals_count',
        'saves_count',
    ];

    protected $casts = [
        'budget_min' => 'decimal:2',
        'budget_max' => 'decimal:2',
        'required_skills' => 'array',
        'tags' => 'array',
        'deliverables' => 'array',
        'attachment_urls' => 'array',
        'nda_required' => 'boolean',
        'reference_required' => 'boolean',
        'is_featured' => 'boolean',
        'start_date' => 'datetime',
        'deadline' => 'datetime',
        'expires_at' => 'datetime',
        'views_count' => 'integer',
        'proposals_count' => 'integer',
        'saves_count' => 'integer',
        'team_size' => 'integer',
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
            $query->where(function ($q) use ($min) {
                $q->where('budget_max', '>=', $min)
                    ->orWhereNull('budget_max');
            });
        }

        if ($max !== null) {
            $query->where(function ($q) use ($max) {
                $q->where('budget_min', '<=', $max)
                    ->orWhereNull('budget_min');
            });
        }

        return $query;
    }

    /**
     * Scope: Filter by required skills.
     */
    public function scopeBySkills($query, array $skills)
    {
        return $query->whereJsonContains('required_skills', $skills);
    }

    /**
     * Scope: Order by priority and created date.
     */
    public function scopeOrderByPriority($query)
    {
        $priorityOrder = ['urgent' => 4, 'high' => 3, 'medium' => 2, 'low' => 1];
        
        return $query->orderByRaw('CASE priority 
            WHEN "urgent" THEN 4
            WHEN "high" THEN 3
            WHEN "medium" THEN 2
            WHEN "low" THEN 1
            ELSE 0
        END DESC')
        ->orderBy('created_at', 'desc');
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
