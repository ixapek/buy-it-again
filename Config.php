<?php


namespace ixapek\BuyItAgain;


use ixapek\BuyItAgain\Component\Storage\PDOStorage;

/**
 * Class Config
 *
 * @package ixapek\BuyItAgain
 */
class Config
{
    /** @var array Storage configurations */
    const STORAGE_CONFIG = [
        'PDO' => [
            'class' => PDOStorage::class,
            'config' => [
                'dsn' => 'mysql:dbname=buy_it_again;host=127.0.0.1;port=3306',
                'user' => 'localuser',
                'password' => 'Localuser1'
            ]
        ]
    ];

    /** @var string Default storage. Must exists as key in self::STORAGE_CONFIG */
    const STORAGE_DEFAULT = 'PDO';
}