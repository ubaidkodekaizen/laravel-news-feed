<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blocked_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('blocker_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('blocked_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();

            // Prevent duplicate blocks
            $table->unique(['blocker_id', 'blocked_id']);

            // Index for faster queries
            $table->index(['blocker_id', 'blocked_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blocked_users');
    }
};
