<?php
namespace App\Repositories;


use App\Exceptions\UserException;
use App\Models\SessionUser as Model;


class SessionUserRepository extends CoreRepository {

    public function __construct() {
        $this->model = clone app(Model::class);
    }


    // ===========================================
    // удалить много по id
    public function deleteMany($arr_user_id, $session_id){
        return $this->model
            ->where('session_id', $session_id)
            ->whereIn('user_id', $arr_user_id)->delete();
    }


    // ===========================================
    // ===========================================
    // обновить сессию игроков по параметру
    public function updateWhereSessionId($session_id, $arr){
        return $this->model
            ->where('session_id', $session_id)
            ->update($arr);
    }


    // ===========================================
    // сессия юзера и данные о нем
    public function getSessionEndUser($id){
        return $this->model
            ->where('id', $id)
            ->with('user_one')
            ->latest('id')->first();
    }

    // ===========================================
    // сессия юзера - сессия юзера, сессия, аттракцион, локация
    public function getSessionData($id){
        return $this->model
            ->where('user_id', $id)
            ->with('sessions.attraction.location')
            ->latest('id')->first();
    }

}
