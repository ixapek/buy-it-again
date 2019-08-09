<?php


namespace ixapek\BuyItAgain\Component\Main;

/**
 * Trait Singleton
 *
 * @package ixapek\BuyItAgain
 */
trait Singleton
{
    use Ton;

    /** @var self $instance Instance container */
    protected static $instance;

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

}