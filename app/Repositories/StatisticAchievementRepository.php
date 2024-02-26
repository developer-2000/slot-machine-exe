<?php
namespace App\Repositories;

use App\Models\AchievementStatistic as Model;


class StatisticAchievementRepository extends CoreRepository {

    public function __construct() {
        $this->model = clone app(Model::class);
    }

}
