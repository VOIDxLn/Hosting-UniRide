<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToTripUserTable extends Migration
{
    public function up()
    {
        if (
            Schema::hasTable('trip_user') &&
            !Schema::hasColumn('trip_user', 'status')
        ) {
            Schema::table('trip_user', function (Blueprint $table) {
                $table->string('status')->default('pendiente');
            });
        }
    }

    public function down()
    {
        if (
            Schema::hasTable('trip_user') &&
            Schema::hasColumn('trip_user', 'status')
        ) {
            Schema::table('trip_user', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }
    }
}
