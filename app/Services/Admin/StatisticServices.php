<?php
namespace App\Services\Admin;

use App\Exceptions\UserException;
use App\Facades\MyFunctions;
use App\Repositories\AttractionRepository;
use App\Repositories\HistorySessionRepository;
use App\Repositories\LocationRepository;
use App\Repositories\UserRepository;
use App\Services\CarbonServices;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;

class StatisticServices {

    protected $userRepository;
    protected $carbonService;
    protected $attractionService;
    protected $locationRepository;

    public function __construct() {
        $this->userRepository = new UserRepository();
        $this->carbonService = new CarbonServices();
        $this->attractionService = new AttractionServices();
        $this->locationRepository = new LocationRepository();
    }

    // ============================================
    // кол-во не уникальных игроков / кол-во дней
    public function averageAmountPeriod(string $method){

        // все игроки периода времени сессий
        $all_gamer = $this->setArrGamers($method);
        $average = null;

        // если есть игроки в периоде
        if(count($all_gamer)){
            // посчитать кол-во игроков и дней
            $all = $this->setCountValue($all_gamer, $method);

            // среднее в день
            $average = round( ($all[0] / $all[1]) );
            // среднее в месяц
            if($method == 'subYears'){
                $average = round($all[0] / 12);
            }
        }

        return $average;
    }

    // ============================================
    // средний возраст игроков
    public function averageAge(){

        $gamers = (new UserRepository())->getGamers();
        $nowDate = Carbon::now()->format('Y');
        $count_gamer = 0;
        $sum_age = 0;

        foreach ($gamers as $key => $gamer){
            if(!is_null($gamer->date_of_birth)){
                $sum_age += ( $nowDate - $gamer->date_of_birth->format('Y') );
                $count_gamer++;
            }
        }

        return round(( $sum_age / $count_gamer ));
    }

    // ============================================
    // среднее количество игроков в сессии
    public function averagePlayersSession(string $method){

        // все игроки периода времени сессий
        $all_gamer = $this->setArrGamers($method);
        $id = 0;
        $count_session = 0;
        $count_gamer = 0;
        $count = null;

        // если есть игроки в периоде
        if(count($all_gamer)){
            foreach ($all_gamer as $key => $obj){
                if($obj->id != $id && $obj->parent_id == 0){
                    $count_session++;
                    $id = $obj->id;
                }
                $count_gamer++;
            }
            $count = round( ($count_gamer / $count_session) );
        }

    return $count;
    }

    // ============================================
    // Минимальное количество аттракционов на локации
    public function minimumAttractionsLocation($locations){
        $obj = new \stdClass();
        $obj->attraction_count = 999;

        foreach ($locations as $key => $location){
            if ($location->attractions->count() < $obj->attraction_count ){
                $obj->location_id = $location->id;
                $obj->location_title = $location->title;
                $obj->attraction_count = $location->attractions->count();
            }
        }

        return $obj;
    }

    // ============================================
    // Максимальное количество аттракционов на локации
    public function maximumAttractionsLocation($locations){
        $obj = new \stdClass();
        $obj->attraction_count = 0;

        foreach ($locations as $key => $location){
            if ($location->attractions->count() > $obj->attraction_count ){
                $obj->location_id = $location->id;
                $obj->location_title = $location->title;
                $obj->attraction_count = $location->attractions->count();
            }
        }

        return $obj;
    }

    // ============================================
    // Минимальное количество игроков на локации в сутки
    public function minimumPlayersLocationPeriod(string $method){

//        Session::forget('players_location_period_'.$method);
        if (Session::has('players_location_period_'.$method)) {
            return Session::get('players_location_period_'.$method)[0];
        }

        $arr_count_locations = [];
        // все игроки периода времени сессии
        $all_gamer = $this->setArrGamers($method);
        // уникальные аттракционы с локациями периода сессии
        $arr_attractions = $this->getUniqueAttractions($all_gamer);
        // уникальные локации масива аттракционов
        $arr_locations = $this->attractionService->getUniqueLocations($arr_attractions);

        // если нет игроков в периоде
        if(!count($all_gamer)){
            return null;
        }

        // кол-во игроков на локации
        foreach ($all_gamer as $key => $gamer){
            // поиск локации через attraction_id у игрока
            if($loca = $this->searchLocation($gamer, $arr_attractions, $arr_locations)){

                // *** подсчет игр на локациях
                // поиск имеющейся записи
                $location = array_filter($arr_count_locations, function($location) use ($loca) {
                    return ($loca->id == $location['location_id']);
                });

                // создать новую запись
                if(!$location){
                    array_push($arr_count_locations, [
                        'location_id' => $loca->id,
                        'location_title' => $loca->title,
                        'count' => 1,
                    ]);
                }
                // обновить запись
                elseif($location){
                    foreach ($arr_count_locations as $key2 => $location){
                        if($location['location_id'] == $loca->id){
                            $arr_count_locations[$key2]['count'] = $location['count'] + 1;
                        }
                    }
                }
            }
            else{
                throw new UserException(config('game.custom_value.statistic.2'), 3);
            }
        }

        // сортировка от меньшего к большему в многомерном масиве
        $arr_count_locations = MyFunctions::sortMinMax($arr_count_locations);

        // статистика
        $count = (count($arr_count_locations)-1);

        // среднее в день
        if($method == 'subMonths'){
            $arr_count_locations[0]['count'] = round( $arr_count_locations[0]['count'] / 30 );
            $arr_count_locations[$count]['count'] = round( $arr_count_locations[$count]['count'] / 30 );
        }
        // среднее в месяц
        if($method == 'subYears'){
            $arr_count_locations[0]['count'] = round( $arr_count_locations[0]['count'] / 12 );
            $arr_count_locations[$count]['count'] = round( $arr_count_locations[$count]['count'] / 12 );
        }

        Session::put('players_location_period_'.$method, $arr_count_locations);

        return $arr_count_locations[0];
    }

