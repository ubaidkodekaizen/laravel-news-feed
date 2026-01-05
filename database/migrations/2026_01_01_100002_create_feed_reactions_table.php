<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Creates feed_reactions table for news feed (posts/comments reactions)
     * This is separate from the reactions table used for messages
     */
    public function up(): void
    {
        // Create a separate feed_reactions table for news feed (posts/comments)
        // This is separate from the reactions table used for messages
        if (!Schema::hasTable('feed_reactions')) {
            Schema::create('feed_reactions', function (Blueprint $table) {
            $table->id();
            $table->morphs('reactionable'); // Creates reactionable_id and reactionable_type
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('reaction_type', ['like', 'love', 'haha', 'wow', 'sad', 'angry'])->default('like');
            $table->timestamps();

            // Indexes
            $table->index(['reactionable_id', 'reactionable_type']);
            $table->index('user_id');
            $table->index('reaction_type');
                // Unique constraint: one reaction per user per reactionable item
                $table->unique(['reactionable_id', 'reactionable_type', 'user_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feed_reactions');
    }
};
