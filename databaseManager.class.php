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
     * Constructor
     */
    public function __construct($dbDriver, $host, $port, $username, $password, $dbName, $unixSocket, $charset)
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

    abstract protected function openConnection();

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
     * Returns rows from the database based on the conditions
     * @param string name of the table
     * @param array select, where, order_by, limit and return_type conditions
     */
    public function selectData($table, $conditions = array())
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

    /**
     * Insert data into the database
     * @param string name of the table
     * @param array the data for inserting into the table
     */
    public function insertData($table,$data)
    {
        if(!empty($data) && is_array($data))
        {
            $columns = '';
            $values  = '';
            $i = 0;

            $columnString = implode(',', array_keys($data));
            $valueString = ":".implode(',:', array_keys($data));
            $sql = "INSERT INTO ".$table." (".$columnString.") VALUES (".$valueString.")";
            $query = $this->connection->prepare($sql);
            
            foreach($data as $key=>$val)
            {
                    $query->bindValue(':'.$key, $val);
            }

            $insert = $query->execute();

            return $insert?$this->connection->lastInsertId():false;

        } else
        {
            return false;
        }
    }

    /**
     * Update data into the database
     * @param string name of the table
     * @param array the data for updating into the table
     * @param array where condition on updating data
     */
    public function updateData($table, $data, $conditions)
    {
        if(!empty($data) && is_array($data))
        {
            $colvalSet = '';
            $whereSql = '';
            $i = 0;

            foreach($data as $key=>$val)
            {
                $pre = ($i > 0)?', ':'';
                $colvalSet .= $pre.$key."='".$val."'";
                $i++;
            }

            if(!empty($conditions)&& is_array($conditions))
            {
                $whereSql .= ' WHERE ';
                $i = 0;
                foreach($conditions as $key => $value)
                {
                    $pre = ($i > 0)?' AND ':'';
                    $whereSql .= $pre.$key." = '".$value."'";
                    $i++;
                }
            }

            $sql = "UPDATE ".$table." SET ".$colvalSet.$whereSql;
            $query = $this->connection->prepare($sql);
            $update = $query->execute();

            return $update?$query->rowCount():false;

        } else
        {
            return false;
        }
    }

    /**
     * Delete data from the database
     * @param string name of the table
     * @param array where condition on deleting data
     */
    public function deleteData($table, $conditions)
    {
        $whereSql = '';
        if(!empty($conditions)&& is_array($conditions))
        {
            $whereSql .= ' WHERE ';
            $i = 0;
            foreach($conditions as $key => $value)
            {
                $pre = ($i > 0)?' AND ':'';
                $whereSql .= $pre.$key." = '".$value."'";
                $i++;
            }
        }
        $sql = "DELETE FROM ".$table.$whereSql;
        $delete = $this->connection->exec($sql);
        return $delete?$delete:false;
        }
    }

?>