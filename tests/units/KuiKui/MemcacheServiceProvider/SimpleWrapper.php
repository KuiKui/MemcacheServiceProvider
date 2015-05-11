<?php
namespace tests\units\KuiKui\MemcacheServiceProvider;

require_once __DIR__.'/../../../bootstrap.php';

use mageekguy\atoum;
use KuiKui\MemcacheServiceProvider;
use Pimple\Container as Application;

class SimpleWrapper extends atoum\test
{
    public function test__constructParameters()
    {
        $this->exception(function() {
            new MemcacheServiceProvider\SimpleWrapper('memcache', new Application());
        })->hasMessage('Object expected : string given');

        $this->exception(function() {
            new MemcacheServiceProvider\SimpleWrapper(array('memcache'), new Application());
        })->hasMessage('Object expected : array given');

        $this->exception(function() {
            new MemcacheServiceProvider\SimpleWrapper(new Application(), new Application());
        })->hasMessage("'Silex\Application' class is not allowed for memcache object");
    }
}