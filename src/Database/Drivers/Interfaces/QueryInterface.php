<?php

namespace DrMVC\Database\Drivers\Interfaces;

interface QueryInterface
{
    /**
     * Insert in database and return of inserted element
     *
     * @param   array $data array of columns and values
     * @return  mixed
     */
    public function insert(array $data);

    /**
     * Universal select in database
     *
     * @param   array $where array with options for filtering
     * @param   array $nosql_options additional options of query (only for mongo)
     * @return  mixed
     */
    public function select(array $where = [], array $nosql_options = []);

    /**
     * Update some data in database
     *
     * @param   array $data array of columns and values
     * @param   array $where array with options for filtering
     * @return  mixed
     */
    public function update(array $data, array $where = []);

    /**
     * Delete rows from database
     *
     * @param   array $where array with options for filtering
     * @return  mixed
     */
    public function delete(array $where);

}
