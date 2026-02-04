<?php

namespace App\Models\Opportunities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Opportunities\Opportunity;
use App\Models\Users\User;

class OpportunityProposal extends Model
{
    use HasFactory;

    protected $table = 'opportunity_proposals';

    protected $fillable = [
        'opportunity_id',
        'user_id',
        'proposal_text',
        'cover_letter',
        'proposed_budget',
        'estimated_timeline',
        'availability_date',
        'estimated_hours',
        'portfolio_items',
        'attachment_urls',
        'references',
        'proposed_milestones',
        'proposed_deliverables',
        'why_choose_me',
        'relevant_experience',
        'status',
        'rejection_reason',
        'admin_notes',
        'submitted_at',
        'viewed_at',
        'view_count',
    ];

    protected $casts = [
        'proposed_budget' => 'decimal:2',
        'portfolio_items' => 'array',
        'attachment_urls' => 'array',
        'references' => 'array',
        'proposed_milestones' => 'array',
        'proposed_deliverables' => 'array',
        'availability_date' => 'datetime',
        'submitted_at' => 'datetime',
        'viewed_at' => 'datetime',
        'view_count' => 'integer',
        'estimated_hours' => 'integer',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($proposal) {
            if (empty($proposal->submitted_at)) {
                $proposal->submitted_at = now();
            }
        });

        static::created(function ($proposal) {
            // Increment proposals count on opportunity
            $proposal->opportunity->incrementProposalsCount();
        });

        static::deleted(function ($proposal) {
            // Decrement proposals count on opportunity
            $proposal->opportunity->decrementProposalsCount();
        });
    }

    /**
     * Get the opportunity this proposal belongs to.
     */
    public function opportunity(): BelongsTo
    {
        return $this->belongsTo(Opportunity::class);
    }

    /**
     * Get the user who submitted this proposal.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Mark proposal as viewed.
     */
    public function markAsViewed(): void
    {
        if (!$this->viewed_at) {
            $this->viewed_at = now();
        }
        $this->increment('view_count');
        $this->save();
    }

    /**
     * Check if proposal has been viewed.
     */
    public function isViewed(): bool
    {
        return !is_null($this->viewed_at);
    }

    /**
     * Check if proposal is pending.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if proposal is accepted.
     */
    public function isAccepted(): bool
    {
        return $this->status === 'accepted';
    }

    /**
     * Check if proposal is rejected.
     */
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    /**
     * Check if proposal is shortlisted.
     */
    public function isShortlisted(): bool
    {
        return $this->status === 'shortlisted';
    }

    /**
     * Scope: Filter by status.
     */
    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope: Only pending proposals.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope: Only accepted proposals.
     */
    public function scopeAccepted($query)
    {
        return $query->where('status', 'accepted');
    }

    /**
     * Scope: Only shortlisted proposals.
     */
    public function scopeShortlisted($query)
    {
        return $query->where('status', 'shortlisted');
    }

    /**
     * Scope: Only rejected proposals.
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Scope: Order by submission date (newest first).
     */
    public function scopeLatest($query)
    {
        return $query->orderBy('submitted_at', 'desc');
    }

    /**
     * Scope: Order by submission date (oldest first).
     */
    public function scopeOldest($query)
    {
        return $query->orderBy('submitted_at', 'asc');
    }

    /**
     * Scope: Unviewed proposals.
     */
    public function scopeUnviewed($query)
    {
        return $query->whereNull('viewed_at');
    }

    /**
     * Scope: Viewed proposals.
     */
    public function scopeViewed($query)
    {
        return $query->whereNotNull('viewed_at');
    }
}
