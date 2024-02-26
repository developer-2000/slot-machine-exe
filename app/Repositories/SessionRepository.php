<?php
namespace App\Repositories;


use App\Exceptions\UserException;
use App\Models\Session as Model;


class SessionRepository extends CoreRepository {

    public function __construct() {
        $this->model = clone app(Model::class);
    }

    // ===========================================
    // выбрать сесию с игроками и проверкой token
    public function showOpenTokenSession($index){
        return $this->model
            ->where('id', $index['session_id'])
            ->where('session_token', $index['session_token'])
            ->where('attraction_id', $index['attraction_id'])
            ->with('sess_user_many')
            ->first();
    }

    // ===========================================
    // выбрать сесию с игроками
    public function showSessionUser($session_id){
        return $this->model
            ->where('id', $session_id)
            ->with('sess_user_many')
            ->first();
    }

    // ===========================================
    // выбрать сессия, игроки с картинками, аттракцион сессии, локация сессии
    public function showSessionWithDataUser($id){
        return $this->model
            ->where('id', $id)
            ->with('sess_user_many.user_one.user_image')
            ->with('attraction.location.statistic')
            ->first();
    }

    // ===========================================
    // выбрать сесию аттракцион юзер
    public function whereArrAttraction(array $arr){
        return $this->model->where($arr)
            ->with('attraction')
            ->first();
    }

    // ===========================================
    // сессия аттракциона - сессия, аттракцион, локация
    public function getSessionData(array $where){
        return $this->model
            ->where($where)
            ->latest('id')->first();
    }











    // =========================================
    // =========================================
    // =========================================
    // отдать родителю
    protected function getModelClass() {
        return Model::class;
    }


}
