<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('users_roles', function (Blueprint $table) {
            $table->bigInteger('user_id')->unsigned()->index()->default(0);
            $table->integer('role_id')->unsigned()->index()->default(0);
            $table->softDeletes();

            //SETTING THE PRIMARY KEYS
            $table->primary(['user_id','role_id']);
        });
        Schema::table('users_roles', function($table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_roles');
    }
}
