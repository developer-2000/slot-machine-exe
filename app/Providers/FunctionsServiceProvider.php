<?php

namespace App\Providers;

use App\Facades\MyFunctions;
use App\Services\FunctionsServices;
use Illuminate\Support\ServiceProvider;

class FunctionsServiceProvider extends ServiceProvider {
    /**
     * Register services.
     *
     * @return void
     */
    public function register() {
        // связать мой рабочий service с сервис контейнером
        // MyFunctions - имя для фасада
        $this->app->bind('MyFunctions',function(){
            return new FunctionsServices();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
