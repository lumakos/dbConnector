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
            echo $row['firstname']; 
        }
    }

    /* Insert data */
    $tblName = "MyGuests";
    $userData = array(
            'firstname' => 'yiannis'
        );
    $db->insertData($tblName,$userData);

    /* Update data */
    $userData1 = array(
                'firstname' => 'yiannis update'
            );
    $condition = array('id' => 3);
    $db->updateData($tblName,$userData1,$condition);

    /* Delete data */
    $condition = array('id' => 3);
    $db->deleteData($tblName,$condition);

    /* Kill connection */
    $db->closeConnection();
?>