<?php

namespace App\Http\Controllers\Location;

use App\Http\Controllers\Controller;
use App\Http\Requests\Location\editLicenseRequest;
use App\Http\Requests\Location\updateLicenseRequest;
use App\Http\Traits\LocationTraite;
use App\Repositories\LocationRepository;
use App\Services\CarbonServices;
use App\Services\HeaderCheckTokenAttractionServices;
use App\Services\LicenseServices;
use App\Services\Permissions\PermissionsLocationServices;
use Illuminate\Http\Request;

class LocationTokenAttrController extends Controller {

    use LocationTraite;

    protected $permissionService;
    protected $access_attraction = null;
    protected $carbonService;
    protected $locationRepository;
    protected $licenseService;

    public function __construct(Request $req) {
        // идентификация оттракциона через токен и id аттракциона
        // содержит false или коллекцию аттракциона
        if(!$this->access_attraction = (new HeaderCheckTokenAttractionServices())->checkToken($req)){
            // в другом случае авторизует по bearer token
            $this->middleware('auth:api');
        }

        $this->permissionService = new PermissionsLocationServices();
        $this->locationRepository = new LocationRepository();
        $this->carbonService = new CarbonServices();
        $this->licenseService = new LicenseServices();
    }

    /**
     * выбрать данные для оплаты лицензии
     * может root или admin для себя
     * POST /api/location/edit-license
     * Header - Authorization Token
     * location_id
     * admin_id - на кого регистрация локация
     * @param updateLicenseRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    // подсчет - ((цена месяца / кол-во дней в текущем месяце) * кол-во дней оплаты) * кол-во аттракционов
    public function editLicense(editLicenseRequest $request) {
        $index = $request->validated();

        $return = $this->editLicenseTrate($index);

        if(is_string($return)){
            return response()->json([ 'code' => 777, 'status' => 'success', 'message' => $return ], 201);
        }

        return response()->json([ 'code' => 555, 'status' => 'payment', 'message' => $return ], 200);
    }


}
