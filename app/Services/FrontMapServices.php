<?php
namespace App\Services;

use App\Facades\MyFunctions;
use App\Repositories\HistorySessionUserRepository;
use App\Repositories\LocationRepository;

class FrontMapServices {

    protected $historySessionUserRepository;
    protected $locationRepository;

    public function __construct() {
        $this->historySessionUserRepository = new HistorySessionUserRepository();
        $this->locationRepository = new LocationRepository();
    }

    // выдать посещенные локации юзером
    // ========================================
    public function getLocationsUser($user){
        // вся история игр игрока - локации
        $visited = $this->historySessionUserRepository->getAllHistoryGamer($user->id);
        $arrVisLocations = [];
        // все локации игр игрока
        foreach ($visited as $key => $obj){
            array_push($arrVisLocations, $obj->session_history->attraction->location);
        }
        // уникальные локации по ключу
        $arrVisLocations = MyFunctions::removeDuplicates($arrVisLocations, 'id');

        // все локации в игре
        $allLocations = $this->locationRepository->get();

        return [
            'locations_user'=>$arrVisLocations,
            'all_locations'=>$allLocations
        ];
    }

}
