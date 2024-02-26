<?php
namespace App\Http\Controllers\Admin;

use App\Http\Traits\Admin\StatisticTraite;
use App\Repositories\LocationRepository;
use App\Repositories\UserRepository;
use App\Services\Admin\StatisticServices;
use App\Services\PermissionsServices;

class StatisticAdminController extends BaseApiController {

    use StatisticTraite;

    protected $permissions;
    protected $userRepository;
    protected $locationRepository;
    protected $statisticService;

    public function __construct() {
        parent::__construct();
        // проверка доступа
        $this->permissions = new PermissionsServices();
        $this->userRepository = new UserRepository();
        $this->locationRepository = new LocationRepository();
        $this->statisticService = new StatisticServices();
    }


    // статистика игроков
    // ========================================
    public function getGamerStatistic() {
        $statistic = $this->getGamerStatisticTraite();

        return $this->getResponse($statistic, 201);
    }

    // статистика локаций
    // ========================================
    public function getLocationStatistic() {
        $statistic = $this->getLocationStatisticTraite();
        return $this->getResponse($statistic, 201);
    }

    // статистика режимов
    // ========================================
    public function getModesStatistic() {
        $statistic = $this->getModesStatisticTraite();

        return $this->getResponse($statistic, 201);
    }


    // >>>>>>>>>>>>>>>>>
    // сгенерировать для тестов игроков в сессиях
    public function generateGamerSession() {

        $boll = $this->statisticService->generateGamerSession();

        return $this->getResponse($boll, 201);
    }

    // >>>>>>>>>>>>>>>>>
    // удалить сессии для тестов игроков
    public function deleteGamerSession() {

        $boll = $this->statisticService->deleteGamerSession();

        return $this->getResponse($boll, 201);
    }

}
