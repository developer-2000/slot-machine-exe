<?php
namespace App\Repositories;

use App\Models\LocationAchievement as Model;


class LocationAchievementRepository extends CoreRepository {

    public function __construct() {
        $this->model = clone app(Model::class);
    }

}
