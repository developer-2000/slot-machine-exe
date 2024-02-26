<?php
namespace App\Http\Controllers\Attraction;

use App\Http\Controllers\Controller;
use App\Http\Requests\Attraction\EditAttractionRequest;
use App\Repositories\AttractionRepository;
use App\Services\HeaderCheckTokenAttractionServices;
use App\Services\Permissions\PermissionsSessionServices;
use Illuminate\Http\Request;

class AttractionTokenAttrController extends Controller {

    protected $service;
    protected $access_attraction;

    public function __construct(Request $req) {
        // проверяет токен аттракциона в header и ищет его в таблице аттракцион
        if(!$this->access_attraction = (new HeaderCheckTokenAttractionServices())->checkToken($req)){
            // в другом случае авторизует по bearer token
            $this->middleware('auth:api');
        }

    // доступы
    $this->service = new PermissionsSessionServices();
    }


    /**
     * показ одного аттракциона
     * доступы token-attr, root для admin или admin
     * POST  /api/get_data_attraction
     * Header - Authorization Token
     * admin_id
     * attraction_id
     * @param EditAttractionRequest $request
     * @return
     * @throws \App\Exceptions\UserException
     */
    public function getDataAttraction(EditAttractionRequest $request) {
        $index = $request->validated();
        // доступы аттракцион, root для admin или admin
        $this->service->attractionRootOrAdmin($this->access_attraction, $index);

        // показать один аттракцион
        $attr = (new AttractionRepository)->show($index['attraction_id']);
        return response()->json($attr, 201);
    }

}
