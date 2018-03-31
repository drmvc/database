<?php

namespace DrMVC\Database\Drivers;

use MongoDB\BSON\ObjectID;
use MongoDB\Driver\Exception\InvalidArgumentException;
use MongoDB\Driver\Exception\RuntimeException;
use MongoDB\Driver\Manager as MongoManager;
use DrMVC\Database\Exception;

/**
 * Class for work with modern MongoDB php driver (for PHP >= 7.0)
 * @package DrMVC\Database\Drivers
 */
class Mongodb extends NoSQL
{
    const DEFAULT_HOST = 'localhost';
    const DEFAULT_PORT = '27017';

    /**
     * @link http://nl1.php.net/manual/en/mongodb-driver-manager.construct.php
     *
     * Additional connection string options, which will overwrite any options with
     * the same name in the uri parameter.
     */
    const AVAILABLE_OPTIONS = [
        'appname',
        'authMechanism',
        'authMechanismProperties',
        'authSource',
        'canonicalizeHostname',
        'compressors',
        'connectTimeoutMS',
        'gssapiServiceName',
        'heartbeatFrequencyMS',
        'journal',
        'localThresholdMS',
        'maxStalenessSeconds',
        'password',
        'readConcernLevel',
        'readPreference',
        'readPreferenceTags',
        'replicaSet',
        'retryWrites',
        'safe',
        'serverSelectionTimeoutMS',
        'serverSelectionTryOnce',
        'slaveOk',
        'socketCheckIntervalMS',
        'socketTimeoutMS',
        'ssl',
        'username',
        'w',
        'wTimeoutMS',
        'zlibCompressionLevel'
    ];

    const AVAILABLE_DRIVER_OPTIONS = [
        'allow_invalid_hostname',
        'ca_dir',
        'ca_file',
        'crl_file',
        'pem_file',
        'pem_pwd',
        'context',
        'weak_cert_validation'
    ];

    /**
     * Initiate connection with database
     *
     * @return  DriverInterface
     */
    public function connect(): DriverInterface
    {
        // URL options
        $options = $this->getConfig()->get();

        // Driver options
        $optionsDriver = $options['driver_options']->get();

        try {
            $connection = new MongoManager(
                $this->getDsn(),
                $this->getOptions($options, self::AVAILABLE_OPTIONS),
                $this->getOptions($optionsDriver, self::AVAILABLE_DRIVER_OPTIONS)
            );
            $this->setConnection($connection);

        } catch (RuntimeException $e) {
            new Exception('Unable to connect');
        } catch (InvalidArgumentException $e) {
            new Exception('Invalid argument provided');
        }

        return $this;
    }

    /**
     * Generate DSN by parameters in config
     *
     * @param   array $config
     * @return  string
     */
    public function genDsn($config): string
    {
        // Get driver of connection
        $driver = strtolower($config['driver']);
        $url = $config['url'];

        return "$driver://$url";
    }

    /**
     * Generate options array
     *
     * @param   array $options
     * @param   array $allowed
     * @return  array
     */
    private function getOptions(array $options, array $allowed): array
    {
        $result = [];
        foreach ($options as $key => $value) {
            if (\in_array($key, $allowed, false)) {
                $result[$key] = $value;
            }
        }
        return $result;
    }

    public function delete(array $where)
    {
        // TODO: Implement delete() method.
    }

    public function update(array $data, array $where)
    {
        // TODO: Implement update() method.
    }

    public function exec(string $query)
    {
        // TODO: Implement exec() method.
    }

    public function insert(array $data)
    {
        // TODO: Implement insert() method.
    }

    public function select(string $query, array $data)
    {
        // TODO: Implement select() method.
    }

    public function truncate()
    {
        // TODO: Implement truncate() method.
    }

//    /**
//     * Check if incoming hash is valid mongo object id hash
//     *
//     * @param $value
//     * @return bool
//     */
//    function isValid($value)
//    {
//        if ($value instanceof ObjectID) {
//            return true;
//        }
//        try {
//            new ObjectID($value);
//            return true;
//        } catch (\Exception $e) {
//            return false;
//        }
//    }
//
//    /**
//     * Write into database
//     *
//     * @param $collection
//     * @param $command
//     * @param $data
//     * @return mixed
//     */
//    public function write($collection, $command, $data)
//    {
//        // Set the last query
//        $this->_last_query = $data;
//
//        // Exec bulk command
//        $bulk = new \MongoDB\Driver\BulkWrite();
//
//        switch ($command) {
//            case 'insert':
//                $data['_id'] = new \MongoDB\BSON\ObjectID;
//                $bulk->insert($data);
//                break;
//            case 'update';
//                $bulk->update($data[0], $data[1], $data[2]);
//                break;
//            case 'delete';
//                $bulk->delete($data[0], $data[1]);
//                break;
//        }
//
//        try {
//            $writeConcern = new \MongoDB\Driver\WriteConcern(\MongoDB\Driver\WriteConcern::MAJORITY, 1000);
//            $this->_connection->executeBulkWrite($this->_config['database'] . '.' . $collection, $bulk, $writeConcern);
//            if ($command == 'insert') $response = (string) new \MongoDB\BSON\ObjectID($data['_id']); else $response = true;
//        } catch (\MongoDB\Driver\Exception\BulkWriteException $e) {
//            error_log(
//                "Uncaught Error: " . $e->getMessage() . " in " . $e->getFile() . ":" . $e->getLine() . "\n"
//                . "Stack trace:\n" . $e->getTraceAsString() . "\n"
//                . "\tthrown in " . $e->getFile() . " on line " . $e->getLine()
//            );
//            exit;
//        }
//
//        return $response;
//    }
//
//    /**
//     * Execute MongoCommand
//     *
//     * @param $query - Should be like new MongoDB\Driver\Query($filter, $options);
//     * @return mixed
//     */
//    public function command($query)
//    {
//        // Set the last query
//        $this->_last_query = $query;
//
//        // Create command from query
//        $command = new \MongoDB\Driver\Command($query);
//
//        try {
//            $cursor = $this->_connection->executeCommand($this->_config['database'], $command);
//            $response = $cursor->toArray();
//        } catch (\MongoDB\Driver\Exception\Exception $e) {
//            error_log(
//                "Uncaught Error: " . $e->getMessage() . " in " . $e->getFile() . ":" . $e->getLine() . "\n"
//                . "Stack trace:\n" . $e->getTraceAsString() . "\n"
//                . "\tthrown in " . $e->getFile() . " on line " . $e->getLine()
//            );
//            exit;
//        }
//
//        return $response;
//    }
//
//    /**
//     * Execute MongoQuery
//     *
//     * @param $collection
//     * @param $filter
//     * @param $options
//     * @return mixed
//     */
//    public function query($collection, $filter, $options)
//    {
//        // Set the last query
//        $this->_last_query = array($collection, $filter, $options);
//
//        // Create command from query
//        $query = new \MongoDB\Driver\Query($filter, $options);
//
//        try {
//            $cursor = $this->_connection->executeQuery($this->_config['database'] . '.' . $collection, $query);
//            $response = $cursor->toArray();
//        } catch (\MongoDB\Driver\Exception\Exception $e) {
//            error_log(
//                "Uncaught Error: " . $e->getMessage() . " in " . $e->getFile() . ":" . $e->getLine() . "\n"
//                . "Stack trace:\n" . $e->getTraceAsString() . "\n"
//                . "\tthrown in " . $e->getFile() . " on line " . $e->getLine()
//            );
//            exit;
//        }
//
//        return $response;
//    }
}
