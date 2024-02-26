<?php

namespace App\Services\Admin\Language;


use App\Http\Traits\Admin\BaseRestTrait;

class LanguageServices {

    use BaseRestTrait;

    protected $arr_language;

    public function __construct() {
        $this->arr_language = config('game.language.prefixes');
    }

    // установить язык интерфейса
    // ========================================
    public function setLanguage($prefix){
        // доступность языка в системе
        if (in_array( $prefix, $this->arr_language )) {
            session(['language_prefix' => $prefix]);
            return true;
        }
    return false;
    }

    // выбрать язык интерфейса
    // ========================================
    public function getLanguage(){

        $default_lang = config('app.locale');        // ru
        $session_lang = session('language_prefix');  // null или ru

    return !is_null($session_lang) ? $session_lang : $default_lang;
    }

}
