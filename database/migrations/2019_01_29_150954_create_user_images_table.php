<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_images', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned()->default(0);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->string('border_image')->nullable()->comment('обьект всех рамок игрока');
            $table->tinyInteger('select_border')->default(1)->comment('выбрано юзером');
            $table->string('avatar_image')->nullable()->comment('обьект всех аватарок игрока');
            $table->tinyInteger('select_avatar')->default(1)->comment('выбрано юзером');
            $table->string('change_image')->nullable()->comment('все аним смены хода игрока');
            $table->tinyInteger('select_change')->default(1)->comment('выбрано юзером');
            $table->string('impact_image')->nullable()->comment('все аним импакта игрока');
            $table->tinyInteger('select_impact')->default(1)->comment('выбрано юзером');
            $table->string('victory_image')->nullable()->comment('все аним победы игрока');
            $table->tinyInteger('select_victory')->default(1)->comment('выбрано юзером');
            $table->timestamps();
        });


        $users = \App\Models\User::with('user_image')->get();
        $config = config('game.user_images');

        foreach ($users as $key => $user){
            if(is_null($user->user_image)){
                \App\Models\UserImage::create(
                    [
                        'user_id' => $user->id,
                        'avatar_image' => array_column($config['avatar_image']['free'], 'id'),
                        'border_image' => array_column($config['border_image']['free'], 'id'),
                        'change_image' => array_column($config['change_image']['free'], 'id'),
                        'impact_image' => array_column($config['impact_image']['free'], 'id'),
                        'victory_image' => array_column($config['victory_image']['free'], 'id'),
                    ]
                );
            }
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_images');
    }
}
