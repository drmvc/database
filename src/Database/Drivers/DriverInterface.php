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
     * @param   ConfigInterface $config
     * @return  DriverInterface
     */
    public function setConfig(ConfigInterface $config): DriverInterface;

    /**
     * @return  ConfigInterface
     */
    public function getConfig(): ConfigInterface;

    /**
     * @param   $connection
     * @return  DriverInterface
     */
    public function setConnection(\PDO $connection): DriverInterface;

    /**
     * @return  \PDO
     */
    public function getConnection(): \PDO;

    /**
     * @param   string $dsn
     * @return  DriverInterface
     */
    public function setDsn(string $dsn): DriverInterface;

    /**
     * @return  string
     */
    public function getDsn(): string;
}
