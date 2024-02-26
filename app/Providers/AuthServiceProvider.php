<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Support\Carbon;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
         'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot(GateContract $gate) {
        $this->registerPolicies();

            // define (устанавливает правило)
            $gate->define('access', function ($user, $access) {
                // метод в моделе таблицы User - canDo - вернет истину если есть такой доступ
                return $user->canDo($access);
            });

     // настройка для Laravel Passport Package OAuth2
     Passport::routes();
    }
}
