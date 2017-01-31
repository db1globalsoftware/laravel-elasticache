<?php

namespace Db1Fpp\Providers;

use Db1Fpp\Extensions\ElastiCacheStore;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;

class ElastiCacheServiceProvider extends ServiceProvider
{

    public function boot()
    {
        Cache::extend('elasticache', function ($app) {
            return new ElastiCacheStore(new \Memcached, 'laravel');
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