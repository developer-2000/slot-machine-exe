<?php
namespace App\Repositories;

use App\Models\HistoryPayment as Model;

class HistoryPaymentRepository extends CoreRepository {

    public function __construct() {
        $this->model = clone app(Model::class);
    }

}
