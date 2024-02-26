<?php
namespace App\Http\Traits;

use App\Events\AddGamer;
use App\Events\DeleteGamer;
use App\Events\ExitGamer;
use App\Events\ResponseAddGamer;
use App\Events\SessionPlayerTransitEvent;
use App\Events\SessionStatusEvent;
use App\Events\QueryAddGamer;
use App\Exceptions\UserException;
use App\Facades\MyFunctions;
use App\Repositories\AttractionRepository;
use App\Repositories\HistorySessionRepository;
use App\Repositories\HistorySessionUserRepository;
use App\Repositories\LocationModeStatisticRepository;
use App\Repositories\PlayerStatisticLocationsRepository;
use App\Repositories\SessionRepository;
use App\Repositories\SessionUserRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;

trait SessionTraite {

    // ========================================
    // создать сессию
    protected function creatSession($index)
    {

        // доступы аттракцион, root для admin или admin
        $this->service->attractionRootOrAdmin($this->access_attraction, $index);

        // уже есть открытая сессия
        if ($session = (new AttractionRepository)->session_any($index['attraction_id'])) {
            throw new UserException(config('game.custom_value.session.1'), 2);
        }

        $session = (new SessionRepository)->create(
            $this->creatParameterForSession($index)
        );

        return $session;
    }

    // ========================================
    // добавление игрока и обновление токена сессии
    protected function addGamerSession($index)
    {

        $bool = false;

        // авторизованный или token-attr
        $this->service->sessionRootOrAdmin($this->access_attraction, $index);

        // открытая сессия по токену и id аттракциона
        if (!$session = (new SessionRepository())->showOpenTokenSession($index)) {
            throw new UserException(config('game.custom_value.session.3'), 3);
        }

        // если игрок подтвердил на аттракционе удаление себя из сессии
        if ($index['confirmation']) {
            // логика добавления игрока и токена сессии
            $bool = $this->addGamerUpdateSession($session, $index);
        }

        // сессия, игроки в ней, атракцион-локация-статистика локации
        $session = (new SessionRepository())->showSessionWithDataUser($index['session_id']);

        // перебрать статистику локации и оставить только игроков сессии
        $session = $this->onlyStatisticSession($session);

        // отправить socket игроку
        event(new ResponseAddGamer([$index['gamer_id'], $bool]));

        return $session;
    }

    // ========================================
    // выбрать указанную сессию
    protected function showSession($index)
    {

        // выбрать сессию и ее аттракцион
        if ($session = (new SessionRepository())->showSessionWithDataUser($index['session_id'])) {
            // перебрать статистику локации и оставить только игроков сессии
            $session = $this->onlyStatisticSession($session);
            return $session;
        }

        throw new UserException(config('game.custom_value.session.6'), 4);
    }

    // ========================================
    // удаляет указанную сессию
    protected function deleteSession($index)
    {

        // доступы аттракцион, root для admin или admin
        $this->service->sessionRootOrAdmin($this->access_attraction, $index);

        // удаление игроков сессии или одного указанного
        $this->deleteTargetGamersSession($index['session_id']);

        // удалить сессию
        (new SessionRepository())->delete($index['session_id']);

        // выслать socket в клиент о запуске сессии
        event(new SessionStatusEvent([$index['session_id'], 'close']));
    }

    // ========================================
    // закрывает указанную сессию
    // создает новую и обновляет игроков вней
    protected function closeSession($index) {
        // доступы аттракцион, root для admin или admin
        $this->service->sessionRootOrAdmin($this->access_attraction, $index);
        // выбрать сессия, игроки с картинками, аттракцион сессии, локация сессии
        $session = $this->sessionRepository->showSessionWithDataUser($index['session_id']);

        // >>> 1 увеличивает кол-во ссыгранных игр локации в определенном режиме
        $this->updateStatisticModeLocation($session);
        // >>> 2 внести сессию и игроков в history
        $this->addDataInHistory($session, $index);
        // >>> 3 обновить статистику игрока глобально и на локации аттракциона
        $this->updateStatisticGamersLocation($session, $index);
        // >>> 4 удалить старую, создать новую сесиию, обновить параметр игроков
        $session = $this->creatNewDeleteOldSession($session, $index['session_id']);

        // выбрать новую сессию, игроки в ней, атракцион-локация-статистика локации
        $session = $this->sessionRepository->showSessionWithDataUser($session->id);
        // перебрать статистику локации и оставить только игроков сессии
        $session = $this->onlyStatisticSession($session);
        // выслать socket в клиент об остановке сессии
        event(new SessionStatusEvent([$index['session_id'], 'stop']));

        return $session;
    }

