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
        if (!Schema::hasTable('post_shares')) {
            Schema::create('post_shares', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained('posts')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('shared_post_id')->nullable()->constrained('posts')->onDelete('cascade');
            $table->text('shared_content')->nullable();
            $table->enum('share_type', ['share', 'repost'])->default('share');
            $table->timestamps();

            // Indexes
            $table->index('post_id');
            $table->index('user_id');
            $table->index('shared_post_id');
            $table->index('created_at');
                $table->index(['post_id', 'created_at']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_shares');
    }
};

