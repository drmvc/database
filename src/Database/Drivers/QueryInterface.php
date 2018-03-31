<?php

namespace DrMVC\Database\Drivers;

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
     * @return  mixed
     */
    public function select(array $where = []);

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
