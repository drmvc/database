<?php

namespace DrMVC\Database\Drivers;

use DrMVC\Config\ConfigInterface;

interface DriverInterface
{
    /**
     * @param   string $collection
     * @return  DriverInterface
     */
    public function setCollection(string $collection): DriverInterface;

    /**
     * @return  string
     */
    public function getCollection(): string;

    /**
     * Save current config
     *
     * @param   ConfigInterface $config
     * @return  DriverInterface
     */
    public function setConfig(ConfigInterface $config): DriverInterface;

    /**
     * Return config object
     *
     * @return  ConfigInterface
     */
    public function getConfig(): ConfigInterface;

    /**
     * Save connection with database via driver
     *
     * @param   mixed $instance
     * @return  DriverInterface
     */
    public function setInstance($instance): DriverInterface;

    /**
     * Get current connection
     *
     * @return  mixed
     */
    public function getInstance();

    /**
     * Generate DSN by data in config
     *
     * @return  string
     */
    public function getDsn(): string;

    /**
     * Get some parameter from config by keyname
     *
     * @param   string $param
     * @return  mixed
     */
    public function getParam(string $param);
}
