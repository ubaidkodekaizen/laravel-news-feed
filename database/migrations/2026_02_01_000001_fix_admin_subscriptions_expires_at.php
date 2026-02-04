<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Fix existing admin-created subscriptions that are missing expires_at
     * Set expires_at to renewal_date if expires_at is null
     */
    public function up(): void
    {
        if (Schema::hasTable('subscriptions')) {
            // Update subscriptions where expires_at is null but renewal_date exists
            // This affects admin-created users with Free subscriptions
            DB::table('subscriptions')
                ->whereNull('expires_at')
                ->whereNotNull('renewal_date')
                ->where('status', 'active')
                ->where('subscription_type', 'Free')
                ->whereIn('platform', ['Admin', 'Amcob'])
                ->update([
                    'expires_at' => DB::raw('renewal_date'),
                    'auto_renewing' => false, // Free subscriptions don't auto-renew
                ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to reverse - we're just fixing data
    }
};
