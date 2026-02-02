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
        Schema::create('saved_opportunities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('opportunity_id')->constrained('opportunities')->onDelete('cascade');
            $table->timestamps();
            
            $table->index('user_id');
            $table->index('opportunity_id');
            $table->unique(['user_id', 'opportunity_id']); // Prevent duplicate saves
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('saved_opportunities');
    }
};
