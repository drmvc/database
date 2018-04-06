[![Latest Stable Version](https://poser.pugx.org/drmvc/database/v/stable)](https://packagist.org/packages/drmvc/database)
[![Build Status](https://travis-ci.org/drmvc/database.svg?branch=master)](https://travis-ci.org/drmvc/database)
[![Total Downloads](https://poser.pugx.org/drmvc/database/downloads)](https://packagist.org/packages/drmvc/database)
[![License](https://poser.pugx.org/drmvc/database/license)](https://packagist.org/packages/drmvc/database)
[![PHP 7 ready](https://php7ready.timesplinter.ch/drmvc/database/master/badge.svg)](https://travis-ci.org/drmvc/database)
[![Code Climate](https://codeclimate.com/github/drmvc/database/badges/gpa.svg)](https://codeclimate.com/github/drmvc/database)
[![Scrutinizer CQ](https://scrutinizer-ci.com/g/drmvc/database/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/drmvc/database/)

# DrMVC\Database

Advanced module for work with databases and models.

    composer require drmvc/database

It is a specific module, allows you to use multiple databases at the same time, you can switch database from model class.
You can call same model methods via different databases, also you can work with MongoDB and Mysql from one controller.

This module uses an ORM similar concept of work, but if you need a more convenient ORM you can try
[AerodORM](https://github.com/drmvc/aerodorm), which is also part of DrMVC.

## Supported databases

For now only databases from list below is supported, but support of
some new databases will be added soon.

| Driver  | Database |
|---------|----------|
| mysql   | MySQL and MariaDB |
| pgsql   | PostgreSQL |
| sqlite  | SQLite (file and memory modes) |
| mongodb | MongoDB (php 7.0 and above only) |

## How to use

### Database configs

You can find few examples of database config files with description
and links [here](extra/configs/).

Example of MySQL `database.php` config file:

```php
<?php
return [
    'default' => [
        'driver'    => 'mysql',
        'host'      => '127.0.0.1',
        'port'      => '3306',
        'username'  => 'admin',
        'password'  => 'admin_pass',
        'dbname'    => 'database',
        'prefix'    => 'prefix_',
        'collation' => 'utf8_unicode_ci',
        'charset'   => 'utf8',
    ],
];
```

Where:

* default - name of database connection which must be used by default
* driver - any driver from [this](#supported-databases) list
* prefix - prefix of tables names, required by almost all methods (but not in `select()` or `exec()`)

### Basic example

Source code of `example.php` file:

```php
<?php
require_once __DIR__ . '/../vendor/autoload.php';

// Load configuration of current database instance
$config = new \DrMVC\Config();
$config->load(__DIR__ . '/database.php', 'database');

// Initiate model with collection with what we want work, 'test' for example
$model = new \DrMVC\Database\Model($config->get('database'), 'test');

// Direct call query via model
$where = ['name' => 'somename', 'email' => 'someemail'];
$test = $model->select($where);

// Advanced example of select usage
$bind = ['name' => 'somename', 'email' => 'someemail'];
$test = $model->rawSQL("SELECT * FROM prefix_users WHERE name = :name AND email = :email", $bind);

// Call insert method
$data = ['key' => 'value', 'key2' => 'value2'];
$test = $model->insert($data);

// Update some data in table
$data = ['key' => 'value', 'key2' => 'value2'];
$where = ['id' => 111];
$test = $model->update($data, $where);

// Execute query in silent mode
$model->exec('create table example_table');
```

### Simple connect to database



```php
use \DrMVC\Config;
use \DrMVC\Database;

// Load configuration of current database instance
$config = new Config();
$config->load(__DIR__ . '/database.php', 'database');

// Create database object
$db = new Database($config);

// Get only instance (PDO or MongoManager, depending on the "driver"
// which you set in config)
$instance = $db->getInstance();
```

### OOP style

```php
<?php

namespace MyApp\Models;

use DrMVC\Database\Model;

//
// You need create object of this model with DrMVC\Config class
// as first parameter, because it need for parent:
//
// parent::__construct(ConfigInterface $config = null)
//

class Test extends Model
{
    protected $collection = 'test';

    public function select_all()
    {
        return $this->select();
    }

    public function sql_insert(array $data = ['key' => 'value', 'key2' => 'value2'])
    {
        return $this->insert($data);
    }

    public function sql_update(int $id)
    {
        $data = ['key' => 'value', 'key2' => 'value2'];
        $where = ['id' => $id];
        return $this->update($data, $where);
    }

    public function sql_delete(array $where)
    {
        return $this->delete($where);
    }
}
```

# Links

* [DrMVC project website](https://drmvc.com/)
* [PDO MySQL](http://php.net/manual/en/ref.pdo-mysql.connection.php)
* [PDO PostgreSQL](http://php.net/manual/en/ref.pdo-pgsql.connection.php)
* [PDO SQLite](http://php.net/manual/en/ref.pdo-sqlite.connection.php)
* [MongoDB](http://php.net/manual/en/set.mongodb.php)
