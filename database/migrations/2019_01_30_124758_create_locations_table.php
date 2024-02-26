<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned()->index()->default(0);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->smallInteger('price')->unsigned()->default(0)->comment('цена 1 аттракциона на локации');
            $table->string('url_avatar')->nullable()->comment('аватар локации');
            $table->string('title')->unique()->nullable();
            $table->boolean('activation')->default(1)->comment('акцивация локации');
            $table->string('country')->nullable()->comment('страна и код');
            $table->string('region')->nullable()->comment('регион и код');
            $table->string('city')->nullable()->comment('город и код');
            $table->text('street')->nullable()->comment('улица и код');
            $table->string('working_hours', 100)->nullable()->comment('рабочие часы локации');
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
        Schema::dropIfExists('locations');
    }
}
