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
        share VARCHAR(7),
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
        "date" => $values['date'],
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
      require_once("validate-date.php");

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
        $errors[] = ["date", "Wrong format. Date cannot be empty."];
      }
      if (!date_is_valid($values['date'])) {
        $errors[] = ["date", "Wrong format. Date must be in format 'yyyy-mm-dd'."];
      }

      $valid = (count($errors) > 0 ? false : true);

      return [$valid, $errors];
    }

    function post_status($values) {
      $query = "SELECT * FROM ".self::$statusTable." WHERE statuscode = '".$values['statuscode']."'";
      $result = $this->conn->query($query);

      if ($result->num_rows >= 1) {
        return "Status code already exists. The status code must be unique. Please try another code.";
      };

      $stmt = $this->conn->prepare("INSERT INTO ".self::$statusTable." (
        statuscode,
        status,
        share,
        date
      ) VALUES (?, ?, ?, ?)");

      $stmt->bind_param("ssss", $values['statuscode'], $values['status'], $values['visibility'], $values['date']);
      $stmt->execute();

      if ($stmt->error) {
        return "An error occurred. Please try again. Error: ".$stmt->error;
      }

      foreach ($values['permissions'] as $permission => $value) {
        if (!$value) continue;

        $query = "SELECT id FROM ".self::$permTable." WHERE name = '".$permission."'";
        $result = $this->conn->query($query);

        if ($result->num_rows < 1) {
          return "Permission ".$permission." does not exist.";
        }

        $permission_id = $result->fetch_assoc()['id'];

        $stmt = $this->conn->prepare("INSERT INTO ".self::$joinTable." (
          statuscode,
          permission_id
        ) VALUES (?, ?)");
        $stmt->bind_param("si", $values['statuscode'], $permission_id);
        $stmt->execute();

        if ($stmt->error) {
          return "An error occurred. Please try again. Error: ".$stmt->error;
        }
      }

      return "Status posted.";
    }

    function search_status($q) {
      $q = "%$q%";

      $statusStmt = $this->conn->prepare("SELECT * FROM ".self::$statusTable." WHERE status LIKE ?");
    
      $statusStmt->bind_param("s", $q);
      $statusStmt->execute();
      $statusStmt->bind_result($statuscode, $status, $visibility, $date);
      $statusStmt->store_result();

      $statuses = array();

      while ($statusStmt->fetch()) {
        $statuses[] = ["statuscode" => $statuscode, "status" => $status, "visibility" => $visibility, "date" => $date];
      }

      if (count($statuses) < 1) return false;

      $permStmt = $this->conn->prepare("SELECT p.name FROM ".self::$permTable." p, ".self::$joinTable." sp WHERE p.id = sp.permission_id AND sp.statuscode = ?");

      foreach ($statuses as &$status) {
        $status['permissions'] = array();

        $permStmt->bind_param("s", $status['statuscode']);
        $permStmt->execute();
        $permStmt->bind_result($permission);
        $permStmt->store_result();

        while ($permStmt->fetch()) {
          $status['permissions'][] = $permission;
        }
      }

      return $statuses;
    }
  }
?>