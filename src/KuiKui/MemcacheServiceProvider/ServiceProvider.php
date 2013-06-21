<?php

/**
 * This file is part of the MemcacheServiceProvider package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license    MIT License
 */

namespace KuiKui\MemcacheServiceProvider;

use Silex\Application;
use Silex\ServiceProviderInterface;

/**
 * Memcache service provider.
 *
 * @author Denis Roussel <denis.roussel@gmail.com>
 */
class ServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
	$mainClass = __CLASS__;
        $app['memcache'] = $app->share(function() use ($app, $mainClass) {
            $memcacheClass = $mainClass::getMemcacheClass($app);
            $memcacheInstance = new $memcacheClass();

            foreach ($mainClass::getConnections($app) as $connection) {
                call_user_func_array(array($memcacheInstance, 'addServer'), array_values($connection));
            }

            $wrapperClass = $mainClass::getWrapperClass($app);
            if ($wrapperClass === false) {
                return $memcacheInstance;
            }

            return new $wrapperClass($memcacheInstance, $app);
        });
    }

    public function boot(Application $app)
    {
        $app['memcache.default_duration'] = $this->getDefaultExpirationTime($app);
        $app['memcache.default_compress'] = $this->getDefaultCompress($app);
    }

    public static function getMemcacheClass(Application $app)
    {
        if (!isset($app['memcache.class'])) {
            return '\Memcache';
        }

        $memcacheClass = $app['memcache.class'];

        if (!in_array($memcacheClass, array('\Memcache', '\Memcached'))) {
            throw new \Exception(sprintf("Unknown class %s. Please set '\Memcache' or '\Memcached'", $memcacheClass));
        }

        return $memcacheClass;
    }

    public static function getWrapperClass(Application $app)
    {
        if (!isset($app['memcache.wrapper'])) {
            return __NAMESPACE__.'\SimpleWrapper';
        }

        return $app['memcache.wrapper'];
    }

    public static function getConnections(Application $app)
    {
        if (!isset($app['memcache.connections'])) {
            return array(array('127.0.0.1', 11211));
        }

        return $app['memcache.connections'];
    }

    public function getDefaultExpirationTime(Application $app)
    {
        if (isset($app['memcache.default_duration']) && is_int($app['memcache.default_duration'])) {
            return $app['memcache.default_duration'];
        }

        return 0;
    }

    public function getDefaultCompress(Application $app)
    {
        if (isset($app['memcache.default_compress']) && is_bool($app['memcache.default_compress'])) {
            return $app['memcache.default_compress'];
        }

        return false;
    }
}
