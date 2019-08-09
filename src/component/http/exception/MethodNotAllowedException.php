<?php


namespace ixapek\BuyItAgain\Component\Http\Exception;


use ixapek\BuyItAgain\Component\Http\Code;

/**
 * Class MethodNotAllowedException
 *
 * @package ixapek\BuyItAgain
 */
class MethodNotAllowedException extends HttpException
{
    protected $code = Code::METHOD_NOT_ALLOWED;
}