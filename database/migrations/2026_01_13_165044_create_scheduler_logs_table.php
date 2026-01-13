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
        Schema::create('scheduler_logs', function (Blueprint $table) {
            $table->id();
            $table->string('scheduler')->index(); // Name of the scheduler (e.g., 'subscriptions:sync-all', 'backup:run', etc.)
            $table->string('command')->index(); // Full command name/signature
            $table->enum('status', ['success', 'failed', 'partial'])->default('success')->index();
            $table->text('result_detail')->nullable(); // Detailed result/response (can be JSON or text)
            $table->json('result_data')->nullable(); // Structured result data (JSON) - flexible for any scheduler
            $table->integer('records_processed')->default(0)->nullable(); // Generic count of records processed
            $table->integer('records_updated')->default(0)->nullable(); // Generic count of records updated
            $table->integer('records_failed')->default(0)->nullable(); // Generic count of records that failed
            $table->text('error_message')->nullable(); // Error message if failed
            $table->text('error_trace')->nullable(); // Full error trace/stack if available
            $table->integer('execution_time_ms')->nullable(); // Execution time in milliseconds
            $table->timestamp('ran_at')->useCurrent()->index(); // When the scheduler ran
            $table->softDeletes(); // Soft delete support
            $table->timestamps();
            
            $table->index(['scheduler', 'ran_at']);
            $table->index(['status', 'ran_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scheduler_logs');
    }
};
