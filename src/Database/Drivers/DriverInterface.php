<?php

namespace DrMVC\Database\Drivers;

interface DriverInterface
{
    /**
     * @return mixed
     */
    public function getConnection();

    /**
     * @param $connection
     * @return DriverInterface
     */
    public function setConnection($connection): DriverInterface;
}
