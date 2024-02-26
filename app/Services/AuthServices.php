<?php
namespace App\Services;

use App\Exceptions\UserException;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;

class AuthServices {

    // ===================================================
    // ===================================================
    // регистрация пользователя
    public function signupAttempt($data){
        $data['password'] = Hash::make($data['password']);

        try {
            $user = (new UserRepository)->create([
                'nickname'=>$data['nickname'],
                'email'=>$data['email'],
                'password'=>$data['password'],
            ]);
        } catch (\Exception $e) {
            throw new UserException(config('game.custom_value.ex.2'), 3);
        }

        return $user;
    }

    //    ===================================================
    //    ===================================================
    // генерация unique кода для регистрации
    public function generateCode($length = 10) {
        $num = range(0, 9);
        $alf = range('a', 'z');
        $_alf = range('a', 'z');
        $symbols = array_merge($num, $alf, $_alf);
        shuffle($symbols);
        $code_array = array_slice($symbols, 0, (int)$length);
        $code = implode("", $code_array);

        return $code;
    }





}
