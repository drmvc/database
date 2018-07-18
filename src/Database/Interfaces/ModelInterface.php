<?php

namespace DrMVC\Database\Interfaces;

use MongoDB\Driver\Manager;
use PDO;
use DrMVC\Database\Drivers\Interfaces\QueryInterface;

interface ModelInterface extends QueryInterface
{
    /**
     * Get current database name
     *
     * @return string
     */
    public function getConnection(): string;

    /**
     * Set name of collection for queries
     *
     * @param   null|string $collection
     * @return  ModelInterface
     */
    public function setCollection(string $collection): ModelInterface;

    /**
     * Get current collection
     *
     * @return  string|null
     */
    public function getCollection();

    /**
     * Get database instance
     *
     * @return  PDO|Manager|QueryInterface
     */
    public function getInstance();

}
