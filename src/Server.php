<?php

namespace Maaaxim\PHPServer;

/**
 * Class Server
 * @package Maaaxim\PHPServer
 */
class Server
{
    /**
     * @var
     */
    protected $host;

    /**
     * @var
     */
    protected $port;

    /**
     * @var
     */
    protected $socket;

    /**
     * @var array
     */
    protected static $blockUri = [
        "/favicon.ico",
        "other.php"
    ];

    /**
     * Server constructor.
     * @param $host
     * @param $port
     */
    public function __construct($host, $port)
    {
        $this->host = $host;
        $this->port = $port;
        $this->createSocket();
        $this->bind();
    }

    /**
     * Create sock
     */
    protected function createSocket()
    {
        $this->socket = socket_create(AF_INET, SOCK_STREAM, 0);
    }

    /**
     * Set up socket
     */
    protected function bind()
    {
        socket_set_option($this->socket, SOL_SOCKET, SO_REUSEADDR, 1);
        socket_bind($this->socket, $this->host, $this->port);
    }

    /**
     * Start listening for socket
     *
     * @param $callback
     */
    public function listen($callback)
    {
        while (true) {

            socket_listen($this->socket);

            $client = socket_accept($this->socket);

            if (!$client) {
                socket_close($client);
                continue;
            }

            $text = trim(socket_read($client, 1024));
            if (!empty($text)) {
                $request = Request::createRequest($text);
                $uri = $request->getUri();
                $clientName = $request->getHeader("User-Agent");
                if(in_array($uri, self::$blockUri)){
                    echo "Request of {$clientName} to {$uri} blocked" . PHP_EOL;
                } else {
                    $response = call_user_func($callback, $request);
                    socket_write($client, $response, strlen($response));
                    echo "Request from {$clientName} is:" . PHP_EOL;
                    echo json_encode($request) . PHP_EOL;
                }
            }

            socket_close($client);
        }
    }

}