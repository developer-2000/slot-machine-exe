<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLicensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('licenses', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned()->index()->default(0);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->bigInteger('location_id')->unsigned()->index()->default(0);
            $table->foreign('location_id')->references('id')->on('locations')->onDelete('cascade');

            $table->timestamp('month')->nullable()->comment('лицензия 1 месяц');
            $table->text('request')->nullable()->comment('данные оплаты');
            $table->double('total_payment')->default(0)->comment('полная оплата лицензии');
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
        Schema::dropIfExists('licenses');
    }
}
