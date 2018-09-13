#!/usr/bin/env php
<?php

use Maaaxim\PHPServer\Request;
use Maaaxim\PHPServer\Response;
use Maaaxim\PHPServer\Server;

require_once './vendor/autoload.php';

$server = new Server('localhost', 8888);
$server->listen(function (Request $request) {
    return new Response(
        'Hi, '
        . $request->param('name')
    );
});