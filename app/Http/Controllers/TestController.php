<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Admin\BaseApiController;
use App\Services\HeaderCheckTokenAttractionServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TestController extends BaseApiController {

    public function __construct() {
        parent::__construct();

    }

// вернет то что передал, header status - return $this->getResponse($this->user, 201);
// вернет message, массив errors, header status - return $this->getErrorResponse('Ошибка ', 403, ['sdf']);
// вернет message, header status - return $this->getSuccessResponse(201);

    public function test(Request $request) {


        return $this->getSuccessResponse(201);
//        return response()->json([
//            'user' => $this->user
//        ], 201);
    }



}



























