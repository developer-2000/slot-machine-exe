<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Language\ChangeLanguageRequest;
use App\Http\Traits\Admin\LanguageTraite;
use App\Services\Admin\Language\LanguageServices;
use Illuminate\Http\Request;


class LanguageAdminController extends BaseApiController {

    use LanguageTraite;

    protected $lang_service;


    public function __construct() {
        parent::__construct();
        $this->lang_service = new LanguageServices();
    }

// ======================================================
    // заменить в сессии laravel на указаный язык
    // выбрать и отправить файл интерфейса на указаном языке
    public function changeLanguage(ChangeLanguageRequest $request){

        $index = $request->validated();

        $response = $this->changeLanguageTraite($index);

    return $this->getResponse($response, 201);
    }

// ======================================================
    // выбрать файл интерфейса на языке юзера
    public function getLanguage(Request $request){

        $response = $this->getLanguageTraite();

    return $this->getResponse($response, 201);
    }











}
