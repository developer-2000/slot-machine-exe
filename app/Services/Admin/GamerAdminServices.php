<?php
namespace App\Services\Admin;

use App\Models\HistorySessionUsers;
use App\Models\PlayerAchievement;
use App\Models\User;
use App\Repositories\HistorySessionRepository;
use App\Repositories\UserRepository;
use App\Services\Permissions\PermissionsAdminPanelServices;

class GamerAdminServices {

    protected $permissionService;
    protected $userRepository;
    protected $adminService;
    protected $collGamer;
    protected $step;

    public function __construct() {
        $this->permissionService = new PermissionsAdminPanelServices();
        $this->userRepository = new UserRepository();
        $this->adminService = new AdminServices();
        $this->collGamer = User::leftJoin('users_roles', 'users.id', '=', 'users_roles.user_id')
            ->where('users_roles.role_id', 3);
    }

    // ========================================
    // выбрать всех игроков в Пагинации
    public function getGamers($index, $user) {

        // проверка существования параметров запроса
        if(is_string($check = $this->adminService->checkParameters($index))){
            return $check;
        }
        // доступ root
        $this->permissionService->checkRoot($user);
        $this->step = $this->adminService->getCountPaginate();
        $pagination = null;

        // есть поиск
        if (isset($index['filter']) && gettype($index['filter']) == 'string') {
            $pagination = $this->search($index);
        }
        // нет поиска
        else {
            // есть сортировка
            if(!is_null($index['sort']) && !is_null($index['operator'])){
                $pagination = $this->sorting($index);
            }
            // нет сортировки
            else{
                $pagination = $this->collGamer
                    ->where('id', '>', 0)
                    ->with('history_session_user')
                    ->select('users.*')
                    ->paginate($this->step);
            }
        }

        // пересобрать данные
        return $this->rebuildData($pagination);
    }


    // PRIVATE
    // >>> фильтр поиска
    private function search($index){

        $this->collGamer = $this->collGamer
            ->where("users.nickname", 'like', "{$index['filter']}%")
            ->orWhere("users.first_name", 'like', "{$index['filter']}%");

            if($index['sort'] == 'date_of_birth'){
                $pagination = $this->collGamer
                    ->with('history_session_user')
                    ->select('users.*')
                    ->orderBy($index['sort'], $index['operator'])
                    ->paginate($this->step);
            }
            elseif($index['sort'] == 'count_game'){
                $pagination = $this->collGamer
                        // связь с играми игроков
                        ->leftJoin('history_session_users', 'users.id', '=', 'history_session_users.user_id')
                        ->selectRaw("users.*, count(history_session_users.user_id) AS `count_game`")
                        ->groupBy('users.id')
                        ->orderBy('count_game', $index['operator'])
                    ->paginate($this->step);
            }
            elseif($index['sort'] == 'last_game'){
                if( $array = $this->collGamer->pluck('users.id') ){
                    $pagination = $this->sortLastGame($index, $array);
                }
            }
            // нет сортировки
            else{
                $pagination = $this->collGamer
                    ->select('users.*')
                    ->with('history_session_user')
                    ->paginate($this->step);
            }

        return $pagination;
    }

    // >>> пересобрать данные
    private function rebuildData($pagination){
        $arrAttractions = [];
        $pagination = collect($pagination);

        foreach ($pagination['data'] as $key => $obj) {
            array_push($arrAttractions, [
                'id' => $obj["id"],
                'first_name' => $obj["first_name"],
                'nickname' => $obj["nickname"],
                'date_of_birth' => $obj["date_of_birth"],
                'last_game' => $this->lastGameGamer($obj),
                'last_location' => $this->lastGameLocation($obj),
                'progress' => null,
                'count_game' => $this->countGameGamer($obj),
                'favorite_mode' => null,
                'activation' => $obj["activation"],
                'count_achievements' => $this->countAchievements($obj["id"]),
            ]);
        }

        $pagination['data'] = $arrAttractions;

        return $pagination;
    }

