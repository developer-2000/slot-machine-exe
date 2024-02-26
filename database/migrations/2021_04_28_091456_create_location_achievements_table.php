<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocationAchievementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('location_achievements', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('location_id')->unsigned()->index()->default(0);
            $table->foreign('location_id')->references('id')->on('locations')->onDelete('cascade');

            $table->text('long_liver')->nullable();
            $table->text('axes_all_directions')->nullable();
            $table->text('access_granted')->nullable();
            $table->text('in_love_bullseye')->nullable();
            $table->text('in_love_sniper')->nullable();
            $table->text('in_love_throw_royal')->nullable();
            $table->text('in_love_monopoly')->nullable();
            $table->text('hype')->nullable();
            $table->text('packed_to_eyeballs')->nullable();
            $table->text('who_you')->nullable();
            $table->timestamp('last_update');
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
        Schema::dropIfExists('location_achievements');
    }
}
