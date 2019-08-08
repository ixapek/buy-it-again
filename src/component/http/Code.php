<?php


namespace ixapek\BuyItAgain\Component\Http;

/**
 * Class Code
 *
 * @package ixapek\BuyItAgain
 */
class Code
{
    /** @var int All is fine */
    public const OK = 200;
    /** @var int Successful created */
    public const CREATED = 201;
    /** @var int Request with error */
    public const BAD_REQUEST = 400;
    /** @var int Requested page or item isn't exists */
    public const NOT_FOUND = 404;
    /** @var int Method not supported for item */
    public const METHOD_NOT_ALLOWED = 405;
    /** @var int Server throw error */
    public const INTERNAL_ERROR = 500;
}