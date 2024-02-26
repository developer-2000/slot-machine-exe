<?php
namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\AttractionAdminRequest;
use App\Http\Requests\Admin\CreateAttractionAdminRequest;
use App\Services\Admin\AttractionAdminServices;

class AttractionAdminController extends BaseApiController {

    protected $attractionService;

    public function __construct() {
        parent::__construct();
        $this->attractionService = new AttractionAdminServices();
    }

    /**
     * доступ root
     * выбрать все аттракционы и их локации
     * POST URL - /admin/attraction/all_attractions_user
     * Header - Authorization Token
     * @param AttractionAdminRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllAttractions(AttractionAdminRequest $request) {
        $index = $request->validated();
        $attractions = $this->attractionService->getAllAttractions($index, $this->user);

        return $this->getResponse($attractions, 201);
    }

    /**
     * доступ root
     * создать аттракцион
     * POST URL - /admin/attraction/create_attraction
     * Header - Authorization Token
     * @param CreateAttractionAdminRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createAttraction(CreateAttractionAdminRequest $request) {
        $index = $request->validated();
        $attractions = $this->attractionService->createAttraction($index, $this->user);

        return $this->getResponse($attractions, 201);
    }

}
