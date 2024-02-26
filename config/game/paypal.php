<?php

// SENDBOX
return [
    'client_id' => 'AcMJb1iY9Ya81SN20HF6osT4ctRXfHOmHxSZaNIXVKZgY6I-VNY-BuzYb7sJaskIadhsDSXvfwSTerJ3',
    'secret' => 'EN11KRGci_R_WxpymX6F45CdXfJif6fkt9f71aB7HDywX_MqFU3AersfjxZn95Nzorwnzegaih2ZSMrk',
    'settings' => [
        'mode' => env('PAYPAL_MODE','sandbox'),
        'http.ConnectionTimeOut' => 30,
        'log.LogEnabled' => true,
        'log.FileName' => storage_path() . '/logs/paypal.log',
        'log.LogLevel' => 'ERROR'
    ],
];
