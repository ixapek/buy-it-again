<?php


namespace ixapek\BuyItAgain\Component\Http;

/**
 * Class Message
 *
 * This is simple non-PSR HTTP-message container
 *
 * @package ixapek\BuyItAgain\Component\Http
 */
abstract class Message
{
    /** @var array[]|int[]|bool[]|string[]|float[] */
    protected $params;
    /** @var string $method POST|PUT|GET|DELETE */
    protected $method;
    /** @var string $url */
    protected $url;
    /** @var string $body */
    protected $body;
    /** @var array $headers */
    protected $headers;
    /** @var string $error */
    protected $error;
    /** @var int $code */
    protected $code;

    /**
     * Get integer request param
     *
     * @param string $key
     *
     * @return int|null
     */
    public function getInt(string $key): ?int
    {
        return filter_var($this->params[$key] ?? null, FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
    }

    /**
     * Get float request param
     *
     * @param string $key
     *
     * @return float|null
     */
    public function getFloat(string $key): ?float
    {
        return filter_var($this->params[$key] ?? null, FILTER_VALIDATE_FLOAT, FILTER_NULL_ON_FAILURE);
    }

    /**
     * Get array request param
     *
     * @param string $key
     *
     * @return array|null
     */
    public function getArray(string $key): ?array
    {
        return filter_var($this->params[$key] ?? null, FILTER_REQUIRE_ARRAY, FILTER_NULL_ON_FAILURE);
    }

    /**
     * Get string request param
     *
     * @param string $key
     *
     * @return string|null
     */
    public function getString(string $key): ?string
    {
        return filter_var($this->params[$key] ?? null, FILTER_DEFAULT, FILTER_NULL_ON_FAILURE);
    }

    /**
     * Get bool request param
     *
     * @param string $key
     *
     * @return bool|null
     */
    public function getBool(string $key): ?bool
    {
        return filter_var($this->params[$key] ?? null, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
    }

    /**
     * @return array[]|bool[]|float[]|int[]|string[]
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param array[]|bool[]|float[]|int[]|string[] $params
     *
     * @return $this
     */
    public function setParams($params)
    {
        $this->params = $params;
        return $this;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @param string $method
     *
     * @return $this
     */
    public function setMethod(string $method): self
    {
        $this->method = $method;
        return $this;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     *
     * @return $this
     */
    public function setUrl(string $url): self
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * @param string $body
     *
     * @return $this
     */
    public function setBody(string $body): self
    {
        $this->body = $body;
        return $this;
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @param array $headers
     *
     * @return $this
     */
    public function setHeaders(array $headers): self
    {
        $this->headers = $headers;
        return $this;
    }

    /**
     * @return string
     */
    public function getError(): string
    {
        return $this->error;
    }

    /**
     * @param string $error
     *
     * @return Message
     */
    public function setError(string $error): Message
    {
        $this->error = $error;
        return $this;
    }

    /**
     * @return int
     */
    public function getCode(): int
    {
        return $this->code;
    }

    /**
     * @param int $code
     *
     * @return Message
     */
    public function setCode(int $code): Message
    {
        $this->code = $code;
        return $this;
    }


}