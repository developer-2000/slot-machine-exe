<?php


use Illuminate\Support\Facades\Route;

Route::namespace('Admin')->middleware('auth:api')->group( function () {

    // 0 Language
    Route::prefix('language')->group( function () {
        // заменить язык на сервере
        Route::post('change_language', 'LanguageAdminController@changeLanguage');
        // выбрать контент языка сервера
        Route::post('get_language', 'LanguageAdminController@getLanguage');
    });

    // 1 Profile
    Route::prefix('profile')->group( function () {
        // отправить лицензии админа и их локации и их аттракционы
        Route::post('get_profile', 'ProfileAdminController@getProfile');
    });

    // 2 Clients
    Route::prefix('clients')->group( function () {
        // все админы и данные о лицензии
        Route::post('get_clients', 'ClientsAdminController@getClients');
    });

    // 3 Locations
    Route::prefix('location')->group( function () {
        Route::post('all_locations_pagination', 'LocationAdminController@getLocationsAdminPagin');
    });

    // 4 Attractions
    Route::prefix('attraction')->group( function () {
        // Пагинация все аттракционы и их локации
        Route::post('all_attractions_user', 'AttractionAdminController@getAllAttractions');
        // создать аттракцион
        Route::post('create_attraction', 'AttractionAdminController@createAttraction');
    });

    // 5 Gamers
    Route::prefix('gamers')->group( function () {
        Route::post('all_gamers', 'GamersAdminController@getGamers');
    });

    // 6 Statistics
    Route::prefix('statistic')->group( function () {
        Route::post('gamer_statistic', 'StatisticAdminController@getGamerStatistic');
        Route::post('location_statistic', 'StatisticAdminController@getLocationStatistic');
        Route::post('modes_statistic', 'StatisticAdminController@getModesStatistic');


        Route::post('generate_gamer_session', 'StatisticAdminController@generateGamerSession');
        Route::post('delete_gamer_session', 'StatisticAdminController@deleteGamerSession');
    });

    // 7 Licenses
    Route::prefix('license')->group( function () {
        Route::post('all_licenses', 'LicenseAdminController@getLicenses');
    });

    // 8 Count line paginate
    Route::prefix('paginate')->group( function () {
        Route::post('set_paginate', 'AdminController@setCountPaginate');
        Route::post('get_paginate', 'AdminController@getCountPaginate');
    });

    // 9 Auth
    Route::prefix('auth')->group( function () {
        // создать админа и выслать сообщение на Email для продолжения регистрации
        Route::post('signup_admin', 'AuthAdminController@signupAdmin');
        // обновить админа
        Route::post('update_admin', 'AuthAdminController@updateAdmin');
    });

});

// ===========================================
// ===========================================
// БЕЗ АВТОРИЗАЦИИ
Route::namespace('Admin')->group( function () {
    // 1 Auth
    Route::prefix('auth')->group( function () {
        // продолжение регистрации админа
        Route::post('continue_signup_admin', 'AuthAdminController@continueSignup');
    });
});


Route::post('test', 'TestController@test');
