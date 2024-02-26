<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\ClientsAdminRequest;
use App\Http\Requests\Admin\LicenseAdminRequest;
use App\Services\Admin\LicenseAdminServices;

class LicenseAdminController extends BaseApiController {

    protected $licenseService;

    public function __construct() {
        parent::__construct();
        $this->licenseService = new LicenseAdminServices();
    }

    /**
     * доступ root
     * выбрать все лицензии
     * POST URL - /admin/license/all_licenses
     * Header - Authorization Token
     * @param LicenseAdminRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLicenses(LicenseAdminRequest $request) {
        $index = $request->validated();
        $licenses = $this->licenseService->getLicenses($index, $this->user);

        return $this->getResponse($licenses, 201);
    }

}
