# Simple PHP Throttle

This is yet another Throttling library for PHP applications, that provides a throttling interface and a flexible API for implementing custom throttling algorithms (aka providers) and storage strategies.

## Installation

```
$ composer require nhphero/throttle
```

## Basic Usage

```php
<?php

$storage = new NhpHero\Throttle\Store\RedisStore( 'throttle_storage_prefix::',[
            'scheme' => config('redis.scheme'),
            'host' => config('redis.host'),
            'port' => config('redis.port'),
            'password' => config('redis.password'),
            'timeout' => config('redis.timeout'),
        ]);
$throttle = new NhpHero\Throttle\Throttle($storage);
//Limit 10 request per 60 seconds
$limit = 10;
$time= 60;
$throttleKey = $_SERVER['REMOTE_ADDR'];
if ($throttle->attempt($throttleKey, $limit, $time)) {
    // allow
} else {
    // deny
}
```

## Storage Strategies

Currently, only Redis storage .


## Development and Tests

Feel free to contribute with bug fixing, new providers and storage strategies.

To start contributing, just make a fork of this repo, create a branch which the name explains what you are doing, code your solution and send us a Pull Request.

### Development Installation

```
$ composer install --dev
```

### Running the Tests

```
$ ./vendor/bin/phpunit
```

## Documentation

Coming soon.

## License

This library is licensed under the [MIT license](LICENSE).
