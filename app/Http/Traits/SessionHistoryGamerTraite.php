<?php

namespace App\Http\Traits;


use App\Repositories\HistorySessionRepository;
use App\Repositories\HistorySessionUserRepository;
use App\Repositories\SessionRepository;

trait SessionHistoryGamerTraite {


    // ========================================
    // выбрать историю игр игрока
    protected function getAllHistoryTrait($index){

    $arr_history = [];
    // выбрать вся история игр игрока + история сессий + атракцион
    $history = (new HistorySessionUserRepository())->getAllHistoryGamer($index['user_id']);

        // сформировать нужный обьект
        foreach ($history as $key => $obj){
            array_push($arr_history, [
                'session_id' => isset($obj->session_id) ? $obj->session_id : null,
                'mode_id' => isset($obj->session_history->mode_id) ? $obj->session_history->mode_id : null,
                'result_gamer' => isset($obj->result) ? $obj->result : null,
                'attraction_name' => isset($obj->session_history->attraction->location->title) ? $obj->session_history->attraction->location->title : null,
                'game_date' => isset($obj->created) ? $obj->created : null,
                'end_game_date' => $obj->updated_at,
            ]);
        }

    return $arr_history;
    }



}
