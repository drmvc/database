<?php

namespace DrMVC\Database;

use DrMVC\Config;
use DrMVC\Config\ConfigInterface;
use DrMVC\Database;
use DrMVC\Database\Drivers\QueryInterface;

class Model implements ModelInterface
{
    /**
     * @var QueryInterface
     */
    private $_instance;

    /**
     * Default database
     * @var string
     */
    protected $database = Database::DEFAULT_DATABASE;

    /**
     * Name of collection for query
     * @var string
     */
    protected $collection;

    /**
     * Model constructor.
     * @param ConfigInterface $config
     */
    public function __construct(ConfigInterface $config = null)
    {
        if (null === $config) {
            $config = new Config();
            $config
                ->set(
                    Database::DEFAULT_DATABASE,
                    [
                        'driver' => 'sqlite',
                        'path' => '/tmp/sqlite.db'
                    ]
                );
        }

        try {
            $collection = $this->getCollection();
            if (null === $collection) {
                throw new Exception('Collection is not set');
            }
        } catch (Exception $e) {
            // __constructor
        }

        // Extract config of current database
        $configDB = $config->get($this->getDatabase());

        // Create database object with correct config
        $database = new Database($configDB);

        // Save instance as model by instance from database
        $this->setInstance($database->getInstance());
    }

    /**
     * @return string
     */
    public function getDatabase(): string
    {
        return $this->database;
    }

    /**
     * @return string
     */
    public function getCollection(): string
    {
        return $this->collection;
    }

    /**
     * @param QueryInterface $instance
     */
    public function setInstance(QueryInterface $instance)
    {
        $this->_instance = $instance;
    }

    /**
     * @return QueryInterface
     */
    public function getInstance(): QueryInterface
    {
        return $this->_instance;
    }

}
