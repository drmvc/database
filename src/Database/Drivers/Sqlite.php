<?php

namespace DrMVC\Database\Drivers;

class Sqlite extends SQL
{
    /**
     * @link https://secure.php.net/manual/en/ref.pdo-mysql.connection.php
     *
     * The PDO_SQLITE Data Source Name (DSN) is composed of the following elements:
     */
    const AVAILABLE_ELEMENTS = [
        'path',
    ];

    /**
     * Generate DSN by parameters in config
     *
     * @param   array $config
     * @return  string
     */
    public function genDsn($config): string
    {
        // Parse config
        $dsn = '';
        foreach ($config as $key => $value) {
            if (\in_array($key, self::AVAILABLE_ELEMENTS, false)) {
                $dsn .= $value;
            }
        }
        return $dsn;
    }

}
