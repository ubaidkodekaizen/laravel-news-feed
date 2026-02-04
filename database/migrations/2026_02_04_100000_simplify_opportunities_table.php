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
        Schema::table('opportunities', function (Blueprint $table) {
            // Remove budget fields (will be replaced with single budget field)
            $table->dropColumn(['budget_min', 'budget_max', 'budget_type']);
            
            // Remove unnecessary fields (keep timeline, tags, contact_preference)
            $table->dropColumn([
                'payment_terms',
                'start_date',
                'required_skills',
                'preferred_experience',
                'team_size',
                'deliverables',
                'nda_required',
                'reference_required',
                'priority',
            ]);
            
            // Remove indexes for dropped columns
            if (Schema::hasColumn('opportunities', 'priority')) {
                $table->dropIndex(['priority']);
            }
            if (Schema::hasColumn('opportunities', 'start_date')) {
                $table->dropIndex(['start_date']);
            }
        });
        
        // Add single budget field
        Schema::table('opportunities', function (Blueprint $table) {
            $table->decimal('budget', 15, 2)->nullable()->after('industry_id');
        });
        
        // Update contact_preference enum if it exists (remove 'chat' option)
        Schema::table('opportunities', function (Blueprint $table) {
            if (Schema::hasColumn('opportunities', 'contact_preference')) {
                // Note: Laravel doesn't support modifying enum directly, so we'll keep it as is
                // The application will handle the validation
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('opportunities', function (Blueprint $table) {
            // Remove the new budget field
            $table->dropColumn('budget');
            
            // Re-add removed columns
            $table->decimal('budget_min', 15, 2)->nullable()->after('industry_id');
            $table->decimal('budget_max', 15, 2)->nullable()->after('budget_min');
            $table->enum('budget_type', ['fixed', 'hourly', 'project', 'negotiable'])->default('negotiable')->after('budget_max');
            $table->text('payment_terms')->nullable()->after('budget_type');
            $table->timestamp('start_date')->nullable()->after('timeline');
            $table->json('required_skills')->nullable()->after('work_type');
            $table->string('preferred_experience')->nullable()->after('required_skills');
            $table->integer('team_size')->nullable()->after('preferred_experience');
            $table->json('deliverables')->nullable()->after('tags');
            $table->boolean('nda_required')->default(false)->after('contact_preference');
            $table->boolean('reference_required')->default(false)->after('nda_required');
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium')->after('reference_required');
            
            // Re-add indexes
            $table->index('priority');
            $table->index('start_date');
        });
    }
};
