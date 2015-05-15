<?php

/**
 * This file is part of the MemcacheServiceProvider package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license MIT License
 */

namespace KuiKui\MemcacheServiceProvider;

use Silex\Application;

/**
 * Memcache wrapper interface.
 *
 * @author Denis Roussel <denis.roussel@gmail.com>
 */
abstract class AbstractWrapper
{
    protected $memcache;
    protected $app;

    public function __construct($memcache, Application $app)
    {
        if (!is_object($memcache)) {
            throw new \Exception(sprintf("Object expected : %s given", gettype($memcache)));
        }

        if (!in_array(get_class($memcache), array('Memcache', 'Memcached'))) {
            throw new \Exception(sprintf("'%s' class is not allowed for memcache object", get_class($memcache)));
        }

        $this->memcache = $memcache;
        $this->app = $app;
    }
}