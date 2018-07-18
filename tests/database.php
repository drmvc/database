<?php
return [
    'default' => [
        'driver'    => 'sqlite',
        'path'      => ':memory:',
    ],
    'wrong_driver_1' => [
        'driver'    => 123,
        'path'      => ':memory:',
    ],
    'wrong_driver_2' => [
        'driver'    => 'sklite',
        'path'      => ':memory:',
    ],
];
