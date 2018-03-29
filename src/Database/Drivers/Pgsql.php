<?php

namespace DrMVC\Database\Drivers;

use DrMVC\Config\ConfigInterface;

class Pgsql extends SQL
{
    public function __construct(ConfigInterface $config)
    {
        parent::__construct($config);
        $this->setDsn('pgsql:' . $this->getConfig()->get('path'));
    }
}
