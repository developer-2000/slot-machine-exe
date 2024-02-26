<?php

namespace App\Http\Controllers\Admin;

use App\Services\Admin\ProfileServices;
use Illuminate\Http\Request;

class ProfileAdminController extends BaseApiController {

    protected $profileService;

    public function __construct() {
        parent::__construct();
        $this->profileService = new ProfileServices();
    }

    // отправить лицензии админа и их локации и их аттракционы
    public function getProfile() {
        $profile = $this->profileService->getProfile($this->user);

        return $this->getResponse($profile, 201);
    }

















}
