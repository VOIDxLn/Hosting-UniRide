<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTelefonoCorrectamenteToUsersTable extends Migration
{
    public function up()
    {
        if (
            Schema::hasTable('users') &&
            !Schema::hasColumn('users', 'telefono')
        ) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('telefono')->nullable()->after('email');
            });
        }
    }

    public function down()
    {
        if (
            Schema::hasTable('users') &&
            Schema::hasColumn('users', 'telefono')
        ) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('telefono');
            });
        }
    }
}
