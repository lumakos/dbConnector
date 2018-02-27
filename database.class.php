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

        /**
         * Constructor
         */
        public function __construct($dbDriver, $host, $username, $password, $dbName)
        {
            $this->dbDriver = $dbDriver;
            $this->host = $host;
            $this->username = $username;
            $this->password = $password;
            $this->dbName = $dbName;
        }

        /**
         * Get prepared statement
         * @param string
         * @return PDOStatement
         */
        public function getStatement($query)
        {
            $db = $this->openConnection();
            return $db->prepare($query);
        }

        /**
         * @return bool
         */
        public function isConnected()
        {
            return ($this->connection instanceof PDO);
        }

        /**
         * @return PDO
         */
        public function openConnection()
        {
            if ($this->isConnected() != 1)
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
            return $this->connection;
            
        }

        /**
         * Kill connection
         */
        public function closeConnection()
        {
            $this->connection = null;
        }

        /**
         * Returns rows from the database based on the conditions
         * @param string name of the table
         * @param array select, where, order_by, limit and return_type conditions
         */
        public function selectData($table,$conditions = array())
        {
            $sql = 'SELECT ';
            $sql .= array_key_exists("select",$conditions)?$conditions['select']:'*';
            $sql .= ' FROM '.$table;
            if(array_key_exists("where",$conditions))
            {
                $sql .= ' WHERE ';
                $i = 0;
                foreach($conditions['where'] as $key => $value)
                {
                    $pre = ($i > 0)?' AND ':'';
                    $sql .= $pre.$key." = '".$value."'";
                    $i++;
                }
            }
            
            if(array_key_exists("order_by",$conditions))
            {
                $sql .= ' ORDER BY '.$conditions['order_by']; 
            }
            
            if(array_key_exists("start",$conditions) && array_key_exists("limit",$conditions))
            {
                $sql .= ' LIMIT '.$conditions['start'].','.$conditions['limit']; 
            } elseif(!array_key_exists("start",$conditions) && array_key_exists("limit",$conditions))
            {
                $sql .= ' LIMIT '.$conditions['limit']; 
            }
            
            $query = $this->connection->prepare($sql);
            $query->execute();
            
            if(array_key_exists("return_type",$conditions) && $conditions['return_type'] != 'all')
            {
                switch($conditions['return_type'])
                {
                    case 'count':
                        $data = $query->rowCount();
                        break;
                    case 'single':
                        $data = $query->fetch(PDO::FETCH_ASSOC);
                        break;
                    default:
                        $data = '';
                }
            } else
            {
                if($query->rowCount() > 0)
                {
                    $data = $query->fetchAll();
                }
            }
            return !empty($data)?$data:false;
        }

    }
?>