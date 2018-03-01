<?php

    include 'databaseManager.class.php';

    /**
     * Database connection
     */
    class Database extends DatabaseManager
    {
        /**
         * @return PDO
         */
        public function openConnection()
        {
            if ($this->isConnected() != 1)
            {
                try 
                {                    
                    $this->connection = new PDO($this->dbDriver.":host=".$this->host.";port=".$this->port.";charset=".$this->charset.";unix_socket=".$this->unixSocket.";dbname=".$this->dbName,$this->username,$this->password);
                    echo "Successfull connection to database" . "\n";

                    /* disable emulated prepared statements and use real prepared statements */
                    $this->connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
                    $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                } catch (PDOException $e) 
                {
                    die($e->getMessage());
                }  
            }
            return $this->connection;    
        }
    }
?>