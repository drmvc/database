<?php

namespace DrMVC\Models;

use DrMVC\Database\Model;

class Test extends Model
{
    protected $collection = 'test';

    public function test_select()
    {
        return $this->select('select * from prefix_test');
    }

    public function test_insert()
    {
        $data = ['test' => 'zzz'];
        return $this->insert($data);
    }
}
