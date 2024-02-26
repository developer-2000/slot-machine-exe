<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlayerAchievementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('player_achievements', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('achievement_id')->unsigned()->index()->default(0);
            $table->foreign('achievement_id')->references('id')->on('achievements')->onDelete('cascade');

            $table->bigInteger('user_id')->unsigned()->index()->default(0);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->tinyInteger('type')->default(0)->comment('уровень достижения юзера');
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
        Schema::dropIfExists('player_achievements');
    }
}
