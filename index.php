<?php
    include 'database.class.php';

    $config = parse_ini_file('config.ini');

    $db = new Database($config['driver'], $config['host'], $config['username'], $config['password'], $config['dbname']);

    /* Connect to database */
    $db->openConnection();

    $db->startTransaction();

    /* Select data from database */
    $rows = $db->selectData('MyGuests', array('order_by'=>'id DESC'));

    /* Insert data */
    $tblName = "MyGuests";
    $userData = array(
            'firstname' => 'test'
        );
    $insertedData = $db->insertData($tblName,$userData);

    /* Update data */
    // $userData1 = array(
    //             'firstname' => 'yiannis update'
    //         );
    // $condition = array('id' => 3);
    // $updatedData = $db->updateData($tblName,$userData1,$condition);

    // /* Delete data */
    // $condition = array('id' => 3);
    // $deletedData =$db->deleteData($tblName,$condition);

    /* Check if all queries execute correctly , then commit
    *  the changes, else rollback the transaction
    */
    // if (!$insertedData && !$updatedData && !$deletedData) 

    if (!empty($insertedData)) 
    {
        $db->commitTransaction();
    } else 
    {
        $db->rollbackTransaction();
    }

    /* Kill connection */
    $db->closeConnection();
?>