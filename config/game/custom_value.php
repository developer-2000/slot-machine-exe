<?php


return [

    // все exeptions (ошибки исключения)
    'ex' => [
        1 => 'User has no role',
        2 => 'Registration unsuccessful',
        3 => 'Authorization not possible',
        4 => 'User has no permissions',
        5 => 'Error value and access id in the privilege table',
        6 => 'No access rights',
        7 => 'Access denied',
        8 => 'Unauthenticated',
        9 => 'Not Found',
        10 => 'Bad request',
        11 => 'Exceeded circulation, wait',
        12 => 'No geolocation data in the database',
        13 => 'Model is softly deleted',
    ],

    // контроллер Auth
    'auth' => [
        1 => 'You were successfully registered. Use your emails and password to sign in.',
        2 => 'You are successfully logout',
        3 => 'Token authorized',
        4 => 'Incorrect password data',
        5 => 'Password update error',
        6 => 'Error while updating user data',
    ],

    // контроллер attraction
    'attraction' => [
        1 => 'Attraction created successfully',
        2 => 'Data updated successfully',
        3 => 'Attraction successfully deleted',
        4 => 'All non-remote attractions',
    ],

    // контроллер session
    'session' => [
        1 => 'Previous session not closed',
        2 => 'Session successfully created',
        3 => 'Incorrect parameter data',
        4 => 'The session and its data have been successfully deleted',
        5 => 'Player parameter problem',
        6 => 'Session not found',
        7 => 'Error transferring to history tables',
        8 => 'Session closed successfully',
        9 => 'The number of players in the party exceeds the parameter exceeds the maximum allowed in the new mode',
        10 => 'The session has no attraction or location or statistics',
        11 => 'Session re-creation error',
        12 => 'Player or stats location is not present in the session',
    ],

    // контроллер локации
    'location' => [
        1 => 'Location successfully created',
        2 => 'Data updated successfully',
        3 => 'Location successfully deleted',
        4 => 'Location creation error',
        5 => 'License creation error',
    ],

    // контроллер загрузка файлов
    'download' => [
        1 => 'The specified file is not a zip archive',
    ],

    // контроллер языков админки
    'language' => [
        1 => 'The specified language does not exist',
    ],

    // service Statistic
    'statistic' => [
        1 => 'Error in the searchLocation algorithm',
        2 => 'Location not found',
    ],

    // admin panel Auth
    'auth_admin' => [
        1 => 'Invalid registration code',
        2 => 'Identification problem Email',
    ],


];
