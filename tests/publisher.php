<?php

include (__DIR__.'/../vendor/autoload.php');

$redis = new \PhpRedis\Redis();

$redis->publish('mychan', $argv[1]);

