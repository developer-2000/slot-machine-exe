<?php
namespace App\Http\Controllers;

use App\Exceptions\UserException;
use App\Http\Controllers\Admin\BaseApiController;
use App\Http\Requests\Achievement\CreateAchievementRequest;
use App\Http\Requests\Achievement\SelectAchievementRequest;
use App\Services\HeaderCheckTokenAttractionServices;
use App\Services\StatisticAchievementServices;
use Illuminate\Http\Request;

class StatisticAchievementController extends BaseApiController {

    protected $statisticAchievementService;

    public function __construct(Request $req) {
        parent::__construct();
        // проверяет токен аттракциона в header и ищет его в таблице аттракцион
        if(!$this->access_attraction = (new HeaderCheckTokenAttractionServices())->checkToken($req)){
            throw new UserException(config('game.custom_value.ex.7'), 1);
        }
        $this->statisticAchievementService = new StatisticAchievementServices();
    }


    /**
     * создать статистику достижения
     * доступы token-attr
     * Post /api/statistic_achievement/create_achievement
     * Header - Token-attr
     * @param CreateAchievementRequest $request
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function createAchievement(CreateAchievementRequest $request) {
        $index = $request->validated();
        $statistic = $this->statisticAchievementService->createAchievement($index);

        if (is_string($statistic)){
            return $this->getErrorResponse($statistic, 403);
        }

        return $this->getResponse($statistic, 201);
    }

    /**
     * выбрать указанную статистику
     * доступы token-attr
     * Post /api/statistic_achievement/select_achievement
     * Header - Token-attr
     * @param SelectAchievementRequest $request
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function selectAchievement(SelectAchievementRequest $request) {
        $index = $request->validated();
        $statistic = $this->statisticAchievementService->selectAchievement($index);

        return $this->getResponse($statistic, 201);
    }

    /**
     * обновить указанную статистику
     * доступы token-attr
     * Post /api/statistic_achievement/update_achievement
     * Header - Token-attr
     * @param CreateAchievementRequest $request
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function updateAchievement(CreateAchievementRequest $request) {
        $index = $request->validated();
        $bool = $this->statisticAchievementService->updateAchievement($index);

        if (!$bool){
            return $this->getErrorResponse(__('player_achievement.1'), 403);
        }

        return $this->getResponse($bool, 201);
    }

}
