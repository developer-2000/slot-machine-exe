<?php

namespace App\Http\Middleware;

use App\Exceptions\UserException;
use App\Services\PermissionsServices;
use Closure;
use Illuminate\Support\Facades\Auth;


class CheckPermissions {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $permis = null) {

        $user = Auth::user();

        // 1 Авторизован И заполняет данные о permission
        if (!is_null($user) && is_null($permis)) {
            // масив всех Доступов юзера
            auth()->user()->access = (new PermissionsServices())->arrPermissionsUser($user->id);
        }
        // 2 Авторизован И проверка доступа
        if (!is_null($user) && !is_null($permis)) {
            if (strpos(implode(", ", $user->access), $permis) === false) {
                throw new UserException(config('game.custom_value.ex.6'), 2);
            }
        }
        // 3 НЕ Авторизован Но проверка доступа
        if (is_null($user) && !is_null($permis)) {
            throw new UserException(config('game.custom_value.ex.6'), 2);
        }

    return $next($request);
    }






}
