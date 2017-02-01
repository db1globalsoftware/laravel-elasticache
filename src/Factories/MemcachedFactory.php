<?php

namespace Db1Fpp\Factories;


use Db1Fpp\Exceptions\ConnectionErrorException;
use Db1Fpp\Exceptions\ExtensionNotLoadedException;

class MemcachedFactory
{
    public static function factory($elasticacheConfig)
    {
        if (!extension_loaded('memcached')) {
            throw new ExtensionNotLoadedException('The memcached extension was not loaded');
        }

        $memcached = new \Memcached;

        // Set Elasticache options here
        $amazonExtensionOK = false;
        if (defined('\Memcached::OPT_CLIENT_MODE') &&
            defined('\Memcached::DYNAMIC_CLIENT_MODE') &&
            defined('\Memcached::STATIC_CLIENT_MODE')
        ) {

            $amazonExtensionOK = true;
        }

        // verifica se é modo dinâmico
        if ($elasticacheConfig['mode'] == 'dynamic') {
            if (!$amazonExtensionOK) {
                throw new ExtensionNotLoadedException('You tried to use dynamic mode but the Amazon Memcached extension is 
                not loaded. Do you really installed the Amazon memcached extension for PHP?');
            } else {
                $memcached->setOption(\Memcached::OPT_CLIENT_MODE, \Memcached::DYNAMIC_CLIENT_MODE);
            }
        }

        /*
         * Verifica se é modo estático. Este não irá gerar exception pois este é o comportamento
         * padrão do memcached, a diferença é que se a extensão da Amazon estiver carregada nos
         * vamos setar o modo estático usando as configurações da extensão, caso contrário será
         * uma instância de Memcached normal, como se a extensão nem existisse
         */
        if ($elasticacheConfig['mode'] == 'static' && $amazonExtensionOK) {
            $memcached->setOption(\Memcached::OPT_CLIENT_MODE, \Memcached::STATIC_CLIENT_MODE);
        }

        // adiciona os nós do elasticache
        foreach ($elasticacheConfig['nodes'] as $node) {
            $memcached->addServer($node['host'], $node['port'], $node['weight']);
        }

        if ($memcached->getVersion() === false) {
            throw new ConnectionErrorException('Could not establish Memcached connection.');
        }

        return $memcached;
    }
}