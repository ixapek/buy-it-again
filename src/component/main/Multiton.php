<?php


namespace ixapek\BuyItAgain\Component\Main;


trait Multiton
{
    use Ton;

    /** @var self $instance Instances container */
    private static $instance = [];

    /**
     * @param string|null $instanceKey
     *
     * @return mixed
     */
    abstract public static function init(?string $instanceKey = null);
}