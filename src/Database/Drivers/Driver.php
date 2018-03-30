<?php

namespace DrMVC\Database\Drivers;

use DrMVC\Config\ConfigInterface;

abstract class Driver implements DriverInterface, QueryInterface
{
    /**
     * @var \PDO
     */
    protected $_connection;

    /**
     * @var string
     */
    private $_dsn;

    /**
     * @var ConfigInterface
     */
    private $_config;

    /**
     * Name of collection
     * @var string
     */
    private $_collection;

    /**
     * Driver constructor.
     *
     * @param   ConfigInterface $config object with current configuration
     * @param   string $collection name of collection
     */
    public function __construct(ConfigInterface $config, string $collection)
    {
        $this->setConfig($config);
        $this->setCollection($this->getConfig()->get('prefix') . $collection);
    }

    /**
     * Initiate connection with database
     *
     * @return  DriverInterface
     */
    abstract public function connect(): DriverInterface;

    /**
     * Disconnect from database
     *
     * @return  DriverInterface
     */
    abstract public function disconnect(): DriverInterface;

    /**
     * @param   string $collection
     * @return  DriverInterface
     */
    public function setCollection(string $collection): DriverInterface
    {
        $this->_collection = $collection;
        return $this;
    }

    /**
     * @return string
     */
    public function getCollection(): string
    {
        return $this->_collection;
    }

    /**
     * @param   ConfigInterface $config
     * @return  DriverInterface
     */
    public function setConfig(ConfigInterface $config): DriverInterface
    {
        $this->_config = $config;
        return $this;
    }

    /**
     * @return  ConfigInterface
     */
    public function getConfig(): ConfigInterface
    {
        return $this->_config;
    }

    /**
     * @param   string $dsn
     * @return  DriverInterface
     */
    public function setDsn(string $dsn): DriverInterface
    {
        $this->_dsn = $dsn;
        return $this;
    }

    /**
     * @return  string
     */
    public function getDsn(): string
    {
        return $this->_dsn;
    }

    /**
     * @param   \PDO $connection
     * @return  DriverInterface
     */
    public function setConnection(\PDO $connection): DriverInterface
    {
        $this->_connection = $connection;
        return $this;
    }

    /**
     * @return  \PDO
     */
    public function getConnection(): \PDO
    {
        return $this->_connection;
    }
}
