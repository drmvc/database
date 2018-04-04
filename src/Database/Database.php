<?php

namespace DrMVC\Database;

use DrMVC\Config\ConfigInterface;
use DrMVC\Database\Drivers\QueryInterface;

/**
 * Class for work with databases
 * @package Modules\Database\Core
 */
class Database implements DatabaseInterface
{
    /**
     * @var ConfigInterface
     */
    private $_config;

    /**
     * Database constructor.
     *
     * @param   ConfigInterface $config
     */
    public function __construct(ConfigInterface $config)
    {
        $this->setConfig($config);
    }

    /**
     * @param   string $collection
     * @return  QueryInterface
     */
    public function getInstance(string $collection = null): QueryInterface
    {
        $class = $this->getDriver();
        $instance = new $class($this->getConfig(), $collection);

        // If collection name is not provided then need return clean instance for ORM
        return (null !== $collection)
            ? $instance->connect()
            : $instance->connect()->getInstance();
    }

    /**
     * Get driver of current database
     *
     * @return  string
     */
    public function getDriver(): string
    {
        // Extract driver name from current config
        $driver = $this->getConfig('driver');

        try {
            if (!\is_string($driver)) {
                throw new Exception('Wrong type of "driver" item in config, must be a string');
            }

            if (!\in_array($driver, self::ALLOWED_DRIVERS, false)) {
                $allowed = implode(',', self::ALLOWED_DRIVERS);
                throw new Exception("Driver \"$driver\" is not in allowed list [" . $allowed . ']');
            }
        } catch (Exception $e) {
            // __constructor
        }

        return __NAMESPACE__ . '\\Drivers\\' . ucfirst(strtolower($driver));
    }

    /**
     * Set configuration of database
     *
     * @param   ConfigInterface $config
     * @return  DatabaseInterface
     */
    public function setConfig(ConfigInterface $config): DatabaseInterface
    {
        $this->_config = $config;
        return $this;
    }

    /**
     * Get database configuration object or array with single database
     *
     * @param   string|null $name
     * @return  mixed
     */
    public function getConfig(string $name = null)
    {
        return (null !== $name)
            ? $this->_config->get($name)
            : $this->_config;
    }

}
