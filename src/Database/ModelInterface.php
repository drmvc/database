<?php

namespace DrMVC\Database;

use DrMVC\Database\Drivers\QueryInterface;

interface ModelInterface extends QueryInterface
{
    /**
     * Get current database name
     *
     * @return string
     */
    public function getConnection(): string;

    /**
     * Get current collection
     *
     * @return  string|null
     */
    public function getCollection();

    /**
     * Get database instance
     *
     * @return QueryInterface
     */
    public function getInstance(): QueryInterface;

}
