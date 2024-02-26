<?php

namespace App\Jobs;

use App\Services\MakeLocationDbServices;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AddLocalizationData implements ShouldQueue {
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
        // сформировать в базе записи локации города , регионы, страны
        (new MakeLocationDbServices())->allCountry();
        logs()->info("Заливка Локализации в базу завершена");
    }


    // метод вызывается при ошибке выполнения задачи
    // $exception - передает сообщение об ошибке
    public function failed(\Throwable $e) {
        logs()->info("Ошибка задачи : AddLocalizationData ");
        logs()->info($e->getMessage());
    }

}
