<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nickname')->nullable();
            $table->string('first_name')->nullable()->comment('имя человека');
            $table->string('surname')->nullable()->comment('фамилия');
            $table->string('middlename')->nullable()->comment('отчество');
            $table->string('email')->unique()->comment('email регистрации');
            $table->string('password')->nullable();
            $table->boolean('activation')->default(1)->comment('акцивация юзера');
            $table->string('company')->nullable()->comment('комания владельца');
            $table->string('tell')->nullable()->comment('телефон');
            $table->string('country')->nullable()->comment('страна и код');
            $table->string('region')->nullable()->comment('регион и код');
            $table->string('city')->nullable()->comment('город и код');
            $table->text('street')->nullable()->comment('улица и код');
            $table->timestamp('date_of_birth')->nullable()->comment('дата рождения');
            $table->text('statistic')->nullable('{}')->comment('общая статистика игрока');
            $table->timestamp('email_verified_at')->nullable();

            $table->rememberToken();
            $table->softDeletes();
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
        Schema::dropIfExists('users');
    }
}
