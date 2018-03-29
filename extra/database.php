<?php

return [
    'default' => [
        'driver'    => getenv('DB_DRIVER'),
        'host'      => getenv('DB_HOST'),
        'port'      => getenv('DB_PORT'),
        'prefix'    => getenv('DB_PREFIX') . '_',
        'collation' => getenv('DB_COLLATION'),
        'charset'   => getenv('DB_CHARSET'),
        'database'  => getenv('DB_DATABASE'),
        'username'  => getenv('DB_USERNAME'),
        'password'  => getenv('DB_PASSWORD')
    ],
    'mongo' => []
];
