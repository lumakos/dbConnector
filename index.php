<?php
    include 'database.class.php';

    $config = parse_ini_file('config.ini');

    $db = new Database($config['driver'], $config['host'], $config['username'], $config['password'], $config['dbname']);

    /* Connect to database */
    $db->openConnection();

    /* Kill connection */
    $db->closeConnection();
?>