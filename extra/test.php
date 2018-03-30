<?php
require_once __DIR__ . '/../vendor/autoload.php';

$config = new \DrMVC\Config();
$config->load(__DIR__ . '/database.php', 'database');
//print_r($config);

$model = new \DrMVC\Models\Test($config->get('database'));
print_r($model);
