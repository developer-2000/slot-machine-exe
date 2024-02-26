<?php

namespace App\Http\Controllers\Location;

use App\Http\Controllers\Controller;
use App\Http\Requests\Location\ActivationLocationUpdateRequest;
use App\Http\Requests\Location\DestroyLocationRequest;
use App\Http\Requests\Location\EditLocationRequest;
use App\Http\Requests\Location\LoadAvatarLocationRequest;
use App\Http\Requests\Location\SelectAttractionsLocationRequest;
use App\Http\Requests\Location\updateLicenseRequest;
use App\Http\Requests\Location\StoreLocationRequest;
use App\Http\Requests\Location\UpdateLocationRequest;
use App\Http\Traits\LocationTraite;
use App\Repositories\LicenseRepository;
use App\Repositories\LocationRepository;
use App\Services\CarbonServices;
use App\Services\PermissionsServices;

class LocationController extends Controller {

    use LocationTraite;

    protected $permissionService;
    protected $locationRepository;
    protected $licenseRepository;
    protected $carbonService;

    public function __construct() {
        $this->permissionService = new PermissionsServices();
        $this->locationRepository = new LocationRepository();
        $this->licenseRepository = new LicenseRepository();
        $this->carbonService = new CarbonServices();
    }

    /**
     * Создать локацию и лицензию для нее
     * доступ root для admin или сам админ
     * Post /api/location
     * Header - Authorization Token
     * admin_id - на кого регистрация location
     * title
     * country
     * region
     * city
     * street
     * price
     * url_avatar
     * @param StoreLocationRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\UserException
     */
    public function store(StoreLocationRequest $request) {
        $index = $request->validated();
        $location = $this->creatLocation($index);

        return response()->json([
            'code' => 777,
            'status' => 'success',
            'message' => config('game.custom_value.location.1'),
            'location' => $location
        ], 201);
    }

    /**
     * Выдать локацию
     * доступ root для admin или сам админ
     * GET URL - /api/location/id локации/edit
     * Header - Authorization Token
     * @param EditLocationRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(EditLocationRequest $request) {
        $index = $request->validated();
        $show = $this->editLocation($index);

    return response()->json( $show , 201);
    }

    /**
     * Выдать все локации и статистику
     * доступ root для admin или сам админ
     * POST URL - /api/location/get-location-statistic
     * Header - Authorization Token
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLocationStatistic() {
        $locations = $this->getLocationStatisticTraite();
    return response()->json( $locations , 201);
    }

    /**
     * обновить данные локации
     * доступ root для admin или сам админ
     * PATCH URL - /api/location/id локации
     * Header - Authorization Token
     * title
     * country
     * region
     * city
     * street
     * Patch изменить только то что передал в request.
     * @param UpdateLocationRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateLocationRequest $request) {
        $index = $request->validated();
        $update = $this->updateLocation($index);

        return response()->json([
            'code' => 777,
            'status' => 'success',
            'message' => config('game.custom_value.location.2'),
        ], 201);
    }

    /**
     * доступ root для admin или сам админ
     * DELETE URL - /api/attraction/id локации
     * Header - Authorization Token
     * @param DestroyLocationRequest $request
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function destroy(DestroyLocationRequest $request) {
        $index = $request->validated();
        $delete = $this->deleteLocation($index);

        return response()->json([
            'code' => 777,
            'status' => 'success',
            'message' => config('game.custom_value.location.3'),
        ], 201);
    }

    /**
     * оплата локации - все аттракционы на ней.
     * может root или admin для себя
     * POST /api/location/update-license
     * Header - Authorization Token
     * location_id
     * location_id -
     * admin_id - на кого регистрация локация
     * @param updateLicenseRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateLicense(updateLicenseRequest $request) {
        $index = $request->validated();
        $bool = $this->updateLicenseTrate($index);

        return response()->json([
            'code' => 777,
            'status' => 'success',
            'message' => $bool
        ], 201);
    }

    /**
     * обновить статус активности
     * доступ root
     * PATCH URL - /api/location/activation_update
     * Header - Authorization Token
     * location_id
     * activation
     * @param ActivationLocationUpdateRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\UserException
     */
    public function activationUpdate(ActivationLocationUpdateRequest $request) {
        $index = $request->validated();
        $bool = $this->activationUpdateTrait($index);

        return response()->json([
            'code' => 777,
            'status' => 'success',
            'message' => $bool,
        ], 201);
    }

    /**
     * создать или обновить аватар локации
     * доступ admin
     * PATCH URL - /api/location/load_avatar
     * Header - Authorization Token
     * location_id
     * image
     * @param LoadAvatarLocationRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function loadAvatar(LoadAvatarLocationRequest $request) {
        $index = $request->validated();
        $bool = $this->loadAvatarTrait($index);

        return response()->json([
            'code' => 777,
            'status' => 'success',
            'message' => $bool,
        ], 201);
    }

    /**
     * выбрать все аттркционы локации
     * доступ root
     * PATCH URL - /api/location/select_attractions
     * Header - Authorization Token
     * location_id
     * @param LoadAvatarLocationRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function selectAttractions(SelectAttractionsLocationRequest $request) {
        $index = $request->validated();
        $bool = $this->selectAttractionsTrait($index);

        return response()->json([
            'code' => 777,
            'status' => 'success',
            'message' => $bool,
        ], 201);
    }
}
