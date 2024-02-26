<?php
namespace App\Http\Controllers;

use App\Exceptions\UserException;
use App\Http\Controllers\Admin\BaseApiController;
use App\Http\Requests\Achievement\CreateTypeAchievementRequest;
use App\Http\Requests\Achievement\DeleteTypeAchievementRequest;
use App\Http\Requests\Achievement\SelectTypesAchievementRequest;
use App\Services\AchievementServices;
use App\Services\HeaderCheckTokenAttractionServices;
use Illuminate\Http\Request;

class PlayerAchievementController extends BaseApiController {

    protected $achievementService;
    protected $access_attraction;
    protected $headerCheckTokenService;

    public function __construct(Request $req) {
        parent::__construct();

        $this->achievementService = new AchievementServices();
        $this->headerCheckTokenService = new HeaderCheckTokenAttractionServices();

        // проверяет токен аттракциона в header и ищет его в таблице аттракцион
        if(!$this->access_attraction = $this->headerCheckTokenService->checkToken($req)){
            // открыть доступ к определенному url обычному авторизованному
            if($this->headerCheckTokenService->forCheckString( $_SERVER['REQUEST_URI'], [ 'select_types_gamer' ] )){
                 // проверка bearer token
                 $this->middleware('auth:api');
            }
            else{
                throw new UserException(config('game.custom_value.ex.7'), 1);
            }
        }

    }


    /**
     * создать тип достижения игрока
     * доступы token-attr
     * Post /api/achievements_user/create_type
     * Header - Token-attr
     * @param CreateTypeAchievementRequest $request
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function createType(CreateTypeAchievementRequest $request) {
        $index = $request->validated();
        $type = $this->achievementService->createType($index);

        if (is_string($type)){
            return $this->getErrorResponse($type, 403);
        }

        return $this->getResponse($type, 201);
    }

    /**
     * удалить тип достижения игрока
     * доступы token-attr
     * Post /api/achievements_user/delete_type
     * Header - Token-attr
     * @param DeleteTypeAchievementRequest $request
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function deleteType(DeleteTypeAchievementRequest $request) {
        $index = $request->validated();
        $bool = $this->achievementService->deleteType($index);

        if (!$bool){
            return $this->getErrorResponse($bool, 403);
        }

        return $this->getResponse($bool, 201);
    }

    /**
     * выбрать все типы достижений игрока
     * доступы token-attr, либо авторизованный
     * Post /api/achievements_user/select_types_gamer
     * Header - Authorization Token, Token-attr
     * @param SelectTypesAchievementRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function selectTypesGamer(SelectTypesAchievementRequest $request) {
        $index = $request->validated();
        $array = $this->achievementService->selectTypesGamer($index);

        return $this->getResponse($array, 201);
    }

}
