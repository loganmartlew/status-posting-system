<?php
  class StatusService {
    private $conn;
    private static $statusTable = "status";
    private static $permTable = "permission";
    private static $joinTable = "status_permission";

    public function __construct() {
      require_once("../../conf/settings.php");

      $this->conn = new mysqli($host, $user, $pswd, $dbnm);

      $this->init_tables();
    }

    function init_tables() {
      echo "1";
      $statusStmt = @$this->conn->prepare("CREATE TABLE ".self::$statusTable." (
        statuscode VARCHAR(5) PRIMARY KEY,
        status VARCHAR(200),
        share VARCHAR(1),
        date DATE
      )");
      $statusStmt->execute();

      $permStmt = $this->conn->prepare("CREATE TABLE ".self::$permTable." (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(10) UNIQUE
      )");
      $permStmt->execute();

      $joinStmt = $this->conn->prepare("CREATE TABLE ".self::$joinTable." (
        statuscode VARCHAR(5),
        permission_id INT,
        PRIMARY KEY (statuscode, permission_id),
        FOREIGN KEY (statuscode) REFERENCES status(statuscode),
        FOREIGN KEY (permission_id) REFERENCES ".self::$permTable."(id)
      )");
      $joinStmt->execute();

      $insertPermStmt = $this->conn->prepare("INSERT INTO ".self::$permTable." (name) VALUES 
        ('like'),
        ('comment'),
        ('share')
      ");
      $insertPermStmt->execute();
    }
  }
?>