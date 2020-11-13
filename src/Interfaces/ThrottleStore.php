<?php
namespace NhpHero\Throttle\Interfaces;


interface ThrottleStore
{
    public function get(string $key);
    public function put(string $key , $number,int $time);
    public function increment(string $key ,int $value, int $time);
}
