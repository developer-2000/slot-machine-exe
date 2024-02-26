<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistoryPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('history_payments', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned()->default(0)->comment('администратор локации');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->bigInteger('location_id')->unsigned()->default(0)->comment('локация');
            $table->foreign('location_id')->references('id')->on('locations')->onDelete('cascade');
            $table->text('request')->nullable()->comment('данные оплаты');
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
        Schema::dropIfExists('history_payments');
    }
}
