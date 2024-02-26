<?php
namespace App\Http\Controllers;

use App\Http\Requests\User\AddImageUserRequest;
use App\Http\Requests\User\SelectImageRequest;
use App\Http\Traits\UserImageTraite;
use App\Repositories\UserImageRepository;
use App\Services\PermissionsServices;

class UserImageController extends Controller {
    use UserImageTraite;

    protected $permission;
    protected $repositoryImage;
    protected $arrColumn;

    public function __construct() {
        // проверка доступа
        $this->permission = new PermissionsServices();
        $this->repositoryImage = new UserImageRepository();
        $this->arrColumn = [
            'select_border'=>'border_image',
            'select_avatar'=>'avatar_image',
            'select_change'=>'change_image',
            'select_impact'=>'impact_image',
            'select_victory'=>'victory_image',
        ];
    }

    // ===============
    // выбрать user_images и картинки магазина
    public function getCollection() {
        $collection = $this->getCollectionTrate();
        return response()->json($collection, 201);
    }

    /**
     * добавить юзеру картинку из магазина
     * доступ root или владельцу user_id
     * Header - Authorization Token
     * user_id - кому сменить image
     * column - название поля в таблице в котором изменить
     * select_id - id image в списке image
     * @param AddImageUserRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addInCollection(AddImageUserRequest $request) {
        $index = $request->validated();

        if(is_string($bool = $this->addInCollectionTrate($index))){
            return response()->json( [
                "errors"=>[
                    'status' => 'error',
                    'message' => $bool
                ]
            ], 404);
        }

        return response()->json( [
            'status' => 'success',
            'message' => $bool
        ], 201);
    }

    /**
     * установить картинку для юзера
     * доступ root или владельцу user_id
     * Header - Authorization Token
     * user_id - кому сменить image
     * column - название поля в таблице в котором изменить
     * select_id - id image в списке image
     * @param SelectImageRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function setImage(SelectImageRequest $request) {
        $index = $request->validated();
        if(is_string($bool = $this->setImageTrate($index))){
            return response()->json( [
                "errors"=>[
                    'status' => 'error',
                    'message' => $bool
                ]
            ], 404);
        }

        return response()->json( [
            'status' => 'success',
            'message' => $bool
        ], 201);
    }


}
