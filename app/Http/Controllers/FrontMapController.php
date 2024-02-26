<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Admin\BaseApiController;
use App\Services\FrontMapServices;
use Illuminate\Http\Request;

class FrontMapController extends BaseApiController {

    protected $frontService;

    public function __construct() {
        parent::__construct();
        $this->frontService = new FrontMapServices();
    }


    // выдать посещенные локации юзером
    public function getLocationsUser() {
        $arrLocations = $this->frontService->getLocationsUser($this->user);

        return $arrLocations;
    }

}
