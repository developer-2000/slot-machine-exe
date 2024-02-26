<?php
namespace App\Repositories;

use App\Models\LocationModeStatistic as Model;


class LocationModeStatisticRepository extends CoreRepository {

    public function __construct() {
        $this->model = clone app(Model::class);
    }

}
