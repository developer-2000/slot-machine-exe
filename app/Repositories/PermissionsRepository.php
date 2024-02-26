<?php
namespace App\Repositories;

use App\Exceptions\UserException;
use App\Models\Permissions as Model;
use Illuminate\Support\Facades\DB;


class PermissionsRepository extends CoreRepository {

    public function __construct() {
        $this->model = clone app(Model::class);
    }


    // ===========================================
    // масив - выбрать названия доступов по связке role_id->permision_id
    public function getNameRolePermissions($arr_role_id){

        $obj = DB::table('permissions as p')
            ->leftJoin('role_permissions as rp', 'rp.permission_id', 'p.id')
            ->whereIn('rp.role_id', $arr_role_id)
            ->select(DB::raw('p.name'))
            ->get();

        if ($obj) {
            return array_keys($obj->keyBy('name')->toArray());
        } else {
            throw new UserException(config('game.custom_value.ex.4'), 1);
        }
    }

    // ===========================================
    // выбрать названия доступов по масиву id permission
    public function getNamePermissions($permiss_id){
        $obj = $this->model->whereIn('id', $permiss_id)
            ->select('name')->get();
        if ($obj) {
            return array_keys($obj->keyBy('name')->toArray());
        } else {
            throw new UserException(config('game.custom_value.ex.5'), 1);
        }
    }

}
