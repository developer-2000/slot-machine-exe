<?php


namespace App\Services;

class UsersServices {


    // найти юзера в коллекции и отдать его параметр
    // ========================================
    public function selectUserGetValue($coll, $id, $parameter){
        foreach ($coll as $key => $obj){
            if($obj->id == $id){
                return $obj->$parameter;
            }
        }
    return null;
    }





}
