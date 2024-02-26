<?php
namespace App\Repositories;

use App\Models\Attraction as Model;

class AttractionRepository extends CoreRepository {

    public function __construct() {
        $this->model = clone app(Model::class);
    }

    // ===========================================
    // показать атракцион
    public function edit($id){
        return $this->model->withTrashed()->find($id);
    }

    // ===========================================
    // выбрать любую сессию у аттракциона
    public function session_any($id){
        return $this->model->find($id)->session_any;
    }

    // ===========================================
    // выбрать Все по масиву id вместе с локациями
    public function whereInAttractionLocation(string $column, array $arr_id){
        return $this->model
            ->whereIn($column, $arr_id)
            ->with('location')
            ->get();
    }

}
