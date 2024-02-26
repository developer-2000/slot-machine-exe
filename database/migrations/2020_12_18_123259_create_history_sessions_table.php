<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistorySessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('history_sessions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('session_id')->unsigned()->default(0);
            $table->bigInteger('parent_id')->unsigned()->index()->default(0)->comment('родительская сессия');
            $table->bigInteger('attraction_id')->unsigned()->index()->default(0);
            $table->tinyInteger('mode_id')->index()->default(0);
            $table->tinyInteger('player_max_count')->default(0)->comment('кол-во игроков в режиме');
            $table->text('result')->nullable()->comment('результат игры в сессии');
            $table->timestamp('created')->nullable()->comment('создание таблицы sessions');
            $table->timestamp('updated')->nullable()->comment('обновление таблицы sessions');
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
        Schema::dropIfExists('history_sessions');
    }
}
