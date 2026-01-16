<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if there are any existing duplicate records before adding unique constraint
        // Clean up any duplicates first (keep the oldest one)
        \DB::statement('
            DELETE t1 FROM subscription_billings t1
            INNER JOIN subscription_billings t2 
            WHERE t1.id > t2.id 
            AND t1.subscription_id = t2.subscription_id 
            AND t1.event_type = t2.event_type 
            AND COALESCE(t1.event_date, t1.billing_date) = COALESCE(t2.event_date, t2.billing_date)
            AND COALESCE(t1.status_to, "") = COALESCE(t2.status_to, "")
        ');

        Schema::table('subscription_billings', function (Blueprint $table) {
            // Add unique constraint to prevent duplicate events
            // Same subscription + event_type + event_date should be unique
            $table->unique(['subscription_id', 'event_type', 'event_date'], 'subscription_billings_unique_event');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscription_billings', function (Blueprint $table) {
            // Drop the unique index
            $table->dropUnique('subscription_billings_unique_event');
        });
    }
};
