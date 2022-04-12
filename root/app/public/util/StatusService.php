<?php
  class StatusService {
    private $conn;
    private $dbnm;
    private static $statusTable = "status";
    private static $permTable = "permission";
    private static $joinTable = "status_permission";

    public function __construct() {
      require_once("../../conf/settings.php");
      $this->dbnm = $dbnm;

      $this->conn = new mysqli($host, $user, $pswd, $dbnm);

      $this->init_tables();
    }

    function init_tables() {
      if (!$this->table_exists(self::$statusTable)) {
        $this->create_status_table();
      }

      if (!$this->table_exists(self::$permTable)) {
        $this->create_permission_table();
      }

      if (!$this->table_exists(self::$joinTable)) {
        $this->create_join_table();
      }

      $this->insert_permissions();
    }

    function table_exists($table) {
      $query = "SELECT * FROM INFORMATION_SCHEMA.Tables WHERE table_schema = '".$this->dbnm."' AND table_name = '".$table."'";
      $result = $this->conn->query($query);

      if ($result->num_rows >= 1) return true;
      return false;
    }

    function create_status_table() {
      $statusStmt = @$this->conn->prepare("CREATE TABLE ".self::$statusTable." (
        statuscode VARCHAR(5) PRIMARY KEY,
        status VARCHAR(200),
        share VARCHAR(1),
        date DATE
      )");
      $statusStmt->execute();
    }

    function create_permission_table() {
      $permStmt = $this->conn->prepare("CREATE TABLE ".self::$permTable." (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(10) UNIQUE
      )");
      $permStmt->execute();
    }

    function create_join_table() {
      $joinStmt = $this->conn->prepare("CREATE TABLE ".self::$joinTable." (
        statuscode VARCHAR(5),
        permission_id INT,
        PRIMARY KEY (statuscode, permission_id),
        FOREIGN KEY (statuscode) REFERENCES status(statuscode),
        FOREIGN KEY (permission_id) REFERENCES ".self::$permTable."(id)
      )");
      $joinStmt->execute();
    }

    function insert_permissions() {
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
      $errors = array();

      require_once("regex-patterns.php");

      if (!$values['statuscode']) {
        $errors[] = ["statuscode", "Status code cannot be empty."];
      };
      if (!preg_match($STATUS_CODE_REGEXP, $values['statuscode'])) {
        $errors[] = ["statuscode", "Wrong format. The status code must start with an \"S\" followed by four digits, like \"S0001\". "];
      };

      if (!$values['status']) {
        $errors[] = ["status", "Status cannot be empty."];
      };
      if (!preg_match($STATUS_REGEXP, $values['status'])) {
        $errors[] = ["status", "Wrong format. The status can only contain alphanumericals and spaces, comma, period, exclamation point and question mark."];
      };

      if ($values['visibility'] !== "public" && $values['visibility'] !== "friends" && $values['visibility'] !== "me") {
        $errors[] = ["visibility", "Wrong format. Visibility must be 'public', 'friends', or 'me'."];
      }

      if (!$values['date']) {
        $errors[] = ["date", "Wrong format. Date cannot be empty and must be in format 'yyyy-mm-dd'."];
      }

      $valid = (count($errors) > 0 ? false : true);

      return [$valid, $errors];
    }

    function post_status($values) {
      
    }
  }
?>