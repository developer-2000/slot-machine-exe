<?php
namespace App\Repositories;

use App\Models\HistorySessionUsers as Model;

class HistorySessionUserRepository extends CoreRepository {

    public function __construct() {
        $this->model = clone app(Model::class);
    }

    // выбрать вся история игр игрока + история сессий + атракцион
    public function getAllHistoryGamer($user_id){
        return $this->model->where('user_id', $user_id)
            ->with('session_history.attraction.location')->get();
    }

}
