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
        Schema::create('device_tokens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('fcm_token', 500); // FCM token for push notifications (max length ~200, using 500 for safety)
            $table->string('device_type')->nullable(); // 'ios', 'android', 'web'
            $table->string('device_id')->nullable(); // Unique device identifier
            $table->string('device_name')->nullable(); // Device name/model
            $table->timestamps();
            
            // Indexes
            $table->index('user_id');
            $table->index('fcm_token');
            $table->index(['user_id', 'fcm_token']);
            
            // Unique constraint: one token per user-device combination
            $table->unique(['user_id', 'fcm_token']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('device_tokens');
    }
};
