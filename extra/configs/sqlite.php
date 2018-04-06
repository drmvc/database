<?php
/**
 * More details about PDO-SQLite driver:
 * @link https://secure.php.net/manual/en/ref.pdo-sqlite.connection.php
 */

return [
    'sqlite' => [
        'driver'    => 'sqlite',
        'prefix'    => 'prefix_',       // optional, default: is empty

        /**
         * You also can work with "in memory" sqlite database,
         * for this you need to use this ['path' => ':memory:']
         */
        'path'      => __DIR__ . '/../sqlite.db',
    ],
];

