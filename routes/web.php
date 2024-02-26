<?php

use App\Events\SendLicenseEvent;
use App\Models\Test;
use App\Repositories\LicenseRepository;
use App\Repositories\SessionRepository;
use Illuminate\Support\Facades\Route;
use \Illuminate\Support\Carbon;


// /artisan/clear_all
Route::get('/artisan/{cmd}', function($cmd) {
    $cmd = trim(str_replace("-",":", $cmd));
    $validCommands = [
        'optimize',
        'route:cache',
        'route:clear',
        'view:clear',
        'config:cache',
        'config:clear',
        'cache:clear'
    ];
    if ($cmd == 'clear_all'){
        \Illuminate\Support\Facades\Artisan::call('route:clear');
        \Illuminate\Support\Facades\Artisan::call('view:clear');
        \Illuminate\Support\Facades\Artisan::call('config:clear');
        \Illuminate\Support\Facades\Artisan::call('cache:clear');
        return "<h1>all_clear</h1>";
    }
    else {
        return "<h1>Not valid Artisan command</h1>";
    }
});

Route::get('/', function () {
//    event(new \App\Events\AddGamer([1, '222']));
//    event(new SessionStatusEvent([1, 'start']));
//    event(new SessionPlayerTransitEvent([1, "{\"gamer\":{\"user_id\":1,\"value\":{}}}"]));
//    event(new \App\Events\ExitGamer([1, 3]));
//    event(new \App\Events\QueryAddGamer([1, 3]));
//    event(new \App\Events\ResponseAddGamer([1, 3]));
//    event(new \App\Events\DeleteGamer([1, true]));

(new \App\Services\MakeLocationDbServices());

    return view('welcome');
});



Route::get('/test_payment', function () {
    return redirect()->route('payment', [
        'location_id'=>1,
        'admin_id'=>2,
    ]);
});

//Payment
Route::get('/paypal/payment', ['as' => 'payment', 'uses' => 'PaymentController@payWithPaypal']);
Route::get('/paypal/payment/status', ['as' => 'status', 'uses' => 'PaymentController@getPaymentStatus']);


// ================================================================================================
// Группа функций административной панели ==========================================================
// ================================================================================================
Route::prefix('admin')->group( function () {
    Route::namespace('Admin')->group(function () {
        Route::get('/', 'AdminController@index')->name('admin');
        Route::get('/{step}', 'AdminController@index');
    });
});

// ================================================================================================
// Группа функций для карты =======================================================================
// ================================================================================================
Route::prefix('googlemap')->group(function () {
    Route::namespace('Map')->group(function () {

        Route::get('/', 'MapController@index')->name('Map');
    });
});

Route::get('/privacy', function () {
    return view('privacy');
});
Route::get('/load_image', function () {
    return view('admin_panel.loadImage');
});






























