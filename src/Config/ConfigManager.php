<?php

namespace Db1Fpp\Config;


use Db1Fpp\Exceptions\ConnectionNotFoundException;
use Db1Fpp\Exceptions\UndefinedModeException;
use Db1Fpp\Exceptions\UndefinedServerListExceptions;

class ConfigManager
{
    protected $config = null;

    /**
     * ConfigManager constructor.
     * @param null $config
     */
    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * Get Elasticache config from connection name
     * @param string $connection the name of connection
     * @return array
     */
    public function get($connection)
    {
        $connections = $this->config->get('cache.elasticache');
        if (!array_key_exists($connection, $connections)) {
            throw new ConnectionNotFoundException('The connection "'.$connection.'" 
                dosen\'t existis in connections list');
        }

        $current_connection = $connections[$connection];

        return $this->nomalizeConfig($current_connection);
    }

    private function nomalizeConfig($current_connection)
    {
        if (!array_key_exists('nodes', $current_connection) || empty($current_connection['nodes'])) {
            throw new UndefinedServerListExceptions('Please provide at least one server');
        }

        if (!array_key_exists('mode', $current_connection)) {
            $current_connection['mode'] = 'dynamic';
        }

        // valida se de fato o modo selecionado é static ou dynamic
        if (!in_array($current_connection['mode'], ['dynamic', 'static'])) {
            throw new UndefinedModeException('Undefine mode provided, please chose one of "dynamic" or "static"');
        }

        return $current_connection;
    }
}