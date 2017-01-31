<?php

include (__DIR__.'/../vendor/autoload.php');

$redis = new \PhpRedis\Redis();

$redis->subscribe('mychan', function($redis,$channel, $payload) {
    echo "Receive $channel : $payload \n";
    if ($payload == 'end')
        return 'end';
});

