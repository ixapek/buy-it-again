<?php


namespace ixapek\BuyItAgain\Controller;


/**
 * Interface IController
 *
 * @package ixapek\BuyItAgain
 */
interface IController
{
    /**
     * Get allowed methods for controller
     *
     * @return string[]
     */
    public function getAllowed(): array;
}