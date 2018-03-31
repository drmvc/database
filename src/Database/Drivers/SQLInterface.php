<?php

namespace DrMVC\Database\Drivers;

interface SQLInterface extends QueryInterface
{
    /**
     * Execute raw query, without generation, useful for some advanced operations
     *
     * @param   array $arguments
     * @return  mixed
     */
    public function rawSQL(array $arguments);

    /**
     * Insert in database and return of inserted element
     *
     * @param   array $data array of columns and values
     * @param   string $id name of id for `returnLastInsertedId`
     * @return  mixed
     */
    public function insert(array $data, string $id = null);

    /**
     * Clean table function
     *
     * @return mixed
     */
    public function truncate();

}
