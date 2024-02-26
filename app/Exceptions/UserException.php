<?php

namespace App\Exceptions;

use Exception;

class UserException extends Exception {


    public function render($request, $data = '') {

        // не авторизован
        if ($this->getCode() == 1){
            $data = $request->expectsJson() ?
                response()->json(['code' => 0, 'status' => 'error', 'message' => $this->getMessage()], 401) :
                response(['code' => 0, 'status' => 'error', 'message' => $this->getMessage()], 401);
        }
        // запрещено
        elseif ($this->getCode() == 2){
            $data = $request->expectsJson() ?
                response()->json(['code' => 0, 'status' => 'error', 'message' => $this->getMessage()], 403) :
                response(['code' => 0, 'status' => 'error', 'message' => $this->getMessage()], 403);
        }
        // плохой, неверный запрос
        elseif ($this->getCode() == 3){
            $data = $request->expectsJson() ?
                response()->json(['code' => 0, 'status' => 'error', 'message' => $this->getMessage()], 400) :
                response(['code' => 0, 'status' => 'error', 'message' => $this->getMessage()], 400);
        }
        // не найдено
        elseif ($this->getCode() == 4){
            $data = $request->expectsJson() ?
                response()->json(['code' => 0, 'status' => 'error', 'message' => $this->getMessage()], 404) :
                response(['code' => 0, 'status' => 'error', 'message' => $this->getMessage()], 404);
        }
        // удален
        elseif ($this->getCode() == 5){
            $data = $request->expectsJson() ?
                response()->json(['code' => 0, 'status' => 'error', 'message' => $this->getMessage()], 410) :
                response(['code' => 0, 'status' => 'error', 'message' => $this->getMessage()], 410);
        }
    return $data;
    }




}
