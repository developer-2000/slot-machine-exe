<?php
namespace App\Repositories;

use App\Models\PlayerAchievement as Model;


class PlayerAchievementRepository extends CoreRepository {

    public function __construct() {
        $this->model = clone app(Model::class);
    }

}
