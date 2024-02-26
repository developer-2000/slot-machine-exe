<?php
namespace App\Http\Traits\Admin;

trait LanguageTraite {

    // заменить в сессии laravel на указаный язык
    // выбрать и отправить файл интерфейса на указаном языке
    // ========================================
    protected function changeLanguageTraite($index){

        if($this->lang_service->setLanguage($index['language'])){
            app()->setLocale($this->lang_service->getLanguage());
        // отправить файл перевода интерфейса
        return [
            'prefix'=>session('language_prefix'),
            'file'=>__('admin_interface'),
        ];
        }

    return $this->getErrorResponse(config('game.custom_value.language.1'), 403);
    }

    // ========================================
    protected function getLanguageTraite(){

        $prefix = $this->lang_service->getLanguage();

        app()->setLocale($prefix);

    return [
        'prefix'=>$prefix,
        'file'=>__('admin_interface'),
    ];
    }




}
