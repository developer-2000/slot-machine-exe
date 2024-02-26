<?php
namespace App\Repositories;

use App\Exceptions\UserException;
use App\Models\UsersRoles as Model;


class UserRoleRepository extends CoreRepository {

    public function __construct() {
        $this->model = clone app(Model::class);
    }

    // =========================================
    // масив всех id ролей юзера
    public function getRolesIdForUser($user_id){
        $obj = $this->model->where('user_id', $user_id)->get();
        if($obj){
            return array_keys($obj->keyBy('role_id')->toArray());
        }else{
            throw new UserException(config('game.custom_value.ex.1'), 1);
        }
    }

    // =========================================
    // создать новую связь для юзера
    public function creatUserRole($user_id, $role_id){
        if( !$obj = $this->model->updateOrCreate(
            [ 'user_id'=>$user_id ],
            [ 'role_id'=>$role_id ]
        ) ){
            throw new UserException(config('game.custom_value.ex.1'), 1);
        }
    }

}
