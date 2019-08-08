<?php

define('STORAGE_CONFIG', [
    'PDO' => [
        'class' => \ixapek\BuyItAgain\Component\Storage\PDOStorage::class,
        'config' => [
            'dsn' => '',
            'user' => '',
            'password' => ''
        ]
    ]
]);
define('STORAGE_DEFAULT', 'PDO');