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
        Schema::create('subscription_billings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subscription_id')->nullable()->constrained('subscriptions')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('platform', 50)->nullable(); // Web, Google, Apple
            $table->string('event_type', 50)->nullable(); // created, renewed, cancelled, expired, suspended, reactivated, status_changed
            $table->date('event_date')->nullable(); // Date when event occurred (billing_date for billing events)
            $table->date('billing_date')->nullable(); // Alias for event_date when event_type is billing-related (for backward compatibility)
            $table->decimal('amount', 10, 2)->nullable(); // Amount charged (for billing events)
            $table->string('transaction_id')->nullable(); // Transaction ID from payment gateway
            $table->string('status', 20)->nullable(); // success, failed, pending (for billing events), or status value
            $table->string('status_from', 20)->nullable(); // Previous status (for status_changed events)
            $table->string('status_to', 20)->nullable(); // New status (for status_changed events)
            $table->text('notes')->nullable(); // Additional notes
            $table->json('metadata')->nullable(); // Additional data as JSON (e.g., reason for cancellation)
            $table->timestamps();
            
            // Indexes for better query performance
            $table->index(['user_id', 'platform']);
            $table->index(['subscription_id', 'event_date']);
            $table->index(['subscription_id', 'event_type']);
            $table->index('event_date');
            $table->index('event_type');
            $table->index('billing_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_billings');
    }
};
