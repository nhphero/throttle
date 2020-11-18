<?php


declare(strict_types=1);

/*
 * This file is part of PHP Throttle.
 *
 * (c) Phuong Nguyen <nhphero@gmail.com>
 *
 */

namespace NhpHero\Throttle;


use NhpHero\Throttle\Interfaces\ThrottleStore;

/**
 * @author Phuong Nguyen <nhphero@gmail.com>
 */
class Throttle
{
    /**
     * @var ThrottleStore
     */
    protected $store;

    /**
     * The number of requests.
     *
     * @var int
     */
    protected $number;

    /**
     * Throttle constructor.
     * @param ThrottleStore $store
     */
    public function __construct(ThrottleStore $store)
    {
        $this->store = $store;
    }

    /**
     * @param string $key
     * @param int $limit
     * @param int $time
     * @return bool
     */
    public function attempt(string $key, int $limit, int $time)
    {
        $response = $this->check($key, $limit, $time);

        if (!$response) {
            $this->number = null;
        }

        $this->hit($key, $time);

        return $response;
    }

    /**
     * @param $key
     * @param $time
     * @return $this
     */
    public function hit($key, $time)
    {
        if ($this->count($key)) {
            $this->store->increment($key, 1, $time);
            $this->number++;
        } else {
            $this->store->put($key, 1, $time);
            $this->number = 1;
        }

        return $this;
    }

    /**
     * Clear the throttle.
     *
     * @return $this
     */
    public function clear($key, $number, $time)
    {
        $this->number = 0;

        $this->store->put($key, $number, $time);

        return $this;
    }

    /**
     * @param $key
     * @return int
     */
    public function count($key)
    {
        if ($this->number !== null) {
            return $this->number;
        }

        $this->number = (int)$this->store->get($key);

        if (!$this->number) {
            $this->number = 0;
        }

        return $this->number;
    }

    /**
     * @param $key
     * @param $limit
     * @param $time
     * @return bool
     */
    public function check($key, $limit, $time)
    {
        return $this->count($key, $time) < $limit;
    }


}
