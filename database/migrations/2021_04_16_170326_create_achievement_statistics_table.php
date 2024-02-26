<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAchievementStatisticsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('achievement_statistics', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('achievement_id')->unsigned()->index()->default(0);
            $table->foreign('achievement_id')->references('id')->on('achievements')->onDelete('cascade');

            $table->bigInteger('user_id')->unsigned()->index()->default(0);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('data')->nullable()->comment('статистика достижения');

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
        Schema::dropIfExists('achievement_statistics');
    }
}
