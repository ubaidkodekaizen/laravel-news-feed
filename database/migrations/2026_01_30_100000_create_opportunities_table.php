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
        Schema::create('opportunities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('slug')->unique()->nullable(); // SEO-friendly URL
            $table->string('title');
            $table->text('description');
            $table->enum('category', ['looking_for_partner', 'need_consultant', 'project_work', 'fulltime_contract']);
            $table->foreignId('industry_id')->nullable()->constrained('industries')->onDelete('set null');
            
            // Budget fields
            $table->decimal('budget_min', 15, 2)->nullable();
            $table->decimal('budget_max', 15, 2)->nullable();
            $table->enum('budget_type', ['fixed', 'hourly', 'project', 'negotiable'])->default('negotiable');
            $table->text('payment_terms')->nullable(); // e.g., "50% upfront, 50% on completion", "Milestone-based", "Net 30"
            
            // Timeline and dates
            $table->string('timeline')->nullable(); // e.g., "2 weeks", "1 month", "3 months"
            $table->timestamp('start_date')->nullable(); // When work should start
            $table->timestamp('deadline')->nullable(); // Application deadline
            $table->timestamp('expires_at')->nullable(); // Auto-expiry date (default: 30 days from creation)
            
            // Location and work type
            $table->string('location')->nullable();
            $table->enum('work_type', ['remote', 'on_site', 'hybrid'])->nullable(); // Remote, On-site, or Hybrid
            
            // Requirements
            $table->json('required_skills')->nullable(); // Array of skills
            $table->string('preferred_experience')->nullable();
            $table->integer('team_size')->nullable(); // Number of team members needed
            $table->json('tags')->nullable(); // Additional categorization tags
            $table->json('deliverables')->nullable(); // Structured deliverables/milestones (JSON array)
            
            // Contact information
            $table->string('contact_email')->nullable(); // Optional separate contact email for this opportunity
            $table->string('contact_phone')->nullable(); // Optional separate contact phone
            $table->enum('contact_preference', ['email', 'phone', 'both', 'chat'])->default('both'); // Preferred contact method
            
            // Additional requirements
            $table->boolean('nda_required')->default(false); // NDA required before proposal
            $table->boolean('reference_required')->default(false); // References required
            $table->json('attachment_urls')->nullable(); // URLs to RFP documents, requirements, etc.
            
            // Priority and visibility
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->boolean('is_featured')->default(false); // Featured/premium opportunities
            
            // Status
            $table->enum('status', ['open', 'in_review', 'shortlisted', 'awarded', 'completed', 'closed'])->default('open');
            
            // Counters
            $table->unsignedInteger('views_count')->default(0);
            $table->unsignedInteger('proposals_count')->default(0);
            $table->unsignedInteger('saves_count')->default(0); // How many users saved this
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes for performance
            $table->index('user_id');
            $table->index('slug');
            $table->index('industry_id');
            $table->index('category');
            $table->index('status');
            $table->index('work_type');
            $table->index('priority');
            $table->index('is_featured');
            $table->index('created_at');
            $table->index('start_date');
            $table->index('deadline');
            $table->index('expires_at');
            $table->index(['status', 'created_at']);
            $table->index(['status', 'is_featured', 'created_at']); // For featured opportunities listing
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('opportunities');
    }
};
