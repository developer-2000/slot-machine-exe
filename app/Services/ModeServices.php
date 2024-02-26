<?php
namespace App\Services;

use App\Repositories\UserModeRepository;

class ModeServices {

    protected $userModeRepository;
    protected $modes;

    public function __construct() {
        $this->userModeRepository = new UserModeRepository();
        $this->modes = config('game.modes_game.modes');
    }

    // внести статистику игрока в указанном режиме
    // ========================================
    public function setStatisticGamer($index){
        // проверка существования id режима
        if(!$this->checkIdMode($index['mode_id'])){
            return __('modes.0');
        }

        $bool = $this->userModeRepository->updateOrCreate(
            [ 'mode_id'=>$index['mode_id'], 'user_id'=>$index['user_id'] ],
            [ 'data'=> json_decode($index['data']) ]
        );

    return $bool;
    }

    // выбрать статистику игрока
    // ========================================
    public function getStatisticGamer($index){
        $modes = $this->userModeRepository->whereAll([
            'user_id'=>$index['user_id']
        ]);

        return [
            'modes_gamer'=>$modes,
            'all_modes_game'=>config('game.modes_game.modes'),
        ];
    }

    // проверка существования режима по id
    // ========================================
    public function checkIdMode(int $id){
        return isset($this->modes[$id]) ? true : false;
    }

    // выбрать все режимы
    // ========================================
    public function getModes(){
        return $this->modes;
    }

}
