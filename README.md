# DrMVC / Database

Advanced plugin for work with databases.

This specific module, it allows you to use multiple databases at the same time, you can switch database from model class.

You can call same model methods via different databases.

Also you can work with MongoDB and MySQL from one controller.

## Supported databases

* PostgreSQL
* MySQL
* SQLite
* MongoDB (PHP5 and PHP7 versions)
* and any other PDO databases

## How to install

`composer require drmvc/database`

## How to use

You can write your custom method, for example:

    public function someMethod() {
        return $this->db->select("SELECT * FROM table");
    }

Or work with system calls:

### Select

    $where = ['name' => 'somename', 'email' => 'someemail'];
    $model = new Model();
    $model->select($where); 

### Insert

    $data = ['name' => 'somename', 'email' => 'someemail'];
    $model = new Model();
    $model->insert($data);

### Update

    $data = ['name' => 'newname'];
    $where = ['name' => 'somename', 'email' => 'someemail'];
    $model = new Model();
    $model->update($data, $where);

### Delete

    $where = ['name' => 'somename', 'email' => 'someemail'];
    $model = new Model();
    $model->delete($where);
