<?php

namespace Db1Fpp\Providers;

use Db1Fpp\Config\ConfigManager;
use Db1Fpp\Factories\MemcachedFactory;
use Illuminate\Cache\MemcachedStore;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;

class ElastiCacheServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $config = $this->app->make('config');
        $configManager = new ConfigManager($config);

        Cache::extend('elasticache', function (Application $app, $driverConfig) use ($configManager, $config) {
            $elasticacheConfig = $configManager->get($driverConfig['connection']);
            $memcachedInstance = MemcachedFactory::factory($elasticacheConfig);

            return new MemcachedStore($memcachedInstance, $config->get('cache.prefix'));
        });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // TODO: Implement register() method.
    }
}