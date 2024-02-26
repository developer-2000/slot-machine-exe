<?php
namespace App\Http\Traits\Admin;

trait StatisticTraite {

    protected $method = [ 'subMonths', 'subYears' ];

    // отдать статистику по игрокам
    // ========================================
    protected function getGamerStatisticTraite(){
        // я root
        $this->permissions->checkAccess($this->user->id, ['developer']);

        // ============================================
        // *** number_registered_players - все зарегистрированные игроки
        // *** average_amount_day - среднее количество игроков в день
        // общее кол-во игравших игроков за последний месяц / кол-во дней месяца
        // *** average_amount_month - среднее количество игроков в месяц
        // общее кол-во игравших игроков за последний год / 12
        // *** average_age - Средний возраст игроков
        // сумма лет зарегистрированных игроков / кол-во зарегистрированных игроков
        // *** average_amount_players_session - Среднее количество игроков в сессии
        // кол-во игроков в сессиях за месяц / кол-во сессий за месяц
        $arr_statistic = [
            'number_registered_players' => $this->userRepository->getCountGamers(),
            'average_amount_day' => $this->statisticService->averageAmountPeriod($this->method[0]),
            'average_amount_month' => $this->statisticService->averageAmountPeriod($this->method[1]),
            'average_age' => $this->statisticService->averageAge(),
            'average_amount_players_session' => $this->statisticService->averagePlayersSession($this->method[0]),
        ];

    return $arr_statistic;
    }

    // отдать статистику локаций
    // ========================================
    protected function getLocationStatisticTraite(){
        // я root
        $this->permissions->checkAccess($this->user->id, ['developer']);
        $all_attraction_locations = $this->locationRepository->allLocationsAttraction();

        // ============================================
        // *** total_number_locations - все записи локаций
        // *** minimum_attractions_location - Минимальное количество аттракционов на локации
        // *** maximum_attractions_location - Максимальное количество аттракционов на локации
        // сортировка аттракционов на всех локациях
        // *** minimum_players_location_day - Минимальное количество игроков на локации (за сутки)
        // *** maximum_players_location_day - Максимальное количество игроков на локации (за сутки)
        // выборка всех игр из сесий за месяц
        // подсчет игр на локациях через связь attraction_id сессии к локации
        // *** minimum_players_location_month - Минимальное количество игроков на локации (за месяц)
        // аналогия с minimum_players_location_day
        // выборка сессий берется за год
        $arr_statistic = [
            'total_number_locations' => $this->locationRepository->getCountAll(),
            'minimum_attractions_location' => $this->statisticService->minimumAttractionsLocation($all_attraction_locations),
            'maximum_attractions_location' => $this->statisticService->maximumAttractionsLocation($all_attraction_locations),
            'minimum_players_location_day' => $this->statisticService->minimumPlayersLocationPeriod($this->method[0]),
            'maximum_players_location_day' => $this->statisticService->maximumPlayersLocationPeriod($this->method[0]),
            'minimum_players_location_month' => $this->statisticService->minimumPlayersLocationPeriod($this->method[1]),
            'maximum_players_location_month' => $this->statisticService->maximumPlayersLocationPeriod($this->method[1]),
        ];

    return $arr_statistic;
    }

    // отдать статистику режимов
    // ========================================
    protected function getModesStatisticTraite(){
        // я root
        $this->permissions->checkAccess($this->user->id, ['developer']);

        // ============================================
        // *** total_number_locations - все записи локаций

//        $arr_modes = [
//            'total_number_locations' => $this->locationRepository->getCountAll(),
//        ];

    return 1;
//    return $arr_modes;
    }

}
