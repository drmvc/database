<?php

namespace DrMVC\Database;

use DrMVC\Config\Config;
use DrMVC\Database\Drivers\Sqlite;
use PHPUnit\Framework\TestCase;

class DatabaseTest extends TestCase
{
    private $config;

    public function __construct(string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->config = new Config();
        $this->config->load(__DIR__ . '/../extra/configs/sqlite.php');
        $this->config->load(__DIR__ . '/../extra/configs/pgsql.php');
    }

    public function test__construct()
    {
        try {
            $config = $this->config->get('sqlite');
            $obj = new Database($config);
            $this->assertInternalType('object', $obj);
            $this->assertInstanceOf(Database::class, $obj);
        } catch (\Exception $e) {
            $this->assertContains('Must be initialized ', $e->getMessage());
        }
    }

    public function testGetInstance()
    {
        $config = $this->config->get('sqlite');
        $obj = new Database($config);
        $instance = $obj->getInstance();

        $this->assertInternalType('object', $instance);
        $this->assertInstanceOf(\PDO::class, $instance);
    }

    public function testGetConfig()
    {
        $config = $this->config->get('sqlite');
        $obj = new Database($config);
        $cfg = $obj->getConfig();

        $this->assertInternalType('object', $cfg);
        $this->assertInstanceOf(Config::class, $cfg);
    }

    public function testSetConfig()
    {
        $config = $this->config->get('sqlite');
        $obj = new Database($config);

        $cfg = new Config();
        $cfg->load(__DIR__ . '/../extra/configs/mysql.php');
        $obj->setConfig($cfg);

        $cfg2 = $obj->getConfig('mysql');
        $this->assertInternalType('object', $cfg2);
        $this->assertInstanceOf(Config::class, $cfg2);

        $driver = $cfg2->get('driver');
        $this->assertInternalType('string', $driver);
    }

    public function testGetDriver()
    {
        $config = $this->config->get('sqlite');
        $obj = new Database($config);
        $driver = $obj->getDriver();

        $this->assertInternalType('string', $driver);
        $this->assertEquals(Sqlite::class, $driver);
    }
}
