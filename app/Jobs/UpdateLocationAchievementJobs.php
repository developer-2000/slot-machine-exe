<?php
namespace App\Jobs;

use App\Repositories\LocationAchievementRepository;
use App\Services\CarbonServices;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use App\Repositories\LocationRepository;

class UpdateLocationAchievementJobs implements ShouldQueue {
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    // задержка повторной попытки неудачного задания
    public $retryAfter = 30;
    // количество ошибочных выполнений задачи
    public $tries = 3;

    protected $location_id;
    protected $locationRepository;
    protected $locationAchRepository;
    protected $carbonService;
    protected $config;
    protected $allGames;
    protected $hypeAchievement = [false, false, false];
    protected $eyeballsAchievement = [false, false, false];
    protected $whoYouAchievement = [false, false, false];

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($location_id) {
        $this->location_id = $location_id;
        // назначить этой задаче имя очереди
        $this->onQueue('database');
        $this->locationRepository = new LocationRepository();
        $this->locationAchRepository = new LocationAchievementRepository();
        $this->carbonService = new CarbonServices();
        $this->config = config('game.location_achievements');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle() {
        // локация и статистика сыгранных режимов на ней
        $location = $this->locationRepository->locationModeStatistic([ 'id' => $this->location_id ]);
        // 1 расскрыть все игры сессий , сортировка игр по дате
        $this->revealAllGames($location);
        // 2 выбрать достижения
        $obj = $this->selectData($location);
        // 3 обновить достижения этой локации
        $up = $this->locationAchRepository->updateOrCreate( ['location_id'=>$this->location_id], $obj );
    }


    // метод вызывается при ошибке выполнения задачи
    // $exception - передает сообщение об ошибке
    public function failed(\Throwable $e) {
        logs()->info("Ошибка задачи : UpdateLocationAchievementJobs ");
        logs()->info($e->getMessage());
    }


    // долгожитель
    protected function longLiver($location){
        $data = null;
        $config = $this->config['long_liver'];
        $created_at = $this->carbonService->getCarbonYearMonthDay($location->created_at);
        $year_1 = $this->carbonService->getCarbonYearMonthDay(Carbon::now()->subYears($config['type'][0]));
        $year_3 = $this->carbonService->getCarbonYearMonthDay(Carbon::now()->subYears($config['type'][1]));
        $year_5 = $this->carbonService->getCarbonYearMonthDay(Carbon::now()->subYears($config['type'][2]));

        if($created_at <= $year_1){
            $data = $this->insertDescription($config, $config['type'][0], 1);
        }
        if($created_at <= $year_3){
            $data = $this->insertDescription($config, $config['type'][1], 2);
        }
        if($created_at <= $year_5){
            $data = $this->insertDescription($config, $config['type'][2], 3);
        }

        return $data;
    }

    // Топоры во все стороны
    protected function axesAllDirections($location){
        $count = 0;
        $config = $this->config['axes_all_directions'];

        foreach ($location->mode_statistic as $key => $arr){
            $count += $arr['count_game'];
        }

        // заполнить данные достижений второй вариант
        $data = $this->fillAchievementsData_2($config, $count);

        return $data;
    }

    // Доступ разрешен
    protected function accessGranted(){
        $arr_gamer_id = [];
        $config = $this->config['access_granted'];

        // 1 выбрать уникальные id игроков из всех игр на этой локации
        foreach ($this->allGames as $key => $game){
            // этот id игрока еще не добавлен
            if (!in_array($game['gamer']['gamer_id'], $arr_gamer_id)) {
                array_push($arr_gamer_id, $game['gamer']['gamer_id']);
            }
        }

        // заполнить данные достижений второй вариант
        $data = $this->fillAchievementsData_2($config, count($arr_gamer_id));

        return $data;
    }

    // Любовь к Bullseye
    protected function inLoveBullseye($location){
        $count = 0;
        $config = $this->config['in_love_bullseye'];

        foreach ($location->mode_statistic as $key => $statistic){
            // существует статистика для режима Bullseye
            if($statistic["mode_id"] == 0){
                $count = $statistic["count_game"];
                break;
            }
        }

        // заполнить данные достижений второй вариант
        $data = $this->fillAchievementsData_2($config, $count);

        return $data;
    }

    // Любовь к Sniper
    protected function inLoveSniper($location){
        $count = 0;
        $config = $this->config['in_love_sniper'];

        foreach ($location->mode_statistic as $key => $statistic){
            // существует статистика для режима Sniper
            if($statistic["mode_id"] == 2){
                $count = $statistic["count_game"];
                break;
            }
        }

        // заполнить данные достижений второй вариант
        $data = $this->fillAchievementsData_2($config, $count);

        return $data;
    }

    // Любовь к Throw Royal
    protected function inLoveThrowRoyal($location){
        $count = 0;
        $config = $this->config['in_love_throw_royal'];

        foreach ($location->mode_statistic as $key => $statistic){
            // существует статистика для режима Throw Royal
            if($statistic["mode_id"] == 3){
                $count = $statistic["count_game"];
                break;
            }
        }

        // заполнить данные достижений второй вариант
        $data = $this->fillAchievementsData_2($config, $count);

        return $data;
    }

    // Любовь к Monopoly
    protected function inLoveMonopoly($location){
        $count = 0;
        $config = $this->config['in_love_monopoly'];

        foreach ($location->mode_statistic as $key => $statistic){
            // существует статистика для режима Monopoly
            if($statistic["mode_id"] == 1){
                $count = $statistic["count_game"];
                break;
            }
        }

        // заполнить данные достижений второй вариант
        $data = $this->fillAchievementsData_2($config, $count);

        return $data;
    }

    // Ажиотаж
    protected function hype(){
        $string_date = '';
        $count_gamer = 0;
        $data = null;
        $config = $this->config['hype'];

        foreach ($this->allGames as $key => $game){
            // день создания сессии
            $created_session = $this->carbonService->getCarbonYearMonthDay($game['created_session']);
            // дата сессии изменилась - фиксация достижения
            if ($string_date != $created_session) {
                // фиксация достижения
                $this->hypeAchievement = $this->fixingAchievement(
                    $count_gamer, $config, $this->hypeAchievement
                );
                // запомнить день
                $string_date = $created_session;
                $count_gamer = 0;
            }
            // новый подсчет игроков в дне
            $count_gamer++;
        }
        // фиксация достижения
        $this->hypeAchievement = $this->fixingAchievement(
            $count_gamer, $config, $this->hypeAchievement
        );

        // заполнить данные достижений
        $data = $this->fillAchievementsData($config, $this->hypeAchievement);

        return $data;
    }

    // Не протолкнуться
    protected function packedToEyeballs(){
        $string_date = '';
        $count_gamer = 0;
        $data = null;
        $config = $this->config['packed_to_eyeballs'];

        foreach ($this->allGames as $key => $game){
            // месяц создания сессии
            $created_session = $this->carbonService->getCarbonYearMonth($game['created_session']);
            // дата сессии изменилась - фиксация достижения
            if ($string_date != $created_session) {
                // фиксация достижения
                $this->eyeballsAchievement = $this->fixingAchievement(
                    $count_gamer, $config, $this->eyeballsAchievement
                );
                // запомнить день
                $string_date = $created_session;
                $count_gamer = 0;
            }
            // новый подсчет игроков в дне
            $count_gamer++;
        }
        // фиксация достижения
        $this->eyeballsAchievement = $this->fixingAchievement(
            $count_gamer, $config, $this->eyeballsAchievement
        );

        // заполнить данные достижений
        $data = $this->fillAchievementsData($config, $this->eyeballsAchievement);

        return $data;
    }

    // Кто вы?
    protected function whoYou(){
        $string_date = '';
        $count_gamer = 0;

        $config = $this->config['who_you'];

        foreach ($this->allGames as $key => $game){
            // день создания сессии
            $created_session = $this->carbonService->getCarbonYearMonthDay($game['created_session']);
            // дата сессии изменилась - фиксация достижения
            if ($string_date != $created_session) {
                // фиксация достижения
                $this->whoYouAchievement = $this->fixingAchievement(
                    $count_gamer, $config, $this->whoYouAchievement
                );
                // запомнить день
                $string_date = $created_session;
                $count_gamer = 0;
            }

            // новый подсчет игроков в дне
            // считаю только не авторизованных
            if($game['gamer']["gamer_id"] == 0){
                $count_gamer++;
            }
        }
        // фиксация достижения
        $this->whoYouAchievement = $this->fixingAchievement(
            $count_gamer, $config, $this->whoYouAchievement
        );

        // заполнить данные достижений
        $data = $this->fillAchievementsData($config, $this->whoYouAchievement);

        return $data;
    }


    // PRIVATE
    // выбрать достижения
    // выбираются только если они не достигли максимального уровня
    private function selectData($location){
        $obj = [];

        // выбрать достижения этой локации
        $achievement = $this->locationAchRepository->whereArrFirst(
            [ 'location_id' => $this->location_id ]
        );

        // заполнение данных
        if(is_null($achievement) || is_null($achievement->long_liver) || $achievement->long_liver['type'] < 3){
            $obj['long_liver'] = $this->longLiver($location);
        }
        if(is_null($achievement) || is_null($achievement->axes_all_directions) || $achievement->axes_all_directions['type'] < 3){
            $obj['axes_all_directions'] = $this->axesAllDirections($location);
        }
        if(is_null($achievement) || is_null($achievement->access_granted) || $achievement->access_granted['type'] < 3){
            $obj['access_granted'] = $this->accessGranted();
        }
        if(is_null($achievement) || is_null($achievement->in_love_bullseye) || $achievement->in_love_bullseye['type'] < 3){
            $obj['in_love_bullseye'] = $this->inLoveBullseye($location);
        }
        if(is_null($achievement) || is_null($achievement->in_love_sniper) || $achievement->in_love_sniper['type'] < 3){
            $obj['in_love_sniper'] = $this->inLoveSniper($location);
        }
        if(is_null($achievement) || is_null($achievement->in_love_throw_royal) || $achievement->in_love_throw_royal['type'] < 3){
            $obj['in_love_throw_royal'] = $this->inLoveThrowRoyal($location);
        }
        if(is_null($achievement) || is_null($achievement->in_love_monopoly) || $achievement->in_love_monopoly['type'] < 3){
            $obj['in_love_monopoly'] = $this->inLoveMonopoly($location);
        }
        if(is_null($achievement) || is_null($achievement->hype) || $achievement->hype['type'] < 3){
            $obj['hype'] = $this->hype();
        }
        if(is_null($achievement) || is_null($achievement->packed_to_eyeballs) || $achievement->packed_to_eyeballs['type'] < 3){
            $obj['packed_to_eyeballs'] = $this->packedToEyeballs();
        }
        if(is_null($achievement) || is_null($achievement->who_you) || $achievement->who_you['type'] < 3){
            $obj['who_you'] = $this->whoYou();
        }

        $obj['last_update'] = Carbon::now();

        return $obj;
    }

    // расскрыть все игры сессий , сортировка игр по дате
    private function revealAllGames($location){
        $all_games = [];
        // 1 выбрать уникальные id игроков из всех игр на этой локации
        foreach ($location->attractions as $key => $attraction){
            // есть игры в истории
            if(count($attraction['hystory_sessions'])){
                foreach ($attraction['hystory_sessions'] as $key2 => $session){
                    // есть статистика игроков в сессии
                    if(isset($session["result"]["stats"])){
                        foreach ($session["result"]["stats"] as $key3 => $gamer){
                            // есть игрок
                            if (isset($gamer['gamer_id'])) {
                                array_push($all_games, [
                                    'created_session'=>$session["created"],
                                    'gamer'=>$gamer,
                                ]);
                            }
                        }
                    }
                }
            }
        }

        // сортировка игр по дате
        usort($all_games, array($this, 'sort_by_time') );
        $this->allGames = $all_games;
    }

    // сортировка по времени
    private function sort_by_time($a,$b) {
        $a_time = strtotime($a['created_session']);
        $b_time = strtotime($b['created_session']);
        return $a_time > $b_time;
    }

    // фиксация достижения
    private function fixingAchievement($count_gamer, $config, $achievement){
        if($count_gamer >= $config['type'][0]){
            $achievement[0] = true;
        }
        if($count_gamer >= $config['type'][1]){
            $achievement[1] = true;
        }
        if($count_gamer >= $config['type'][2]){
            $achievement[2] = true;
        }
        return $achievement;
    }

    // подставить значение для строки достижения
    private function insertDescription($config, $value, $type){
        $config['description_ru'] = str_replace(":type", $value, $config['description_ru']);
        $config['description_en'] = str_replace(":type", $value, $config['description_en']);
        $config['type'] = $type;
        return $config;
    }

    // заполнить данные достижений
    private function fillAchievementsData($config, $achievement){
        $data = null;
        if($achievement[0] == true){
            $data = $this->insertDescription($config, $config['type'][0], 1);
        }
        if($achievement[1] == true){
            $data = $this->insertDescription($config, $config['type'][1], 2);
        }
        if($achievement[2] == true){
            $data = $this->insertDescription($config, $config['type'][2], 3);
        }
        return $data;
    }

    // заполнить данные достижений второй вариант
    private function fillAchievementsData_2($config, $count){
        $data = null;
        if($count >= $config['type'][0]){
            $data = $this->insertDescription($config, $config['type'][0], 1);
        }
        if($count >= $config['type'][1]){
            $data = $this->insertDescription($config, $config['type'][1], 2);
        }
        if($count >= $config['type'][2]){
            $data = $this->insertDescription($config, $config['type'][2], 3);
        }
        return $data;
    }

}
