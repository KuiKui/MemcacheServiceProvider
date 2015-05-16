# Memcache service provider for Silex 2.x

[![Build Status](https://secure.travis-ci.org/KuiKui/MemcacheServiceProvider.svg?branch=master)](http://travis-ci.org/KuiKui/MemcacheServiceProvider?branch=master)
[![Total Downloads](https://poser.pugx.org/kuikui/memcache-service-provider/downloads.svg)](https://packagist.org/packages/kuikui/memcache-service-provider)

It provides access to :
* a simple Memcache wrapper with very simple API for standard use,
* or your own Memcache wrapper with `$app` injection for custom use,
* or a genuine Memcache(d) object for advanced use.

## Installation

Create a composer.json in your projects root-directory :

```json
{
    "require": {
        "kuikui/memcache-service-provider": "~2.0"
    }
}
```

and run :

```shell
$ curl -sS http://getcomposer.org/installer | php
$ php composer.phar install
```

## Registering

```php
$app->register(new KuiKui\MemcacheServiceProvider\ServiceProvider());
```

## Example

```php
$app->register(new KuiKui\MemcacheServiceProvider\ServiceProvider());

// Simple use
$app['memcache']->set('key', 'value');
$value = $app['memcache']->get('key');
$app['memcache']->delete('key');

// Advanced use : use \Closure to generate default value and save it during a retrieve process
$value = $app['memcache']->get('key', function() use ($app) {
    return $app['some_other_service']->getData();
});

```

## Options

After registration, you can customize the service with these options :

#### Connections

Allows you to set up one or more Memcache connections.  
Each connection should be defined as follows `array('ip_address', port)`.

```php
$app['memcache.connections'] = array(
    array('127.0.0.1', 11211),
    array('10.0.1.118', 12345)
; // default: array('127.0.0.1', 11211)
```

#### Class

Allows you to choose between the two PHP Memcache libraries : `\Memcache` or `\Memcached`.

```php
$app['memcache.class'] = '\Memcached'; // default: '\Memcache'
```

#### Wrapper

* By default, you access to an instance of `KuiKui\MemcacheServiceProvider\SimpleWrapper`.  
* For custom needs, you can use your own wrapper :

```php
$app['memcache.wrapper'] = '\My\Custom\Wrapper';
```

* Or you can have direct acces to Memcache(d) object :

```php
$app['memcache.wrapper'] = false;
```

#### Duration

If you use `SimpleWrapper`, you can configure the default duration of cached data (in seconds):

```php
$app['memcache.default_duration'] = 60; // default: 0 (no limit)
```

## Running the tests

The development environment is provided by Vagrant and the [Xotelia box](https://github.com/Xotelia/VagrantBox).

```shell
$ cp Vagrantfile.dist Vagrantfile
$ vagrant up
$ vagrant ssh
```

```shell
$ cd /vagrant
$ composer install
$ ./vendor/bin/atoum
```

## Dependencies

PHP 5.5+

MemcacheServiceProvider needs one of these PHP modules to be installed :
* [memcache](http://www.php.net/manual/en/book.memcache.php)
* [memcached](http://www.php.net/manual/en/book.memcached.php)

## Credits

Deeply inspired by [MemcacheServiceProvider](https://github.com/RafalFilipek/MemcacheServiceProvider) from Rafał Filipek.  
Tested with [atoum](http://atoum.org).

## License

The MemcacheServiceProvider is licensed under the [MIT license](https://github.com/KuiKui/MemcacheServiceProvider/blob/master/LICENSE).

