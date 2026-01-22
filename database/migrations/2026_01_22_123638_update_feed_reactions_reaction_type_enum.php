<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update reaction_type enum to LinkedIn-style reactions
        // First, update any existing reactions that don't match new types
        DB::table('feed_reactions')
            ->whereIn('reaction_type', ['haha', 'wow', 'sad', 'angry'])
            ->update(['reaction_type' => 'like']); // Convert old reactions to 'like'

        // Alter the enum column to support LinkedIn reactions
        DB::statement("ALTER TABLE feed_reactions MODIFY COLUMN reaction_type ENUM('like', 'love', 'celebrate', 'support', 'insightful') DEFAULT 'like'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Convert LinkedIn reactions back to old Facebook-style
        DB::table('feed_reactions')
            ->whereIn('reaction_type', ['celebrate', 'support', 'insightful'])
            ->update(['reaction_type' => 'like']); // Convert to 'like'

        // Revert to old enum
        DB::statement("ALTER TABLE feed_reactions MODIFY COLUMN reaction_type ENUM('like', 'love', 'haha', 'wow', 'sad', 'angry') DEFAULT 'like'");
    }
};
