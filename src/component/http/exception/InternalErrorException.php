<?php


namespace ixapek\BuyItAgain\Component\Http\Exception;

use ixapek\BuyItAgain\Component\Http\Code;

/**
 * Class InternalErrorException
 *
 * @package ixapek\BuyItAgain\Component\Http\Exception
 */
class InternalErrorException extends HttpException
{
    protected $code = Code::INTERNAL_ERROR;
}