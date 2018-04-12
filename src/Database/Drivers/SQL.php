<?php

namespace DrMVC\Database\Drivers;

use \DrMVC\Database\SQLException;
use \PDO;

abstract class SQL extends Driver implements SQLInterface
{
    /**
     * Get current connection
     *
     * @return  PDO
     */
    public function getInstance(): PDO
    {
        return $this->_instance;
    }

    /**
     * Check if input value is integer
     *
     * @param   mixed $value
     * @return  int
     */
    private function isPdoInt($value): int
    {
        return \is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR;
    }

    /**
     * Initiate connection to database
     *
     * @return  DriverInterface
     */
    public function connect(): DriverInterface
    {
        try {
            $connection = new PDO(
                $this->getDsn(),
                $this->getParam('username'),
                $this->getParam('password')
            );

            // We allow to print the errors whenever there is one
            $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $this->setInstance($connection);

        } catch (SQLException $e) {
            // __construct
        }

        return $this;
    }

    /**
     * Generate basic select query
     *
     * @param   array $where
     * @return  string
     */
    private function getSelect(array $where): string
    {
        // Current table
        $table = $this->getCollection();

        // Generate where line
        $whereDetails = !empty($where)
            ? ' WHERE ' . $this->genWhere($where)
            : '';

        return "SELECT * FROM $table $whereDetails";
    }

    /**
     * Run a select statement against the database
     *
     * @param   array $where array with data for filtering
     * @return  mixed
     */
    public function select(array $where = [])
    {
        // Set statement
        $query = $this->getSelect($where);
        $statement = $this->getInstance()->prepare($query);

        // Bind where values
        foreach ($where as $key => $value) {
            $statement->bindValue(":where_$key", $value, $this->isPdoInt($value));
        }

        // Execute operation
        $statement->execute();

        // Return object
        return $statement->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Execute raw query, without generation, useful for some advanced operations
     *
     * @param   array $arguments array of arguments
     * @return  mixed
     */
    public function rawSQL(array $arguments)
    {
        if (!isset($arguments[1])) {
            $arguments[1] = [];
        }
        if (!isset($arguments[2])) {
            $arguments[2] = false;
        }
        /*
         * @param string $query  pure sql query
         * @param  array $bind   array with values in [':key' => 'value'] format
         * @param   bool $fetch  make fetch and return data?
         */
        list($query, $bind, $fetch) = $arguments;

        // Set statement
        $statement = $this->getInstance()->prepare($query);

        // Execute operation
        $statement->execute($bind);

        return (true === $fetch)
            // Return object
            ? $statement->fetchAll(PDO::FETCH_OBJ)
            // Return count of affected rows
            : $statement->rowCount();
    }

    /**
     * Generate INSERT query by array
     *
     * @param   array $data
     * @return  string
     */
    private function genInsert(array $data): string
    {
        // Current table
        $table = $this->getCollection();

        // Generate array of fields for insert
        $fieldNames = implode(',', array_keys($data));

        // Generate line with data for update
        $fieldDetails = ':' . implode(', :', array_keys($data));

        return "INSERT INTO $table ($fieldNames) VALUES ($fieldDetails)";
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
        // Prepare query
        $query = $this->genInsert($data);
        $statement = $this->getInstance()->prepare($query);

        // Bind values
        foreach ($data as $key => $value) {
            $statement->bindValue(":$key", $value, $this->isPdoInt($value));
        }

        // Execute operation
        $statement->execute();

        return (null !== $id)
            // Return ID of inserted element
            ? $this->getInstance()->lastInsertId($id)
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
     * Generate update query
     *
     * @param   array $data
     * @param   array $where
     * @return  string
     */
    private function genUpdate(array $data, array $where): string
    {
        // Current table
        $table = $this->getCollection();

        // Generate line with data for update
        $fieldDetails = !empty($data)
            ? $this->genFields($data)
            : '';

        // Generate where line
        $whereDetails = !empty($where)
            ? ' WHERE ' . $this->genWhere($where)
            : '';

        return "UPDATE $table SET $fieldDetails $whereDetails";
    }

    /**
     * Update method
     *
     * @param   array $data array of columns and values
     * @param   array $where array with data for filtering
     * @return  mixed
     */
    public function update(array $data, array $where = [])
    {
        // Prepare query
        $query = $this->genUpdate($data, $where);
        $statement = $this->getInstance()->prepare($query);

        // Bind field values
        foreach ($data as $key => $value) {
            $statement->bindValue(":field_$key", $value, $this->isPdoInt($value));
        }

        // Bind where values
        foreach ($where as $key => $value) {
            $statement->bindValue(":where_$key", $value, $this->isPdoInt($value));
        }

        // Execute operation
        $statement->execute();

        // Return count of affected rows
        return $statement->rowCount();
    }

    /**
     * Delete rows from database
     *
     * @param   array $where array with data for filtering
     * @return  mixed
     */
    public function delete(array $where)
    {
        // Current table
        $table = $this->getCollection();

        // Generate where line
        $whereDetails = !empty($where)
            ? ' WHERE ' . $this->genWhere($where)
            : '';

        // Prepare query
        $statement = $this->getInstance()->prepare("DELETE FROM $table $whereDetails");

        // Bind where values
        foreach ($where as $key => $value) {
            $statement->bindValue(":where_$key", $value, $this->isPdoInt($value));
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
        $table = $this->getInstance();

        // Exec the truncate command
        return $this->rawSQL(["TRUNCATE TABLE $table"]);
    }
}
