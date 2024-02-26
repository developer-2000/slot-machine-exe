<?php

namespace App\Services\Permissions;

use App\Exceptions\UserException;

class PermissionsAdminPanelServices {

    // ========================================
    // доступ администратору
    public function checkAdmin($auth) {
        if($auth->access[0] == 'attraction'){
            return true;
        }

    throw new UserException(config('game.custom_value.ex.7'), 2);
    }

    // ========================================
    // доступ root
    public function checkRoot($auth) {
        if($auth->access[0] == 'developer'){
            return true;
        }

        throw new UserException(config('game.custom_value.ex.7'), 2);
    }

    // ========================================
    // доступ root или владелец
    public function checkRootOrOwner($index, $user) {

        if($user->access[0] == 'developer'){
            return true;
        }
        elseif($user->id == $index['user_id']){
            return true;
        }

        throw new UserException(config('game.custom_value.ex.7'), 2);
    }






















}