    // >>> сортировать данные
    private function sorting($index){

        if($index['sort'] == 'date_of_birth'){
            $pagination = $this->collGamer
                ->with('history_session_user')
                ->select('users.*')
                ->orderBy($index['sort'], $index['operator'])
                ->paginate($this->step);
        }
        elseif($index['sort'] == 'count_game'){

            $pagination = $this->collGamer
                // связь с играми игроков
                ->leftJoin('history_session_users', 'users.id', '=', 'history_session_users.user_id')
                ->selectRaw("users.*, count(history_session_users.user_id) AS `count_game`")
                ->groupBy('users.id')
                ->orderBy('count_game', $index['operator'])
                ->paginate($this->step);
        }
        elseif($index['sort'] == 'last_game'){
            // сортировка последних игр игрока
            $pagination = $this->sortLastGame($index);
        }

        return $pagination;
    }

    // >>> сортировка последних игр игрока
    private function sortLastGame($index, $filter = null){

        $last_game_id = [];

        // $filter не пуст если задействован поиск - придут поисковые id игроков
        $arrUserId = is_null($filter) ?
            $this->collGamer ->pluck('users.id') :
            $filter;

        // последние игры игроков
        $last_games = User::whereIn('users.id', $arrUserId) ->with('last_game_user') ->get();
        // выбрать id игр
        foreach ($last_games as $key => $last_game){
            // у игрока может не быть игры
            if(isset($last_game['last_game_user']->id)){
                array_push($last_game_id, $last_game['last_game_user']->id);
            }
        }

        // сортируем нужные игры в заданом порядке
        $pagination = User::leftJoin('history_session_users', 'users.id', '=', 'history_session_users.user_id')
            ->whereIn('history_session_users.id', $last_game_id)
            ->selectRaw("users.*, history_session_users.updated AS `data_last_game`, history_session_users.session_id AS `last_session_id`")
            ->orderBy("data_last_game", $index['operator'])
            ->paginate($this->step);

        return $pagination;
    }

    // >>> последняя игра игрока
    private function lastGameGamer($obj){
        // при выборке без поиска и сортировки
        if(isset($obj["history_session_user"])){
            return count($obj["history_session_user"]) ? array_pop($obj["history_session_user"])["updated"] : null;
        }
        // при сортировке last_game
        elseif(isset($obj["data_last_game"])){
            return $obj["data_last_game"];
        }

        // при сортировке count_game
        $coll = User::where('id', $obj["id"])
            ->with('last_game_user') ->first();

        return $coll->last_game_user['updated'];
    }

    // >>> локация последней игры
    private function lastGameLocation($obj){
        // при выборке без поиска и сортировки
        if(isset($obj["history_session_user"])){
            $session_id = count($obj["history_session_user"]) ? array_pop($obj["history_session_user"])["session_id"] : null;
        }
        // при сортировке last_game
        elseif(isset($obj["last_session_id"])){
            $session_id = $obj["last_session_id"];
        }
        else{
            // при сортировке count_game и date_of_birth
            $coll = User::where('id', $obj["id"]) ->with('last_game_user') ->first();
            $session_id = $coll->last_game_user['session_id'];
        }


        $session = null;
        $loc = null;

        if(!is_null($session_id)){
            $session = (new HistorySessionRepository())->getInfoGame([
                'session_id' => $session_id
            ]);

            $loc = $session['attraction']["location"];
        }

        return [
            'location_id' => is_null($loc) ? null : $loc['id'],
            'title' => is_null($loc) ? null : $loc['title']
        ];
    }

    // >>> последняя игра игрока
    private function countGameGamer($obj){
        // при выборке без поиска и сортировки
        if(isset($obj["history_session_user"])){
            return count($obj["history_session_user"]) ? count($obj["history_session_user"]) : 0;
        }
        // при сортировке count_game
        elseif(isset($obj["count_game"])){
            return $obj["count_game"];
        }

        // при сортировке last_game
        return $this->userCountGame($obj["id"]);
    }

    // >>> все игры игрока
    private function userCountGame($id){
        return HistorySessionUsers::where('user_id', $id)
            ->count();
    }

    // >>> все игры игрока
    private function countAchievements($id){
        return PlayerAchievement::where('user_id', $id)
            ->count();
    }

}
