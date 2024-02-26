<?php
namespace App\Http\Controllers;

use App\Http\Requests\Session\ActivateSessionRequest;
use App\Http\Requests\Session\ChangeModeSessionRequest;
use App\Http\Requests\Session\CheckAttractionRequest;
use App\Http\Requests\Session\CloseSessionRequest;
use App\Http\Requests\Session\DeleteGamerSessionRequest;
use App\Http\Requests\Session\DestroySessionRequest;
use App\Http\Requests\Session\EditSessionRequest;
use App\Http\Requests\Session\GetInfoGameRequest;
use App\Http\Requests\Session\PlayerExitRequest;
use App\Http\Requests\Session\PlayerStartRequest;
use App\Http\Requests\Session\PlayerTransitRequest;
use App\Http\Requests\Session\UpdateSessionRequest;
use App\Http\Requests\Session\StoreSessionRequest;
use App\Http\Traits\SessionTraite;
use App\Repositories\SessionRepository;
use App\Services\HeaderCheckTokenAttractionServices;
use App\Services\Permissions\PermissionsSessionServices;
use Illuminate\Http\Request;

class SessionController extends Controller {

    use SessionTraite;

    protected $access_attraction;
    protected $sessionRepository;
    protected $service;

    public function __construct(Request $req) {
        // проверяет токен аттракциона в header и ищет его в таблице аттракцион
        if(!$this->access_attraction = (new HeaderCheckTokenAttractionServices())->checkToken($req)){
            // в другом случае авторизует по bearer token
            $this->middleware('auth:api');
        }
        // доступы
        $this->service = new PermissionsSessionServices();
        $this->sessionRepository = new SessionRepository();
    }

    /**
     * Создать новую сессию
     * доступы token-attr, root для admin или admin
     * Post /api/session
     * Header - Authorization Token
     * mod_id - указанный режим игры
     * player_max_count - максимальное кол-во участников в режиме
     * attraction_id - id аттракциона
     * admin_id - указанный id администратора атракциона
     * @param StoreSessionRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\UserException
     */
    public function store(StoreSessionRequest $request) {
        $index = $request->validated();
        $session = $this->creatSession($index);

        return response()->json($session, 201);
    }

    /**
     * Добавить к сессии игрока.
     * доступы token-attr или любой авторизованный
     * PATCH /api/session/id сессии
     * Header - Authorization Token
     * session_token - токен верификации нового игрока
     * attraction_id - аттракцион
     * gamer_id - id игрока для добавления
     * admin_id - хозяин аттракциона
     * @param UpdateSessionRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\UserException
     */
    public function update(UpdateSessionRequest $request) {
        $index = $request->validated();
        // добавить игрока в сессию
        $session = $this->addGamerSession($index);

    return response()->json($session, 201);
    }

    /**
     * Выбрать указанную сессию.
     * доступы - все авторизованные и по session_id
     * GET /api/session/id сессии
     * Header - Authorization Token
     * attraction_id - id аттракциона
     * @param EditSessionRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\UserException
     */
    public function show(EditSessionRequest $request) {
        $index = $request->validated();
        $session = $this->showSession($index);

    return response()->json($session, 201);
    }

    /**
     * Удалить Сессию и ее игроков.
     * доступы token-attr, root для admin или admin
     * DELETE /api/session/id сессии
     * Header - Authorization Token
     * admin_id
     * attraction_id
     * session_token
     * @param DestroySessionRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(DestroySessionRequest $request) {
        $index = $request->validated();
        $this->deleteSession($index);

    return response()->json([
        'code' => 777,
        'status' => 'success',
        'message' => config('game.custom_value.session.4'),
    ], 201);
    }

    /**
     * Завершение игры. Сессия и игроки переносится в исторю. Создается подсессия
     * доступы token-attr, root для admin или admin
     * POST /api/session/close_session
     * Header - Authorization Token
     * admin_id - админ аттракциона
     * attraction_id - id аттракциона
     * session_token - токен сессии
     * result_game - итоги игры с участниками
     * statistic_gamers - полная статистика игроков
     * statistic_location_gamers - статистика игроков на локации аттракциона
     * @param CloseSessionRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\UserException
     */
    public function close(CloseSessionRequest $request){
        $index = $request->validated();
        $session = $this->closeSession($index);

    return response()->json( $session , 201);
    }

