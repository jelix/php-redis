<?php

class deleteKeysTest extends PHPUnit_Framework_TestCase {

    static $redis;

    function setUp() {
        if (!self::$redis) {
            self::$redis = new Redis(REDIS_TEST_HOST, REDIS_TEST_PORT);
            self::$redis->flushall();
        }
    }

    function tearDown() {
    }

    function testDeletePrefix() {
        // let's fill the database values
        self::$redis->set('foo:bar', "yes");
        self::$redis->set('hello', "world");

        for($i=0; $i < 5500; $i++) {
            self::$redis->set('user:lorem:ipsum:machin:bidule:'.$i.'aaaaaa/bbbbbbb/ccccccc/dddddd', "name".$i);
        }

        sleep(1);
        //$a = memory_get_peak_usage();
        // now let's delete them
        self::$redis->flushByPrefix("user:lorem:ipsum:machin:bidule:");
        //$b = memory_get_peak_usage();
        //echo $b-$a;

        // let's verify that there is only two keys
        $keys = self::$redis->keys('*');
        $this->assertEquals(2, count($keys));
        sort($keys);
        $this->assertEquals(array('foo:bar', 'hello'), $keys);
    }
}