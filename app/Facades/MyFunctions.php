<?php


namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class MyFunctions extends Facade {

    // вернуть имя ключа привязки сервис контейнера
    protected static function getFacadeAccessor() {
        return 'MyFunctions';
    }

}
