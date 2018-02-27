<?php

    /**
    * Database connection
    */
    class Database
    {

        private $dbDriver;
        private $host;
        private $username;
        private $password;
        private $dbName;
        private $connection;

        public function __construct($dbDriver, $host, $username, $password, $dbName)
        {
            $this->dbDriver = $dbDriver;
            $this->host = $host;
            $this->username = $username;
            $this->password = $password;
            $this->dbName = $dbName;
        }

        public function openConnection()
        {
            try 
            {
                $this->connection = new PDO($this->dbDriver.":host=".$this->host.";dbname=".$this->dbName,$this->username,$this->password);
                echo "Successfull connection to database" . "\n";

                /* disable emulated prepared statements and use real prepared statements */
                $this->connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
                $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) 
            {
                die($e->getMessage());
            }
        }

        public function closeConnection()
        {
            $this->connection = null;
        }

    }
?>