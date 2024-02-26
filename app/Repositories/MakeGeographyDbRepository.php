<?php
namespace App\Repositories;

use App\Exceptions\UserException;
use App\Models\MakeGeographyDb as Model;
use App\Services\Admin\Language\LanguageServices;

class MakeGeographyDbRepository extends CoreRepository {

    public function __construct() {
        $this->model = clone app(Model::class);
    }

    // ===========================================
    // выбрать все страны
    public function selectCountries(){
        $lang = mb_strtoupper( (new LanguageServices())->getLanguage() );

        try {
            return $this->show(1)->country[$lang];
        }
        catch (\Exception $e) {
            throw new UserException(config('game.custom_value.ex.12').'. Select all Country', 4);
        }
    }

    // ===========================================
    // выбрать все регионы
    public function selectRegionsForCountry($code){
        $lang = mb_strtoupper( (new LanguageServices())->getLanguage() );

        try {
            $coll = $this->show(1);
            if(!is_null($coll)){
                if(isset($coll->regions[$lang][$code])){
                    return $coll->regions[$lang][$code];
                }
            }
            return false;
        }
        catch (\Exception $e) {
            throw new UserException(config('game.custom_value.ex.12').'. Code Country - '.$code, 4);
        }
    }

    // ===========================================
    // выбрать города по code региона и языку
    public function selectCitiesForRegion($code){
        $lang = mb_strtoupper( (new LanguageServices())->getLanguage() );

        try {
            $coll = $this->show(1);
            if(!is_null($coll)){
                if(isset($coll->cities[$lang][$code])){
                    return $coll->cities[$lang][$code];
                }
            }
            return false;
        }
        catch (\Exception $e) {
            throw new UserException(config('game.custom_value.ex.12').'. Code Region - '.$code, 4);
        }
    }

}
