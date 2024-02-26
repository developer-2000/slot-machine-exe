<?php
namespace App\Repositories;


use App\Exceptions\UserException;
use App\Models\Test as Model;

class TestRepository extends CoreRepository {

    public function __construct() {
        $this->model = clone app(Model::class);
    }



}
