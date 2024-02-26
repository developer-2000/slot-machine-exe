<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Admin\BaseApiController;
use App\Http\Requests\Location\SelectAchievementLocationRequest;
use App\Services\LocationAchievementServices;

class LocationAchievementController extends BaseApiController {

    protected $locationAchievementService;

    public function __construct() {
        parent::__construct();
        $this->locationAchievementService = new LocationAchievementServices();
    }

    /**
     * выбрать достижения локации
     * доступы игрок
     * Post /api/achievements_location/select
     * Header - Authorization Token
     * @param SelectAchievementLocationRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function select(SelectAchievementLocationRequest $request) {
        $index = $request->validated();
        $array = $this->locationAchievementService->select($index);

        return $this->getResponse($array, 201);
    }

}
