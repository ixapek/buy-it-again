<?php


namespace ixapek\BuyItAgain\Component\Main;

/**
 * Trait Singleton
 *
 * @package ixapek\BuyItAgain
 */
trait Singleton
{
    /** @var self $instance Instance container */
    protected static $instance;

    /**
     * Singleton constructor.
     */
    final protected function __construct()
    {
    }

    /**
     * @return self
     */
    public static function init(): self
    {
        return
            (static::$instance === null) ?
                static::$instance = new static() :
                static::$instance;
    }

    /**
     * Singleton protection.
     */
    final protected function __clone()
    {
    }

    /**
     * Singleton protection.
     */
    final protected function __wakeup()
    {
    }

}