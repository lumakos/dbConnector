<?php
  include 'database.class.php';
  $config = parse_ini_file('config.ini');

  $db = new Database($config['driver'], $config['host'], $config['username'], $config['password'], $config['dbname']);
  // $db->connect();
?>