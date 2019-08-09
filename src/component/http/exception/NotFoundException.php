<?php


namespace ixapek\BuyItAgain\Component\Http\Exception;

use ixapek\BuyItAgain\Component\Http\Code;

/**
 * Class NotFoundException
 *
 * @package ixapek\BuyItAgain
 */
class NotFoundException extends HttpException
{
    protected $code = Code::NOT_FOUND;
}