<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\ClientsAdminRequest;
use App\Http\Requests\Admin\LocationAdminRequest;
use App\Services\Admin\LocationServices;

class LocationAdminController extends BaseApiController {

    protected $locationService;

    public function __construct() {
        parent::__construct();
        $this->locationService = new LocationServices();
    }

    /**
     * доступ root
     * выбрать все локации, аттракционы и лицензии Администратора в Пагинации
     * POST URL - /admin/location/all_locations_pagination
     * Header - Authorization Token
     * filter = string or integer
     * @param LocationAdminRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLocationsAdminPagin(LocationAdminRequest $request) {
        $index = $request->validated();
        $locations = $this->locationService->getLocationsPagination($index, $this->user);

        return $this->getResponse($locations, 201);
    }










}
