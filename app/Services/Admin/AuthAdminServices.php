<?php
namespace App\Services\Admin;

use App\Exceptions\UserException;
use App\Mail\SendContinueMail;
use App\Models\CodeAuth;
use App\Repositories\UserRepository;
use App\Repositories\UserRoleRepository;
use App\Services\AuthServices;
use App\Services\Permissions\PermissionsAdminPanelServices;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthAdminServices {

    protected $permissionService;
    protected $authService;
    protected $userRoleRepository;
    protected $userRepository;

    public function __construct() {
        $this->permissionService = new PermissionsAdminPanelServices();
        $this->authService = new AuthServices();
        $this->userRoleRepository = new UserRoleRepository();
        $this->userRepository = new UserRepository();
    }

    // ============================================
    // ============================================
    // создать админа и выслать сообщение на Email для продолжения регистрации
    public function signupAdmin($index, $user){
        // доступ root
        $this->permissionService->checkRoot($user);

        // 1 создаем проверочный код и записываем в базу
        $code = $this->authService->generateCode(8);
        CodeAuth::updateOrCreate(
            ['email' => $index['email']],
            ['code' => $code]
        );

        // 2 создать юзера
        try {
            $user = $this->userRepository->updateOrCreate(
                ['email' => $index['email']],
                [
                    'first_name'=>$index['first_name'],
                    'surname'=>$index['surname'],
                    'middlename'=>$index['middlename'],
                    'company'=>$index['company'],
                    'tell'=>$index['tell'],
                    'password'=>Hash::make($code),
                    'activation'=>0,
                ]
            );
        } catch (\Exception $e) {
            throw new UserException(config('game.custom_value.ex.2'), 3);
        }

        // 3 создать новую связь UserRole для юзера - админ
        $this->userRoleRepository->creatUserRole($user->id, 2);

        // 4 отправка Email будущему Admin для перехода на регистрацию
        $link = url('/').'/admin/continue_reg?email='.$index['email'].'&code='.$code;
        Mail::to($index['email'])->send(new SendContinueMail([
                'link' => $link,
        ]));

        return $user;
    }

    // ============================================
    // ============================================
    // продолжение регистрации админа
    public function continueSignup($index){
        // выбрать проверочный код регистрации с Email
        $coll = CodeAuth::where('code',$index['code'])->first();

        if($coll) {
            // обновить данные регистрации
            $user = $this->userRepository->updateWhere(
                [ 'email' => $index['email'] ],
                [
                    'nickname'=>$index['nickname'],
                    'password'=>Hash::make($index['password']),
                    'activation'=>1
                ]
            );

            if(!$user){
                return config('game.custom_value.auth_admin.2');
            }

            //Удаляем использованный код
            $coll->delete();

            return true;
        }

        return config('game.custom_value.auth_admin.1');
    }

    // ============================================
    // ============================================
    // обновить админа
    public function updateAdmin($index, $user){

        // доступ root или владелец
        $this->permissionService->checkRootOrOwner($index, $user);

        // обновить данные регистрации
        $user = $this->userRepository->update($index['user_id'],
            [
                'first_name'=>$index['first_name'],
                'surname'=>$index['surname'],
                'middlename'=>$index['middlename'],
                'company'=>$index['company'],
                'email'=>$index['email'],
                'tell'=>$index['tell'],
            ]
        );

        return !$user ? false : true;
    }

}
