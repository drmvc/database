<?php

namespace DrMVC\Database\Drivers;

use DrMVC\Database\SQLException;

abstract class SQL extends Driver
{
    /**
     * Initiate connection to database
     *
     * @return  DriverInterface
     */
    public function connect(): DriverInterface
    {
        try {
            $connection = new \PDO(
                $this->getDsn(),
                $this->getParam('username'),
                $this->getParam('password')
            );
        } catch (SQLException $e) {
            // __construct
        }
        $this->setConnection($connection);

        return $this;
    }

    /**
     * Close database connection
     *
     * @return  DriverInterface
     */
    public function disconnect(): DriverInterface
    {
        $this->setConnection(null);
        return $this;
    }

    /**
     * Run a select statement against the database
     *
     * @param   string $query
     * @param   array $data
     * @return  array|object
     */
    public function select(string $query, array $data = [])
    {
        // Set statement
        $statement = $this->getConnection()->prepare($query);

        // Parse parameters from array
        foreach ($data as $key => $value) {
            $value_type = \is_int($value) ? \PDO::PARAM_INT : \PDO::PARAM_STR;
            $statement->bindValue($key, $value, $value_type);
        }

        // Execute the statement
        $statement->execute();

        // Return result
        return $statement->fetchAll(\PDO::FETCH_OBJ);
    }

    /**
     * Exec query without return, create table for example
     *
     * @param   string $query
     * @return  mixed
     */
    public function exec(string $query)
    {
        return $this->getConnection()->exec($query);
    }

    /**
     * Insert in database and return of inserted element
     *
     * @param   array $data array of columns and values
     * @param   string $id field name of ID which must be returned
     * @return  mixed
     */
    public function insert(array $data, string $id = null)
    {
        // Current table
        $table = $this->getCollection();

        // Generate array of fields for insert
        $fieldNames = implode(',', array_keys($data));

        // Generate line with data for update
        $fieldDetails = ':' . implode(', :', array_keys($data));

        // Prepare query
        $statement = $this->_connection->prepare("INSERT INTO $table ($fieldNames) VALUES ($fieldDetails)");

        // Bind values
        foreach ($data as $key => $value) {
            $statement->bindValue(":$key", $value);
        }

        // Execute operation
        $statement->execute();

        return (null !== $id)
            // Return ID of inserted element
            ? $this->getConnection()->lastInsertId($id)
            // Return count of affected rows
            : $statement->rowCount();
    }

    /**
     * Generate the line by provided keys
     *
     * @param   array $array array with data
     * @param   string $glue by this glue need merge items
     * @param   string $name name of field for PDO->bindValue
     * @return  string
     */
    private function genLine(array $array, string $glue, string $name): string
    {
        $line = '';
        $i = 0;
        foreach ($array as $key => $value) {
            $line .= (($i !== 0) ? null : $glue) . "$key = :${name}_$key";
            $i = 1;
        }
        return $line;
    }

    /**
     * Generate set of fields
     *
     * @param   array $array
     * @return  string
     */
    private function genFields(array $array): string
    {
        return $this->genLine($array, ', ', 'field');
    }

    /**
     * Generate WHERE line
     *
     * @param   array $array
     * @return  string
     */
    private function genWhere(array $array): string
    {
        return $this->genLine($array, ' AND ', 'where');
    }

    /**
     * Update method
     *
     * @param  array $data array of columns and values
     * @param  array $where array of columns and values
     * @return mixed
     */
    public function update(array $data, array $where)
    {
        // Current table
        $table = $this->getConnection();

        // Generate line with data for update
        $fieldDetails = !empty($data)
            ? $this->genFields($data)
            : '';

        // Generate where line
        $whereDetails = !empty($where)
            ? ' WHERE ' . $this->genWhere($where)
            : '';

        // Prepare query
        $statement = $this->getConnection()->prepare("UPDATE $table SET $fieldDetails $whereDetails");

        // Bind field values
        foreach ($data as $key => $value) {
            $statement->bindValue(":field_$key", $value);
        }

        // Bind where values
        foreach ($where as $key => $value) {
            $statement->bindValue(":where_$key", $value);
        }

        // Execute operation
        $statement->execute();

        // Return count of affected rows
        return $statement->rowCount();
    }

    /**
     * Delete rows from database
     *
     * @param   array $where
     * @return  mixed
     */
    public function delete(array $where)
    {
        // Current table
        $table = $this->getConnection();

        // Generate where line
        $whereDetails = !empty($where)
            ? ' WHERE ' . $this->genWhere($where)
            : '';

        // Prepare query
        $statement = $this->getConnection()->prepare("DELETE FROM $table $whereDetails");

        // Bind where values
        foreach ($where as $key => $value) {
            $statement->bindValue(":where_$key", $value);
        }

        // Execute operation
        $statement->execute();

        // Return count of affected rows
        return $statement->rowCount();
    }

    /**
     * Clean table function
     *
     * @return mixed
     */
    public function truncate()
    {
        // Current table
        $table = $this->getConnection();

        // Exec the truncate command
        return $this->exec("TRUNCATE TABLE $table");
    }
}
