<?php
namespace App\Services\Admin;

use App\Facades\MyFunctions;
use App\Models\Attraction;
use App\Repositories\AttractionRepository;
use App\Services\Permissions\PermissionsAdminPanelServices;

class AttractionAdminServices {

    protected $permissionService;
    protected $attractionRepository;

    public function __construct() {
        $this->permissionService = new PermissionsAdminPanelServices();
        $this->attractionRepository = new AttractionRepository();
    }

    // ========================================
    // все аттракционы с локациями в Пагинации
    public function getAllAttractions($index, $user) {
        // доступ root
        $this->permissionService->checkRoot($user);
        $step = (new AdminServices())->getCountPaginate();
        $pagination = null;

        // есть фильтр
        if (isset($index['filter']) && gettype($index['filter']) == 'string') {
            $pagination = $this->search($index, $step);
        }
        else {
            // есть сортировка
            if(!is_null($index['sort']) && !is_null($index['operator'])){
                $pagination = Attraction::where('id', '>', 0)
                    ->orderBy($index['sort'], $index['operator'])
                    ->paginate($step);
            }
            else{
                $pagination = Attraction::where('id', '>', 0)
                    ->paginate($step);
            }
        }

        // пересобрать данные
        return $this->rebuildData($pagination);
    }

    // ========================================
    // создать аттракцион
    public function createAttraction($index, $user) {
        // доступ root
        $this->permissionService->checkRoot($user);

        // создать атракцион
        $attraction = $this->attractionRepository->create([
            'user_id' => $index['user_id'],
            'location_id' => $index['location_id'],
            'title' => $index['title'],
            'access_token' => MyFunctions::generateStr(40)
        ]);

        return $attraction;
    }


    // PRIVATE
    // >>> фильтр поиска
    private function search($index, $step){
        $arrValue = ['id'];

        // перебрать возможные поля
        foreach ($arrValue as $key => $value){
            // есть сортировка
            if(!is_null($index['sort']) && !is_null($index['operator'])){
                $pagination = Attraction::where($value, $index['filter'])
                    ->orderBy($index['sort'], $index['operator'])
                    ->paginate($step);
            }
            else{
                $pagination = Attraction::where($value, $index['filter'])
                    ->paginate($step);
            }
        }

        return $pagination;
    }

    // >>> пересобрать данные
    private function rebuildData($pagination){
        $arrAttractions = [];
        $pagination = collect($pagination);

        foreach ($pagination['data'] as $key => $obj) {
            array_push($arrAttractions, [
                'id' => $obj["id"],
                'location_id' => $obj["location_id"],
                'activation' => $obj["activation"],
            ]);
        }

        $pagination['data'] = $arrAttractions;

        return $pagination;
    }

}
