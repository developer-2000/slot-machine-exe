<?php

namespace App\Services\Admin;


class AttractionServices {


    // ============================================
    // уникальные локации периода сессии
    public function getUniqueLocations($attractions){
        $arr_locations = [];

        foreach ($attractions as $key => $attraction){
            if(!count($arr_locations)){
                array_push($arr_locations, $attraction->location);
            }
            elseif(
            !$arr = array_filter($arr_locations, function($location) use ($attraction) {
                return ($location->id == $attraction->location->id);
            })
            ){ array_push($arr_locations, $attraction->location); }
        }

        return $arr_locations;
    }













}
