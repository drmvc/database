<?php

namespace DrMVC\Database;

use DrMVC\Config\ConfigInterface;
use DrMVC\Database\Drivers\DriverInterface;
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
    public function __construct(ConfigInterface $config = null)
    {
        if (null !== $config) {
            $this->setConfig($config);
        }
    }

    /**
     * @return QueryInterface
     */
    public function getInstance(): QueryInterface
    {
        $class = $this->getDriver();
        return new $class($this->getConfig());
    }

    /**
     * Get driver of current database
     *
     * @return  string
     */
    private function getDriver(): string
    {
        $driver = $this->getConfig('driver');

        try {
            if (!\in_array($driver, self::ALLOWED_DRIVERS, false)) {
                throw new Exception("Driver \"$driver\" is not in allowed list [" . implode(',',
                        self::ALLOWED_DRIVERS) . ']');
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
