<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAchievementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('achievements', function (Blueprint $table) {
            $table->id();
            $table->string('title_ru')->nullable()->comment('название достижения');
            $table->string('title_en')->nullable();
            $table->string('description_ru')->nullable()->comment('описание достижения');
            $table->string('description_en')->nullable();
            $table->timestamps();
        });

        // залить все достижения юзеров
        $achievements = config('game.achievements');
        // множественное создание с заполнением полей даты
        (new \App\Repositories\AchievementRepository)->insertAddDate($achievements);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('achievements');
    }
}
