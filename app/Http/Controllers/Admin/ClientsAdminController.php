<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\ClientsAdminRequest;
use App\Services\Admin\ClientsServices;

class ClientsAdminController extends BaseApiController {

    protected $clientsService;

    public function __construct() {
        parent::__construct();
        $this->clientsService = new ClientsServices();
    }

    // все админы и данные о лицензии
    public function getClients(ClientsAdminRequest $request) {
        $index = $request->validated();
        $administrators = $this->clientsService->getClients($index, $this->user);

        return $this->getResponse($administrators, 201);
    }
}
