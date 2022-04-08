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

    static function process_values($values) {
      $values = array(
        "statuscode" => $values['statuscode'],
        "status" => $values['status'],
        "visibility" => $values['visibility'],
        "date" => date("Y-m-d", $values['date']),
        "permissions" => array(
          "like" => ($values['like'] ? true : false),
          "comment" => ($values['comment'] ? true : false),
          "share" => ($values['share'] ? true : false)
        )
      );
      return $values;
    }

    static function status_is_valid($values) {
      require_once("regex-patterns.php");

      if (!$values['statuscode']) return false;
      if (preg_match($values['statuscode'], $STATUS_CODE_REGEXP) < 1) return false;

      if (!$values['status']) return false;
      if (preg_match($values['status'], $STATUS_REGEXP) < 1) return false;
    }
  }
?>