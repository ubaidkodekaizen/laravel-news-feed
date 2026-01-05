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
        if (!Schema::hasTable('posts')) {
            Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->unsignedBigInteger('original_post_id')->nullable();
            $table->text('content')->nullable();
            $table->boolean('comments_enabled')->default(true);
            $table->enum('status', ['active', 'deleted'])->default('active');
            $table->integer('reactions_count')->default(0);
            $table->integer('comments_count')->default(0);
            $table->integer('shares_count')->default(0);
            $table->softDeletes();
            $table->timestamps();

            // Indexes
            $table->index('user_id');
            $table->index('original_post_id');
            $table->index('created_at');
            $table->index('status');
                $table->index(['user_id', 'status', 'created_at']);
            });

            // Add foreign key constraint after table is created (self-referencing)
            Schema::table('posts', function (Blueprint $table) {
                $table->foreign('original_post_id')->references('id')->on('posts')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};

