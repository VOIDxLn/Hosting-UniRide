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

            // 🔑 IMPORTANTE:
            // Laravel NO lanza error si la FK ya existe
            try {
                Schema::table('trips', function (Blueprint $table) {
                    $table->foreign('user_id')
                        ->references('id')
                        ->on('users')
                        ->onDelete('cascade');
                });
            } catch (\Throwable $e) {
                // Silencioso: la FK ya existe
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('trips') && Schema::hasColumn('trips', 'user_id')) {
            Schema::table('trips', function (Blueprint $table) {
                try {
                    $table->dropForeign(['user_id']);
                } catch (\Throwable $e) {}
                
                $table->dropColumn('user_id');
            });
        }
    }
};
