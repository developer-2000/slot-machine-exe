<?php
namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\Auth\ContinueSignUpAdminRequest;
use App\Http\Requests\Admin\Auth\SignUpAdminRequest;
use App\Http\Requests\Admin\Auth\UpdateAdminRequest;
use App\Services\Admin\AuthAdminServices;

class AuthAdminController extends BaseApiController {

    protected $authService;

    public function __construct() {
        parent::__construct();
        $this->authService = new AuthAdminServices();
    }

    /**
     * доступ root
     * зарегистрировать Admin , выслать сообщение на Email для продолжения регистрации админа
     * POST URL - /admin/auth/signup_admin
     * Header - Authorization Token
     * @param SignUpAdminRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\UserException
     */
    public function signupAdmin(SignUpAdminRequest $request) {
        $index = $request->validated();
        $user = $this->authService->signupAdmin($index, $this->user);

        return $this->getResponse([
            'status' => 'success',
            'message' => $user
        ], 201);
    }

    /**
     * продолжение регистрации админа
     * POST URL - /admin/auth/continue_signup_admin
     * Header - Authorization Token
     * @param ContinueSignUpAdminRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function continueSignup(ContinueSignUpAdminRequest $request) {
        $index = $request->validated();
        $bool = $this->authService->continueSignup($index);

        if(is_string($bool)){
            return $this->getErrorResponse($bool);
        }

        return $this->getResponse([
            'status' => 'success',
            'message' => config('game.custom_value.auth.1')
        ], 201);
    }

    /**
     * доступ root или сам админ
     * обновить админа
     * POST URL - /admin/auth/update_admin
     * Header - Authorization Token
     * @param UpdateAdminRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateAdmin(UpdateAdminRequest $request) {
        $index = $request->validated();
        $bool = $this->authService->updateAdmin($index, $this->user);

        if(!$bool){
            return $this->getErrorResponse($bool);
        }

        return $this->getResponse([
            'status' => 'success',
            'message' => $bool
        ], 201);
    }

}
