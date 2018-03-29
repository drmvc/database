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
     * Driver constructor.
     * @param ConfigInterface $config
     */
    public function __construct(ConfigInterface $config)
    {
        $this->_config = $config;
    }

    /**
     * @return DriverInterface
     */
    abstract public function connect(): DriverInterface;

    /**
     * @return DriverInterface
     */
    abstract public function disconnect(): DriverInterface;

    /**
     * @param ConfigInterface $config
     * @return DriverInterface
     */
    public function setConfig(ConfigInterface $config): DriverInterface
    {
        $this->_config = $config;
        return $this;
    }

    /**
     * @return ConfigInterface
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
     * @param   mixed $connection
     * @return  DriverInterface
     */
    public function setConnection($connection): DriverInterface
    {
        $this->_connection = $connection;
        return $this;
    }

    /**
     * @return  mixed
     */
    public function getConnection()
    {
        return $this->_connection;
    }
}
