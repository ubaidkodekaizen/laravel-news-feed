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
     * Update existing reportable_type values from App\Models\User to App\Models\Users\User
     */
    public function up(): void
    {
        if (Schema::hasTable('reports')) {
            // Update existing records that use the old namespace
            DB::table('reports')
                ->where('reportable_type', 'App\Models\User')
                ->update(['reportable_type' => 'App\Models\Users\User']);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('reports')) {
            // Revert back to old namespace
            DB::table('reports')
                ->where('reportable_type', 'App\Models\Users\User')
                ->update(['reportable_type' => 'App\Models\User']);
        }
    }
};
