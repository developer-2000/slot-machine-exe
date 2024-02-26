<?php


namespace App\Services\Permissions;

use App\Exceptions\UserException;
use App\Repositories\AttractionRepository;
use App\Repositories\LocationRepository;

class PermissionsLocationServices {


    // ========================================
    // root или владелец локации
    public function accessLocation($access, $index) {

        if($access){
            if ($attr = (new LocationRepository())->whereArrFirst([
                'id'=>$index['location_id'],
                'user_id'=>$index['admin_id'],
            ]) ){
                return true;
            }
        }

        throw new UserException(config('game.custom_value.ex.7'), 2);
    }


























}
