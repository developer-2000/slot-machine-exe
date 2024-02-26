<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\RolePermissions;

class CreateRolePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role_permissions', function (Blueprint $table) {
            $table->integer('role_id')->unsigned()->index()->default(0);
            $table->foreign('role_id')->references('id')->on('roles');

            $table->integer('permission_id')->unsigned()->index()->default(0);
            $table->foreign('permission_id')->references('id')->on('permissions');

            $table->softDeletes();
            //SETTING THE PRIMARY KEYS
            $table->primary(['role_id','permission_id']);
        });

        // связь роли с доступом
        RolePermissions::create([ 'role_id' => 1, 'permission_id' => 1]);
        RolePermissions::create([ 'role_id' => 2, 'permission_id' => 2]);
        RolePermissions::create([ 'role_id' => 3, 'permission_id' => 3]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('role_permissions');
    }
}
