<?php

namespace DrMVC\Database\Drivers;

use DrMVC\Config\ConfigInterface;

class Mysql extends SQL
{
    const DEFAULT_HOST = 'localhost';
    const DEFAULT_PORT = 3306;

    public function __construct(ConfigInterface $config, string $collection)
    {
        parent::__construct($config, $collection);
        $db_host = $this->getHost();
        $db_port = $this->getPort();
        $db_auth = $this->getAuth();
        $db_driv = $this->getConfig()->get('driver');
        $db_name = $this->getConfig()->get('database');

        $dsn = "$db_driv://$db_auth$db_host:$db_port/$db_name";

        $this->setDsn($dsn);
    }

    private function getHost(): string
    {
        $host = $this->getConfig()->get('host');
        return $host ?? self::DEFAULT_HOST;
    }

    private function getPort(): int
    {
        $port = $this->getConfig()->get('port');
        return $port ?? self::DEFAULT_PORT;
    }

    private function getAuth(): string
    {
        $user = $this->getConfig()->get('username');
        $pass = $this->getConfig()->get('password');

        return ($user && $pass) ? $user . ':' . $pass . '@' : '';
    }

}
