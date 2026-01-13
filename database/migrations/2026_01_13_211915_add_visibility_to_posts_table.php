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
        Schema::table('posts', function (Blueprint $table) {
            // Add visibility column if it doesn't exist
            if (!Schema::hasColumn('posts', 'visibility')) {
                $table->enum('visibility', ['public', 'connections', 'private'])
                      ->default('public')
                      ->after('comments_enabled');
            }
        });

        // Also ensure post_media has duration column for videos
        Schema::table('post_media', function (Blueprint $table) {
            if (!Schema::hasColumn('post_media', 'duration')) {
                $table->integer('duration')->nullable()->after('mime_type');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            if (Schema::hasColumn('posts', 'visibility')) {
                $table->dropColumn('visibility');
            }
        });

        Schema::table('post_media', function (Blueprint $table) {
            if (Schema::hasColumn('post_media', 'duration')) {
                $table->dropColumn('duration');
            }
        });
    }
};
