<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Update reaction types from LinkedIn-style to original names to avoid legal/IP issues
     */
    public function up(): void
    {
        // Map old LinkedIn-style reactions to new original names
        // Like → Appreciate
        // Celebrate → Cheers
        // Love → Support
        // Insightful → Insight
        // Support → Support (keep as is)
        
        DB::table('feed_reactions')
            ->where('reaction_type', 'like')
            ->update(['reaction_type' => 'appreciate']);
        
        DB::table('feed_reactions')
            ->where('reaction_type', 'celebrate')
            ->update(['reaction_type' => 'cheers']);
        
        DB::table('feed_reactions')
            ->where('reaction_type', 'love')
            ->update(['reaction_type' => 'support']);
        
        DB::table('feed_reactions')
            ->where('reaction_type', 'insightful')
            ->update(['reaction_type' => 'insight']);

        // Alter the enum column to support original reaction types
        DB::statement("ALTER TABLE feed_reactions MODIFY COLUMN reaction_type ENUM('appreciate', 'cheers', 'support', 'insight', 'curious', 'smile') DEFAULT 'appreciate'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Convert back to LinkedIn-style reactions
        DB::table('feed_reactions')
            ->where('reaction_type', 'appreciate')
            ->update(['reaction_type' => 'like']);
        
        DB::table('feed_reactions')
            ->where('reaction_type', 'cheers')
            ->update(['reaction_type' => 'celebrate']);
        
        DB::table('feed_reactions')
            ->where('reaction_type', 'support')
            ->update(['reaction_type' => 'love']);
        
        DB::table('feed_reactions')
            ->where('reaction_type', 'insight')
            ->update(['reaction_type' => 'insightful']);

        // Revert to LinkedIn-style enum
        DB::statement("ALTER TABLE feed_reactions MODIFY COLUMN reaction_type ENUM('like', 'love', 'celebrate', 'support', 'insightful') DEFAULT 'like'");
    }
};
