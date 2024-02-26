<?php
namespace App\Http\Controllers\Attraction;

use App\Http\Controllers\Controller;
use App\Http\Requests\Attraction\ActivationAttractionUpdateRequest;
use App\Http\Requests\Attraction\DestroyAttractionRequest;
use App\Http\Requests\Attraction\EditAttractionRequest;
use App\Http\Requests\Attraction\SigninAttractionRequest;
use App\Http\Requests\Attraction\StoreAttractionRequest;
use App\Http\Requests\Attraction\UpdateAttractionRequest;
use App\Http\Traits\AttractionTraite;
use App\Models\Attraction;
use App\Repositories\AttractionRepository;
use App\Repositories\UserRoleRepository;
use App\Services\PermissionsServices;

class AttractionController extends Controller {

    use AttractionTraite;

    protected $permissions;
    protected $attractionRepository;
    protected $permissionService;

    public function __construct() {
        // проверка доступа
        $this->permissions = new PermissionsServices();
        $this->attractionRepository = new AttractionRepository();
        $this->permissionService = new PermissionsServices();
    }

    /**
     * создать аттракцион может root или admin для себя
     * Post /api/attraction
     * Header - Authorization Token
     * user_id - на кого регистрация атракциона
     * @param \Illuminate\Http\Request $request
     * @param UserRoleRepository $userRoleRep
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\UserException
     */
    public function store(StoreAttractionRequest $request) {
        $index = $request->validated();
        $create = $this->creatAttraction($index);

        return response()->json([
            'code' => 777,
            'status' => 'success',
            'message' => config('game.custom_value.attraction.1'),
            'attraction' => $create
        ], 201);
    }

    /**
     * доступ root
     * Выдать атракцион и все страны
     * GET URL - /api/attraction/1/edit
     * Header - Authorization Token
     * @param EditAttractionRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\UserException
     */
    public function edit(EditAttractionRequest $request) {
        $index = $request->validated();
        $show = $this->editAttraction($index);

        return response()->json([ $show ], 201);
    }

    /**
     * доступ root и admin
     * PATCH URL - /api/attraction/1 ---
     * Header - Authorization Token
     * params
     * location_id - принадлежит этой локе
     * title - название аттракциона
     * Patch изменить только то что передал в request. PATCH изменяет отдельные поля ресурса.
     * @param UpdateAttractionRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateAttractionRequest $request) {
        $index = $request->validated();
        $update = $this->updateAttraction($index);

        return response()->json([
            'code' => 777,
            'status' => 'success',
            'message' => config('game.custom_value.attraction.2'),
        ], 201);
    }

    /**
     * удалить  может root
     * DELETE URL - /api/attraction/1
     * Header - Authorization Token
     * @param DestroyAttractionRequest $request
     * @return Attraction|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object
     */
    public function destroy(DestroyAttractionRequest $request) {
        $index = $request->validated();
        $delete = $this->deleteAttraction($index);

        return response()->json([
            'code' => 777,
            'status' => 'success',
            'message' => config('game.custom_value.attraction.3'),
        ], 201);
    }

    /**
     * вывод всех записей доступ для root и admin
     * Get  URL - /api/attraction
     * Header - Authorization Token
     * user_id - на кого регистрация атракциона
     * @param StoreAttractionRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(StoreAttractionRequest $request) {
        $index = $request->validated();
        $attractions = $this->indexAttraction($index);

        return response()->json([
            'code' => 777,
            'status' => 'success',
            'message' => config('game.custom_value.attraction.4'),
            'attraction' => $attractions
        ], 201);
    }

    /**
     * доступ - root или admin для себя
     * смена токен аттракциона
     * POST /api/attraction/new_token
     * Header - Authorization Token
     * attraction_id - id аттракциона
     * admin_id - на кого регистрация атракциона
     * @param SigninAttractionRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function newToken(SigninAttractionRequest $request) {
        $index = $request->validated();
        $token = $this->newTokenTrate($index);

        return response()->json([
            'code' => 777,
            'status' => 'success',
            'message' => $token
        ], 201);
    }

    /**
     * доступ - root или admin для себя
     * удалить токен аттракциона
     * POST /api/attraction/delete_token
     * Header - Authorization Token
     * attraction_id - id аттракциона
     * admin_id - на кого регистрация атракциона
     * @param SigninAttractionRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteToken(SigninAttractionRequest $request) {
        $index = $request->validated();
        $bool = $this->deleteTokenTrate($index);

        return response()->json([
            'code' => 777,
            'status' => 'success',
            'message' => $bool
        ], 201);
    }

    /**
     * обновить статус активности
     * доступ root
     * PATCH URL - /api/attraction/activation_update
     * Header - Authorization Token
     * attraction_id
     * activation
     * @param ActivationAttractionUpdateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function activationUpdate(ActivationAttractionUpdateRequest $request) {
        $index = $request->validated();
        $bool = $this->activationUpdateTrait($index);

        return response()->json([
            'code' => 777,
            'status' => 'success',
            'message' => $bool,
        ], 201);
    }

}
