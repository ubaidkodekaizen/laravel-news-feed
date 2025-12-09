<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::dropIfExists('accreditations');
        Schema::dropIfExists('bussiness_contributions');
        Schema::dropIfExists('community_interests');
        Schema::dropIfExists('muslim_organizations');
    }

    public function down()
    {
        // No rollback needed since tables are being dropped
    }
};
