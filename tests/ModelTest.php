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
//
//    public function testGetConnection()
//    {
//
//    }
//
//    public function testSetCollection()
//    {
//
//    }
//
//    public function testGetInstance()
//    {
//
//    }
//
//    public function testGetCollection()
//    {
//
//    }
//
//    public function testRawSQL()
//    {
//
//    }
//
//    public function test__call()
//    {
//
//    }

}
