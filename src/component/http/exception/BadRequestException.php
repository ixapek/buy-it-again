<?php


namespace ixapek\BuyItAgain\Component\Http\Exception;

use ixapek\BuyItAgain\Component\Http\Code;

/**
 * Class BadRequestException
 *
 * @package ixapek\BuyItAgain
 */
class BadRequestException extends HttpException
{
    protected $code = Code::BAD_REQUEST;
}