<?php

namespace NhpHero\Throttle\Store;


use Predis\Client;
use NhpHero\Throttle\Interfaces\ThrottleStore;

class RedisStore implements ThrottleStore
{
    /**
     * @var Predis\Client
     */
    private $client;
    /**
     * @var string
     */
    private $prefix;

    /**
     * RedisStore constructor.
     * @param string $prefix
     * @param array $redisConfig
     * @param array $redisOptions
     */
    public function __construct($prefix = '', array $redisConfig, array $redisOptions = [])
    {
        $this->client = new Client($redisConfig, $redisOptions);
        $this->prefix = $prefix;
    }

    /**
     * @param string $key
     * @return string|null
     */
    public function get(string $key)
    {
        return $this->client->get($this->computeRedisKey($key));
    }

    /**
     * @param string $key
     * @param $value
     * @param int $time
     * @return mixed
     */
    public function put(string $key, $value, int $time)
    {
        return $this->increment($key, $value, $time);
    }

    public function increment(string $key,int $value, int $time)
    {
        $lua = 'local v = redis.call(\'incr\', KEYS[1]) ' .
            'if v>1 then return v ' .
            'else redis.call(\'setex\', KEYS[1], ARGV[1], 1) return 1 end';

        $number = $this->client->eval($lua, $value, $this->computeRedisKey($key), $time);

        return $number;
    }

    public function computeRedisKey($key)
    {
        return $this->prefix . $key;
    }
}
