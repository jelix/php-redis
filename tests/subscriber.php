<?php

require(__DIR__.'/../Redis.php');

$redis = new Redis();

$redis->subscribe('mychan', function($redis,$channel, $payload) {
    echo "Receive $channel : $payload \n";
    if ($payload == 'end')
        return 'end';
});

