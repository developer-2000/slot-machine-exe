<?php
namespace App\Http\Controllers;

use App\Http\Requests\Attraction\LoadCitiesRequest;
use App\Http\Requests\Attraction\LoadRegionsRequest;
use App\Repositories\MakeGeographyDbRepository;
use App\Services\PermissionsServices;
use Illuminate\Http\Request;

class GeoController extends Controller {

    protected $repository;

    public function __construct() {
        // проверка доступа
        $this->repository = new MakeGeographyDbRepository();
    }

    // =================================
    // подгрузка всех стран
    public function loadCountry() {
        return response()->json(
            ['countries' => $this->repository->selectCountries(),]
            , 201);
    }

    // =================================
    // передать все регионы по языку и code Country
    public function loadRegions(LoadRegionsRequest $request) {
        $index = $request->validated();
        $regions = $this->repository->selectRegionsForCountry($index['code']);

        if(!$regions){
            return response()->json( [ "status" => "not found" ] , 201);
        }

        return response()->json( [
            "status"=>"success",
            'regions' => $regions
        ], 201);
    }

    // =================================
    // передать все города по языку и code Regions
    public function loadCities(LoadCitiesRequest $request) {
        $index = $request->validated();
        $cities = $this->repository->selectCitiesForRegion($index['code']);

        if(!$cities){
            return response()->json( [ "status" => "not found" ] , 201);
        }

        return response()->json( [
            "status"=>"success",
            'cities' => $cities
        ], 201);
    }

}
