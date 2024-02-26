<?php
namespace App\Repositories;

use App\Models\User as Model;


class UserRepository extends CoreRepository {

    public function __construct() {
        $this->model = clone app(Model::class);
    }

    // =========================================
    // выбрать юзера и его картинки
    public function getUserImage($user_id){
        return $this->model->with('user_image')->find($user_id);
    }

    // =========================================
    // выбрать Все по масиву id и их картинки
    public function whereInImage(string $column, $arr_id){
        return $this->model->with('user_image')
            ->whereIn($column, $arr_id)->get();
    }

    // =========================================
    // выбрать локации и их аттракционы
    public function locationAttractions($user_id){
        return $this->model->find($user_id)->location_user;
    }

    // =========================================
    // выбрать лицензия админа
    public function license($user_id){
        return $this->model->find($user_id)->license;
    }

    // =========================================
    // выбрать похожие по полю
    public function selectLike($column, $value){
        return $this->model
            ->where($column, 'like', '%'.$value.'%')->get();
    }

    // =========================================
    // выбрать всех игроков
    public function getGamers(){
        return $this->model
            ->leftJoin('users_roles', 'users.id', '=', 'users_roles.user_id')
            ->where('users_roles.role_id', 3)
            ->select('users.*')
            ->get();
    }

    // =========================================
    // выбрать количество всех игроков
    public function getCountGamers(){
        return $this->model
            ->leftJoin('users_roles', 'users.id', '=', 'users_roles.user_id')
            ->where('users_roles.role_id', 3)
            ->count();
    }

}
