<?php

/**
 * This file is part of the MemcacheServiceProvider package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license MIT License
 */

namespace KuiKui\MemcacheServiceProvider;

/**
 * Memcache simple wrapper.
 *
 * @author Denis Roussel <denis.roussel@gmail.com>
 */
class SimpleWrapper extends AbstractWrapper
{
    public function set($key, $data, $expiration = null, $compress = null)
    {
        if (is_null($expiration)) {
            $expiration = $this->app['memcache.default_duration'];
        }

        if (is_null($compress)) {
            $compress = $this->app['memcache.default_compress'];
        }

        if (get_class($this->memcache) == 'Memcache') {
            return $this->memcache->set($key, $data, ($compress) ? MEMCACHE_COMPRESSED : null, $expiration);
        } else if (get_class($this->memcache) == 'Memcached') {
            return $this->memcache->set($key, $data, $expiration);
        }
    }

    public function get($key, \Closure $fallback = null, $expiration = null, $compress = null)
    {
        $result = $this->memcache->get($key);

        if ($result === false && $fallback instanceof \Closure) {
            $result = $fallback();
            if ($this->set($key, $result, $expiration, $compress) === false) {
                return false;
            }
        }

        return $result;
    }

    public function delete($key)
    {
        return $this->memcache->delete($key, 0);
    }

    public function flush()
    {
        return $this->memcache->flush();
    }
}
