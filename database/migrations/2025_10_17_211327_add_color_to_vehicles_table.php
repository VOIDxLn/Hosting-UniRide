<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColorToVehiclesTable extends Migration
{
    public function up()
    {
        if (
            Schema::hasTable('vehicles') &&
            !Schema::hasColumn('vehicles', 'color')
        ) {
            Schema::table('vehicles', function (Blueprint $table) {
                $table->string('color')->nullable()->after('model');
            });
        }
    }

    public function down()
    {
        if (
            Schema::hasTable('vehicles') &&
            Schema::hasColumn('vehicles', 'color')
        ) {
            Schema::table('vehicles', function (Blueprint $table) {
                $table->dropColumn('color');
            });
        }
    }
}
