<?php

use Illuminate\Support\Facades\Route;

// технические роуты
//  доступ - авторизован и root
Route::middleware(['auth:api', 'access:developer']) ->namespace('Techno') ->group(function () {
    Route::get('location_db', 'TechnoController@locationDb')->middleware('location_db');
});


// ограничения обращения к маршрутам
// не авторизованый 10 раз в минуту
// авторизованный 60
Route::middleware('throttle:100|60,1')->group(function () {

    // >>> ========================
    // 1 auth для Laravel Passport Package OAuth2
    Route::prefix('auth')->namespace('Auth')->group(function () {

        Route::group(['middleware' => 'auth:api'], function () {

            // /api/auth/logout
            Route::get('logout', 'AutherController@logout');
            // сменить пароль
            Route::post('change_password', 'AutherController@changePassword');
            // проверка токена
            Route::get('check_auth', 'AutherController@checkAuth');
            // получить данные о юзере
            Route::post('get_user', 'AutherController@userData');
            // обновить данные юзера
            Route::post('update_data', 'AutherController@updateData');
            // выбрать всех игроков
            Route::post('get_gamers', 'AutherController@getGamers');
            // обновить статус активности
            Route::post('activation_update', 'AutherController@activationUpdate');

            // обновить адрес юзера
            Route::post('update_user_address', 'AutherController@updateUserAddress');
            // обновить ник, имя, дата-рождения юзера
            Route::post('update_user_about', 'AutherController@updateUserAbout');
            // обновить статистику юзера
            Route::post('update_user_statistics', 'AutherController@updateUserStatistics');
        });

//        Post  Params
//        `nickname` - string
//        `first_name` - string
//        `country` - json
//        `region` - json
//        `city` - json
//        `date_of_birth` - date (2020-12-01)
//        `emails` - string
//        `password` - string

        // roles 1 = root, 2 = admin, 3=gamer
        // регистрация Root
        // /api/auth/xoi57231qhy4z6f/signup/1
        Route::post('xoi57231qhy4z6f/signup/{id}', 'AutherController@signUp')
            ->where('id', '[0-9]+')
            ->where('id', '1');
        // регистрация Admin
        // /api/auth/p75tlwfziscgq9u/signup/2
        Route::post('p75tlwfziscgq9u/signup/{id}', 'AutherController@signUp')
            ->where('id', '[0-9]+')
            ->where('id', '2');
        // регистрация Gamer
        // /api/auth/6f52kysoa80j1hq/signup/3
        Route::post('6f52kysoa80j1hq/signup/{id}', 'AutherController@signUp')
            ->where('id', '[0-9]+')
            ->where('id', '3');

    // авторизация
    // /api/auth/signin
        Route::post('/signin', 'AutherController@signIn');
    });

    // >>> ========================
    // 2 строго для авторизованных
    Route::group(['middleware' => ['auth:api']], function () {

        // ========================
        // 1 атракционы
        // STORE создать - POST - /api/attraction?user_id=2
        // EDIT подгрузка данных и стран для редактирования - GET - /api/attraction/цифра id аттракциона/edit
        // UPDATE обновить аттракцион PATCH - /api/attraction/цифра id аттракциона + parameter data = {поля аттракциона}
        // DESTROY удалить аттракцион DELETE - /api/attraction/цифра id аттракциона
        // SHOW показ одного аттракциона - GET /api/attraction/id аттракциона
        // INDEX один аттракцион GET - /api/attraction  + parameter user_id = id админа
        Route::namespace('Attraction')->group(function () {
            Route::resource('attraction', 'AttractionController')->except([ 'show' ]);

            // смена токен аттракциона
            Route::post('attraction/new_token', 'AttractionController@newToken');
            // удалить токен аттракциона
            Route::post('attraction/delete_token', 'AttractionController@deleteToken');
            // обновить статус активности
            Route::post('attraction/activation_update', 'AttractionController@activationUpdate');
        });

        // ========================
        // 2 локация для аттракционов - создавать и редактировать может Root или админ для себя
        // STORE создать - POST - /api/location?user_id=2
        // EDIT подгрузка данных локации для редактирования - GET - /api/location/id локации/edit
        // UPDATE обновить локацию PATCH - /api/location/id локации
        // DESTROY удалить локацию DELETE - /api/location/id локации
        Route::namespace('Location')->group(function () {
            Route::resource('location', 'LocationController')->only([
                'store', 'update', 'edit', 'destroy'
            ]);

            Route::post('location/get-location-statistic', 'LocationController@getLocationStatistic');
            // Test для аттракциона - оплата локации - все аттракционы на ней.
            Route::post('location/update-license', 'LocationController@updateLicense');
            // обновить статус активности
            Route::post('location/activation_update', 'LocationController@activationUpdate');
            // создать или обновить аватар локации
            Route::post('location/load_avatar', 'LocationController@loadAvatar');
            // выбрать все аттркционы локации
            Route::post('location/select_attractions', 'LocationController@selectAttractions');
        });

        // ========================
        // 3 картинки юзера
        // подгрузка images - borders, avatars (для юзера и магазина)
        Route::post('user_images/get_collection', 'UserImageController@getCollection');
        // добавить юзеру картинку из магазина
        Route::post('user_images/add_in_collection', 'UserImageController@addInCollection');
        // установить картинку для юзера
        Route::post('user_images/set_id', 'UserImageController@setImage');

        // ========================
        // 4 выдать посещенные локации юзером для Map (Vue-js)
        Route::post('visited_locations/get_locations_user', 'FrontMapController@getLocationsUser');
    });

    // >>> ========================
    // 3 Geo
    // подгрузка всех стран
    Route::post('load_country', 'GeoController@loadCountry');

    // подгрузка регионов по code страны
    Route::post('load_regions', 'GeoController@loadRegions');

    // подгрузка городов по code региона
    Route::post('load_cities', 'GeoController@loadCities');


    // ============================================
    // ============================================
    //    Группа проверок на Token-attr
    // ============================================
    // ============================================

    // >>> ========================
    // 1 Sessions
    Route::resource('session', 'SessionController')->only([
        'store', 'update', 'show', 'destroy'
    ]);
    Route::prefix('session')->group( function () {
        // активировать сессию
        Route::post('activate_session', 'SessionController@activateSession');
        Route::post('deactivation_session', 'SessionController@deactivationSession');
        // закрывает указанную сессию
        // создает новую и обновляет игроков вней
        Route::post('close_session', 'SessionController@close');
        // удалить игрока
        Route::post('delete_gamer', 'SessionController@deleteGamer');
        // сменить режим игры
        Route::post('change_mode', 'SessionController@changeMode');
        // переход хода игрока
        Route::post('player_transit', 'SessionController@playerTransit');

        // завершение игры игроком
        Route::post('player_exit', 'SessionController@playerExit');
        // запрос игроком на добавление к сессии
        Route::post('player_start', 'SessionController@playerStart');
        // проверка игроком состоит-ли в сессии
        Route::post('check_gamer_session', 'SessionController@checkGamer');
        // проверка аттракционом состоит-ли в сессии
        Route::post('check_attraction_session', 'SessionController@checkAttraction');
        // выбрать информацию прошлой сессии и игроков в ней
        Route::post('get_info_game', 'SessionController@getInfoGame');


        // 3.2 session Gamer
        // выбрать историю игр игрока
        Route::post('get_all_history_gamer', 'GamerSessionController@getAllHistoryGamer');
    });

    // >>> ========================
    // 2 версия приложения
    // обновление клиента
    Route::resource('download', 'DownloadController')->only([
        'show',
    ]);
    // узнать версию файла - приложения
    Route::post('download/check_version/{download}', 'DownloadController@checkVersion');

    // ========================
    // 3 режимы игры для игрока
    Route::prefix('mode')->group( function () {
        // внести статистику игрока в указанном режиме
        Route::post('set_statistic_gamer', 'UserModeStatisticController@setStatisticGamer');
        // взять статистику игрока в указанном режиме
        Route::post('get_statistic_gamer', 'UserModeStatisticController@getStatisticGamer');
    });

    // >>> ========================
    // 4 для аттракциона - полные данные админа - локация , аттракцион и прочее
    Route::post('user_data', 'Auth\AutherTokenAttrController@userData');

    // >>> ========================
    // 5 выбрать данные аттракциона
    Route::post('get_data_attraction', 'Attraction\AttractionTokenAttrController@getDataAttraction');

    // >>> ========================
    // 6 проверка лицензии у локации
    Route::post('location/edit-license', 'Location\LocationTokenAttrController@editLicense');

    // >>> ========================
    // 7 Статистика достижений игроков
    Route::prefix('statistic_achievement')->group( function () {
        // создать статистику
        Route::post('create_achievement', 'StatisticAchievementController@createAchievement');
        // выбрать указанную статистику
        Route::post('select_achievement', 'StatisticAchievementController@selectAchievement');
        // обновить указанную статистику
        Route::post('update_achievement', 'StatisticAchievementController@updateAchievement');
    });

    // >>> ========================
    // 8 достижения игрока
    Route::prefix('achievements_user')->group( function () {
        // создать тип достижения игрока
        Route::post('create_type', 'PlayerAchievementController@createType');
        // удалить тип достижения игрока
        Route::post('delete_type', 'PlayerAchievementController@deleteType');
        // выбрать все типы достижений игрока
        Route::post('select_types_gamer', 'PlayerAchievementController@selectTypesGamer');
    });

    // >>> ========================
    // 9 достижения локации
    Route::prefix('achievements_location')->group( function () {
        // выбрать достижения Локации
        // обновляется через Job - после запроса игрока к этой локации
        // обновляется в том случае если обновление было час назад
        // обновляет те достижения которые не достигли максимума
        Route::post('select', 'LocationAchievementController@select');
    });
    // ============================================
    // ============================================
    //  конец - Группа проверок на Token-attr
    // ============================================
    // ============================================








    // >>> ========================
    // 5 Test
//    Route::group(['middleware' => ['auth:api']], function () {


//        Route::get('/test', 'TestController@test')->middleware('location_db');
//        Route::post('/test', 'TestController@test');


//    });
//    Route::post('/paypal/payment', ['as' => 'payment', 'uses' => 'PaymentController@payWithpaypal']);
//    Route::get('/paypal/payment/status',['as' => 'status', 'uses' => 'PaymentController@getPaymentStatus']);


}); // End routes
