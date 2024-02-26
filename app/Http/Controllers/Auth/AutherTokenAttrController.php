<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\UserException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Session\DestroySessionRequest;
use App\Http\Requests\User\UserDataRequest;
use App\Http\Traits\AuthTraite;
use App\Services\HeaderCheckTokenAttractionServices;
use App\Services\Permissions\PermissionsSessionServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class AutherTokenAttrController extends Controller {

    use AuthTraite;

    protected $access_attraction;
    protected $service;


    public function __construct(Request $req) {
        // проверяет токен аттракциона в header и ищет его в таблице аттракцион
        if(!$this->access_attraction = (new HeaderCheckTokenAttractionServices())->checkToken($req)){
            // в другом случае авторизует по bearer token
            $this->middleware('auth:api');
        }

        // доступы
        $this->service = new PermissionsSessionServices();
    }

// ===============================================================
// ===== выбрать юзера
    /**
     * выбрать данные юзера
     * доступы token-attr
     * POST  /api/user_data
     * Header - Authorization Token
     * admin_id
     * attraction_id
     * user_id
     * @param UserDataRequest $request
     * @return
     * @throws \App\Exceptions\UserException
     */
    public function userData(UserDataRequest $request) {

        $index = $request->validated();

    return $this->getUser($index['user_id']);
    }




}
