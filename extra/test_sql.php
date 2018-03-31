<?php
require_once __DIR__ . '/../vendor/autoload.php';

$config = new \DrMVC\Config();
$config->load(__DIR__ . '/database.php', 'database');
print_r($config);

$model = new \DrMVC\Models\Test($config->get('database'));
print_r($model);

// Direct call from model
$test = $model->select('select * from prefix_test;');
print_r($test);

// Call model's method of select
$test = $model->test_select();
print_r($test);

// Call model's method of insert
//$test = $model->test_insert();
print_r($test);
