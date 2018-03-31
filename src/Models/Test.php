<?php

namespace DrMVC\Models;

use DrMVC\Config;
use DrMVC\Config\ConfigInterface;
use DrMVC\Database\Model;

class Test extends Model
{
    //protected $collection = 'test';

    public function sql_select()
    {
        return $this->select('SELECT * FROM prefix_test');
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

    public function sql_delete(array $data)
    {
        return $this->delete($data);
    }
}
