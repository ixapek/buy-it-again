<?php


namespace ixapek\BuyItAgain\Component\Storage\Exception;


use Exception;
use ixapek\BuyItAgain\Component\Http\Code;

/**
 * Class StorageException
 *
 * @package ixapek\BuyItAgain\Component\Storage\Exception
 */
class StorageException extends Exception
{
    /** @var int $code Default error code */
    protected $code = Code::INTERNAL_ERROR;
}