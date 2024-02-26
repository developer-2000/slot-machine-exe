<?php
namespace App\Repositories;

use App\Models\License as Model;


class LicenseRepository extends CoreRepository {

    public function __construct() {
        $this->model = clone app(Model::class);
    }

    // выбрать локации и их аттракционы
    public function locationAttractions(array $where){
        return $this->model->where($where)->with('location.attractions')->get();
    }

    // =========================================
    // пагинация все лицензии с аттракционами
    public function paginLicense($where, $step){
        return $this->model
            ->where($where)
            ->with('user', 'attractions')
            ->paginate($step);
    }

    // ===========================================
    // пагинация все лицензии whereIn с аттракционами
    public function paginWhereInLicense($column, $arr_id, $step){
        return $this->model
            ->whereIn($column, $arr_id)
            ->with('user', 'attractions')
            ->paginate($step);
    }
}
