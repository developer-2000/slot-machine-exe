<?php
namespace App\Services;

use App\Repositories\StatisticAchievementRepository;

class StatisticAchievementServices {

    protected $statisticAchievementRepository;

    public function __construct() {
        $this->statisticAchievementRepository = new StatisticAchievementRepository();
    }

    // ========================================
    // создать статистику достижения
    public function createAchievement($index) {
        // проверка на существование этой записи
        if($this->checkExistencecheckString($index)){
            return __('player_achievement.0');
        }

        $statistic = $this->statisticAchievementRepository->create([
            'achievement_id'=>$index['achievement_id'],
            'user_id'=>$index['user_id'],
            'data'=> json_decode($index['data']),
        ]);

    return $statistic;
    }

    // ========================================
    // выбрать указанную статистику
    public function selectAchievement($index) {
        $statistic = $this->statisticAchievementRepository->whereAll([
            'achievement_id'=>$index['achievement_id'],
            'user_id'=>$index['user_id'],
        ]);

        return $statistic;
    }

    // ========================================
    // обновить указанную статистику
    public function updateAchievement($index) {
        $bool = $this->statisticAchievementRepository->updateWhere(
            [ 'achievement_id'=>$index['achievement_id'], 'user_id'=>$index['user_id'] ],
            [ 'data'=> $index['data'] ]);

        return $bool;
    }


    // PRIVATE
    // ========================================
    // проверка существование этой записи
    private function checkExistencecheckString($index) {
        if($this->statisticAchievementRepository->whereArrFirst([
            ['achievement_id',$index['achievement_id']],
            ['user_id',$index['user_id']],
        ])){
            return true;
        }
        return false;
    }

}
