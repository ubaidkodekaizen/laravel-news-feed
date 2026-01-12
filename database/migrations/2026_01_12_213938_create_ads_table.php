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
        Schema::create('ads', function (Blueprint $table) {
            $table->id();
            $table->string('media'); // Can be image, gif, or video (stores file path/URL)
            $table->string('url')->nullable(); // URL where the ad should link to
            $table->boolean('featured')->default(false); // Yes (true) or No (false)
            $table->string('status')->default('Active'); // Active or Inactive
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ads');
    }
};
