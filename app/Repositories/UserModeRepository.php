<?php
namespace App\Repositories;

use App\Models\UserModeStatistic as Model;

class UserModeRepository extends CoreRepository {

    public function __construct() {
        $this->model = clone app(Model::class);
    }


}
