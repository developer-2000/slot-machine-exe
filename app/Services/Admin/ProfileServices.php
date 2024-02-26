<?php

namespace App\Services\Admin;

use App\Repositories\LicenseRepository;
use App\Services\LicenseServices;
use App\Services\Permissions\PermissionsAdminPanelServices;

class ProfileServices {

    protected $permissionService;
    protected $licenseRepository;
    protected $licenseService;

    public function __construct() {
        $this->permissionService = new PermissionsAdminPanelServices();
        $this->licenseRepository = new LicenseRepository();
        $this->licenseService = new LicenseServices();
    }

    // ========================================
    // отправить лицензии админа и их локации и их аттракционы
    public function getProfile($user) {
        // доступ админу
        $this->permissionService->checkAdmin($user);
        // лицензии их локации и их аттракционы
        $licenses = $this->licenseRepository->locationAttractions([ 'user_id'=>$user->id ]);
        $data = new \stdClass();

        // данные админа
        $data->user = [
            'id'=> $user->id,
            'email'=> $user->email,
            'nickname'=> $user->nickname,
            'middlename'=> $user->middlename,
            'surname'=> $user->surname,
            'company'=> $user->company,
            'tell'=> $user->tell,
        ];

        // лицензия
        $data->licenses = [];
        foreach($licenses as $key => $lic){
            array_push($data->licenses, [
                'id'=> $lic->id,
                'payment_state' => $this->licenseService->checkPayLocation($lic),
                'payment_date' => $lic->month,
                'count_attractions' => count($lic->location->attractions),
                'license_cost' => $lic->location->price.'$',
                'location' => $lic->location,
            ]);
        }

        return $data;
    }











}
