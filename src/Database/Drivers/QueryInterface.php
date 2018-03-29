<?php

namespace DrMVC\Database\Drivers;

interface QueryInterface
{
    /**
     * Universal select in database
     *
     * @param   string $query
     * @param   array $data
     * @return  array|object
     */
    public function select($query, array $data);

    /**
     * Exec query without return, create table for example
     *
     * @param   string $query
     * @return  mixed
     */
    public function exec($query);

    /**
     * Update some data in database
     *
     * @param  array $data array of columns and values
     * @param  array $where array of columns and values
     * @return mixed
     */
    public function update(array $data, array $where);

    /**
     * Insert in database and return of inserted element
     *
     * @param  array $data array of columns and values
     * @return mixed
     */
    public function insert(array $data);

    /**
     * Delete rows from database
     *
     * @param   array $where
     * @return  mixed
     */
    public function delete(array $where);

    /**
     * Clean table function
     *
     * @return mixed
     */
    public function truncate();

}
