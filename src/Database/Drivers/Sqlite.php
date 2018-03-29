<?php

namespace DrMVC\Database\Drivers;

use DrMVC\Config\ConfigInterface;

class Sqlite extends SQL
{
    public function __construct(ConfigInterface $config)
    {
        parent::__construct($config);
        $this->setDsn('sqlite:' . $this->getConfig()->get('path'));
    }
}
