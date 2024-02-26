<?php

namespace App\Services\Admin;

use App\Models\User;
use App\Repositories\UserRepository;
use App\Services\LicenseServices;
use App\Services\Permissions\PermissionsAdminPanelServices;

class ClientsServices {

    protected $permissionService;
    protected $userRepository;
    protected $licenseService;

    public function __construct() {
        $this->permissionService = new PermissionsAdminPanelServices();
        $this->userRepository = new UserRepository();
        $this->licenseService = new LicenseServices();
    }

    // ========================================
    // все админы и данные о лицензии
    public function getClients($index, $user) {
        $step = (new AdminServices())->getCountPaginate();
        // доступ root
        $this->permissionService->checkRoot($user);

        // есть поиск
        if (isset($index['filter']) && gettype($index['filter']) == 'string') {
            $pagination = $this->search($index, $step);
        }
        else {
            // пагинация все админы и лицензии
            $coll = User::leftJoin('users_roles', 'users.id', '=', 'users_roles.user_id')
                ->where('users_roles.role_id', 2)
                ->with('license')
                ->select('users.*');

            $pagination = $this->insertSort($coll, $index)->paginate($step);
        }

        // пересобрать данные
        $pagination = $this->rebuildData($pagination);

        return $pagination;
    }

    // >>>>>>>>>>>>>>>>>>>>>>>>>>>
    // PRIVATE
    // >>> выбрать через фильтер
    private function search($index, $step){

        // поля в которых поиск
        $arrValue = [
            'id', 'first_name', 'surname', 'middlename', 'company'
        ];

        foreach ($arrValue as $key => $cell){
            // цифра
            if ($cell == 'id') {
                $pagination = User::where($cell, $index['filter'])
                    ->with('license')
                    ->paginate($step);
            }
            // строки
            else {
                $coll = User::leftJoin('users_roles', 'users.id', '=', 'users_roles.user_id')
                    ->where('users_roles.role_id', 2)
                    ->where($cell, 'like', $index['filter'] . '%')
                    ->with('license');

                $pagination = $this->insertSort($coll, $index)->paginate($step);
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
        $pagination = collect($pagination);
        $admines = $pagination['data'];
        $administrators = [];

        foreach($admines as $key => $admin){

            array_push($administrators, [
                'id'=> $admin['id'],
                'first_name'=>$admin['first_name'],
                'surname'=>$admin['surname'],
                'middlename'=>$admin['middlename'],
                'company'=>$admin['company'],
                'email'=>$admin['email'],
                'tell'=>$admin['tell'],
                'activation'=> $admin['activation'],
                'licenses'=> $this->rebuildLicense($admin['license']),
            ]);
        }

        $pagination['data'] = $administrators;

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

    // >>> пересобрать лицензии
    private function rebuildLicense($licenses){
        $arr = [];

        foreach($licenses as $key => $license){
            array_push($arr, [
                'license_id'=> $license['id'],
                'paid'=> $this->licenseService->checkPayLocation($license),
            ]);
        }

        return $arr;
    }

}