    // ============================================
    // Максимальное количество игроков на локации в сутки
    public function maximumPlayersLocationPeriod(string $method){
        // сохраненная сессия из метода minimumPlayersLocationDay
        if (Session::has('players_location_period_'.$method)) {
            $arr = Session::get('players_location_period_'.$method);
            return $arr[ (count($arr)-1) ];
        }

        return null;
    }



    // PRIVATE
    // ============================================
    // все игроки периода времени сессий
    private function setArrGamers(string $method){

        if (Session::has('set_arr_gamers_'.$method)) {
            return Session::get('set_arr_gamers_'.$method);
        }

        // вычесть период
        $date = Carbon::now()->$method(1);
        // выборка периода
        $period = (new HistorySessionRepository()) ->whereAll([ ['updated', '>=', $date] ]);

        $all_gamer = [];

        // сформировать масив игроков периода времени со своей статистикой
        foreach ($period as $key => $obj){
            if (!is_null($obj->result)){
                // развернуть игроков со своей статистикой
                foreach ($obj->result['stats'] as $key2 => $stat){
                    $gamer = clone $obj;
                    $gamer['result'] = $stat;
                    array_push($all_gamer, $gamer);
                }
            }
        }

        Session::put('set_arr_gamers_'.$method, $all_gamer);

        return $all_gamer;
    }

    // ============================================
    // посчитать кол-во игроков и дней
    private function setCountValue(array $all_gamer, string $method){

        if (Session::has('set_count_value_'.$method)) {
            return Session::get('set_count_value_'.$method);
        }

        // вычесть период
        $date = Carbon::now()->$method(1);
        $count_gamer = 0;
        $count_day = 0;

        foreach ($all_gamer as $key => $obj){
            // проверка дня периода
            if(
                $this->carbonService->parseCarbon($this->carbonService->parseCarbon($date)->format('d.m.Y')) <
                $this->carbonService->parseCarbon($this->carbonService->parseCarbon($obj->updated)->format('d.m.Y'))
            ){
                $count_day++;
                // добавить день
                $date->addDays(1);
            }
            $count_gamer++;
        }

        Session::put('set_count_value_'.$method, [$count_gamer, $count_day]);

        return [$count_gamer, $count_day];
    }

    // ============================================
    // уникальные аттракционы с локациями периода сессии
    private function getUniqueAttractions($all_gamer){
        $attraction_id = [];

        // уникальные id аттракционов
        foreach ($all_gamer as $key => $obj){
            if (!in_array($obj->attraction_id, $attraction_id)) {
                array_push($attraction_id, $obj->attraction_id);
            }
        }

        return (new AttractionRepository())->whereInAttractionLocation('id', $attraction_id);
    }

    // ============================================
    // поиск локации через attraction_id у игрока
    private function searchLocation($gamer, $arr_attractions, $arr_locations){
        $attraction = false;
        $location = false;

        try {
            foreach ($arr_attractions as $key => $attr){
                if($attr->id == $gamer->attraction_id){
                    $attraction = $attr;
                    break;
                }
            }

            if($attraction){
                foreach ($arr_locations as $key => $loc){
                    if($loc['id'] == $attraction['location_id']){
                        $location = $loc;
                        break;
                    }
                }
            }
        } catch (\Exception $e) {
            throw new UserException(config('game.custom_value.statistic.1'), 3);
        }

        return $location;
    }

}
