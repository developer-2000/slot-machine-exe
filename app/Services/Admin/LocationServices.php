<?php
namespace App\Services\Admin;

use App\Models\Attraction;
use App\Models\Location;
use App\Repositories\HistorySessionRepository;
use App\Repositories\LocationRepository;
use App\Services\CarbonServices;
use App\Services\Permissions\PermissionsAdminPanelServices;
use Carbon\Carbon;

class LocationServices {

    protected $permissionService;
    protected $locationRepository;
    protected $method = [ 'subMonths', 'subYears' ];
    protected $carbonService;

    public function __construct() {
        $this->permissionService = new PermissionsAdminPanelServices();
        $this->locationRepository = new LocationRepository();
        $this->carbonService = new CarbonServices();
    }

    // ========================================
    // выбрать все локации, аттракционы и лицензии Администратора в Пагинации
    public function getLocationsPagination($index, $user) {
        // доступ root
        $this->permissionService->checkRoot($user);
        $step = (new AdminServices())->getCountPaginate();

        // есть поиск
        if (isset($index['filter']) && gettype($index['filter']) == 'string') {
            $pagination = $this->search($index, $step);
        }
        else {
            // сортировка по смежной таблице
            if( $index['sort'] == 'attractions' ){
                // выдать данные локаций по сортировке смежной таблицы аттракционов
                $coll = Location::leftJoin('attractions', 'locations.id', '=', 'attractions.location_id');
                $pagination = $this->insertSelectRaw($coll, $index, $step);
            }
            else{
                $coll = Location::where('id', '>', 0)
                    ->with('attractions', 'user');
                $pagination = $this->insertSort($coll, $index)->paginate($step);
            }
        }

        // пересобрать данные
        return $this->rebuildData($pagination);
    }


    // PRIVATE
    // >>> фильтр поиска
    private function search($index, $step){

        $arrValue = [ 'id', 'surname', 'title' ];

        // перебрать возможные поля
        foreach ($arrValue as $key => $cell){

            // поиск по id локации
            if ($cell == 'id') {
                // поиск по location_id
                $pagination = Location::where('id', $index['filter'])
                    ->with('attractions', 'user')
                    ->paginate($step);
            }
            // поиск по title локации
            elseif($cell == 'title') {
                // сортировка по смежной таблице
                if( $index['sort'] == 'attractions' ){
                    // выдать данные локаций по сортировке смежной таблицы аттракционов
                    $coll = Location::leftJoin('attractions', 'locations.id', '=', 'attractions.location_id')
                        ->where('locations.title', 'like', $index['filter'] . '%');
                    $pagination = $this->insertSelectRaw($coll, $index, $step);
                }
                else{
                    $coll = Location::where('title', 'like', $index['filter'] . '%')
                        ->with('attractions', 'user');
                    $pagination = $this->insertSort($coll, $index)->paginate($step);
                }
            }
            // поиск по фамилии владельца
            elseif($cell == 'surname') {
                // выдать данные локаций по сортировке смежной таблицы аттракционов
                $coll = Location::leftJoin('users', 'locations.user_id', '=', 'users.id')
                    ->leftJoin('attractions', 'locations.id', '=', 'attractions.location_id')
                    ->where('users.surname', 'like', $index['filter'] . '%');
                $pagination = $this->insertSelectRaw($coll, $index, $step);
            }


            // выборка состоялась
            if (collect($pagination)['total'] != 0) {
                return $pagination;
            }
        }

        return $pagination;
    }

    // >>> пересобрать данные
    private function rebuildData($pagination){
        $arrLocations = [];
        $pagination = collect($pagination);

        foreach ($pagination['data'] as $key => $obj) {
            array_push($arrLocations, [
                'location_id' => $obj["id"],
                'title' => $obj["title"],
                'address' => [
                    'country' => $obj["country"],
                    'region' => $obj["region"],
                    'city' => $obj["city"],
                    'street' => $obj["street"],
                ],
                'first_name'=>$obj["user"]['first_name'],
                'middlename'=>$obj["user"]['middlename'],
                'surname'=>$obj["user"]['surname'],
                'email'=>$obj["user"]['email'],
                'count_attractions' => is_int($obj["attractions"]) ? $obj["attractions"] : count($obj["attractions"]),
                'activation' => $obj["activation"],
                'working_hours' => $obj["working_hours"],
            ]);
        }

        $pagination['data'] = $arrLocations;

        return $pagination;
    }

    // >>> вставка сортировки
    private function insertSort($coll, $index){
        // указана сортировка и направление
        if(!is_null($index['sort']) && !is_null($index['operator'])){
            $coll = $coll->orderBy($index['sort'], $index['operator']);
        }

        return $coll;
    }

    // >>> вставка сортировки по смежной таблице
    private function insertSelectRaw($coll, $index, $step){
        return $coll->selectRaw("locations.*, count(attractions.location_id) AS `attractions`")
            ->with('user')
            ->groupBy('locations.id')
            ->orderBy('attractions', $index['operator'])
            ->paginate($step);
    }

    // подсчет игроков в этом дне
    private function countingPlayersMonth($all_gamer, $arr_id){
        $obj = new \stdClass();

        foreach ($all_gamer as $key => $gamer){
            // игра на этой локации
            if (in_array($gamer->attraction_id, $arr_id)) {
                // вернуть строку в виде год, месяц
                $string_data = $this->carbonService->getCarbonYearMonth($gamer->updated);
                if(!isset($obj->$string_data)){
                    $obj->$string_data = 1;
                }
                else{
                    $obj->$string_data = $obj->$string_data + 1;
                }
            }
        }

        return $obj;
    }

}
