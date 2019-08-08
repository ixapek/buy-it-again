<?php


namespace ixapek\BuyItAgain\Component\Http\Exception;


use Exception;
use ixapek\BuyItAgain\Component\Http\Code;

class InternalErrorException extends Exception
{
    protected $code = Code::INTERNAL_ERROR;
}