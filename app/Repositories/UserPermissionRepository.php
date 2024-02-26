<?php
namespace App\Repositories;

use App\Exceptions\UserException;
use App\Models\UsersPermissions as Model;


class UserPermissionRepository extends CoreRepository {

    public function __construct() {
        $this->model = clone app(Model::class);
    }

    // =========================================
    // масив - все id доступов юзера
    public function getPermissionsIdForUserId($user_id){
        $obj = $this->model->where('user_id', $user_id)->get();
        if($obj){
            return array_keys($obj->keyBy('permission_id')->toArray());
        }
    return false;
    }

}
