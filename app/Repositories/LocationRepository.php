<?php
namespace App\Repositories;

use App\Exceptions\UserException;
use App\Models\Location as Model;

class LocationRepository extends CoreRepository {

    public function __construct() {
        $this->model = clone app(Model::class);
    }

    // ===========================================
    // выбрать Все Локации и статистику
    public function allLocationsStatistic(){
        return $this->model->with('statistic')->get();
    }

    // ===========================================
    // выбрать Одну Локацию с активными аттракционами и лицензией
    public function oneLocationActiveAttractionsLicense($where){
        return $this->model->where($where)->with('activeAttractions','license');
    }

    // ===========================================
    // выбрать локации с аттракционами
    public function allLocationsAttraction(){
        return $this->model->with('attractions')
            ->get();
    }

    // ===========================================
    // выбрать Одну локацию с аттракционами
    public function oneLocationAttractions(array $arr){
        return $this->model->where($arr)
            ->with('attractions')->first();
    }

    // ===========================================
    // локация и статистика сыгранных режимов на ней
    public function locationModeStatistic(array $arr){
        return $this->model->where($arr)
            ->with('mode_statistic')
            ->with('attractions.hystory_sessions')
            ->first();
    }
}
