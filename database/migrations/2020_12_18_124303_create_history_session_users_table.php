<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistorySessionUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('history_session_users', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('session_id')->unsigned()->index()->default(0);
            $table->bigInteger('user_id')->unsigned()->index()->default(0);
            $table->text('result')->nullable()->comment('результат игры в сессии');
            $table->timestamp('created')->nullable()->comment('создание таблицы session_users');
            $table->timestamp('updated')->nullable()->comment('обновление таблицы session_users');

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
        Schema::dropIfExists('history_session_users');
    }
}
