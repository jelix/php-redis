<?php

require(__DIR__.'/../Redis.php');

$redis = new Redis();

$redis->publish('mychan', $argv[1]);

