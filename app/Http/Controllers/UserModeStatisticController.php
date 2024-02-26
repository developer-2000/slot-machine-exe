<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Admin\BaseApiController;
use App\Http\Requests\User\GetStatisticModeRequest;
use App\Http\Requests\User\SetStatisticModeRequest;
use App\Services\HeaderCheckTokenAttractionServices;
use App\Services\ModeServices;
use Illuminate\Http\Request;

class UserModeStatisticController extends BaseApiController {

    protected $accessToken;
    protected $modeService;

    public function __construct(Request $req) {
        parent::__construct();
        // проверяет токен аттракциона в header и ищет его в таблице аттракцион
        if(!$this->accessToken = (new HeaderCheckTokenAttractionServices())->checkToken($req)){
            // в другом случае авторизует по bearer token
            $this->middleware('auth:api');
        }
        $this->modeService = new ModeServices();
    }

    /**
     * внести статистику игрока в указанном режиме
     * доступы token-attr, gamer
     * POST URL - /api/mode/set_statistic_gamer
     * Header - Token-attr
     * @param SetStatisticModeRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function setStatisticGamer(SetStatisticModeRequest $request) {
        $index = $request->validated();
        $bool = $this->modeService->setStatisticGamer($index);
        if(is_string($bool)){
            return $this->getErrorResponse($bool, 400);
        }

        return $this->getResponse($bool, 201);
    }

    /**
     * взять статистику игрока в указанном режиме
     * доступы token-attr, gamer
     * POST URL - /api/mode/get_statistic_gamer
     * Header - Token-attr
     * @param GetStatisticModeRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStatisticGamer(GetStatisticModeRequest $request) {
        $index = $request->validated();

        $modes_gamer = $this->modeService->getStatisticGamer($index);

        return $this->getResponse($modes_gamer, 200);
    }

}
