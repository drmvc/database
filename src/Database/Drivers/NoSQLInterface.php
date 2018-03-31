<?php

namespace DrMVC\Database\Drivers;

interface NoSQLInterface extends QueryInterface
{
    /**
     * Execute MongoCommand
     *
     * @param   array $query should be like normal query command
     * @return  mixed
     */
    public function command(array $query);

}
