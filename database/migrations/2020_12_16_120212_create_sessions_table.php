<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('sessions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('parent_id')->unsigned()->index()->default(0)->comment('родительская сессия');

            $table->bigInteger('attraction_id')->unsigned()->index()->default(0);
            $table->foreign('attraction_id')->references('id')->on('attractions')->onDelete('cascade');

            $table->tinyInteger('mode_id')->index()->default(0);
            $table->tinyInteger('activate')->default(0)->comment('состояние активности игры');
            $table->string('session_token')->nullable()->comment('для верификации игрока при добавлении');
            $table->tinyInteger('player_max_count')->default(0)->comment('кол-во игроков в режиме');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('sessions');
    }
}
