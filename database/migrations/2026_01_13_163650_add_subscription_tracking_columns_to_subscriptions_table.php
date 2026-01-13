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
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->timestamp('cancelled_at')->nullable()->after('status');
            $table->timestamp('last_renewed_at')->nullable()->after('cancelled_at');
            $table->integer('renewal_count')->default(0)->after('last_renewed_at');
            $table->timestamp('expires_at')->nullable()->after('renewal_count');
            $table->boolean('auto_renewing')->default(true)->after('expires_at');
            $table->string('payment_state', 50)->nullable()->after('auto_renewing');
            $table->timestamp('last_checked_at')->nullable()->after('payment_state');
            $table->timestamp('grace_period_ends_at')->nullable()->after('last_checked_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropColumn([
                'cancelled_at',
                'last_renewed_at',
                'renewal_count',
                'expires_at',
                'auto_renewing',
                'payment_state',
                'last_checked_at',
                'grace_period_ends_at',
            ]);
        });
    }
};
