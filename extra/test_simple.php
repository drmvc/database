<?php
require_once __DIR__ . '/../vendor/autoload.php';

use \DrMVC\Config;
use \DrMVC\Database;

// Load configuration of current database instance
$config = new Config();
$config->load(__DIR__ . '/database.php');

// Connection details about required connection
$configDB = $config->get('default');

// Create database object
$db = new Database($configDB);

// Get only instance (PDO or MongoManager, depending on the "driver"
// which you set in config)
$instance = $db->getInstance();
print_r($instance);
