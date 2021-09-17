<?php

use Swoole\WebSocket\Server;
use Swoole\Http\Request;
use Swoole\WebSocket\Frame;

$server = new Server("0.0.0.0", 9502);


$server->on("Start", function (Server $server) {
    echo "Swoole WebSocket Server is started at http://127.0.0.1:9502\n";
});

$server->on('Open', function (Server $server, Swoole\Http\Request $request) {
    echo "connection open: {$request->fd}\n";
});

$server->on('Message', function (Server $server, Frame $frame) {

    $payload = json_decode($frame->data);

    switch ($payload->type) {
        case 'ping':
            $server->push($frame->fd, json_encode([
                "type" => "ping-accept",
                "data" => "pong"
            ]));
            break;
        case 'subscribe':
            $server->push($frame->fd, json_encode([
                "type" => "ack",
                "data" => "accepted"
            ]));
            $server->tick(20000, function () use ($server, $frame) {
                $server->push($frame->fd, json_encode([
                    "type" => "ping",
                    "data" => "pong"
                ]));
            });
            echo "subscribed to : {$payload->data}\n";

            $redis = new Swoole\Coroutine\Redis();

            $redis->connect("127.0.0.1", 6379);

            $channels = ['exlunode_database_' . $payload->data];

            $msg = $redis->subscribe($channels);

            while ($msg = $redis->recv()) {
                $type = $msg[0];
                $channel = $msg[1];
                $message = $msg[2];

                echo "found command at channel $channel with message of $message \n";

                if ($type == "message" && $channel == $channels[0]) {

                    try {
                        $server->push($frame->fd, json_encode([
                            "type" => "mc-command",
                            "data" => $message
                        ]));
                    } catch (\Throwable $th) {
                        return;
                    }
                }
            }
            break;

        default:
            $server->push($frame->fd, json_encode([
                "type" => "exception",
                "data" => "command type is not recognized"
            ]));
            break;
    }
    // echo "received message: {$frame->data}\n";
    // $server->push($frame->fd, json_encode(["hello", time()]));
});

$server->on('Close', function (Server $server, int $fd) {
    echo "connection close: {$fd}\n";
});

$server->on('Disconnect', function (Server $server, int $fd) {
    echo "connection disconnect: {$fd}\n";
});

$server->start();
