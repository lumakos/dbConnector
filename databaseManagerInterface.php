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
        public function storeCacheFile($sqlCacheName, $data);
    }
?>