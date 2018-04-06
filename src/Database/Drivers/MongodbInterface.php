<?php

namespace DrMVC\Database\Drivers;

interface MongodbInterface extends NoSQLInterface
{
    /**
     * Execute MongoCommand
     *
     * @param   array $query should be like normal query command
     * @return  mixed
     */
    public function command(array $query);

}
