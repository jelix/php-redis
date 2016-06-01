<?php

class mainTest extends PHPUnit_Framework_TestCase {

    static $redis;

    function setUp() {
        if (!self::$redis) {
            self::$redis = new Redis(REDIS_TEST_HOST, REDIS_TEST_PORT);
            self::$redis->flushall();
        }
    }

    function tearDown() {
    }

    function testBasic() {
        self::$redis->nonempty = 'abv';
        self::$redis->empty = '';
        $this->assertEquals("OK", self::$redis->set("foo", "bar"));
        $this->assertEquals('abv', self::$redis->get('nonempty'));
        $this->assertEquals('abv', self::$redis->nonempty);
        $this->assertEquals('', self::$redis->get('empty'));
        $this->assertNull(self::$redis->unsetkey);
    }

    function testUtf8() {
        // Test binary safety using UTF8 keys and data (in bulgarian)
        self::$redis->{'КИРИЛИЦА'} = "ДА";
        $this->assertEquals('ДА', self::$redis->get('КИРИЛИЦА'));
    }

    function testCallCommand() {
        $this->assertEquals(1, self::$redis->zadd("zkey", 1, "one"));
        $this->assertEquals(1, self::$redis->zadd("zkey", 2, "two"));
        $this->assertEquals(1, self::$redis->zadd("zkey", 3, "three"));
        $this->assertEquals(3, self::$redis->zcard("zkey"));
        $this->assertEquals(1, self::$redis->zrevrank("zkey", "two"));
        $this->assertEquals(1, self::$redis->zrem("zkey", "two"));
        $this->assertEquals(2, self::$redis->zcount("zkey", "-inf", "+inf"));
        $this->assertEquals(2, self::$redis->ZREMRANGEBYRANK("zkey", 0, 3));
    }

    function testPipeline() {
        self::$redis->pipeline_begin();
        $this->assertNull(self::$redis->set("pipeline1", "val1"));
        $this->assertNull(self::$redis->set("pipeline2", "val2"));
        $this->assertNull(self::$redis->set("pipeline3", "val3"));
        $this->assertNull(self::$redis->set("pipeline4", "val4"));
        $this->assertNull(self::$redis->set("pipeline5", "val5"));
        $this->assertNull(self::$redis->get("pipeline2"));
        $this->assertEquals(array("OK","OK","OK","OK","OK","val2"),
                             self::$redis->pipeline_responses());
    }

}