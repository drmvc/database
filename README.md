# DrMVC / Database

Advanced plugin for work with databases.

    composer require drmvc/database

It is a specific module, allows you to use multiple databases at the same time, you can switch database from model class.
You can call same model methods via different databases, also you can work with MongoDB and MySQL from one controller.

## Supported databases

* PostgreSQL, MySQL, SQLite and any other PDO databases
* MongoDB (PHP5 and PHP7 versions)

## How to use

You can write your custom method inside model, for example:

```php
public function someMethod() {
    return $this->db->select("SELECT * FROM table");
}
```

Or work with system calls:

```php
// Select
$where = ['name' => 'somename', 'email' => 'someemail'];
$model = new Model();
$model->db->select($where); 

// Insert
$data = ['name' => 'somename', 'email' => 'someemail'];
$model = new Model();
$model->db->insert($data);

// Update
$data = ['name' => 'newname'];
$where = ['name' => 'somename', 'email' => 'someemail'];
$model = new Model();
$model->db->update($data, $where);

// Delete
$where = ['name' => 'somename', 'email' => 'someemail'];
$model = new Model();
$model->db->delete($where);
```