    /**
     * Удалить из сессии игрока.
     * доступы token-attr, root для admin или admin
     * POST /api/session/delete_gamer
     * Header - Authorization Token
     * session_id - id сессии
     * gamer_id - id игрока
     * admin_id - админ аттракциона
     * attraction_id - id аттракциона
     * session_token - токен сессии
     * @param DeleteGamerSessionRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteGamer(DeleteGamerSessionRequest $request){
        $index = $request->validated();
        $session = $this->deleteGamerSession($index);

    return response()->json( $session , 201);
    }

    /**
     * Изменить режим игры.
     * доступы token-attr, root для admin или admin
     * POST /api/session/change_mode
     * Header - Authorization Token
     * session_id - id сессии
     * admin_id - админ аттракциона
     * attraction_id - id аттракциона
     * session_token - токен сессии
     * mod_id - указанный режим игры
     * player_max_count - максимальное кол-во участников в режиме
     * @param ChangeModeSessionRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\UserException
     */
    public function changeMode(ChangeModeSessionRequest $request){
        $index = $request->validated();
        $session = $this->changeModeSession($index);

    return response()->json( $session , 201);
    }

    /**
     * Активировать сессию.
     * доступы token-attr, root для admin или admin
     * POST /api/session/activate_session
     * Header - Authorization Token
     * session_id - id сессии
     * admin_id - админ аттракциона
     * attraction_id - id аттракциона
     * session_token - токен сессии
     * @param ActivateSessionRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\UserException
     */
    public function activateSession(ActivateSessionRequest $request){
        $index = $request->validated();
        $bool = $this->activateSessionTrate($index);

    return response()->json( $bool , 201);
    }

    /**
     * Деактивировать сессию.
     * доступы token-attr, root для admin или admin
     * POST /api/session/deactivation_session
     * Header - Authorization Token
     * session_id - id сессии
     * admin_id - админ аттракциона
     * attraction_id - id аттракциона
     * session_token - токен сессии
     * @param ActivateSessionRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\UserException
     */
    public function deactivationSession(ActivateSessionRequest $request){
        $index = $request->validated();
        $bool = $this->deactivationSessionTrate($index);

        return response()->json( $bool , 201);
    }

    /**
     * проверка аттракционом состоит-ли в сессии.
     * может только аттракцион
     * POST /api/session/check_attraction_session
     * Header - Authorization Token
     * @param CheckAttractionRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkAttraction(CheckAttractionRequest $request){
        $index = $request->validated();
        $arr = $this->checkAttractionTraite($index);

        return response()->json( $arr , 201);
    }



    /**
     * переход хода игрока.
     * доступы token-attr, root для admin или admin
     * POST /api/session/player_transit
     * Header - Authorization Token
     * session_id - id сессии
     * admin_id - админ аттракциона
     * attraction_id - id аттракциона
     * session_token - токен сессии
     * @param PlayerTransitRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    // переход хода игрока
    public function playerTransit(PlayerTransitRequest $request){
        $index = $request->validated();
        $bool = $this->playerTransitSession($index);

    return response()->json( $bool , 201);
    }

    /**
     * Запрос от игрока на добавление к сессии.
     * может только игрок
     * POST /api/session/player_start
     * Header - Authorization Token
     * session_id - id сессии
     * gamer_id - id игрока
     * session_token - токен сессии
     * @param PlayerStartRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function playerStart(PlayerStartRequest $request){
        $index = $request->validated();
        $bool = $this->playerStartSession($index);

    return response()->json( $bool , 201);
    }

    /**
     * проверка игроком состоит-ли в сессии.
     * может только игрок
     * POST /api/session/check_gamer_session
     * Header - Authorization Token
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkGamer(){
        $arr = $this->checkGamerSession();

    return response()->json( $arr , 201);
    }

    /**
     * завершение игры игроком. может только игрок
     * POST /api/session/player_exit
     * Header - Authorization Token
     * session_id - id сессии
     * gamer_id - id игрока
     * @param PlayerExitRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function playerExit(PlayerExitRequest $request){
        $index = $request->validated();
        $bool = $this->playerExitSession($index);

    return response()->json( $bool , 201);
    }

    /**
     * выбрать информацию прошлой сессии и игроков в ней
     * POST /api/session/get_info_game
     * Header - Authorization Token
     * session_id - id сессии
     * @param GetInfoGameRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getInfoGame(GetInfoGameRequest $request){
        $index = $request->validated();
        $json = $this->getInfoGameTraite($index);

        return response()->json( $json , 201);
    }

}
