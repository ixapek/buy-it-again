<?php


namespace ixapek\BuyItAgain\Controller;


use ixapek\BuyItAgain\Component\Http\Message;
use ixapek\BuyItAgain\Component\Http\Response;

abstract class AbstractController implements IController
{

    /**
     * Make Response object from data
     *
     * @param array $data    Response data (used as part of body)
     * @param int   $code    Response code
     * @param array $headers Response headers [headerName => headerContent]
     *
     * @return Response|Message
     */
    protected function response(array $data, int $code, array $headers = []): Response
    {
        $body = json_encode([
            'status'    => $code,
            'error'     => '',
            'timestamp' => time(),
            'data'      => $data,
        ]);

        $result = new Response();

        return $result
            ->setCode($code)
            ->setHeaders($headers)
            ->setBody($body);
    }
}