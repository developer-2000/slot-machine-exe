<?php
namespace App\Services;

use App\Models\Achievement;
use App\Repositories\PlayerAchievementRepository;
use Illuminate\Support\Facades\DB;

class AchievementServices {

    protected $playerAchievementRepository;

    public function __construct() {
        $this->playerAchievementRepository = new PlayerAchievementRepository();
    }

    // ========================================
    // создать тип достижения игрока
    public function createType($index) {
        // проверка на существование этой записи
        if($this->checkExistencecheckString($index)){
            return __('player_achievement.0');
        }

        $type = $this->playerAchievementRepository->create([
            'type' => $index['type'],
            'user_id' => $index['user_id'],
            'achievement_id' => $index['achievement_id'],
        ]);

    return $type;
    }

    // ========================================
    // удалить тип достижения игрока
    public function deleteType($index) {
        $type = $this->playerAchievementRepository->deleteWhereAll([
            'id'=>$index['achievement_id']
        ]);

    return $type ? true : false;
    }

    // ========================================
    // выбрать все типы достижений игрока
    public function selectTypesGamer($index) {

       $types = Achievement::leftJoin('player_achievements as pa', 'achievements.id', '=', 'pa.achievement_id')
           ->where("pa.user_id", $index['user_id'])
           ->with('types_user')
           ->select('achievements.*')
           ->get();

       $achivements = Achievement::get();

        return [ 'user_achievements' => $types, 'all_achievements' => $achivements ];
    }



    // PRIVATE
    // ========================================
    // проверка существование этой записи
    private function checkExistencecheckString($index) {
        if($this->playerAchievementRepository->whereArrFirst([
            ['type',$index['type']],
            ['user_id',$index['user_id']],
            ['achievement_id',$index['achievement_id']],
        ])){
            return true;
        }
        return false;
    }

}
