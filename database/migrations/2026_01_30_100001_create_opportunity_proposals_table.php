<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('opportunity_proposals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('opportunity_id')->constrained('opportunities')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            // Proposal content
            $table->text('proposal_text');
            $table->text('cover_letter')->nullable(); // Optional cover letter
            
            // Budget and timeline
            $table->decimal('proposed_budget', 15, 2)->nullable();
            $table->string('estimated_timeline')->nullable();
            $table->timestamp('availability_date')->nullable(); // When can they start
            $table->integer('estimated_hours')->nullable(); // If hourly rate, estimated hours
            
            // Portfolio and references
            $table->json('portfolio_items')->nullable(); // Array of portfolio links/items (can link to user's services/products)
            $table->json('attachment_urls')->nullable(); // URLs to proposal documents, case studies, etc.
            $table->json('references')->nullable(); // Array of references (name, company, email, phone)
            
            // Milestones and deliverables
            $table->json('proposed_milestones')->nullable(); // Structured milestones with dates and payments
            $table->json('proposed_deliverables')->nullable(); // What they will deliver
            
            // Additional information
            $table->text('why_choose_me')->nullable(); // Why they're the best fit
            $table->text('relevant_experience')->nullable(); // Relevant past experience
            
            // Status and tracking
            $table->enum('status', ['pending', 'shortlisted', 'accepted', 'rejected', 'withdrawn'])->default('pending');
            $table->text('rejection_reason')->nullable(); // If rejected, reason (visible to proposer)
            $table->text('admin_notes')->nullable(); // Internal notes (not visible to proposer)
            $table->timestamp('submitted_at')->useCurrent();
            $table->timestamp('viewed_at')->nullable(); // When owner viewed the proposal
            $table->integer('view_count')->default(0); // How many times owner viewed it
            
            $table->timestamps();
            
            // Indexes
            $table->index('opportunity_id');
            $table->index('user_id');
            $table->index('status');
            $table->index('submitted_at');
            $table->index(['opportunity_id', 'status']); // For filtering proposals by status
            $table->unique(['opportunity_id', 'user_id']); // One proposal per user per opportunity
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('opportunity_proposals');
    }
};
