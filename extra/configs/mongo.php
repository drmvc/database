<?php
/**
 * Configuration of MongoDB driver, more details here:
 * @link https://secure.php.net/manual/en/set.mongodb.php
 * @link https://secure.php.net/manual/en/class.mongodb-driver-manager.php
 */

return [
    'default' => [
        'driver'    => 'mongodb',
        'host'      => '127.0.0.1',         // optional, default: 127.0.0.1
        'port'      => '27017',             // optional, default: 27017
        'prefix'    => 'prefix_',
    ],
];
