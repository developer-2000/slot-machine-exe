<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlayerStatisticLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('player_statistic_locations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned()->index()->default(0);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->bigInteger('location_id')->unsigned()->index()->default(0);
            $table->foreign('location_id')->references('id')->on('locations')->onDelete('cascade');

            $table->text('statistic')->nullable()->comment('статистика игрока на локации');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('player_statistic_locations');
    }
}
