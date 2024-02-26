<?php


namespace App\Services;

use App\Exceptions\UserException;
use App\Models\User;
use App\Repositories\AttractionRepository;
use App\Repositories\LocationRepository;
use App\Repositories\PermissionsRepository;
use App\Repositories\UserPermissionRepository;
use App\Repositories\UserRoleRepository;
use Illuminate\Support\Facades\Auth;

class PermissionsServices {

    static $roles;
    static $permissions;
    static $user_permissions;
    public $attractionRepository;

    public function __construct() {
        self::$roles = new UserRoleRepository();
        self::$permissions = new PermissionsRepository();
        self::$user_permissions = new UserPermissionRepository();
        $this->attractionRepository = new AttractionRepository();
    }

    // ========================================
    // проверка указанного доступа
    public function checkAccess(int $my_id, $transfer_permission) : void {

         // мои доступы
         $arr = $this->arrPermissionsUser($my_id);

         // могу проверить себя на несколько доступов
         if (is_array($transfer_permission)){
             $a = null;
             foreach ($transfer_permission as $key => $value){
                 if (in_array($value, $arr) !== false){
                     $a++;
                 }
             }
             if(is_null($a)){
                 throw new UserException(config('game.custom_value.ex.7'), 2);
             }
         }
         elseif (is_string($transfer_permission) && in_array($transfer_permission, $arr) === false){
            throw new UserException(config('game.custom_value.ex.7'), 2);
         }
    }

    // ========================================
    // Я root или админ и переданый id мой
    public function checkOwnerAdmin($auth, int $transfer_id) {
        if (
            in_array('developer', $auth->access) ||
            ( in_array('attraction', $auth->access) && $auth->id == $transfer_id )
        ){ return true; }

        throw new UserException(config('game.custom_value.ex.7'), 2);
    }

    // ========================================
    // Я игрок и переданый id мой
    public function checkGamerId($auth, int $transfer_id) {
        // я не gamer или id не принадлежит мне
        if ( in_array('user', $auth->access) === false || $auth->id != $transfer_id ) {
            throw new UserException(config('game.custom_value.ex.7'), 2);
        }
    }

    // ========================================
    // root или владелец локации
    public function rootOrAdminLocation($auth, int $transfer_id) {
        // мои локации
        $forMyId = (new LocationRepository())->owner($auth->id, $transfer_id);

        if ( in_array('developer', $auth->access) || count($forMyId) ) {
            return true;
        }

        throw new UserException(config('game.custom_value.ex.7'), 2);
    }

    // ========================================
    // root или владелец аттракциона
    public function rootOrAdminAttraction($auth, int $transfer_id) {
        // мои аттракционы
        $forMyId = $this->attractionRepository->owner($auth->id, $transfer_id);

        if ( in_array('developer', $auth->access) || count($forMyId) ){
            return true;
        }

        throw new UserException(config('game.custom_value.ex.7'), 2);
    }

    // ========================================
    // root или владелец id
    public function rootOrOwner($auth, int $transfer_id) {
        // не root и id не равны
        if ( in_array('developer', $auth->access) === false && $auth->id != $transfer_id) {
            throw new UserException(config('game.custom_value.ex.7'), 2);
        }
    }

    // ========================================
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
