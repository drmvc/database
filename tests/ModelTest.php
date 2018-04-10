<?php

namespace DrMVC\Database;

use DrMVC\Config;
use PHPUnit\Framework\TestCase;
use PHPUnit\DbUnit\TestCaseTrait;

class ModelTest extends TestCase
{
    use TestCaseTrait;

    private $config;

    public function getConnection()
    {
        $pdo = new \PDO('sqlite::memory:');
        return $this->createDefaultDBConnection($pdo, ':memory:');
    }

    public function getDataSet()
    {
        return $this->createFlatXMLDataSet(__DIR__ . '/dataset.xml');
    }

    public function __construct(string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->config = new Config();
        $this->config->load(__DIR__ . '/../extra/database.php');
    }

    public function test__construct()
    {

    }

    public function testGetConnection()
    {

    }

    public function test__call()
    {

    }

    public function testGetInstance()
    {

    }

    public function testSetCollection()
    {

    }

    public function testRawSQL()
    {

    }

    public function testGetCollection()
    {

    }
}