    // ========================================
    // удалить игрока из сессии
    protected function deleteGamerSession($index)
    {

        $bool = false;

        // доступы аттракцион, root для admin или admin
        $this->service->sessionRootOrAdmin($this->access_attraction, $index);

        // если игрок подтвердил на аттракционе удаление себя из сессии
        if ($index['confirmation']) {
            // удаление указанного игрока из сессии
            $this->deleteTargetGamersSession($index['session_id'], $index['gamer_id']);
            $bool = true;
        }

        // выбрать сессию и игроков
        $session = (new SessionRepository())->showSessionWithDataUser($index['session_id']);
        // перебрать статистику локации и оставить только игроков сессии
        $session = $this->onlyStatisticSession($session);

        // выслать socket в клиент о статусе его удаления
        event(new DeleteGamer([$index['gamer_id'], $bool]));

        return $session;
    }

    // ========================================
    // сменить режим игры
    protected function changeModeSession($index)
    {

        // доступы аттракцион, root для admin или admin
        $this->service->sessionRootOrAdmin($this->access_attraction, $index);

        // выбрать сессию и проверка доступа
        $session = (new SessionRepository())->showSessionWithDataUser($index['session_id']);

        // в текущей пати сессии больше чем положено игроков
        if (count($session->sess_user_many) > $index['player_max_count']) {
            throw new UserException(config('game.custom_value.session.9'), 2);
        }

        // обновить данные сессии
        $bool = (new SessionRepository())->update($index['session_id'], [
            'mode_id' => $index['mode_id'],
            'player_max_count' => $index['player_max_count'],
        ]);

        // выбрать сессию, игроки в ней, атракцион-локация-статистика локации
        $session = (new SessionRepository())->showSessionWithDataUser($index['session_id']);
        // перебрать статистику локации и оставить только игроков сессии
        $session = $this->onlyStatisticSession($session);

        return $session;
    }

    // ========================================
    // активировать сессию
    protected function activateSessionTrate($index)
    {

        // доступы аттракцион, root для admin или admin
        $this->service->sessionRootOrAdmin($this->access_attraction, $index);

        // обновить данные сессии
        $bool = (new SessionRepository())->update($index['session_id'], [
            'activate' => 1,
        ]);

        // выслать socket в клиент о запуске сессии
        event(new SessionStatusEvent([$index['session_id'], 'start']));

        return $bool;
    }

    // ========================================
    // Деактивировать сессию
    protected function deactivationSessionTrate($index) {

        // доступы аттракцион, root для admin или admin
        $this->service->sessionRootOrAdmin($this->access_attraction, $index);

        // обновить данные сессии
        $bool = (new SessionRepository())->update($index['session_id'], [
            'activate' => 0,
        ]);

        // выслать socket в клиент о деактивации сессии
        event(new SessionStatusEvent([$index['session_id'], 'deactivation']));

        return $bool;
    }

    // ========================================
    // переход хода игрока
    protected function playerTransitSession($index)
    {

        // доступы аттракцион, root для admin или admin
        $this->service->sessionRootOrAdmin($this->access_attraction, $index);

        // отправить json от аттракциона юзеру
        event(new SessionPlayerTransitEvent([$index['session_id'], $index['data']]));
        return true;
    }

    // ========================================
    // Запрос от игрока на добавление к сессии
    protected function playerStartSession($index)
    {

        // доступы к сессии - только игрок
        $this->service->sessionGamer($index);

        // отправить socket в аттракцион
        event(new QueryAddGamer([$index['session_id'], $index['gamer_id']]));
        return true;
    }

    // ========================================
    // проверка игроком состоит-ли в сессии. Выдает все данные о сессии
    protected function checkGamerSession() {

        $auth = Auth::user();

        $coll = (new SessionUserRepository())->getSessionData($auth->id);

        $arr = [
            'connected_to_session' => is_null($coll) ? false : true,
            'session' => $coll,
        ];

        return $arr;
    }

    // ========================================
    // проверка аттракционом состоит-ли в сессии. Выдает все данные о сессии
    protected function checkAttractionTraite($index) {

        $coll = (new SessionRepository())->getSessionData([
            'attraction_id'=>$index['attraction_id']
        ]);

        $arr = [
            'connected_to_session' => is_null($coll) ? false : true,
            'session' => $coll,
        ];

        return $arr;
    }

    // ========================================
    // завершение игры игроком
    protected function playerExitSession($index)
    {

        // Я игрок и переданый id мой
        $this->service->checkGamerId($index);

        // отправить socket в аттракцион
        event(new ExitGamer([$index['session_id'], $index['gamer_id']]));
        return true;
    }

