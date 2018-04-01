<?php

namespace DrMVC\Database;

use DrMVC\Config\ConfigInterface;
use DrMVC\Database\Drivers\QueryInterface;
use DrMVC\Database\Drivers\SQLInterface;
use DrMVC\Database\Drivers\NoSQLInterface;
use Stringy\Stringy;

/**
 * Class Model
 * @package DrMVC\Database
 *
 * Virtual methods from SQLInterface:
 * @method SQLInterface rawSQL(string $query, array $bind = null, bool $fetch = true);
 * @method SQLInterface truncate();
 *
 * Virtual methods from MoSQLInterface:
 * @method NoSQLInterface command(array $query);
 */
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
     * @param   string $collection name of active collection
     */
    public function __construct(ConfigInterface $config, string $collection = null)
    {
        // Create database object with config from above
        $config_db = $this->getConfigDB($config);
        $database = new Database($config_db);

        // If collection name is not provided
        if (null === $collection) {
            // Get current collection name
            $collection = $this->getCollection();
        }

        // Extract instance created by driver and put collection name
        $instance = $database->getInstance($collection);

        // Keep driver's instance as local parameter
        $this->setInstance($instance);
    }

    /**
     * Extract configuration of current database
     *
     * @param   ConfigInterface $config
     * @return  ConfigInterface
     */
    private function getConfigDB(ConfigInterface $config): ConfigInterface
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
        return $config_db;
    }

    /**
     * Get current connection
     *
     * @return  string
     */
    public function getConnection(): string
    {
        return $this->connection;
    }

    /**
     * Convert class name for snake case
     *
     * @return  string
     */
    private function classToShake(): string
    {
        $className = \get_class($this);
        $classArray = explode('\\', $className);
        $class = end($classArray);
        try {
            $class = Stringy::create($class)->underscored();
        } catch (\InvalidArgumentException $e) {
            new Exception('Invalid argument provided');
        }
        return $class;
    }

    /**
     * Get current collection
     *
     * @return  string|null
     */
    public function getCollection()
    {
        return $this->collection ?? $this->classToShake();
    }

    /**
     * Set name of collection for queries
     *
     * @param   null|string $collection
     * @return  ModelInterface
     */
    public function setCollection(string $collection): ModelInterface
    {
        $this->collection = $collection;
        return $this;
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

    /**
     * Insert data into table/collection
     *
     * @param   array $data
     * @return  mixed
     */
    public function insert(array $data)
    {
        return $this->getInstance()->insert($data);
    }

    /**
     * Read data from table/collection
     *
     * @param   array $where
     * @return  mixed
     */
    public function select(array $where = [])
    {
        return $this->getInstance()->select($where);
    }

    /**
     * Update data in table/collection
     *
     * @param   array $data
     * @param   array $where
     * @return  mixed
     */
    public function update(array $data, array $where = [])
    {
        return $this->getInstance()->update($data, $where);
    }

    /**
     * Delete data from table/collection
     *
     * @param   array $where
     * @return  mixed
     */
    public function delete(array $where)
    {
        return $this->getInstance()->delete($where);
    }

    /**
     * Call some dynamic method
     *
     * @param   string $name
     * @param   array $arguments
     * @return  mixed
     */
    public function __call($name, $arguments)
    {
        return $this->getInstance()->$name($arguments);
    }
}
