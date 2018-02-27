<?php
    include 'database.class.php';

    $config = parse_ini_file('config.ini');

    $db = new Database($config['driver'], $config['host'], $config['username'], $config['password'], $config['dbname']);

    /* Connect to database */
    $db->openConnection();

    $db->getStatement('SELECT * FROM MyGuests');

    /* Select data from database */
    $rows = $db->selectData('MyGuests', array('order_by'=>'id DESC'));
    if(!empty($rows)) { 
        foreach($rows as $row) { 
            echo $ow['firstname']; 
        }
    }
    
    /* Kill connection */
    $db->closeConnection();
?>