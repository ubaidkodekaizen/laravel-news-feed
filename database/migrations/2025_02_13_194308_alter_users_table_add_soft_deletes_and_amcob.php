<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::table('users', function (Blueprint $table) {
            $table->string('is_amcob')->default('No'); // Changed to string with default 'No'
            $table->string('duration')->nullable(); // Changed to string and nullable
            $table->softDeletes(); // Adds deleted_at column for soft deletes
        });
    }

    public function down() {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['is_amcob', 'duration', 'deleted_at']); // Rollback changes
        });
    }
};
