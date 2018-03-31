<?php
/**
 * About MongoDB driver in details
 * @link http://nl1.php.net/manual/en/mongodb-driver-manager.construct.php
 *
 * More details about authentication:
 * @link http://nl.php.net/manual/en/mongo.connecting.auth.php
 *
 * About connection string ('url' parameter)
 * @link https://docs.mongodb.com/manual/reference/connection-string/#connections-connection-options
 */

return [
    'default' => [
        'driver'    => 'mongodb',
        'url'       => '127.0.0.1:27017/database',
        'username'  => 'admin',
        'password'  => 'admin_pass',
        'prefix'    => 'prefix_',
        'driver_options' => [
            'pem_file' => __DIR__ . '/ssl/client.pem',
        ]
    ],
];
