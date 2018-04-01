<?php

namespace DrMVC\Database\Drivers;

use MongoDB\BSON\ObjectID;
use MongoDB\Driver\Exception\Exception as MongoException;
use MongoDB\Driver\Exception\InvalidArgumentException;
use MongoDB\Driver\Exception\RuntimeException;
use MongoDB\Driver\Exception\BulkWriteException;
use MongoDB\Driver\Manager as MongoManager;
use MongoDB\Driver\BulkWrite as MongoBulk;
use MongoDB\Driver\WriteConcern as MongoWrite;
use MongoDB\Driver\Command as MongoCommand;
use MongoDB\Driver\Query as MongoQuery;
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
            $this->setInstance($connection);

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

    /**
     * @return MongoBulk
     */
    private function getBulk(): MongoBulk
    {
        try {
            $bulk = new MongoBulk();
        } catch (InvalidArgumentException $e) {
            new Exception('Unable to create Bulk object');
        }
        return $bulk;
    }

    /**
     * @return ObjectID
     */
    private function getID(): ObjectID
    {
        try {
            $objectID = new ObjectID();
        } catch (InvalidArgumentException $e) {
            new Exception('ObjectID could not to be generated');
        }
        return $objectID;
    }

    /**
     * @return MongoWrite
     */
    private function getWrite(): MongoWrite
    {
        try {
            $write = new MongoWrite(MongoWrite::MAJORITY, 1000);
        } catch (InvalidArgumentException $e) {
            new Exception('WriteConcern could not to be initiated');
        }
        return $write;
    }

    /**
     * Insert in database and return of inserted element
     *
     * @param   array $data array of columns and values
     * @return  mixed
     */
    public function insert(array $data): string
    {
        // Set bulk object
        $bulk = $this->getBulk();

        // Set object ID as id of item
        $data['_id'] = $this->getID();

        // Set statement
        $bulk->insert($data);

        try {
            $this->getInstance()->executeBulkWrite(
                $this->getParam('database') . '.' . $this->getCollection(),
                $bulk,
                $this->getWrite()
            );

        } catch (BulkWriteException $e) {
            new Exception('Unable to write in database');
        }

        return (string) $data['_id'];
    }

    /**
     * Create query object from filter and option arrays
     *
     * @param   array $where
     * @param   array $options
     * @return  MongoQuery
     */
    private function getQuery(array $where, array $options): MongoQuery
    {
        try {
            $query = new MongoQuery($where, $options);
        } catch (InvalidArgumentException $e) {
            new Exception('WriteConcern could not to be initiated');
        }
        return $query;
    }

    /**
     * Execute MongoQuery
     *
     * @param   array $filter
     * @param   array $options
     * @return  mixed
     */
    public function select(array $filter = [], array $options = [])
    {
        // Create query object from filter and option arrays
        $query = $this->getQuery($filter, $options);

        try {
            $cursor = $this->getInstance()->executeQuery(
                $this->getParam('database') . '.' . $this->getCollection(),
                $query
            );
            $response = $cursor->toArray();

        } catch (MongoException $e) {
            new Exception('Unable to execute query');
        }

        return $response;
    }

    /**
     * Update data in database
     *
     * @param   array $data
     * @param   array $filter
     * @param   array $updateOptions
     * @return  mixed
     */
    public function update(array $data, array $filter = [], array $updateOptions = [])
    {
        // Set bulk object
        $bulk = $this->getBulk();

        // Set statement
        $bulk->update($filter, $data, $updateOptions);

        try {
            $response = $this->getInstance()->executeBulkWrite(
                $this->getParam('database') . '.' . $this->getCollection(),
                $bulk,
                $this->getWrite()
            );

        } catch (BulkWriteException $e) {
            new Exception('Unable to write in database');
        }

        return $response;
    }

    /**
     * Delete data from table/collection
     *
     * @param   array $filter
     * @param   array $deleteOptions
     * @return  mixed
     */
    public function delete(array $filter, array $deleteOptions = [])
    {
        // Set bulk object
        $bulk = $this->getBulk();

        // Set statement
        $bulk->delete($filter, $deleteOptions);

        try {
            $response = $this->getInstance()->executeBulkWrite(
                $this->getParam('database') . '.' . $this->getCollection(),
                $bulk,
                $this->getWrite()
            );

        } catch (BulkWriteException $e) {
            new Exception('Unable to write in database');
        }

        return $response;
    }

    /**
     * Create command from query
     *
     * @param   array $query
     * @return  MongoCommand
     */
    private function getCommand($query): MongoCommand
    {
        try {
            $command = new MongoCommand($query);
        } catch (InvalidArgumentException $e) {
            new Exception('WriteConcern could not to be initiated');
        }
        return $command;
    }

    /**
     * Execute MongoCommand
     *
     * @param   array $query should be like new MongoDB\Driver\Query($filter, $options);
     * @return  mixed
     */
    public function command(array $query)
    {
        // Create command from query
        $command = $this->getCommand($query);

        try {
            $cursor = $this->getInstance()->executeCommand(
                $this->getParam('database'),
                $command
            );
            $response = $cursor->toArray();

        } catch (MongoException $e) {
            new Exception('Unable to execute command');
        }

        return $response;
    }

}
