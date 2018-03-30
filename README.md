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

* PostgreSQL, Mysql, SQLite and any other PDO databases
* MongoDB (PHP7 version only)

## How to use

You can write your custom method inside model, for example:

```php
public function someMethod() {
    return $this->db->select("SELECT * FROM table");
}
```

Or work with system calls:

```php
// Model object
$model = new Model();

// Dummy data
$data = ['name' => 'somename', 'email' => 'someemail'];
$where = ['name' => 'somename', 'email' => 'someemail'];

// Run some query and return the result
$model->elect("SELECT * FROM users WHERE name = :name AND email = :email", $where);

// Run query without responce
$model->select("SELECT * FROM users");

// Insert new data into `users` table
$model->insert('users', $data);

// Update some data inside table
$model->update('users', $data, $where);

// Delete data from table
$model->delete('users', $where);
```
