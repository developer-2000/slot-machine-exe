<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ActivationUserUpdateRequest;
use App\Http\Requests\Auth\ChangePasswordRequest;
use App\Http\Requests\Auth\UpdateDataRequest;
use \App\Http\Requests\Auth\GetUserRequest;
use App\Http\Requests\Auth\SigninRequest;
use App\Http\Requests\Auth\SignupRequest;
use App\Http\Requests\Auth\UpdateUserAboutRequest;
use App\Http\Requests\Auth\UpdateUserAddressRequest;
use App\Http\Requests\Auth\UpdateUserStatisticsRequest;
use App\Http\Traits\AuthTraite;
use App\Repositories\UserRepository;
use App\Repositories\UserRoleRepository;
use App\Services\Admin\AuthAdminServices;
use App\Services\AuthServices;
use App\Services\Permissions\PermissionsAdminPanelServices;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AutherController extends Controller {

    use AuthTraite;

    protected $authService;
    protected $userRoleRepository;
    protected $permissionService;

    public function __construct() {
        $this->authService = new AuthServices();
        $this->userRoleRepository = new UserRoleRepository();
        $this->permissionService = new PermissionsAdminPanelServices();
    }

    // ===================================================
    // ===================================================
    // ===== регистрация
    /**
     * @param $id
     * @param SignupRequest $req
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\UserException
     */
    public function signUp($id, SignupRequest $req) {
        // регистрация
        $user = $this->authService->signupAttempt($req->validated());

        // создать новую связь UserRole для юзера
        $this->userRoleRepository->creatUserRole($user->id, $id);

        return response()->json([
            'code' => 777,
            'status' => 'success',
            'message' => config('game.custom_value.auth.1')
        ], 201);
    }

    // ===================================================
    // ===================================================
    // ===== авторизация user
    public function signIn(SigninRequest $req) {
        // авторизовать
        $user = $this->signinAttempt($req->validated());

        // Создать Token пересоздать
        $token = $this->createToken($user, $req);

        // Token и время жизни
        return response()->json([
            'user' => $user,
            'token' => $token->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse( $token->token->expires_at )->toDateTimeString()
        ], 200);
    }

    // ===================================================
    // ===================================================
    // ===== logout user
    public function logout(Request $request) {

        // revoke the token:
        $request->user()->token()->revoke();

        return response()->json([
            'code' => 777, 'status' => 'success', 'message' => config('game.custom_value.auth.2'),
        ]);
    }

    // ===================================================
    // ===================================================
    // ===== смена пароля
    public function changePassword(ChangePasswordRequest $request) {

        $index = $request->validated();

        $response = $this->changePasswordTrait($index);

        return response()->json([ 'code' => 777, 'status' => 'success',
            'message' => $response
        ]);
    }

    // ===================================================
    // ===================================================
    // ===== обновить данные юзера
    public function updateData(UpdateDataRequest $request) {
        $index = $request->validated();

        $bool = $this->changeDataTrait($index);

        return response()->json([ 'code' => 777, 'status' => 'success',
            'result'=> $bool
        ]);
    }

    // ===================================================
    // ===================================================
    // ===== обновить статус активности
    public function activationUpdate(ActivationUserUpdateRequest $request) {
        $index = $request->validated();

        $bool = $this->activationUpdateTrait($index);

        return response()->json([ 'code' => 777, 'status' => 'success',
            'result'=> $bool
        ]);
    }

    // ===================================================
    // ===================================================
    // ===== обновить адрес юзера
    public function updateUserAddress(UpdateUserAddressRequest $request) {
        $index = $request->validated();

        $bool = $this->changeDataTrait($index);

        return response()->json([ 'code' => 777, 'status' => 'success',
            'result'=> $bool
        ]);
    }

    // ===================================================
    // ===================================================
    // ===== обновить ник, имя, дата-рождения юзера
    public function updateUserAbout(UpdateUserAboutRequest $request) {
        $index = $request->validated();

        $bool = $this->changeDataTrait($index);

        return response()->json([ 'code' => 777, 'status' => 'success',
            'result'=> $bool
        ]);
    }

    // ===================================================
    // ===================================================
    // ===== обновить статистику юзера
    public function updateUserStatistics(UpdateUserStatisticsRequest $request) {
        $index = $request->validated();
        $bool = false;

        if(isset($index['statistic']) || is_null($index['statistic'])){
            $bool = $this->changeDataTrait($index);
        }

        return response()->json([ 'code' => 777, 'status' => 'success',
            'result'=> $bool
        ]);
    }

    // ===================================================
    // ===================================================
    // ===== проверка токена
    public function checkAuth(Request $request) {

        return response()->json([
            'code' => 777, 'status' => 'success', 'message' => config('game.custom_value.auth.3'),
        ]);
    }

    // ===================================================
    // ===================================================
    // ===== выбрать юзера
    /**
     * выбрать данные юзера
     * доступы token-attr, root для admin или admin
     * POST api/auth/get_user
     * Header - Authorization Token
     * @param GetUserRequest $request
     * @return
     */
    public function userData(GetUserRequest $request) {

        $index = $request->validated();

    return $this->getUser($index['user_id']);
    }

    // ===================================================
    // ===================================================
    // ===== выбрать всех игроков
    /**
     * выбрать всех игроков
     * доступы token-attr, root для admin или admin
     * POST api/auth/get_user
     * Header - Authorization Token
     * @param GetUserRequest $request
     * @return
     */
    public function getGamers() {
        $all_gamer = null;
        $users_id = (new UserRoleRepository())->whereAll([ 'role_id'=>3 ])->pluck('user_id');

        if(count($users_id)){
            $all_gamer = (new UserRepository())->whereInImage('id', $users_id);
        }

        return response()->json(['all_gamers'=>$all_gamer]);
    }

}
