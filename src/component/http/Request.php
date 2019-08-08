<?php


namespace ixapek\BuyItAgain\Component\Http;

/**
 * Class Request
 *
 * @package ixapek\BuyItAgain
 */
class Request extends Message
{
    /** @var Request $current Container for current request */
    protected static $current;

    /**
     * Create new request for url
     *
     * @param string|null $url
     *
     * @return Request
     */
    public static function create(string $url = null): Request
    {
        $request = new static();
        if (null !== $url) {
            $request->setUrl($url);
        }
        return $request;
    }

    /**
     * Init and return current Request
     *
     * @return Request
     */
    public static function current(): Request
    {
        if (null === static::$current) {
            $request = new static();
            $request
                ->setParams($_REQUEST)
                ->setMethod(strtoupper($_SERVER['REQUEST_METHOD']));

            $request->setHeaders($request->loadHeaders());

            static::$current = $request;
        }

        return static::$current;
    }

    /**
     * @return array
     */
    protected function loadHeaders(): array
    {
        $headers = [];
        foreach ($_SERVER as $key => $value) {
            if (substr($key, 0, 5) <> 'HTTP_') {
                continue;
            }
            $header = str_replace(' ', '-', ucwords(str_replace('_', ' ', strtolower(substr($key, 5)))));
            $headers[$header] = $value;
        }
        return $headers;
    }

    /**
     * Send current message and return response message
     *
     * @return Response
     */
    public function send(): Response
    {
        $curl = curl_init();

        $responseHeaders = [];

        curl_setopt_array($curl, [
            CURLOPT_URL            => $this->getUrl(),
            CURLOPT_CUSTOMREQUEST  => $this->getMethod(),
            CURLOPT_HTTPHEADER     => $this->getHeaders(),
            CURLOPT_POSTFIELDS     => $this->getBody(),
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADERFUNCTION =>
                function ($curl, $header) use (&$responseHeaders) {
                    $len = strlen($header);
                    $header = explode(':', $header, 2);
                    if (count($header) < 2) // ignore invalid headers
                    {
                        return $len;
                    }

                    $responseHeaders[strtolower(trim($header[0]))][] = trim($header[1]);

                    return $len;
                },
        ]);

        $responseBody = curl_exec($curl);
        $responseCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        $response = new Response();
        $response
            ->setMethod($this->getMethod())
            ->setBody($responseBody)
            ->setHeaders($responseHeaders)
            ->setCode($responseCode);

        if (curl_errno($curl) > 0) {
            $response->setError(curl_error($curl));
        }

        return $response;
    }
}