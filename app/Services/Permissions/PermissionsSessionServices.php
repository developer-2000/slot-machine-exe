<?php


namespace App\Services\Permissions;

use App\Exceptions\UserException;
use App\Models\User;
use App\Repositories\AttractionRepository;
use App\Repositories\LocationRepository;
use App\Repositories\PermissionsRepository;
use App\Repositories\SessionRepository;
use App\Repositories\UserPermissionRepository;
use App\Repositories\UserRoleRepository;
use Illuminate\Support\Facades\Auth;

class PermissionsSessionServices {

    static $roles;
    static $permissions;
    static $user_permissions;

    public function __construct() {
        self::$roles = new UserRoleRepository();
        self::$permissions = new PermissionsRepository();
        self::$user_permissions = new UserPermissionRepository();
    }


    // ========================================
    // доступы к аттракциону - аттракцион, root для admin или admin для себя
    public function attractionRootOrAdmin($access, $arr) {

        $auth = Auth::user();

        if($access){
            if ($attr = (new AttractionRepository())->whereArrFirst([
                'id'=>$arr['attraction_id'],
                'user_id'=>$arr['admin_id'],
                'access_token'=>$access->access_token,
            ]) ){
                return true;
            }
        }
        else{
            $attraction1 = (new AttractionRepository())->whereArrFirst([
                'id'=>$arr['attraction_id'],
                'user_id'=>$arr['admin_id'],
            ]);
            $attraction2 = (new AttractionRepository())->whereArrFirst([
                'id'=>$arr['attraction_id'],
                'user_id'=>$auth->id,
            ]);

            if ( // я root и аттракцион админа
                array_search('developer', $auth->access) !== false && !is_null($attraction1) ||
                // я admin и аттракцион мой
                (array_search('attraction', $auth->access) !== false && !is_null($attraction2) )
            ){
                return true;
            }
        }

    throw new UserException(config('game.custom_value.ex.7'), 2);
    }

    // ========================================
    // доступы к сессии - аттракцион, root для admin или admin для себя
    public function sessionRootOrAdmin($access, array $arr) {

        $auth = Auth::user();

        if($access){
            if ($session = (new SessionRepository())->whereArrAttraction([
                'attraction_id'=>$arr['attraction_id'],
                'session_token'=>$arr['session_token'],
            ]) ){
                // параметры переданы правильно
                if($session->attraction->user_id == $arr['admin_id']){
                    return true;
                }
            }
        }
        else{
            $session = (new SessionRepository())->whereArrAttraction([
                'attraction_id'=>$arr['attraction_id'],
                'session_token'=>$arr['session_token'],
            ]);

            if ( // я root и аттракцион админа
                array_search('developer', $auth->access) !== false && $session->attraction->user_id == $arr['admin_id'] ||
                // я admin и аттракцион мой
                (array_search('attraction', $auth->access) !== false && $auth->id == $session->attraction->user_id)
            ){
                return true;
            }
        }

        throw new UserException(config('game.custom_value.ex.7'), 2);
    }

    // ========================================
    // доступы к сессии - только игрок
    public function sessionGamer(array $arr) {

        $auth = Auth::user();

            $session = (new SessionRepository())->whereArrFirst([
                'id'=>$arr['session_id'],
                'session_token'=>$arr['session_token'],
            ]);

        // я gamer и переданый id = моему id и в сессии я есть
        if (
            array_search('user', $auth->access) !== false &&
            $auth->id == $arr['gamer_id'] &&
            $session
        ) {
            return true;
        }

    throw new UserException(config('game.custom_value.ex.7'), 2);
    }

    // ========================================
    // Я игрок и переданый id мой
    public function checkGamerId(array $arr) {

        $auth = Auth::user();

        // выбрать сессию, игроки в ней, атракцион-локация-статистика локации
        $session = (new SessionRepository())->showSessionUser($arr['session_id']);
        $ids = [];

        // 1 выбрать id игроков сессии
        foreach ($session->sess_user_many as $key => $user){
            array_push($ids, $user->user_id);
        }

        // я gamer и переданый id = моему id и в сессии я есть
        if (
            array_search('user', $auth->access) !== false &&
            $auth->id == $arr['gamer_id'] &&
            in_array($arr['gamer_id'], $ids)
        ) {
            return true;
        }


    throw new UserException(config('game.custom_value.ex.7'), 2);
    }

    // ========================================
    // Я игрок и переданый id мой
    public function checkAuthOrTokenAttr($access, array $arr) {

        $auth = Auth::user();

        if($access){
            if ($attr = (new AttractionRepository())->whereArrFirst([
                'id'=>$arr['attraction_id'],
                'user_id'=>$arr['admin_id'],
                'access_token'=>$access->access_token,
            ]) ){
                return true;
            }
        }
        elseif (!is_null($auth)){
            return true;
        }

        throw new UserException(config('game.custom_value.ex.7'), 2);
    }







    // >>> ========================================
    // масив всех доступов юзера (developer, attraction, user)
    public function arrPermissionsUser($user_id){
        $array_permis1 = [];
        $array_permis2 = [];

        // 1 выбрать все имена доступов юзера
        // если есть роли - масив всех id ролей юзера
        $arr_role_id  = self::$roles->getRolesIdForUser($user_id);
        // масив - выбрать названия доступов по связке role_id->permision_id
        $array_permis1 = self::$permissions->getNameRolePermissions($arr_role_id);

        // 2 если в таблицу user_permission был добавлен юзер минуя добавления роли
        // если есть юзер в таблице привилегий - масив - все id доступов юзера
        if ($permiss_id = self::$user_permissions->getPermissionsIdForUserId($user_id)){
            // масив - выбрать названия доступов по масиву id permission
            $array_permis2 = self::$permissions->getNamePermissions($permiss_id);
        }

        // масив доступов юзера
        return array_unique(array_merge($array_permis1, $array_permis2));
    }





















}
