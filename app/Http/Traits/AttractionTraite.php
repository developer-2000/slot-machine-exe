<?php

namespace App\Http\Traits;

use App\Events\SendLicenseEvent;
use App\Exceptions\UserException;
use App\Facades\MyFunctions;
use App\Repositories\AttractionRepository;
use App\Repositories\LicenseRepository;
use App\Repositories\LocationRepository;
use App\Repositories\MakeGeographyDbRepository;
use App\Services\LicenseServices;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

trait AttractionTraite {


    // ========================================
    // создать атракцион
    protected function creatAttraction($index){
        $auth = Auth::user();
        // Я root или owner
        $this->permissions->checkOwnerAdmin($auth, $index['user_id']);

        // создать атракцион
        $attraction = $this->attractionRepository->create([
            'user_id' => $index['user_id'],
            'title' => isset($index['title']) ? $index['title'] : null,
            'access_token' => MyFunctions::generateStr(40)
        ]);

    return $attraction;
    }

    // ========================================
    // предоставляет данные для редактирования атракциона
    protected function editAttraction($index){

        $auth = Auth::user();
        $this->permissions->checkAccess($auth->id, 'developer');

        $model = $this->attractionRepository->edit($index['attraction_id']);

        // мягко удален
        if ($model->trashed()) {
            throw new UserException(config('game.custom_value.ex.13'), 5);
        }

        return [
            'attraction' => $model,
            'countries' => (new MakeGeographyDbRepository())->selectCountries()
        ];
    }

    // ========================================
    // обновить указаный аттракцион
    protected function updateAttraction($index){
        $auth = Auth::user();
        // владелец аттракциона или root (мой id, admin_id)
        $this->permissions->rootOrAdminAttraction($auth, $index['attraction_id']);

        $attraction = $this->attractionRepository->update($index['attraction_id'],
            [
                'title' => $index['title'],
                'location_id' => $index['location_id'],
                'activation' => isset($index['activation']) ? $index['activation'] : 1,
            ]
        );

    return $attraction;
    }

    // ========================================
    // удаляет указаный аттракцион
    protected function deleteAttraction($index){

        $auth = Auth::user();
        $this->permissions->checkAccess($auth->id, 'developer');

        return $this->attractionRepository->delete($index['attraction']);
    }

    // ========================================
    // показать все аттракционы
    protected function indexAttraction($index){

        $auth = Auth::user();

        // владелец аттракциона или root (мой id, admin_id)
       $this->permissions->checkOwnerAdmin($auth, $index['user_id']);

    return $this->attractionRepository->whereAll(['user_id'=>$index['user_id']]);
    }

    // ========================================
    // смена токен аттракциона
    protected function newTokenTrate($index){

        $auth = Auth::user();

        // root или владелец аттракциона
        $this->permissions->rootOrAdminAttraction($auth, $index['attraction_id']);

        $token = MyFunctions::generateStr(40);
        // обновить
        $attraction = $this->attractionRepository->update($index['attraction_id'], [
            'access_token' => $token,
        ]);

    return $token;
    }

    // ========================================
    // удалить токен аттракциона
    protected function deleteTokenTrate($index){

        $auth = Auth::user();

        // root или владелец аттракциона
        $this->permissions->rootOrAdminAttraction($auth, $index['attraction_id']);

        // обновить
        $bool = $this->attractionRepository->update($index['attraction_id'], [
            'access_token' => null,
        ]);

    return $bool;
    }

    // ===================================================
    // ===================================================
    // обновить статус активности
    private function activationUpdateTrait($index){

        $bool = false;
        $this->permissionService->checkAccess(Auth::user()->id, 'developer');

        if((new AttractionRepository())->update( $index['attraction_id'], [
            'activation'=>$index['activation']
        ])) {
            $bool = true;
        }

        return $bool;
    }


}
