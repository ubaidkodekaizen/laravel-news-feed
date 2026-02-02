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
        Schema::create('opportunity_ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('opportunity_id')->constrained('opportunities')->onDelete('cascade');
            $table->foreignId('rated_by_user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('rated_user_id')->constrained('users')->onDelete('cascade');
            $table->tinyInteger('rating'); // Rating 1-5 stars
            $table->text('comment')->nullable();
            $table->timestamps();
            
            $table->index('opportunity_id');
            $table->index('rated_by_user_id');
            $table->index('rated_user_id');
            $table->index('rating');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('opportunity_ratings');
    }
};
