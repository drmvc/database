<?php

namespace DrMVC\Database\Drivers;

use DrMVC\Database\SQLException;

/**
 * Class Mysql for connecto
 * @package DrMVC\Database\Drivers
 */
class Mysql extends SQL
{
    const DEFAULT_HOST = '127.0.0.1';
    const DEFAULT_PORT = '3306';
    const DEFAULT_CHARSET = 'utf8';
    const DEFAULT_COLLATION = 'utf8_unicode_ci';

    /**
     * @link https://secure.php.net/manual/en/ref.pdo-mysql.connection.php
     *
     * The PDO_MYSQL Data Source Name (DSN) is composed of the following elements:
     */
    const AVAILABLE_OPTIONS = [
        'host',
        'port',
        'dbname',
        'unix_socket'
    ];

    /**
     * Generate DSN by parameters in config
     *
     * @param   array $config
     * @return  string
     */
    public function genDsn($config): string
    {
        // Parse config
        $dsn = '';
        foreach ($config as $key => $value) {
            if (\in_array($key, self::AVAILABLE_OPTIONS, false)) {
                $dsn .= "$key=$value;";
            }
        }

        // Get driver of connection
        $driver = strtolower($config['driver']);

        return "$driver:$dsn";
    }

    /**
     * Initiate connection to database
     *
     * @return  DriverInterface
     */
    public function connect(): DriverInterface
    {
        try {
            $connection = new \PDO(
                $this->getDsn(),
                $this->getParam('username'),
                $this->getParam('password'),
                $this->getOptions()
            );
            $this->setConnection($connection);

        } catch (SQLException $e) {
            // __construct
        }

        return $this;
    }

    private function getOptions(): array
    {
        // Current charset
        $charset = $this->getParam('charset') ?? self::DEFAULT_CHARSET;

        // Current collation
        $collation = $this->getParam('collation') ?? self::DEFAULT_COLLATION;

        // Return array of options
        return [\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES '$charset' COLLATE '$collation'"];
    }

}
