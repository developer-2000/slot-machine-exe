<?php


namespace App\Services;

use App\Exceptions\UserException;
use App\Repositories\AttractionRepository;
use App\Repositories\TestRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class HeaderCheckTokenAttractionServices {


    // проверяет токен аттракциона в header и ищет его в таблице аттракцион
    // ========================================
    public function checkToken(Request $request){

        $token = isset($request->header()['token-attr']) ? $request->header()['token-attr'][0] : null;

        if(!is_null($token)){

            $index = $this->validate($request->all());

            // поиск токен в таблице аттрактион
            if (!is_null($token)){
                // выборка аттракциона по токену и id
                if ($attraction = (new AttractionRepository)->whereArrFirst([
                    'id'=>$index['attraction_id'], 'access_token'=>$token,
                ])){
                    return $attraction;
                }
            }
        }

    return false;
    }

    // нахождение в строке подстрок
    // ========================================
    public function forCheckString($string, $array){
        foreach ($array as $key => $substring){
            if(strripos($string, $substring) !== false){
                return true;
            }
        }

        return false;
    }

    // >>>
    // локальная валидация
    private function validate(array $index){

        // 1 валидация параметра http
        $validator = Validator::make($index, [
            'attraction_id' => 'required|integer|exists:attractions,id',
        ]);

        if ($validator->fails()) {
            throw new UserException( $validator->errors()->first('attraction_id')   , 3);
        }

    return $index;
    }










}
