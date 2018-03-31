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

/**
 * For socket connection you need to remove 'host' and 'port'
 * items from array.
 */

return [
    'pgsql_socket' => [
        'driver'    => 'pgsql',
        'username'  => 'admin',
        'password'  => 'admin_pass',
        'dbname'    => 'database',
    ],
];
