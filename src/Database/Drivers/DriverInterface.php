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
     * Save connection with database via PDO drive
     *
     * @param   null|\PDO $connection
     * @return  DriverInterface
     */
    public function setConnection($connection): DriverInterface;

    /**
     * Get current PDO connection
     *
     * @return  \PDO
     */
    public function getConnection(): \PDO;

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
