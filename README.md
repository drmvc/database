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

It is a specific module, allows you to use multiple databases at the
same time, you can switch database from model class, you can call same
model methods with different databases, you can work with MongoDB and
Mysql from one controller, everything what you want.

## Supported databases

For now only databases from list below is supported, but support of
some new databases will be added soon.

| Driver  | Database |
|---------|----------|
| mysql   | MySQL and MariaDB |
| pgsql   | PostgreSQL |
| sqlite  | SQLite (file and memory modes) |
| mongodb | MongoDB (php 7.0 and above only) |

## Database configuration

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
* prefix - prefix of tables names, required by almost all methods (but not for `select()` or `rawSQL()`)

## How to use

### Basic example

A small example of working with a database with the simplest
implementation of the basic CRUD logic, they do not have JOIN, ORDER
and other complex methods, only basic functionality.

Source code of `example.php` file:

```php
<?php
require_once __DIR__ . '/../vendor/autoload.php';

// Load configuration of current database instance
$config = new \DrMVC\Config();
$config->load(__DIR__ . '/database.php', 'database');

// Initiate model, send database config and table name 'test' (without prefix)
$model = new \DrMVC\Database\Model($config, 'test');

// Get all records from table
$select = $model->select();

// Direct call query via model
$where = ['name' => 'somename', 'email' => 'some@email'];
$select = $model->select($where);

// Advanced example of select usage
$bind = ['name' => 'somename', 'email' => 'some@email'];
$raw = $model->rawSQL("SELECT * FROM prefix_users WHERE name = :name AND email = :email", $bind);

// Call insert method
$data = ['key' => 'value', 'key2' => 'value2'];
$insert = $model->insert($data);

// Update some data in table
$data = ['key' => 'value', 'key2' => 'value2'];
$where = ['id' => 111];
$update = $model->update($data, $where);

// Execute query in silent mode
$model->rawSQL('create table example_table');

// Remove some item from table
$where = ['id' => 111];
$delete = $model->delete($where);
```

### Simple connect to database

Sometimes it is required to implement a system that works directly
with the database object (for example some ORM or custom queries),
for this purpose in `Database` class the `getInstance` method was
implemented.

```php
<?php
use \DrMVC\Config;
use \DrMVC\Database;

// Load configuration of current database instance
$config = new Config();
// Example in "Database configs" chapter
$config->load(__DIR__ . '/database.php');

// Create database object
$db = new Database($config->get('default'));

// Get only instance (PDO or MongoManager, depending on the "driver"
// which you set in config)
$instance = $db->getInstance();
```

### OOP style

As mentioned earlier in this library, implemented basic CRUD support,
that is, you can declare the models dynamically and write simple
queries into the database for which the ORM would be redundant. For
example, you need to insert a row, delete or query all rows from the
table.

```php
<?php

namespace MyApp\Models;

use DrMVC\Database\Model;

//
// You need create object of this model with DrMVC\Config class as
// first parameter, because it is required by parent Database class:
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
* [AerodORM](https://github.com/drmvc/aerodorm)
