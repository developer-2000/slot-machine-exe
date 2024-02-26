<?php

namespace App\Console;

use App\Jobs\SendLicenseJobs;


class LicenseConsole {
    public function __invoke() {
        // отправить в очередь задачу с паузой в 5 сек (драйвер в настройках - database)
        SendLicenseJobs::dispatch()->onQueue('database')->delay(5);
    }
}

