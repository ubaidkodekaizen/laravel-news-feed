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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->string('company_logo')->nullable();
            $table->string('company_name')->nullable();
            $table->string('company_slug')->nullable();
            $table->string('company_email')->nullable();
            $table->string('company_web_url')->nullable();
            $table->string('company_linkedin_url')->nullable();
            $table->string('company_position')->nullable();
            $table->text('company_about')->nullable();
            $table->string('company_revenue')->nullable();
            $table->string('company_address')->nullable();
            $table->string('company_country')->nullable();
            $table->string('company_state')->nullable();
            $table->string('company_city')->nullable();
            $table->string('company_county')->nullable();
            $table->string('company_zip_code')->nullable();
            $table->string('company_no_of_employee')->nullable();
            $table->string('company_business_type')->nullable();
            $table->string('company_industry')->nullable();
            $table->string('company_sub_category')->nullable();
            $table->string('company_community_service')->nullable();
            $table->string('company_contribute_to_muslim_community')->nullable();
            $table->string('company_affiliation_to_muslim_org')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
