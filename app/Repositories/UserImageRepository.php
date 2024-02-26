<?php
namespace App\Repositories;

use App\Exceptions\UserException;
use App\Models\UserImage as Model;


class UserImageRepository extends CoreRepository {

    public function __construct() {
        $this->model = clone app(Model::class);
    }





}
