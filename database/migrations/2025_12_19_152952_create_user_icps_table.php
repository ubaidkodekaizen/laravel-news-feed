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
        Schema::create('user_icps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('business_location')->nullable();
            $table->boolean('is_decision_maker')->nullable();
            $table->text('company_current_business_challenges')->nullable();
            $table->text('company_business_goals')->nullable();
            $table->text('company_attributes')->nullable();
            $table->text('company_technologies_you_use')->nullable();
            $table->string('company_buying_process')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_icps');
    }
};
