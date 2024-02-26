<?php

namespace App\Http\Traits;

use App\Events\SendLicenseEvent;
use App\Exceptions\UserException;
use App\Facades\MyFunctions;
use App\Models\EmployerData;
use App\Models\Image;
use App\Models\Location;
use App\Models\User;
use App\Repositories\LocationRepository;
use App\Repositories\UserRepository;
use App\Services\LicenseServices;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

trait LocationTraite {

     // ========================================
    // создать локацию
    protected function creatLocation($index){
        $auth = Auth::user();

        // Я root или админ и переданый id мой
        $this->permissionService->checkOwnerAdmin($auth, $index['admin_id']);

        $index['price'] = !isset($index['price']) ? 0 : $index['price'];

        // 1 создать локацию
        try {
            $location = $this->locationRepository->create([
                'user_id' => $index['admin_id'],
                'title' => $index['title'],
                'country' => json_decode($index['country']),
                'region' => json_decode($index['region']),
                'city' => json_decode($index['city']),
                'street' => json_decode($index['street']),
                'price' => $index['price'],
                'working_hours' => json_decode($index['working_hours']),
            ]);
        } catch (\Exception $e) {
            throw new UserException(config('game.custom_value.location.4'), 3);
        }

        // 2 создать лицензию для локации
        $this->createLicense($index['admin_id'], $location->id);

    return $location;
    }

    // ========================================
    // отдать данные локации для редактирования
    protected function editLocation($index){
        $auth = Auth::user();
        $this->permissionService->rootOrAdminLocation($auth, $index['location']);

        return [
            'location' => $this->locationRepository->show($index['location']),
        ];
    }

    // ========================================
    // Выдать все локации и статистику
    protected function getLocationStatisticTraite(){
        return [
            'locations' => $this->locationRepository->allLocationsStatistic()
        ];
    }

    // ========================================
    // обновить указанную локацию
    protected function updateLocation($index){
        $auth = Auth::user();
        // root или владелец локации
        $this->permissionService->rootOrAdminLocation($auth, $index['location_id']);

            try {
                $location = $this->locationRepository->update(
                    $index['location_id'],
                    [
                        'title' => $index['title'],
                        'country' => $index['country'],
                        'region' => $index['region'],
                        'city' => $index['city'],
                        'street' => $index['street'],
                        'price' => $index['price'],
                        'working_hours' => $index['working_hours'],
                    ]
                );
            }
            catch (\Exception $e) {
                throw new UserException(config('game.custom_value.ex.10'), 3);
            }

    return $location;
    }

    // ========================================
    // удаляет указаный аттракцион
    protected function deleteLocation($index){
        $auth = Auth::user();

        $this->permissionService->rootOrAdminLocation($auth, $index['location']);

        return $this->locationRepository->delete($index['location']);
    }

    // ========================================
    // выбрать данные для оплаты лицензии
    protected function editLicenseTrate($index){

        // Я root или админ и переданый id мой , локация моя
        $this->permissionService->accessLocation($this->access_attraction, $index);

        // Локация с активными аттракционами и лицензией
        $location = $this->locationRepository->oneLocationActiveAttractionsLicense(
            ['id' => $index['location_id'], 'user_id' => $index['admin_id'] ]
        );

        // Проверка оплаты в этом месяце
        if($this->licenseService->checkPayLocation($location->license)){
            return 'This month is paid';
        }

        // Подсчет стоимости оплаты аттракционов на локации
        $payment_cost = $this->licenseService->getPaymentCost($location);

        return ['price'=>round($payment_cost)];
    }

    // ========================================
    // оплата локации - все аттракционы на ней.
    protected function updateLicenseTrate($index){
        $auth = Auth::user();

        // Я root или админ и переданый id мой , локация моя
        $this->permissionService->rootOrAdminLocation($auth, $index['location_id']);

        // внести данные оплаты лицензии
        $license = $this->licenseService->payPayment($index['admin_id'], $index['location_id']);

        event(new SendLicenseEvent([$index['location_id'], 'start']));

        return true;
    }

    // ===================================================
    // ===================================================
    // обновить статус активности
    private function activationUpdateTrait($index){

        $bool = false;
        $this->permissionService->checkAccess(Auth::user()->id, 'developer');

        if((new LocationRepository())->update( $index['location_id'], [
            'activation'=>$index['activation']
        ])) {
            $bool = true;
        }

        return $bool;
    }

    // ===================================================
    // ===================================================
    // создать или обновить аватар локации
    private function loadAvatarTrait($index){

        $bool = false;
        // удалить старый файл аватарки
        $delete = $this->deleteOldFile($index);

        $path = '/uploads/image/location';
        $name = $index['avatar']->getClientOriginalName();
        $name = 'logotype_'.$index['location_id'].MyFunctions::chooseExtension($name);

        // сохранить image на диске
        $path = Storage::disk('public')->putFileAs( $path, $index['avatar'], $name);

        if((new LocationRepository())->update($index['location_id'], [
            'url_avatar'=>$path
        ])){ $bool = true; }

        return [
            'physically_delete'=>$delete,
            'path_physically'=>$path,
            'record_database'=>$bool,
        ];
    }

    // ===================================================
    // ===================================================
    // выбрать все аттркционы локации
    private function selectAttractionsTrait($index){
        $location = false;

        if( $location = (new LocationRepository())->oneLocationAttractions( [
                'id'=>$index['location_id']
                ] )){
            $bool = true;
        }

        return $location;
    }



    // PRIVATE
    // создать лицензию для локации
    private function createLicense($user_id, $location_id){
        try {
            $location = $this->licenseRepository->create([
                'user_id' => $user_id,
                'location_id' => $location_id
            ]);
        } catch (\Exception $e) {
            throw new UserException(config('game.custom_value.location.5'), 3);
        }
    }

    // удалить старый файл аватарки
    private function deleteOldFile($index){
        $location = (new LocationRepository())->show($index['location_id']);
        if (Storage::disk('public')->has($location->url_avatar)){
            if (Storage::disk('public')->delete($location->url_avatar)){
                (new LocationRepository())->update($index['location_id'], [
                    'url_avatar'=>null
                ]);
                return true;
            }
        }
        return false;
    }

}
