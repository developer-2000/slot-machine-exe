<?php

namespace App\Http\Traits;

use App\Events\ChangeUserNicknameEvent;
use App\Exceptions\UnityException;
use App\Exceptions\UserException;
use App\Repositories\SessionUserRepository;
use App\Repositories\UserRepository;
use App\Services\PermissionsServices;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

trait AuthTraite {

    // ===================================================
    // ===================================================
    // Создать Token для авторизованного
    protected function createToken($user, $request) {

        // если есть Удалить Token юзера
        $this->deleteToken($user);

        $token = $user->createToken('Personal Access Token');
        // if select "Remember me" in checkbox in the login form
        $token->token->expires_at = $request->remember_me ?
            Carbon::now()->addMonth() :
            Carbon::now()->addDay();

        $token->token->save();

    return $token;
    }

    // ===================================================
    // ===================================================
    // Удалить Token юзера
    protected function deleteToken($user) {
        DB::table('oauth_access_tokens')->where('user_id', $user->id)->delete();
    }

    // ===================================================
    // ===================================================
    // смена пароля
    public function changePasswordTrait($index){

        $user = Auth::user();
        $bool = [false, false];

        if(Hash::check($index['old_password'],$user->password)) {
            $bool[0] = true;

            try {
                (new UserRepository)->update( $user->id,
                    ['password' => Hash::make($index['new_password'])]
                );
                $bool[1] = true;
            } catch (\Exception $e) {
                throw new UserException(config('game.custom_value.auth.5'), 2);
            }
        }

    return [
        'old_password_is_correct'=>$bool[0],
        'password_change'=>$bool[1],
    ];
    }

    // ===================================================
    // ===================================================
    // обновить данные юзера
    public function changeDataTrait($index){
        $user = Auth::user();
        $bool = false;

        if((new UserRepository)->update( $user->id, $index)) {
            $bool = true;
        }

        if(isset($index['nickname'])){
            // находится ли user в сессии
            if($session = (new SessionUserRepository())->whereArrFirst([ 'user_id' => $user->id ])){
                // отправка в аттракцион новый nickname
                event(new ChangeUserNicknameEvent([
                        $session->session_id,
                        $user->id,
                        $index['nickname'],
                    ]
                ));
            }
        }

    return $bool;
    }

    // ===================================================
    // ===================================================
    // авторизация пользователя
    public function signinAttempt($req){
        if (!Auth::attempt($req)) {
            throw new UnityException(config('game.custom_value.ex.3'), 1);
        }

        // добавить юзеру смежные таблицы
        $user = $this->dataUser(Auth::user()->id);

    return $user;
    }

    // ===================================================
    // ===================================================
    // выбрать юзера
    public function getUser($user_id){

    // добавить юзеру смежные таблицы
    return $this->dataUser($user_id);
    }

    // ===================================================
    // ===================================================
    // смежные таблицы юзера
    private function dataUser($user_id){

        // 1 выбрать юзера и его картинки
        $user = (new UserRepository)->getUserImage($user_id);

        // 2 добавить к обьекту доступы юзера
        $user->access = (new PermissionsServices())->arrPermissionsUser($user_id);

        // это admin
        if (array_search('attraction', $user->access) !== false){
            // 3 добавить локации и атракционы
            $user->location_user = (new UserRepository)->locationAttractions($user_id);
            // 4 добавить лицензия админа
            $user->license = (new UserRepository)->license($user_id);
        }
    return $user;
    }

    // ===================================================
    // ===================================================
    // обновить статус активности
    private function activationUpdateTrait($index){

        $bool = false;
        $this->permissionService->checkRoot(Auth::user());


        if((new UserRepository)->update( $index['user_id'], [
            'activation'=>$index['activation']
        ])) {
            $bool = true;
        }

    return $bool;
    }

}
