<?php

namespace DrMVC\Database;

use DrMVC\Database\Drivers\QueryInterface;

interface ModelInterface
{
    /**
     * Get current connection name
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
     * Set database instance
     *
     * @param   QueryInterface $instance
     */
    public function setInstance(QueryInterface $instance);

    /**
     * Get database instance
     *
     * @return QueryInterface
     */
    public function getInstance(): QueryInterface;

}
