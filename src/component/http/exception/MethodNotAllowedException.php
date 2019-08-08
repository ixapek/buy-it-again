<?php


namespace ixapek\BuyItAgain\Component\Http\Exception;


use Exception;
use ixapek\BuyItAgain\Component\Http\Code;

/**
 * Class MethodNotAllowedException
 *
 * @package ixapek\BuyItAgain
 */
class MethodNotAllowedException extends Exception
{
    protected $code = Code::METHOD_NOT_ALLOWED;
}