<?php
    return [
        'url' => [
            'airtime' => 'https://vtu.ng/wp-json/api/v1/airtime',
            'verify' => 'https://vtu.ng/wp-json/api/v1/verify-customer',
            'cable' => 'https://vtu.ng/wp-json/api/v1/tv',
            'electricity' => 'https://vtu.ng/wp-json/api/v1/electricity'
        ],
        'username' => env('VTU_USERNAME'),
        'password' => env('VTU_PASSWORD')
    ];
