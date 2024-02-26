<?php

namespace App\Http\Traits;

use App\Exceptions\UserException;
use MenaraSolutions\Geographer\Country;

trait MakeGeographyDbTraite {


// ========================================
// ========================================
    // 0 => страна [ "code" => "AF", "name" => "Afghanistan" ]
    // создает custom array стран
    protected function customArrCountry($all_county) {
        $arrCountry = [];

        foreach ($all_county as $key => $arr) {
            // найти эту страну в настройках нужных стран приложения
            if (array_search($arr['code'], $this->settingsCountry) !== false){
                array_push($arrCountry, [
                    'code' => mb_strtoupper($arr['code']),
                    'name' => $arr['name'],
                ]);
            }
        }

    return $arrCountry;
    }

// ========================================
// ========================================
    // создать custom масив штатов
    // штаты [▼
    //    0 => штат [▼
    //       "code" => 1121143
    //       "name" => "Zabul"
    protected function customArrStatesCountry($arrCount, $lang){

        $customStates = [];
        // Регионы страны - указано страна и язык
        $allStates = Country::build($arrCount["code"])
            -> setLocale(mb_strtoupper($lang)) -> getStates() -> toArray();

        // перебор регионов страны
        foreach ($allStates as $key3 => $arrState){
            array_push($customStates, [
                'code' => $arrState['code'],
                'name' => $arrState['name'],
            ]);
        }

    return $customStates;
    }


// ========================================
// ========================================
    // создать custom масив - code регионы и их города на выбраном языке
    // регионы [▼
    //  code региона => array [
    //    масив городов
    protected function customArrCitiesRegions($codeCountry, $lang){

        $arrayRegions = [];
        // страна по code и языку
        $county = Country::build($codeCountry)->setLocale($lang);

        // перебрать регионы
        foreach ($county->getStates() as $region) {

            // города региона
            $arraySity = $this->foreachCities($region);

            try {
                if (count($arraySity)){
                    $arrayRegions[ $region->toArray()['code'] ] = $arraySity;
                }
            } catch (\Exception $e) {
                logger('Произошла ошибка - ' . $e->getMessage() . ' --- Файл - ' . $e->getFile() . ' --- Строка - ' . $e->getLine());
            }

        }

    return $arrayRegions;
    }


// ========================================
// ========================================
    // перебрать города
    // города [▼
    //    code города => array [
    //      "code" => 1123004
    //      "geonamesCode" => 1123004
    //      "name" => "Taloqan"
    //      "latitude" => 36.73605
    //      "longitude" => 69.53451
    //      "population" => 64256
    private function foreachCities($region){

        $arraySity = [];

            // перебрать города
            foreach ($region->getCities() as $city) {
                $city = $city->toArray();

                if (isset($city['code']) && is_int($city['code']) && isset($city['name']) && is_string($city['name'])) {
//                    $arraySity[$city['code']] = $city;
                    array_push($arraySity, $city);
                }
            }

    return $arraySity;
    }










}
