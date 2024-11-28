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
       // Alter the users table to add the 'paid' column after 'status'
        Schema::table('users', function (Blueprint $table) {
            $table->string('paid', 3)->default('No')->after('status');
        });

        // Alter the subscriptions table to add the 'transaction_id' column after 'status'
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->string('transaction_id')->nullable()->after('status');
        });
        }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('paid');
        });

        // Drop the 'transaction_id' column from the subscriptions table
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropColumn('transaction_id');
        });
    }
};
