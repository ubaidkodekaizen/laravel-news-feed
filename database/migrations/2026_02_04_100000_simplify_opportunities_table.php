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
        // Drop indexes safely using raw SQL
        $connection = Schema::getConnection();
        
        // Try to drop priority index if it exists
        try {
            $connection->statement("ALTER TABLE `opportunities` DROP INDEX IF EXISTS `opportunities_priority_index`");
        } catch (\Exception $e) {
            // Index doesn't exist or already dropped, continue
        }
        
        // Try to drop start_date index if it exists
        try {
            $connection->statement("ALTER TABLE `opportunities` DROP INDEX IF EXISTS `opportunities_start_date_index`");
        } catch (\Exception $e) {
            // Index doesn't exist or already dropped, continue
        }
        
        // Drop columns
        Schema::table('opportunities', function (Blueprint $table) {
            $columnsToDrop = [];
            
            // Budget fields to remove
            if (Schema::hasColumn('opportunities', 'budget_min')) {
                $columnsToDrop[] = 'budget_min';
            }
            if (Schema::hasColumn('opportunities', 'budget_max')) {
                $columnsToDrop[] = 'budget_max';
            }
            if (Schema::hasColumn('opportunities', 'budget_type')) {
                $columnsToDrop[] = 'budget_type';
            }
            
            // Other fields to remove (keep timeline, tags, contact_preference)
            $otherColumns = [
                'payment_terms',
                'start_date',
                'required_skills',
                'preferred_experience',
                'team_size',
                'deliverables',
                'nda_required',
                'reference_required',
                'priority',
            ];
            
            foreach ($otherColumns as $column) {
                if (Schema::hasColumn('opportunities', $column)) {
                    $columnsToDrop[] = $column;
                }
            }
            
            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
        
        // Add single budget field if it doesn't exist
        Schema::table('opportunities', function (Blueprint $table) {
            if (!Schema::hasColumn('opportunities', 'budget')) {
                $table->decimal('budget', 15, 2)->nullable()->after('industry_id');
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
