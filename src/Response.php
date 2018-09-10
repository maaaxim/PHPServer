<?php

namespace Maaaxim\PHPServer;

/**
 * Class Response
 * @package Maaaxim\PHPServer
 */
class Response
{
    /**
     * @var
     */
    protected $body;

    /**
     * @var int|null
     */
    protected $status = 200;

    /**
     * @var array
     */
    protected $headers = [];

    /**
     * @var array
     */
    protected $statusCodes = [
        200 => 'Ok',
        404 => 'Error',
    ];

    /**
     * Response constructor.
     * @param $body
     * @param null $status
     */
    public function __construct($body, $status = null)
    {
        if (!is_null($status))
            $this->status = $status;
        $this->body = $body;
        $this->header('Date', gmdate('D, d M Y H:i:s T'));
        $this->header('Content-Type', 'text/html; charset=utf8');
        $this->header('Server', 'Some socket PHPServer');
    }

    /**
     * @param $key
     * @param $value
     */
    public function header($key, $value)
    {
        $this->headers[ucfirst($key)] = $value;
    }

    /**
     * @return string
     */
    public function buildHeader()
    {
        $lines = [];
        $lines[] = "HTTP/1.1 ". $this->status . " " . $this->statusCodes[$this->status];
        foreach ($this->headers as $k => $v) {
            $lines[] = $k . ": ". $v;
        }
        return implode(PHP_EOL, $lines) . PHP_EOL . PHP_EOL;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->buildHeader() . $this->body . PHP_EOL;
    }

}