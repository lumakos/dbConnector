<?php

    include_once 'databaseManagerInterface.php';

    /**
     * Define an Abstract class for all databases
     * connections to extend
     */
    abstract class DatabaseManager implements DatabaseManagerInterface
    {
        protected $dbDriver;
        protected $host;
        protected $port;
        protected $username;
        protected $password;
        protected $dbName;
        protected $unixSocket;
        protected $charset;
        protected $connection;

        /**
         * Connect database
         */
        abstract protected function openConnection();

        /**
         * Returns rows from the database based on the conditions
         * @param string name of the table
         * @param array select, where, order_by, limit and return_type conditions
         */
        abstract protected function selectData($table, $conditions = array());

        /**
         * Insert data into the database
         * @param string name of the table
         * @param array the data for inserting into the table
         */
        abstract protected function insertData($table,$data);
        
        /**
         * Update data into the database
         * @param string name of the table
         * @param array the data for updating into the table
         * @param array where condition on updating data
         */
        abstract protected function updateData($table, $data, $conditions);

        /**
         * Delete data from the database
         * @param string name of the table
         * @param array where condition on deleting data
         */
        abstract protected function deleteData($table, $conditions);

        /**
         * Cache the results of an SQL query to the file system
         */
        abstract protected function queryCaching($cacheFile, $cacheTimeSeconds);

        /**
         * Execute functionality for query caching
         */
        abstract protected function executeQueryCaching($sql);

        /**
         * Constructor
         */
        protected function __construct($dbDriver, $host, $port, $username, $password, $dbName, $unixSocket, $charset)
        {
            $this->dbDriver = $dbDriver;
            $this->host = $host;
            $this->port = $port;
            $this->username = $username;
            $this->password = $password;
            $this->dbName = $dbName;
            $this->unixSocket = $unixSocket;
            $this->charset = $charset;
        }

        /**
         * @return bool
         */
        public function isConnected()
        {
            return ($this->connection instanceof PDO);
        }

        /**
         * Kill connection
         */
        public function closeConnection()
        {
            $this->connection = null;
        }

        /**
         * Start transaction
         */
        public function startTransaction()
        {
            $this->connection->beginTransaction();
        }

        /**
         * Commit the changes
         */
        public function commitTransaction()
        {
            $this->connection->commit();
        }

        /**
         * Rollback the changes
         */
        public function rollbackTransaction()
        {
            $this->connection->rollBack();
        }

        /**
         * Store cache file in cache folder
         */
        public function storeCacheFile($sqlCacheName, $data)
        {   
            $filew = fopen("cache/" . $sqlCacheName, 'w');
            fwrite($filew, print_r($data, true));
            fclose($filew);
        }
        
    }
?>