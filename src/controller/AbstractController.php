<?php


namespace ixapek\BuyItAgain\Controller;


use ixapek\BuyItAgain\Component\Http\Message;
use ixapek\BuyItAgain\Component\Http\Response;

abstract class AbstractController implements IController
{

    /**
     * Make Response object from data. If need some changes with data - make it here
     *
     * @param array $data    Response data (used as part of body)
     * @param int   $code    Response code
     * @param array $headers Response headers [headerName => headerContent]
     *
     * @return Response|Message
     */
    protected function response(array $data, int $code, array $headers = []): Response
    {
        return Response::create($data, $code, $headers);
    }
}