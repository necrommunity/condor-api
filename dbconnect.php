<?php
class dbconnect {
  private $db;

  function __construct() {
    $this->connect_database();
  }

  public function getInstance() {
    return $this->db;
  }

  private function connect_database() {
    define('USER', 'necrobot-read');
    define('PASSWORD', 'necrobot-read');
    // Database connection
      try {
          $connection_string = 'mysql:host=localhost;charset=utf8';
          $connection_array = array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
          );

          $this->db = new PDO($connection_string, USER, PASSWORD, $connection_array);
          echo 'Database connection established';
      }
    catch(PDOException $e) {
      $this->db = null;
    }
  }
}
?>
