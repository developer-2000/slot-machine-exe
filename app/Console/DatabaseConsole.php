<?php

namespace App\Console;

use App\Jobs\AddLocalizationData;
use App\Models\MakeGeographyDb;
use App\Models\Test;

class DatabaseConsole {
    public function __invoke() {

        // если нет заливки в базу локализации страна регион город
        if (!$coll = MakeGeographyDb::find(1)) {
            // отправить в очередь задачу с паузой в 5 сек (драйвер в настройках - database)
            AddLocalizationData::dispatch()->onQueue('database')->delay(5);
        }
    }
}

