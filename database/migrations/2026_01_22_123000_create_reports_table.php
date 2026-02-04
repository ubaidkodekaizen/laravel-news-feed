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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reporter_id')->constrained('users')->onDelete('cascade');
            $table->string('reportable_type'); // App\Models\Users\User, App\Models\Feed\Post, etc.
            $table->unsignedBigInteger('reportable_id');
            $table->string('reason', 50); // spam, harassment, inappropriate_content, fake_account, violence, hate_speech, other
            $table->text('description')->nullable();
            $table->string('status', 20)->default('pending'); // pending, reviewed, resolved, dismissed
            $table->text('admin_notes')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();

            // Unique constraint: same user can't report same item twice
            $table->unique(['reporter_id', 'reportable_type', 'reportable_id']);
            $table->index(['reportable_type', 'reportable_id', 'status']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
