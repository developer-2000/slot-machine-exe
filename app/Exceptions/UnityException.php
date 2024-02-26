<?php

namespace App\Exceptions;

use Exception;

class UnityException extends Exception {


    public function render($request, $data = '') {

        if ($this->getCode() == 1){
            $data = $request->expectsJson() ?
                response()->json(['code' => 0, 'status' => 'error', 'message' => $this->getMessage(),
                    'error' => ['password' => [$this->getMessage()] ]] , 404) :
                response(['code' => 0, 'status' => 'error', 'message' => $this->getMessage(),
                    'error' => ['password' => [$this->getMessage()] ]], 404);
        }

    return $data;
    }




}
