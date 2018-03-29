<?php

namespace DrMVC\Database\Drivers;

abstract class SQL extends Driver
{
    /**
     * Initiate connection to database
     *
     * @return  DriverInterface
     */
    public function connect(): DriverInterface
    {
        $connection = new \PDO($this->getDsn());
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
     * @param  string $query
     * @param  array $data
     * @return array|object
     */
    public function select($query, array $data = [])
    {
        // Set statement
        $statement = $this->_connection->prepare($query);

        // Parse parameters from array
        foreach ($data as $key => $value) {
            if (\is_int($value)) {
                $statement->bindValue($key, $value, \PDO::PARAM_INT);
            } else {
                $statement->bindValue($key, $value);
            }
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
    public function exec($query)
    {
        return $this->_connection->exec($query);
    }

    /**
     * Insert in database and return of inserted element
     *
     * @param  array $data array of columns and values
     * @return int
     */
    public function insert(array $data): int
    {
        // Current table
        $table = $this->getConnection();

        $fieldNames = implode(',', array_keys($data));
        $fieldValues = ':' . implode(', :', array_keys($data));

        $statement = $this->_connection->prepare("INSERT INTO $table ($fieldNames) VALUES ($fieldValues)");

        foreach ($data as $key => $value) {
            $statement->bindValue(":$key", $value);
        }

        $statement->execute();
        return $this->_connection->lastInsertId();
    }


    /**
     * Update method
     *
     * @param  array $data array of columns and values
     * @param  array $where array of columns and values
     * @return int
     */
    public function update(array $data, array $where): int
    {
        // Current table
        $table = $this->getConnection();

        $fieldDetails = null;
        foreach ($data as $key => $value) {
            $fieldDetails .= "$key = :field_$key,";
        }
        $fieldDetails = rtrim($fieldDetails, ',');

        $whereDetails = null;
        $i = 0;
        foreach ($where as $key => $value) {
            if ($i === 0) {
                $whereDetails .= "$key = :where_$key";
            } else {
                $whereDetails .= " AND $key = :where_$key";
            }
            $i++;
        }
        $whereDetails = ltrim($whereDetails, ' AND ');

        if (!empty($where)) {
            $whereDetails = ' WHERE ' . $whereDetails;
        }

        $statement = $this->_connection->prepare("UPDATE $table SET $fieldDetails $whereDetails");

        foreach ($data as $key => $value) {
            $statement->bindValue(":field_$key", $value);
        }

        foreach ($where as $key => $value) {
            $statement->bindValue(":where_$key", $value);
        }

        $statement->execute();
        return $statement->rowCount();
    }

    /**
     * Delete rows from database
     *
     * @param   array $where
     * @return  bool
     */
    public function delete(array $where): bool
    {
        // Current table
        $table = $this->getConnection();

        $whereDetails = null;
        $i = 0;
        foreach ($where as $key => $value) {
            // Parse where array
            $whereDetails .= ($i === 0)
                ? "$key = :where_$key"
                : " AND $key = :where_$key";
            // Increment
            $i++;
        }
        $whereDetails = ltrim($whereDetails, ' AND ');
        $statement = $this->_connection->prepare("DELETE FROM $table WHERE $whereDetails");

        foreach ($where as $key => $value) {
            $statement->bindValue(":where_$key", $value);
        }

        return $statement->execute();
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

        return $this->exec("TRUNCATE TABLE $table");
    }
}
