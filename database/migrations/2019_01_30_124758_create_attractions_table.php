<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttractionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('attractions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned()->index()->default(0);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->string('title')->unique()->nullable();
            $table->boolean('activation')->default(1)->comment('акцивация аттракциона');
            $table->integer('location_id')->unsigned()->index()->default(0);
            $table->string('access_token')->nullable()->comment('токен авторизации');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('attractions');
    }
}
