<?php
    /**
     * Define an Interface about database's functions
     */
    interface DatabaseManagerInterface
    {
        public function isConnected();
        public function closeConnection();
        public function startTransaction();
        public function commitTransaction();
        public function rollbackTransaction();
        public function selectData($table, $conditions = array());
        public function insertData($table,$data);
        public function updateData($table, $data, $conditions);
        public function deleteData($table, $conditions);
    }
?>