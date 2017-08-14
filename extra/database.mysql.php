<?php defined('SYSPATH') OR die('No direct script access.');

return array(
	'default' => array(
		// Database type
		'type'     => 'pdo',
		// Database default PDO driver [pgsql/mysql/oci8/odbc]
		'driver'   => 'mysql',
		// Network configuration
		'hostname' => 'localhost',
		'port'     => '3306',
		// Set the database name and user credentials
		'database' => 'db_name',
		'username' => 'db_user',
		'password' => 'db_pass',
		// Client encoding
		'encoding' => 'utf8',
	),
);
