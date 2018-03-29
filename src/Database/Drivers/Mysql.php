<?php

namespace DrMVC\Database\Drivers;

use DrMVC\Config\ConfigInterface;

class Mysql extends SQL
{
    public function __construct(ConfigInterface $config)
    {
        parent::__construct($config);
        $this->setDsn('mysql:' . $this->getConfig()->get('path'));
    }
}
