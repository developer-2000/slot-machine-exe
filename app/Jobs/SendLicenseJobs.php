<?php

namespace App\Jobs;

use App\Events\SendLicenseEvent;
use App\Repositories\LicenseRepository;
use App\Services\MakeLocationDbServices;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;

class SendLicenseJobs implements ShouldQueue {
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    // задержка повторной попытки неудачного задания
    public $retryAfter = 30;
    // количество ошибочных выполнений задачи
    public $tries = 3;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct() {
        // назначить этой задаче имя очереди
        $this->onQueue('database');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle() {

        $coll = (new LicenseRepository)->get();
        $day_now = Carbon::today()->toDateString();
        $count = 0;

        // Отправка в аттракцион сообщение о завершении лицензии
        foreach ($coll as $key => $license){

            // лицензия не пуста
            if(!is_null($license->month)){
                // проверка в наступившие 00:01 часа ночи
                // прошел месяц и 1 день
                $lic_month = Carbon::parse($license->month)->addMonth()->addDay()->toDateString();
                // лицензия протухла
                if ($day_now >= $lic_month) {
                    event(new SendLicenseEvent([$license->attraction_id, 'stop']));
                    $count++;
                }
            }
        }

        logs()->info("Отправлено socket license stop = ", (Array)$count);
    }


    // метод вызывается при ошибке выполнения задачи
    // $exception - передает сообщение об ошибке
    public function failed(\Throwable $e) {
        logs()->info("Ошибка задачи : AddLocalizationData ");
        logs()->info($e->getMessage());
    }

}
