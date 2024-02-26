<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocationModeStatisticsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('location_mode_statistics', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('location_id')->unsigned()->index()->default(0);
            $table->foreign('location_id')->references('id')->on('locations')->onDelete('cascade');

            $table->tinyInteger('mode_id')->default(0)->comment('идентификатор режима игры');
            $table->integer('count_game')->default(0)->comment('кол-во сыгранных в этом режиме игр на локации');
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
        Schema::dropIfExists('location_mode_statistics');
    }
}
