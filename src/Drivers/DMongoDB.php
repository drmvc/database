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

        // Make sure the database is connected
        $this->_connection or $this->connect();
    }

    /**
     * Connect via MongoClient driver
     */
    public function connect()
    {
        if ($this->_connection) return;

        // Auth to database
        $userpass = isset($this->_config['username']) && isset($this->_config['password']) ? $this->_config['username'] . ':' . $this->_config['password'] . '@' : null;

        // Check if we want to authenticate against a specific database.
        $auth_database = isset($this->_config['options']) && !empty($this->_config['options']['database']) ? $this->_config['options']['database'] : null;

        // Set connection
        $this->_connection = new \MongoDB\Driver\Manager('mongodb://' . $userpass . $this->_config['hostname'] . ':' . $this->_config['port'] . ($auth_database ? '/' . $auth_database : ''));
    }

    /**
     * Check if incoming hash is valid mongo object id hash
     *
     * @param $value
     * @return bool
     */
    function isValid($value)
    {
        if ($value instanceof \MongoDB\BSON\ObjectID) {
            return true;
        }
        try {
            new \MongoDB\BSON\ObjectID($value);
            return true;
        } catch (\Exception $e) {
            return false;
        }
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
        // Set the last query
        $this->_last_query = $data;

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
            $this->_connection->executeBulkWrite($this->_config['database'] . '.' . $collection, $bulk, $writeConcern);
            if ($command == 'insert') $response = (string) new \MongoDB\BSON\ObjectID($data['_id']); else $response = true;
        } catch (\MongoDB\Driver\Exception\BulkWriteException $e) {
            error_log(
                "Uncaught Error: " . $e->getMessage() . " in " . $e->getFile() . ":" . $e->getLine() . "\n"
                . "Stack trace:\n" . $e->getTraceAsString() . "\n"
                . "\tthrown in " . $e->getFile() . " on line " . $e->getLine()
            );
            exit;
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
        // Set the last query
        $this->_last_query = $query;

        // Create command from query
        $command = new \MongoDB\Driver\Command($query);

        try {
            $cursor = $this->_connection->executeCommand($this->_config['database'], $command);
            $response = $cursor->toArray();
        } catch (\MongoDB\Driver\Exception\Exception $e) {
            error_log(
                "Uncaught Error: " . $e->getMessage() . " in " . $e->getFile() . ":" . $e->getLine() . "\n"
                . "Stack trace:\n" . $e->getTraceAsString() . "\n"
                . "\tthrown in " . $e->getFile() . " on line " . $e->getLine()
            );
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
        // Set the last query
        $this->_last_query = array($collection, $filter, $options);

        // Create command from query
        $query = new \MongoDB\Driver\Query($filter, $options);

        try {
            $cursor = $this->_connection->executeQuery($this->_config['database'] . '.' . $collection, $query);
            $response = $cursor->toArray();
        } catch (\MongoDB\Driver\Exception\Exception $e) {
            error_log(
                "Uncaught Error: " . $e->getMessage() . " in " . $e->getFile() . ":" . $e->getLine() . "\n"
                . "Stack trace:\n" . $e->getTraceAsString() . "\n"
                . "\tthrown in " . $e->getFile() . " on line " . $e->getLine()
            );
            exit;
        }

        return $response;
    }
}
