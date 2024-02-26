<?php

namespace App\Services\Admin;

use Illuminate\Support\Facades\Session;

class AdminServices {

    // ============================================
    // установить кол-во отображений в пагинации
    public function setCountPaginate($index){
        $bool = false;

        Session::put('count_paginate', $index['count']);

        if (Session::has('count_paginate')) {
            $bool = true;
        }

        return $bool;
    }

    // ============================================
    // выбрать кол-во отображений в пагинации
    public function getCountPaginate(){

        if (Session::has('count_paginate')) {
            return intval(Session::get('count_paginate'));
        }

        return config('game.admin_settings.pagination_loc');
    }

    // ============================================
    // проверка существования параметров запроса
    public function checkParameters($index){
        try {
            if($index['sort'] && $index['operator'] && $index['filter'] && $index['add_filter']){
                return 1;
            }
        } catch (\Exception $e) {
            return __('sort_pages.0');
        }
    }

}
