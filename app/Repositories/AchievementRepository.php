<?php
namespace App\Repositories;

use App\Models\Achievement as Model;


class AchievementRepository extends CoreRepository {

    public function __construct() {
        $this->model = clone app(Model::class);
    }

}
