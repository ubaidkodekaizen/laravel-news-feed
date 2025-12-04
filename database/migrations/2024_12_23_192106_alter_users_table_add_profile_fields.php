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
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone_public')->default('No')->after('phone');
            $table->string('email_public')->default('No')->after('email');
            $table->string('user_position')->nullable()->after('email_public');
            $table->string('gender')->nullable()->after('user_position');
            $table->string('age_group')->nullable()->after('gender');
            $table->string('ethnicity')->nullable()->after('age_group');
            $table->string('nationality')->nullable()->after('ethnicity');
            $table->string('languages')->nullable()->after('nationality');
            $table->string('marital_status')->nullable()->after('languages');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'phone_public',
                'email_public',
                'user_position',
                'gender',
                'age_group',
                'ethnicity',
                'nationality',
                'languages',
                'marital_status',
            ]);
        });
    }
};
