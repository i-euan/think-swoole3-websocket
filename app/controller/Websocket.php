<?php
declare (strict_types = 1);

namespace app\controller;

use think\Request;

class Websocket
{
	public function __construct()
	{
		$server = new \Swoole\Websocket\Server("127.0.0.1", 9502);
		$server->on('open', function($server, $req) {
        echo "connection open: {$req->fd}\n";
    });

    $server->on('message', function($server, $frame) {
        echo "received message: {$frame->data}\n";
        $server->push($frame->fd, json_encode(["hello", "world"]));
    });

    $server->on('close', function($server, $fd) {
        echo "connection close: {$fd}\n";
    });
    		$server->start();
	}
}
new Websocket;
