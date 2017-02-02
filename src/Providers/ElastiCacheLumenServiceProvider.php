<?php

namespace Db1Fpp\Providers;


use Db1Fpp\Config\ConfigManager;
use Db1Fpp\Factories\MemcachedFactory;
use Illuminate\Cache\MemcachedStore;
use Illuminate\Cache\Repository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;

class ElastiCacheLumenServiceProvider extends ServiceProvider
{

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $config        = $this->app->make('config');
        $configManager = new ConfigManager($config);

        Cache::extend('elasticache', function (Application $app, $driverConfig) use ($configManager, $config) {
            $elasticacheConfig = $configManager->get($driverConfig['connection']);
            $memcachedInstance = MemcachedFactory::factory($elasticacheConfig);

            return new Repository(new MemcachedStore($memcachedInstance, $config->get('cache.prefix')));
        });
    }
}