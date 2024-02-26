<?php
namespace App\Services;

use App\Jobs\UpdateLocationAchievementJobs;
use App\Repositories\LocationAchievementRepository;
use Illuminate\Support\Carbon;

class LocationAchievementServices {

    protected $locationAchRepository;
    protected $carbonService;

    public function __construct() {
        $this->locationAchRepository = new LocationAchievementRepository();
        $this->carbonService = new CarbonServices();
    }

    // ========================================
    // выбрать достижения Локации
    // обновляется через Job - после запроса игрока к этой локации
    // обновляется в том случае если обновление было час назад
    // обновляет те достижения которые не достигли максимума
    public function select($index) {

        // выбрать достижения этой локации
        $achievement = $this->locationAchRepository->whereArrFirst(
            [ 'location_id' => $index['location_id'] ]
        );

        if(!is_null($achievement)){
            // обновить если часы не равны
            if( $this->carbonService->getCarbonHour(Carbon::now()) !=
                $this->carbonService->getCarbonHour($achievement->last_update) ){
                // отправить в очередь задачу с паузой в 5 сек (драйвер в настройках - database)
                UpdateLocationAchievementJobs::dispatch($index['location_id'])->onQueue('database')->delay(5);
            }
        }
        else{
            // отправить в очередь задачу с паузой в 5 сек (драйвер в настройках - database)
            UpdateLocationAchievementJobs::dispatch($index['location_id'])->onQueue('database')->delay(5);
        }

        return $achievement;
    }

}
