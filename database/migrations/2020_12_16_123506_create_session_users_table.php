<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSessionUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('session_users', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('session_id')->unsigned()->index()->default(0);
            $table->foreign('session_id')->references('id')->on('sessions')->onDelete('cascade');

            $table->integer('user_id')->unsigned()->index()->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('session_users');
    }
}
