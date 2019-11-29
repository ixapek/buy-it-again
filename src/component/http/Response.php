<?php


namespace ixapek\BuyItAgain\Component\Http;


use Exception;

/**
 * Class Response
 *
 * @package ixapek\BuyItAgain
 */
class Response extends Message
{
    /**
     * Create response from data
     *
     * @param array $data
     * @param int   $code
     * @param array $headers
     *
     * @return Message
     */
    public static function create(array $data, int $code, array $headers = []){
        $body = json_encode([
            'status'    => $code,
            'error'     => '',
            'timestamp' => time(),
            'data'      => $data,
        ]);

        $result = new static();
        $headers['Content-Type'] = 'application/json';

        return $result
            ->setCode($code)
            ->setHeaders($headers)
            ->setBody($body);
    }

    /**
     * Create response from exception
     *
     * @param Exception $e
     * @param array     $headers
     *
     * @return Message
     */
    public static function fromException(Exception $e, $headers = []){

        $code = $e->getCode() ?? Code::INTERNAL_ERROR;

        $body = json_encode([
            'status'    => $code,
            'error'     => $e->getMessage(),
            'timestamp' => time(),
            'data'      => [],
        ]);

        $result = new static();
        $headers['Content-Type'] = 'application/json';

        return $result
            ->setCode($code)
            ->setHeaders($headers)
            ->setBody($body);
    }

    /**
     * Echo response message
     */
    public function render(): void
    {
        $code = $this->getCode() ?? Code::OK;

        // With status 304 response must be with empty body
        if( $code === Code::NOT_MODIFIED ){
            $this->setBody('');
        }

        foreach ($this->getHeaders() as $header => $value) {
            header("$header: $value");
        }

        http_response_code($code);

        echo $this->getBody();
    }
}