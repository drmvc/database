<?php

namespace DrMVC\Database\Drivers;

/**
 * Wrapper of PDO for work with SQLite databases
 *
 * @package DrMVC\Database\Drivers
 * @since   3.0
 */
class Sqlite extends SQL
{
    /**
     * @link https://secure.php.net/manual/en/ref.pdo-mysql.connection.php
     *
     * The PDO_SQLITE Data Source Name (DSN) is composed of the following elements:
     */
    const AVAILABLE_OPTIONS = [
        'path',
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
                $dsn .= $value;
            }
        }

        // Get driver of connection
        $driver = strtolower($config['driver']);

        return "$driver:$dsn";
    }

}
