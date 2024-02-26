<?php

namespace App\Http\Traits;

use App\Events\ChangeUserImageEvent;
use App\Events\QueryAddGamer;
use App\Repositories\SessionUserRepository;
use App\Repositories\UserImageRepository;
use Illuminate\Support\Facades\Auth;

trait UserImageTraite {

    // ========================================
    // выбрать user_images и картинки магазина
    protected function getCollectionTrate(){
        $auth = Auth::user();
        $config = config('game.user_images');
        // картинки юзера
        $user_images = $this->repositoryImage->whereArrFirst([
            'user_id'=>$auth->id
        ]);
        // картинки магазина
        $pay_images = [
            'avatar_image' => $config['avatar_image']['pay'],
            'border_image' => $config['border_image']['pay'],
            'change_image' => $config['change_image']['pay'],
            'impact_image' => $config['impact_image']['pay'],
            'victory_image' => $config['victory_image']['pay'],
        ];

        return [
            'user_images'=>$user_images,
            'pay_images'=>$pay_images,
        ];
    }

    // ========================================
    // добавить юзеру картинку из магазина
    protected function addInCollectionTrate($index){
        $auth = Auth::user();
        // владелец id или root
        $this->permission->rootOrOwner($auth, $index['user_id']);
        $column = $index['column'];

        // проверка существования id image в магазине в указанном типе картинок
        if( is_string( $bool = $this->checkExistence($column, $index, 'pay') ) ){
            return $bool;
        }
        // картинки юзера
        $images = $this->repositoryImage->whereArrFirst([
            'user_id'=>$index['user_id']
        ]);
        // масив id типа картинок
        $arr_id = $images->$column;

        // имеется ли такой id картинки у юзера
        if (!in_array($index['select_id'], $arr_id)) {
            // добавить в масив новый id картинки
            array_push($arr_id, (int)$index['select_id']);
            // обновить список id типа картинок
            $this->repositoryImage->updateWhere(
                [ 'user_id' => $index['user_id'] ],
                [ $column => $arr_id ]);
        }

        return true;
    }

    // ========================================
    // установить картинку для юзера
    protected function setImageTrate($index){
        $column = $index['column'];
        $auth = Auth::user();
        // владелец id или root
        $this->permission->rootOrOwner($auth, $index['user_id']);

        // картинки юзера
        $images = $this->repositoryImage->whereArrFirst([
            'user_id'=>$index['user_id']
        ]);

        // масив имеющихся у юзера id типа картинок
        $arr_id = $images[$this->arrColumn[$column]];
        // проверка существования id image у юзера
        if(!in_array($index['select_id'], $arr_id )){
            return __('images.1');
        }

            // обновить id выбраной картинки
        $this->repositoryImage->updateWhere(
            [ 'user_id' => $index['user_id'] ],
            [ $column => $index['select_id'] ]);

            // выслать socket аттракциону
            $this->eventSelectImage($index);

        return true;
    }


//    PRIVATE
// ====================================
// выслать socket аттракциону
    private function eventSelectImage($index){
        // user в сессии
        if($session = (new SessionUserRepository())->getSessionData($index['user_id'])){
            // отправить socket в аттракцион

            $select_image = $this->repositoryImage->whereArrFirst([
                'user_id'=>$index['user_id']
            ]);

            event(new ChangeUserImageEvent([
                    $session->session_id,
                    $index['user_id'],
                    $select_image->select_border,
                    $select_image->select_avatar,
                ]
            ));
        }
    }

    // проверка существования id image в магазине в указанном типе картинок
    private function checkExistence($column, $index, $key){
        $config = config('game.user_images');
        // масив id типа картинок
        $arr_id_pay = array_column($config[$column][$key], 'id');

        if(in_array($index['select_id'], $arr_id_pay)){
            return true;
        }

        return __('images.0');
    }

}
