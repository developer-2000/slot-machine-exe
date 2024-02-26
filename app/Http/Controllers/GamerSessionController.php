<?php

namespace App\Http\Controllers;

use App\Http\Requests\HistorySessionGamer\GetDetailSessionGamer;
use App\Http\Requests\HistorySessionGamer\GetHistorySessionGamer;
use App\Http\Traits\SessionHistoryGamerTraite;
use Illuminate\Http\Request;

class GamerSessionController extends Controller {

    use SessionHistoryGamerTraite;

    // =====================================
    // выбрать историю игр игрока
    public function getAllHistoryGamer(GetHistorySessionGamer $request){
        $index = $request->validated();

        $history = $this->getAllHistoryTrait($index);
    return response()->json( ['game_history_item' => $history] , 201);
    }

}
