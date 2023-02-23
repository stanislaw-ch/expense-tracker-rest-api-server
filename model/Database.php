<?php

class Database
{
    protected ?PDO $connection = null;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        if (!isset($this->connection)) {
            try {
                $this->connection = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
                $this->connection->setAttribute( PDO::ATTR_EMULATE_PREPARES, false );
            } catch (PDOException $e) {
                echo 'Connection failed: ' . $e->getMessage();
            }
        }
    }

    /**
     * @throws Exception
     */
    public function select($query = "" , $params = [], $mode = PDO::FETCH_NUM): array
    {
        try {
            $stmt = $this->executeStatement($query , $params);
            return $stmt->fetchAll($mode);
        } catch(PDOException $e) {
            throw New PDOException($e->getMessage());
        }
    }

    /**
     * @throws Exception
     */
    private function executeStatement($query = "" , $params = []): PDOStatement
    {
        try {
            $stmt = $this->connection->prepare($query);
            if($stmt === false) {
                throw New PDOException("Unable to do prepared statement: " . $query);
            }
//            if($params) {
//                $paramTypes = array_shift($params);
//                $stmt->bindParam($paramTypes, ...$params);
//            }
            $stmt->execute($params);
        } catch(PDOException $e) {
            echo $e->getMessage();
        }

        return $stmt;
    }
}