<?php

namespace DrMVC\Database\Tests;

use DrMVC\Config;
use DrMVC\Database;
use DrMVC\Database\Model;
use PHPUnit\Framework\TestCase;
use PHPUnit\DbUnit\TestCaseTrait;

class ModelTest extends TestCase
{
    use TestCaseTrait;

    private $config;

    public function __construct(string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->config = new Config();
        $this->config->load(__DIR__ . '/database.php');
    }

    final public function getConnection()
    {
        // Read config
        $config = $this->config->get('default');

        // Create database connections
        $obj = new Database($config);

        // We raw PDO object
        $instance = $obj->getInstance();

        // Read DDL with database schema (idk why but not possible to make it via dbUnit)
        $instance->exec(file_get_contents(__DIR__ . '/../extra/ddl/sqlite.ddl'));

        // Return connection interface
        return $this->createDefaultDBConnection($instance);
    }

    public function getDataSet()
    {
        return $this->createXMLDataSet(__DIR__ . '/dataset.xml');
    }

    public function test__construct()
    {
        try {
            $obj = new Model($this->config);
            $this->assertInternalType('object', $obj);
            $this->assertInstanceOf(Model::class, $obj);
        } catch (\Exception $e) {
            $this->assertContains('Must be initialized ', $e->getMessage());
        }
    }

    public function testGetInstance()
    {
        $obj = new Model($this->config);
        $inst = $obj->getInstance();
        $this->assertInternalType('object', $inst);
        $this->assertInstanceOf(Database\Drivers\Sqlite::class, $inst);
    }

    public function testGetConnection()
    {
        $obj = new Model($this->config);
        $conn = $obj->getConnection();
        $this->assertEquals('default', $conn);
    }

    public function testGetCollection()
    {
        $obj = new Model($this->config);
        $coll = $obj->getCollection();
        $this->assertEquals('model', $coll);
    }

    public function testSetCollection()
    {
        $obj = new Model($this->config);
        $coll = $obj->getCollection();
        $this->assertEquals('model', $coll);

        $obj->setCollection('users');
        $coll = $obj->getCollection();
        $this->assertEquals('users', $coll);
    }

    public function testRawSQL()
    {
        $obj = new Model($this->config);
        $result = $obj->rawSQL(file_get_contents(__DIR__ . '/../extra/ddl/sqlite.ddl'));
        print_r($result);
        $result = $obj->rawSQL('SELECT * FROM users', [], true);
        print_r($result);
    }

//    public function test__call()
//    {
//
//    }

}
