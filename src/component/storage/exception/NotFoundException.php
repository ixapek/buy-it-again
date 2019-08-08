<?php


namespace ixapek\BuyItAgain\Component\Storage\Exception;


use Exception;
use ixapek\BuyItAgain\Component\Http\Code;

/**
 * Class NotFoundException
 *
 * @package ixapek\BuyItAgain\Component\Storage\Exception
 */
class NotFoundException extends Exception
{
    /** @var int $code Default error code */
    protected $code = Code::NOT_FOUND;
}