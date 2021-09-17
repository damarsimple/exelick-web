<?php
go(function () {
    $redis = new Swoole\Coroutine\Redis();
    $redis->connect("127.0.0.1", 6379);
    $msg = $redis->subscribe(array("exlunode_database_mc_abcd"));
    while ($msg = $redis->recv()) {
        var_dump($msg);
    }
});

go(function () {
    $redis = new Swoole\Coroutine\Redis();
    $redis->connect("127.0.0.1", 6379);
    Swoole\Timer::tick(1000, fn () => $redis->publish('exlunode_database_mc_abcd', 'say hello, ' . time()));
});
