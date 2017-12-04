<?php
return [
    'base_uri'               => 'https://api.worldoftanks.eu',
    'application_id'         => '2ff99442e997bc8794ae14379396211e',
    'default_realm'          => 'eu',
    'redirect_uri'           => env('APP_DEBUG') ?
        'http://7f65d997.ngrok.io/wot_redirect/'
        : url('authenticate')
];