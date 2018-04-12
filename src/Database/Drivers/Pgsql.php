<?php

namespace DrMVC\Database\Drivers;

/**
 * Wrapper of PDO for work with PgSQL databases
 *
 * @package DrMVC\Database\Drivers
 * @since   3.0
 */
class Pgsql extends SQL
{
    const DEFAULT_HOST = '127.0.0.1';
    const DEFAULT_PORT = '5432';

    /**
     * @link https://secure.php.net/manual/en/ref.pdo-pgsql.connection.php
     * @link https://www.postgresql.org/docs/8.3/static/libpq-connect.html
     *
     * The PDO_PGSQL Data Source Name (DSN) is composed of the following elements:
     */
    const AVAILABLE_OPTIONS = [
        'host',
        'hostaddr',
        'port',
        'dbname',

        /**
         * These two parameters below is not needed, because we can put user/pass
         * as parameters of PDO class, you can read more by link
         * @link https://secure.php.net/manual/en/pdo.construct.php
         */
        //'user',
        //'password',

        'passfile',
        'connect_timeout',
        'client_encoding',
        'options',
        'application_name',
        'fallback_application_name',
        'keepalives',
        'keepalives_idle',
        'keepalives_interval',
        'keepalives_count',
        'tty',
        'sslmode',
        'requiressl',
        'sslcompression',
        'sslcert',
        'sslkey',
        'sslrootcert',
        'sslcrl',
        'requirepeer',
        'krbsrvname',
        'gsslib',
        'service',
        'target_session_attrs'
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
}
