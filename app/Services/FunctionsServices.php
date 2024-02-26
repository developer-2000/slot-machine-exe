<?php
namespace App\Services;

class FunctionsServices {

    // генерирует строку с указанным кол-во символов
    // ========================================
    public function generateStr(int $count){
        $str = '0123456789abcdefghijklmnopqrstuvwxyz';
        $input_length = strlen($str);
        $random_string = '';

        for($i = 0; $i < $count; $i++) {
            $random_character = $str[mt_rand(0, $input_length - 1)];
            $random_string .= $random_character;
        }

    return $random_string;
    }

    // сортировка от меньшего к большему в многомерном масиве
    // ========================================
    public function sortMinMax(array $arr_count_locations){

        usort($arr_count_locations, function($a, $b) {
            return ($a['count'] < $b['count']) ?
                -1 :
                (int) !($a['count'] === $b['count']);
        });

    return $arr_count_locations;
    }

    // удалить из многомерного масива повторы по ключу
    // ========================================
    public function removeDuplicates(array $array, $key){
        $result = array_column($array, null, 'id');
        // сбросить ключи масива
        return array_values($result);
    }

    // отобрать расширение файла
    // ========================================
    public function chooseExtension($name){
        $point = '.';
        $difference = strlen($name) - strripos($name, $point);

        return substr($name, '-'.$difference);
    }



}
