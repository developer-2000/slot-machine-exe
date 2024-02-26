<?php
namespace App\Repositories;


use App\Exceptions\UserException;
use App\Models\HistorySessions as Model;


class HistorySessionRepository extends CoreRepository {

    public function __construct() {
        $this->model = clone app(Model::class);
    }

    // ===========================================
    // сессия истории, аттракцион и локация аттракциона
    public function getInfoGame(array $arr){
        return $this->model->where($arr)
            ->with('attraction.location')
            ->first();
    }

}
