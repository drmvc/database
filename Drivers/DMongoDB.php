<?php namespace DrMVC\Database\Drivers;

use DrMVC\Database\Database;

/**
 * Class for work with modern MongoDB php driver (for PHP >= 7.0)
 * @package DrMVC\Database\Drivers
 */
class DMongoDB extends Database
{
    /**
     * DMongoDB constructor
     *
     * @param string $name
     * @param array $config
     */
    public function __construct($name, array $config)
    {
        parent::__construct($name, $config);
    }

    /**
     * Connect via MongoClient driver
     */
    public function connect()
    {
        if ($this->_connection) return;

        // Configurations
        $config = $this->_config;

        // Connect to database
        $this->_connection = new \MongoDB\Driver\Manager('mongodb://' . $config['username'] . ':' . $config['password'] . '@' . $config['hostname'] . ':' . $config['port'] . '/' . $config['database']);
    }

    /**
     * Write into database
     *
     * @param $collection
     * @param $command
     * @param $data
     * @return mixed
     */
    public function write($collection, $command, $data)
    {
        // Make sure the database is connected
        $this->_connection or $this->connect();

        // Set the last query
        $this->_last_query = $data;

        // Configurations
        $config = $this->_config;

        // Exec bulk command
        $bulk = new \MongoDB\Driver\BulkWrite();

        switch ($command) {
            case 'insert':
                $data['_id'] = new \MongoDB\BSON\ObjectID;
                $bulk->insert($data);
                break;
            case 'update';
                $bulk->update($data[0], $data[1], $data[2]);
                break;
            case 'delete';
                $bulk->delete($data[0], $data[1]);
                break;
        }

        try {
            $writeConcern = new \MongoDB\Driver\WriteConcern(\MongoDB\Driver\WriteConcern::MAJORITY, 1000);
            $this->_connection->executeBulkWrite($config['database'] . '.' . $collection, $bulk, $writeConcern);
            if ($command == 'insert') $response = (string) new \MongoDB\BSON\ObjectID($data['_id']); else $response = true;
        } catch (\MongoDB\Driver\Exception\BulkWriteException $e) {
            //print_r($e);die();
            echo $e->getMessage(), "\n";
            //exit;
        }

        return $response;
    }

    /**
     * Execute MongoCommand
     *
     * @param $query - Should be like new MongoDB\Driver\Query($filter, $options);
     * @return mixed
     */
    public function command($query)
    {
        // Make sure the database is connected
        $this->_connection or $this->connect();

        // Set the last query
        $this->_last_query = $query;

        // Configurations
        $config = $this->_config;

        // Create command from query
        $command = new \MongoDB\Driver\Command($query);

        try {
            $cursor = $this->_connection->executeCommand($config['database'], $command);
            $response = $cursor->toArray();
        } catch (\MongoDB\Driver\Exception\Exception $e) {
            echo $e->getMessage(), "\n";
            exit;
        }

        return $response;
    }

    /**
     * Execute MongoQuery
     *
     * @param $collection
     * @param $filter
     * @param $options
     * @return mixed
     */
    public function query($collection, $filter, $options)
    {
        // Make sure the database is connected
        $this->_connection or $this->connect();

        // Set the last query
        $this->_last_query = array($collection, $filter, $options);

        // Configurations
        $config = $this->_config;

        // Create command from query
        $query = new \MongoDB\Driver\Query($filter, $options);

        try {
            $cursor = $this->_connection->executeQuery($config['database'] . '.' . $collection, $query);
            $response = $cursor->toArray();
        } catch (\MongoDB\Driver\Exception\Exception $e) {
            echo $e->getMessage(), "\n";
            exit;
        }

        return $response;
    }
}
