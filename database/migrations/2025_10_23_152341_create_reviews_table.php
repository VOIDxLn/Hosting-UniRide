<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReviewsTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('reviews')) {
            Schema::create('reviews', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('trip_id');
                $table->unsignedBigInteger('passenger_id');
                $table->unsignedBigInteger('driver_id');
                $table->tinyInteger('rating');
                $table->text('comment')->nullable();
                $table->timestamps();

                $table->foreign('trip_id')->references('id')->on('trips')->onDelete('cascade');
                $table->foreign('passenger_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('driver_id')->references('id')->on('users')->onDelete('cascade');
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('reviews');
    }
}