    // ========================================
    // выбрать информацию прошлой сессии и игроков в ней
    protected function getInfoGameTraite($index) {
        // сессия истории, аттракцион и локация аттракциона
        $session = (new HistorySessionRepository)->getInfoGame(['session_id' => $index['session_id']]);
        // выбрать игроков по id вместе с image
        $users = $this->selectUsersAndImages($session);

    return [ 'session'=>$session, 'users'=>$users ];
    }


// PRIVATE
// =================================================
// увеличивает кол-во ссыгранных игр локации в определенном режиме
    private function updateStatisticModeLocation($session) {
        $coll = (new LocationModeStatisticRepository())->whereArrFirst([
            'location_id'=>$session->attraction->location["id"],
            'mode_id'=>$session["mode_id"],
        ]);

        if(!is_null($coll)){
            $coll->increment('count_game');
        }else{
            (new LocationModeStatisticRepository())->create([
                'location_id'=>$session->attraction->location["id"],
                'mode_id'=>$session["mode_id"],
                'count_game'=>1,
            ]);
        }
    }

// =================================================
// внести сессию и игроков в history
    private function addDataInHistory($session, $index) {
        // два масива - сессия, игроки в сессии, для таблиц истории
        $arr_tables = $this->makeColumnsHistory($session, $index['result_game']);

        try {
            $history1 = (new HistorySessionRepository())->create($arr_tables[0]);
            if (count($arr_tables[1])) {
                foreach ($arr_tables[1] as $key => $array) {
                    $history2 = (new HistorySessionUserRepository())->create($array);
                }
            }
        } catch (\Exception $e) {
            throw new UserException(config('game.custom_value.session.7'), 3);
        }
    }

// =================================================
// выбрать игроков по id вместе с image
    private function selectUsersAndImages($session)
    {
        $users_id = [];
        $users = [];

        if (!is_null($session->result['stats'])) {
            foreach ($session->result['stats'] as $key => $obj) {
                if ($obj['gamer_id'] != 0) {
                    array_push($users_id, $obj['gamer_id']);
                }
            }
        }

        if ($users_id) {
            $users = (new UserRepository())->whereInImage('id', $users_id);
        }

        return $users;
    }

// =================================================
// создать масив параметров для новой сессии
    private function creatParameterForSession($index, $parent_id = 0)
    {

        //создать Token
        $token_session = MyFunctions::generateStr(50);
        //создать сессию
        $array = [
            'attraction_id' => $index['attraction_id'],
            'mode_id' => $index['mode_id'],
            'session_token' => $token_session,
            'player_max_count' => $index['player_max_count'],
            'parent_id' => $parent_id,
        ];

        return $array;
    }

// =================================================
// логика добавления игрока и токена сессии
    private function addGamerUpdateSession($session, $index)
    {
        // свободное кол-во мест
        $free_count = $session->player_max_count - count($session->sess_user_many);
        $session_user = null;

        if ($free_count > 0) {

            $array = collect($session->sess_user_many)->pluck('user_id')->toArray();
            // Создать игрока если его нет в списке
            if (array_search($index['gamer_id'], $array) === false) {
                $session_user = (new SessionUserRepository)->create([
                    'session_id' => $index['session_id'],
                    'user_id' => $index['gamer_id'],
                ]);
            }

            // отправить в аттракцион, через Socket, данные о добавленом игроке
            if (!is_null($session_user)) {
                // выбрать сессионного юзера и его данные
                $gamer = (new SessionUserRepository)->getSessionEndUser($session_user->id);
                event(new AddGamer([$index['attraction_id'], $gamer]));
            }

            //создать Token
            $token_session = MyFunctions::generateStr(50);

            // обновить данные сессии
            $attraction = (new SessionRepository())->update($index['session_id'], [
                'session_token' => $token_session,
            ]);

        }

        return !is_null($session_user) ? true : false;
    }

// =================================================
// удаление игроков сессии или одного указанного
    private function deleteTargetGamersSession($session_id, $gamer_id = null)
    {

        // сессия и игроки
        $session = (new SessionRepository())->showSessionUser($session_id);

        // удалить игроков сессии
        if (isset($session->sess_user_many)) {
            // масив id игроков
            $arr_user_id = collect($session->sess_user_many)->pluck('user_id')->toArray();

            if (count($arr_user_id)) {

                // если указали игрока и он есть в сессии - удалить только его
                if (!is_null($gamer_id) && array_search($gamer_id, $arr_user_id) !== false) {
                    $arr_user_id = [$gamer_id];
                } // если указали игрока и его нет в сессии
                elseif (!is_null($gamer_id) && array_search($gamer_id, $arr_user_id) === false) {
                    throw new UserException(config('game.custom_value.session.5'), 4);
                }

                // удалить всех или указанного игрока
                (new SessionUserRepository())->deleteMany($arr_user_id, $session_id);
            }
        } else {
            throw new UserException(config('game.custom_value.session.5'), 4);
        }
    }

// =================================================
// логика удаления игроков сессии
    private function makeColumnsHistory($session, $result) {
        $result = json_decode($result);
        $arr_users = [];

        // сессия
        $arr_session = [
            'session_id' => $session["id"],
            'parent_id' => $session["parent_id"],
            'attraction_id' => $session["attraction_id"],
            'mode_id' => $session["mode_id"],
            'player_max_count' => $session["player_max_count"],
            'result' => $result,
            'created' => $session["created_at"],
            'updated' => $session["updated_at"],
        ];

        // игроки
        if (isset($session["sess_user_many"])) {
            foreach ($session["sess_user_many"] as $key => $array) {
                array_push($arr_users, [
                    'session_id' => $array["session_id"],
                    'user_id' => $array["user_id"],
                    'result' => $this->resultGamer($result, $array["user_id"]),
                    'created' => $session["created_at"],
                    'updated' => $session["updated_at"],
                ]);
            }
        }

        return [$arr_session, $arr_users];
    }

// =================================================
// результат игры юзера
    private function resultGamer($result, $user_id) {

        $data = null;

        if (isset($result->stats)) {
            foreach ($result->stats as $key => $obj) {
                if ($obj->gamer_id == $user_id) {
                    $data = $obj;
                }
            }
        }

        return $data;
    }

// =================================================
// перебрать статистику локации и оставить только игроков сессии
    private function onlyStatisticSession($session) {
        $filtered = null;
        // выбрать id игроков сессии
        $ids = $this->selectIdGamers($session);

        // 2 убрать ненужных игроков
        if (isset($session->attraction->location->statistic)) {

            $collection = collect($session->attraction->location->statistic);

            $filtered = $collection->filter(function ($arr) use ($ids) {
                return in_array($arr->user_id, $ids);
            })->all();
        } else {
            throw new UserException(config('game.custom_value.session.10'), 4);
        }

        $arr = $session->toArray();
        $arr["attraction"]["location"]["statistic"] = array_values($filtered);

        return collect($arr);
    }

// =================================================
// удалить старую, создать новую сесиию, обновить параметр игроков
    private function creatNewDeleteOldSession($session, $session_id) {
        try {

            $parent_id = $session->parent_id == 0 ? $session->id : $session->parent_id;

            // создать параметры для новой сессии
            $arrParams = $this->creatParameterForSession([
                'attraction_id' => $session->attraction_id,
                'mode_id' => $session->mode_id,
                'player_max_count' => $session->player_max_count,
            ], $parent_id);

            // 1 создать новую сессию
            $session = (new SessionRepository)->create($arrParams);

            // 2 обновить параметр игроков новой сессии
            (new SessionUserRepository())->updateWhereSessionId($session_id, [
                'session_id' => $session->id,
                'created_at' => $session->created_at,
                'updated_at' => $session->updated_at,
            ]);

            // 3 удалить старую сессию
            $bool = (new SessionRepository())->delete($session_id);

        } catch (\Exception $e) {
            throw new UserException(config('game.custom_value.session.11'), 3);
        }

        return $session;
    }

// =================================================
// выбрать id игроков сессии
    private function selectIdGamers($session)
    {

        $ids = [];

        // 1 выбрать id игроков сессии
        foreach ($session->sess_user_many as $key => $arr) {
            array_push($ids, $arr->user_id);
        }

        return $ids;
    }

// =================================================
// обновить статистику игрока глобально и на локации аттракциона
    private function updateStatisticGamersLocation($session, $index)
    {
        // выбрать id игроков сессии
        $gamers_id = $this->selectIdGamers($session);
        $location_id = [$session->attraction->location->id];

        $json = json_decode($index['statistic_gamers']);
        //{"gamers":[{"id":1,"statistic":{}},{"id":3,"statistic":{}}]}
        // обновить глобальную статистику игроков
        if (isset($json->gamers)) {
            foreach ($json->gamers as $key => $obj) {
                // игрок присутствует в статистике
                if (in_array($obj->id, $gamers_id)) {
                    (new UserRepository())->update($obj->id, ['statistic' => json_encode($obj->statistic)]);
                } else {
                    throw new UserException(config('game.custom_value.session.12'), 4);
                }
            }
        }

        $json = json_decode($index['statistic_location_gamers']);
        // {"gamers":[{"location_id":1,"user_id":5,"statistic":{}},{"location_id":1,"user_id":3,"statistic":{}}]}
        // обновить статистику игроков на локации
        if (isset($json->gamers)) {
            foreach ($json->gamers as $key => $obj) {
                // игрок и локация присутствует в статистике
                if (in_array($obj->user_id, $gamers_id) && in_array($obj->location_id, $location_id)) {
                    (new PlayerStatisticLocationsRepository())->updateOrCreate(
                        ['user_id' => $obj->user_id, 'location_id' => $obj->location_id],
                        ['statistic' => $obj->statistic]
                    );
                } else {
                    throw new UserException(config('game.custom_value.session.12'), 4);
                }
            }
        }
    }

}
