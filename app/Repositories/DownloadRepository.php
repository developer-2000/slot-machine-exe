<?php
namespace App\Repositories;

use App\Models\Download as Model;


class DownloadRepository extends CoreRepository {

    public function __construct() {
        $this->model = clone app(Model::class);
    }



}
