<?php


namespace ixapek\BuyItAgain\Component\Http\Exception;


use Exception;
use ixapek\BuyItAgain\Component\Http\Code;

class BadRequestException extends Exception
{
    protected $code = Code::BAD_REQUEST;
}