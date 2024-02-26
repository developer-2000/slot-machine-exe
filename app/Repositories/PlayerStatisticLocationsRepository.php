<?php
namespace App\Repositories;

use App\Models\PlayerStatisticLocation as Model;


class PlayerStatisticLocationsRepository extends CoreRepository {

    public function __construct() {
        $this->model = clone app(Model::class);
    }


}
