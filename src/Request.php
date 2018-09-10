<?php

namespace Maaaxim\PHPServer;

/**
 * Class Request
 * @package Maaaxim\PHPServer
 */
class Request
{
    /**
     * @var
     */
    protected $method;

    /**
     * @var
     */
    protected $uri;

    /**
     * @var array
     */
    protected $params = [];

    /**
     * @var array
     */
    protected $headers = [];

    /**
     * Request constructor.
     * @param $method
     * @param $uri
     * @param array $headers
     */
    public function __construct($method, $uri, $headers = [])
    {
        $this->method = $method;
        $this->headers = $headers;
        $uriArr = explode('?', $uri);
        if (isset($uriArr[1])) {
            $params = $uriArr[1];
            parse_str($params, $this->params);
        }
        $this->uri = $uriArr[0];
    }

    /**
     * @param $header
     * @return static
     */
    public static function createRequest($header)
    {
        $lines = explode(PHP_EOL, $header);
        list($method, $uri) = explode(' ', array_shift($lines));
        $headers = [];
        foreach ($lines as $line) {
            $line = trim($line);
            if (strpos($line, ': ') !== false) {
                list($key, $value) = explode(': ', $line);
                $headers[$key] = $value;
            }
        }
        return new static($method, $uri, $headers);
    }

    /**
     * @param $key
     * @return mixed|string
     */
    public function param($key)
    {
        return $this->params[$key] ?? '';
    }

    /**
     * @param $key
     * @return string
     */
    public function getHeader($key): string
    {
        return $this->headers[$key] ?? '';
    }

    /**
     * @return string
     */
    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }
}