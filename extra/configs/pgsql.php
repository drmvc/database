<?php
/**
 * More details about PDO-PostgreSQL driver:
 * @link http://php.net/manual/en/ref.pdo-pgsql.connection.php
 *
 * List of all available options of postgresql 10 driver:
 * @link https://www.postgresql.org/docs/10/static/libpq-connect.html#LIBPQ-PARAMKEYWORDS
 *
 * List of all available charsets:
 * @link https://www.postgresql.org/docs/current/static/multibyte.html
 */

return [
    'pgsql' => [
        'driver'    => 'pgsql',
        'host'      => '127.0.0.1',         // optional, default: 127.0.0.1
        'port'      => '5432',              // optional, default: 5432
        'username'  => 'admin',
        'password'  => 'admin_pass',
        'dbname'    => 'database',
        'prefix'    => 'prefix_',           // optional, default: is empty
        'client_encoding' => 'UTF8'         // optional, default: your local configuration
    ],
];
