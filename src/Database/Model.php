<?php

namespace DrMVC\Database;

use DrMVC\Config\ConfigInterface;
use DrMVC\Database\Drivers\QueryInterface;

class Model implements ModelInterface
{
    /**
     * @var QueryInterface
     */
    private $_instance;

    /**
     * Default connection name
     * @var string
     */
    protected $connection = Database::DEFAULT_CONNECTION;

    /**
     * Name of collection for query
     * @var string|null
     */
    protected $collection;

    /**
     * Model constructor.
     * @param   ConfigInterface $config
     */
    public function __construct(ConfigInterface $config)
    {
        // get current connection
        $connection = $this->getConnection();
        // Get config of required connection
        $config_db = $config->get($connection);
        try {
            if (null === $config_db) {
                throw new Exception("Connection \"$connection\" is not found in database config");
            }
        } catch (Exception $e) {
            // __constructor
        }

        // Create database object with config from above
        $database = new Database($config_db);

        // Get current collection name
        $collection = $this->getCollection();
        // Extract instance created by driver and put collection name
        $instance = $database->getInstance($collection);

        // Keep driver's instance as local parameter
        $this->setInstance($instance);
    }

    /**
     * Get current database name
     *
     * @return string
     */
    public function getConnection(): string
    {
        return $this->connection;
    }

    /**
     * Get current collection
     *
     * @return  string|null
     */
    public function getCollection()
    {
        $collection = $this->collection;
        try {
            if (null === $collection) {
                throw new Exception('Collection is not set');
            }
        } catch (Exception $e) {
            // __constructor
        }
        return $collection;
    }

    /**
     * Set database instance
     *
     * @param   QueryInterface $instance
     */
    private function setInstance(QueryInterface $instance)
    {
        $this->_instance = $instance;
    }

    /**
     * Get database instance
     *
     * @return QueryInterface
     */
    public function getInstance(): QueryInterface
    {
        return $this->_instance;
    }

    public function update(array $data, array $where = [])
    {
        return $this->getInstance()->update($data, $where);
    }

    public function delete(array $where)
    {
        return $this->getInstance()->delete($where);
    }

    public function exec(string $query)
    {
        return $this->getInstance()->exec($query);
    }

    public function insert(array $data)
    {
        return $this->getInstance()->insert($data);
    }

    public function select(string $query, array $data = [])
    {
        return $this->getInstance()->select($query, $data);
    }

    public function truncate()
    {
        return $this->getInstance()->truncate();
    }
}
