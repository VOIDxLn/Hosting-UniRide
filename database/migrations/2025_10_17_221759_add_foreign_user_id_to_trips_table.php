<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('trips')) {

            if (!Schema::hasColumn('trips', 'user_id')) {
                Schema::table('trips', function (Blueprint $table) {
                    $table->unsignedBigInteger('user_id')->nullable()->after('id');
                });
            }

            // Agregar FK solo si no existe
            $connection = Schema::getConnection()->getDoctrineSchemaManager();
            $foreignKeys = $connection->listTableForeignKeys('trips');

            $fkExists = collect($foreignKeys)->contains(fn ($fk) =>
                in_array('user_id', $fk->getLocalColumns())
            );

            if (!$fkExists) {
                Schema::table('trips', function (Blueprint $table) {
                    $table->foreign('user_id')
                        ->references('id')
                        ->on('users')
                        ->onDelete('cascade');
                });
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('trips') && Schema::hasColumn('trips', 'user_id')) {
            Schema::table('trips', function (Blueprint $table) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            });
        }
    }
};
