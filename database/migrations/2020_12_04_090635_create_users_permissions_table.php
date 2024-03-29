<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersPermissionsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_permissions', function ( Blueprint $table ) {
            $table->bigInteger('user_id')->unsigned()->index()->default(0);
            $table->integer('permission_id')->unsigned()->index()->default(0);
            $table->softDeletes();

            //SETTING THE PRIMARY KEYS
            $table->primary(['user_id', 'permission_id']);
        });
        Schema::table('users_permissions', function($table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_permissions');
    }
}
