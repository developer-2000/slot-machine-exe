<?php

namespace App\Services\Admin;

use App\Models\License;
use App\Repositories\LicenseRepository;
use App\Services\CarbonServices;
use App\Services\LicenseServices;
use App\Services\Permissions\PermissionsAdminPanelServices;

class LicenseAdminServices {

    protected $permissionService;
    protected $carbonService;
    protected $licenseRepository;
    protected $step;
    protected $adminService;
    protected $model;

    public function __construct() {
        $this->permissionService = new PermissionsAdminPanelServices();
        $this->carbonService = new CarbonServices();
        $this->licenseRepository = new LicenseRepository();
        $this->adminService = new AdminServices();
        $this->model = new License();
    }

    // ========================================
    // все аттракционы с локациями в Пагинации
    public function getLicenses($index, $user) {

        // доступ root
        $this->permissionService->checkRoot($user);
        // шаг пагинации
        $this->step = $this->adminService->getCountPaginate();
        // проверка существования параметров запроса
        if(is_string($check = $this->adminService->checkParameters($index))){
            return $check;
        }

        $index['add_filter'] = json_decode($index['add_filter']);

        // 1 есть поиск
        if (!is_null($index['filter'])) {
            $pagination = $this->search($index);
        }
        // 2 нет поиска
        else {
            // 2,1 есть сортировка
            if(!is_null($index['sort']) && !is_null($index['operator'])){
                // 2,2,1 есть Доп поиск
                if(!is_null($index['add_filter'])){
                    $coll = $this->addSearch($index)->with('user', 'attractions');
                    $pagination = $this->sortingOptions($index, $coll)->paginate($this->step);
                }
                // 2,2,2 нет Доп поиска
                else{
                    $coll = License::with('user', 'attractions');
                    $pagination = $this->sortingOptions($index, $coll) ->paginate($this->step);
                }
            }
            // 2,2 нет сортировки
            else{
                // 2,2,1 есть Доп поиск
                if(!is_null($index['add_filter'])){
                    $pagination = $this->addSearch($index)
                        ->with('user', 'attractions')
                        ->paginate($this->step);
                }
                // 2,2,2 нет Доп поиска
                else{
                    $pagination = License::with('user', 'attractions') ->paginate($this->step);
                }
            }
        }

        // пересобрать данные
        return $this->rebuildData($pagination);
    }


    // PRIVATE
    // >>> фильтр поиска
    private function search($index){

        if(!is_null($index['add_filter'])){
            $this->model = $this->addSearch($index);
        }

        $coll = $this->model->leftJoin('users', 'licenses.user_id', '=', 'users.id')
            ->where("users.first_name", 'like', "{$index['filter']}%")
            ->orWhere("users.surname", 'like', "{$index['filter']}%")
            ->orWhere("users.middlename", 'like', "{$index['filter']}%")
            ->orWhere("users.email", 'like', "{$index['filter']}%")
            ->orWhere("users.tell", 'like', "{$index['filter']}%")
            ->with('user', 'attractions')
            ->select('licenses.*');

        return $this->sortingOptions($index, $coll)->paginate($this->step);
    }

    // >>> фильтр Доп поиска
    private function addSearch($index){

        // поиск по дате за конкретный день
        if(isset($index['add_filter']->date)){
            if($date = $this->carbonService->checkValidStringData($index['add_filter']->date)){
                $start = $this->carbonService->getCarbonYearMonthDay($date)->toDateTimeString();
                $finish = $this->carbonService->getCarbonYearMonthDay($date)->addDays(1)->toDateTimeString();

                $this->model = $this->model->whereDate('month', '>=', $start) ->whereDate('month', '<=', $finish);
            }
        }
        // поиск по дате за последнюю неделю, месяц
        if(isset($index['add_filter']->period)){
            if($date = $this->carbonService->checkValidStringData($index['add_filter']->period)){
                $this->model = new License();
                $this->model = $this->model->whereDate('month', '>=', $date);
            }
        }
        // интервал цены
        if(isset($index['add_filter']->number)){
            $num = $index['add_filter']->number;

            // коррекция цифр
            // существование
            $num->min = isset($num->min) ? $num->min : 0;
            $num->max = isset($num->max) ? $num->max : 9999999999;
            // целочисленное
            $num->min = !is_int($num->min) ? 0 : $num->min;
            $num->max = !is_int($num->max) ? 9999999999 : $num->max;
            // отношение сторон
            if($num->max < $num->min){ $num->max = $num->min; }
            elseif($num->min > $num->max){ $num->min = $num->max; }

            // выбрать в интервале оплаты
            $this->model = $this->model->where('total_payment', '>=', $num->min)
                ->where('total_payment', '<=', $num->max);
        }

        return $this->model;
    }

    // >>> пересобрать данные
    private function rebuildData($pagination){
        $arrAttractions = [];
        $pagination = collect($pagination);

        foreach ($pagination['data'] as $key => $obj) {
            array_push($arrAttractions, [
                'id' => $obj["id"],
                'location_id' => $obj["location_id"],
                'firstname'=>$obj["user"]['first_name'],
                'middlename'=>$obj["user"]['middlename'],
                'surname'=>$obj["user"]['surname'],
                'email'=>$obj["user"]['email'],
                'tell'=>$obj["user"]['tell'],
                'data_last_payment'=>is_null($obj["month"]) ? null : $obj["month"],
                'total_payment'=> intval($obj['total_payment']),
//                'total_payment'=>is_null($obj["request"]) ? null : intval($obj["request"]['transactions'][0]['amount']['total']),
                'count_attractions'=>is_null($obj["attractions"]) ? null : count($obj["attractions"]),
                'payment'=>is_null($obj["request"]) ? null : (new LicenseServices())->checkPayLocation($obj),
            ]);
        }

        $pagination['data'] = $arrAttractions;

        return $pagination;
    }

    // >>> варианты сортировки
    private function sortingOptions($index, $coll){
        if($index['sort'] == 'total_payment'){
            return $coll->orderBy('total_payment', $index['operator']);
        }
        elseif($index['sort'] == 'data_last_payment'){
            return $coll->orderBy('month', $index['operator']);
        }
        // если сортировки нет
        return $coll;
    }

}
