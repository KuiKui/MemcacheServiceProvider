<?php
namespace tests\units\KuiKui\MemcacheServiceProvider;

require_once __DIR__.'/../../../bootstrap.php';

use mageekguy\atoum;
use KuiKui\MemcacheServiceProvider;
use Silex\Application;

class ServiceProvider extends atoum\test
{
    public function testBootParametersGeneration()
    {
        $app = new Application();
        $provider = new MemcacheServiceProvider\ServiceProvider();

        $this->array($app->keys())
            ->notContains('memcache.default_duration')
            ->notContains('memcache.default_compress')
        ;

        $provider->boot($app);

        $this->array($app->keys())
            ->contains('memcache.default_duration')
            ->contains('memcache.default_compress')
        ;
    }

    public function testGetMemcacheClassDefaultValue()
    {
        $app = new Application();
        $provider = new MemcacheServiceProvider\ServiceProvider();

        $this->string($provider->getMemcacheClass($app))
            ->isEqualTo('\Memcache')
        ;
    }

    public function testGetMemcacheClassIncorrectValue()
    {
        $this->exception(function() {
            $app = new Application();
            $provider = new MemcacheServiceProvider\ServiceProvider();

            $app['memcache.class'] = 'IncorrectValue';
            $provider->getMemcacheClass($app);
        })->hasMessage("Unknown class IncorrectValue. Please set '\Memcache' or '\Memcached'");
    }

    public function testGetMemcacheClassCorrectValue()
    {
        $app = new Application();
        $provider = new MemcacheServiceProvider\ServiceProvider();

        $app['memcache.class'] = '\Memcache';
        $this->string($provider->getMemcacheClass($app))
            ->isEqualTo('\Memcache')
        ;

        $app['memcache.class'] = '\Memcached';
        $this->string($provider->getMemcacheClass($app))
            ->isEqualTo('\Memcached')
        ;
    }

    public function testGetWrapperClassDefaultValue()
    {
        $app = new Application();
        $provider = new MemcacheServiceProvider\ServiceProvider();

        $this->string($provider->getWrapperClass($app))
            ->isEqualTo('KuiKui\MemcacheServiceProvider\SimpleWrapper')
        ;
    }

    public function testGetWrapperClassCorrectValue()
    {
        $app = new Application();
        $provider = new MemcacheServiceProvider\ServiceProvider();

        $app['memcache.wrapper'] = '\CustomWrapper';
        $this->string($provider->getWrapperClass($app))
            ->isEqualTo('\CustomWrapper')
        ;
    }

    public function testGetConnectionsDefaultValue()
    {
        $app = new Application();
        $provider = new MemcacheServiceProvider\ServiceProvider();

        $defaultConnection = $provider->getConnections($app);

        $this->array($defaultConnection)->hasSize(1);
        $this->array($defaultConnection[0])->hasSize(2);
        $this->string($defaultConnection[0][0])->isEqualTo('127.0.0.1');
        $this->integer($defaultConnection[0][1])->isEqualTo(11211);
    }

    public function testGetConnectionsCorrectValue()
    {
        $app = new Application();
        $provider = new MemcacheServiceProvider\ServiceProvider();

        $app['memcache.connections'] = array(
            array('192.168.0.17', 12345),
            array('10.0.1.118', 8080)
        );
        $connections = $provider->getConnections($app);

        $this->array($connections)->hasSize(2);
        $this->array($connections[0])->hasSize(2);
        $this->string($connections[0][0])->isEqualTo('192.168.0.17');
        $this->integer($connections[0][1])->isEqualTo(12345);
        $this->array($connections[1])->hasSize(2);
        $this->string($connections[1][0])->isEqualTo('10.0.1.118');
        $this->integer($connections[1][1])->isEqualTo(8080);
    }

    public function testGetDefaultExpirationTimeDefaultValue()
    {
        $app = new Application();
        $provider = new MemcacheServiceProvider\ServiceProvider();

        $this->integer($provider->getDefaultExpirationTime($app))
            ->isEqualTo(0)
        ;
    }

    public function testGetDefaultExpirationTimeIncorrectValue()
    {
        $app = new Application();
        $provider = new MemcacheServiceProvider\ServiceProvider();

        $app['memcache.default_duration'] = 'IncorrectValue';
        $this->integer($provider->getDefaultExpirationTime($app))
            ->isEqualTo(0)
        ;
    }

    public function testGetDefaultExpirationTimeCorrectValue()
    {
        $app = new Application();
        $provider = new MemcacheServiceProvider\ServiceProvider();

        $app['memcache.default_duration'] = 54321;
        $this->integer($provider->getDefaultExpirationTime($app))
            ->isEqualTo(54321)
        ;
    }

    public function testGetDefaultCompressDefaultValue()
    {
        $app = new Application();
        $provider = new MemcacheServiceProvider\ServiceProvider();

        $this->boolean($provider->getDefaultCompress($app))
            ->isFalse()
        ;
    }

    public function testGetDefaultCompressIncorrectValue()
    {
        $app = new Application();
        $provider = new MemcacheServiceProvider\ServiceProvider();

        $app['memcache.default_compress'] = 'IncorrectValue';
        $this->boolean($provider->getDefaultCompress($app))
            ->isFalse()
        ;
    }

    public function testGetDefaultCompressCorrectValue()
    {
        $app = new Application();
        $provider = new MemcacheServiceProvider\ServiceProvider();

        $app['memcache.default_compress'] = true;
        $this->boolean($provider->getDefaultCompress($app))
            ->isTrue()
        ;
    }

    public function testRegisterWrapperSelection()
    {
        $app = new Application();
        $provider = new MemcacheServiceProvider\ServiceProvider();

        $provider->register($app);

        $this->object($app['memcache'])
            ->isInstanceOf('\KuiKui\MemcacheServiceProvider\AbstractWrapper')
        ;
    }
}
