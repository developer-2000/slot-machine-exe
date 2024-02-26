<?php


namespace App\Services;

use Carbon\Carbon;

class CarbonServices {


    // ========================================
    // количество дней до начало следущего месяца
    public function countDayForNextMonth(){
        $next_month = Carbon::now()->addMonth();
        $next_month->startOfMonth();
        $now = Carbon::now();
    return $next_month->diffInDays($now);
    }

    // ========================================
    // преобразовать в Carbon обьект
    public function parseCarbon($value){
        $value = (gettype($value) == "string") ?
            Carbon::parse($value) :
            $value;
        return $value;
    }

    // ========================================
    // проверка на валидность строки для преобразования в обьект даты
    public function checkValidStringData($value){
        try {
            $date = $this->parseCarbon($value);
        } catch(\InvalidArgumentException $e) {
            return false;
        }
        return $date;
    }

    // ========================================
    // вернуть обьект в виде год, месяц, день
    public function getCarbonYearMonthDay($value){
        $value = $this->parseCarbon($value)->format('Y-m-d');
        return $this->parseCarbon($value);
    }

    // ========================================
    // вернуть строку в виде год, месяц
    public function getCarbonYearMonth($value){
        return $this->parseCarbon($value)->format('Y-m');
    }

    // ========================================
    // вернуть строку в виде год, месяц, день
    public function getStringYearMonthDay($value){
        return $this->parseCarbon($value)->format('Y-m-d');
    }

    // ========================================
    // вернуть строку в виде часа
    public function getCarbonHour($value){
        return $this->parseCarbon($value)->format('H');
    }

}
