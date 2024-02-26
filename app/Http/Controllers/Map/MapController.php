<?php

namespace App\Http\Controllers\Map;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MapController extends Controller
{

    // сообщает vue url браузера для перехода на этот адрес
    public function index()
    {
        $data = [
            'url' => "http://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']
        ];
        return view('map.index', compact('data'));
    }
}
