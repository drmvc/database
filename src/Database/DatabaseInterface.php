<?php

namespace DrMVC\Database;

use DrMVC\Database\Drivers\QueryInterface;

interface DatabaseInterface
{
    /**
     * Default name of database
     */
    const DEFAULT_DATABASE = 'default';

    /**
     * Allowed drivers
     */
    const ALLOWED_DRIVERS = ['mysql', 'pgsql', 'sqlite', 'mongo'];

    /**
     * @return QueryInterface
     */
    public function getInstance(): QueryInterface;
}
