<?php

namespace DrMVC\Database;

use DrMVC\Database\Drivers\QueryInterface;

interface DatabaseInterface
{
    /**
     * Default name of database
     */
    const DEFAULT_CONNECTION = 'default';

    /**
     * Allowed drivers
     */
    const ALLOWED_DRIVERS = ['mysql', 'pgsql', 'sqlite', 'mongodb'];

    /**
     * @param   string $collection
     * @return  QueryInterface
     */
    public function getInstance(string $collection): QueryInterface;
}
