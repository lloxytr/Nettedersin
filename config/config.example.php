<?php
return [
    'app_name' => 'Nettedersin',
    'base_url' => 'http://localhost',
    'db' => [
        'host' => 'localhost',
        'port' => '3306',
        'database' => 'nettepfg_db',
        'username' => 'nettepfg_user',
        'password' => 'Sifre1234.',
        'charset' => 'utf8mb4'
    ],
    'security' => [
        'session_name' => 'nettedersin_session',
        'csrf_key' => 'CHANGE_ME_RANDOM',
    ],
    'payment' => [
        'mode' => 'manual',
        'provider' => 'iyzico',
    ]
];
