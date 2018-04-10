<?php

namespace DrMVC\Database;

use DrMVC\Config\ConfigInterface;
use DrMVC\Database\Drivers\QueryInterface;
use MongoDB\Driver\Manager;
use PDO;

interface DatabaseInterface
{
    /**
     * Default name of database
     */
    const DEFAULT_CONNECTION = 'default';

    /**
     * Allowed drivers
     */
    const ALLOWED_DRIVERS = ['mysql', 'pgsql', 'sqlite', 'mongodb'];

    /**
     * @param   string $collection
     * @return  QueryInterface|PDO|Manager
     */
    public function getInstance(string $collection);

    /**
     * Get driver of current database
     *
     * @return  string
     */
    public function getDriver(): string;

    /**
     * Set configuration of database
     *
     * @param   ConfigInterface $config
     * @return  DatabaseInterface
     */
    public function setConfig(ConfigInterface $config): DatabaseInterface;

    /**
     * Get database configuration object or array with single database
     *
     * @param   string|null $name
     * @return  mixed
     */
    public function getConfig(string $name = null);

}
