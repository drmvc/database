<?php
require_once __DIR__ . '/../vendor/autoload.php';

// Load configuration of current database instance
$config = new \DrMVC\Config();
$config->load(__DIR__ . '/database.php', 'database');

// Initiate model with collection with what we want work, 'test' for example
$model = new \DrMVC\Database\Model($config->get('database'), 'test');

// Direct call query via model
$test = $model->select('SELECT * FROM prefix_test');

// Call insert method
$data = ['key' => 'value', 'key2' => 'value2'];
$test = $model->insert($data);

// Update some data in table
$data = ['key' => 'value', 'key2' => 'value2'];
$where = ['id' => 111];
$test = $model->update($data, $where);

// Execute query in silent mode
$model->exec('create table example_table');
