<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\ClientsAdminRequest;
use App\Http\Requests\Admin\GamerAdminRequest;
use App\Repositories\UserRepository;
use App\Services\Admin\GamerAdminServices;

class GamersAdminController extends BaseApiController {

    protected $gamerService;

    public function __construct() {
        parent::__construct();
        $this->gamerService = new GamerAdminServices();
    }

    /**
     * доступ root
     * выбрать всех игроков
     * POST URL - /admin/attraction/all_attractions_user
     * Header - Authorization Token
     * @param GamerAdminRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getGamers(GamerAdminRequest $request) {
        $index = $request->validated();
        $gamers = $this->gamerService->getGamers($index, $this->user);

        if(is_string($gamers)){
            return $this->getErrorResponse($gamers, 404);
        }

        return $this->getResponse($gamers, 201);
    }




}
