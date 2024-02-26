<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\Auth\ContinueRegAdminRequest;
use App\Http\Requests\Admin\SetPaginateAdminRequest;
use App\Services\Admin\AdminServices;
use Illuminate\Http\Request;

class AdminController extends BaseApiController {

    protected $adminService;

    public function __construct() {
        parent::__construct();
        $this->adminService = new AdminServices();
    }

    /**
     * сообщает vue url браузера для перехода на этот адрес
     * Post /admin
     */
    public function index(Request $request) {
        $data = [
            'url' => "http://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'],
            'request' => $request->all()
        ];
    return view('admin_panel.authorization', compact('data'));
    }

    /**
     * установить кол-во отображений в пагинации
     * Post /admin/paginate/set_count_paginate
     * @param SetPaginateAdminRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function setCountPaginate(SetPaginateAdminRequest $request) {
        $index = $request->validated();

        if(!$bool = $this->adminService->setCountPaginate($index)){
            return $this->getErrorResponse('Session error');
        }

        return $this->getResponse($bool, 201);
    }

    /**
     * выбрать кол-во отображений в пагинации
     * Post /admin/paginate/get_count_paginate
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCountPaginate() {
        $count = $this->adminService->getCountPaginate();

        return $this->getResponse($count, 201);
    }

}
