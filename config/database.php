<?php
return [
    'host' => getenv('DB_HOST') ?: 'localhost',
    'port' => getenv('DB_PORT') ?: '3306',
    'database' => getenv('DB_NAME') ?: 'nettepfg_db',
    'username' => getenv('DB_USER') ?: 'nettepfg_user',
    'password' => getenv('DB_PASS') ?: 'Sifre1234.',
    'charset' => 'utf8mb4'
];
