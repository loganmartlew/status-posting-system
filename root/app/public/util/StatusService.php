<?php
  class StatusService {
    private $conn;
    private $dbnm;
    private static $statusTable = "status";
    private static $permTable = "permission";
    private static $joinTable = "status_permission";

    public function __construct() {
      // Save database name for use in table_exists() method
      require_once("../../conf/settings.php");
      $this->dbnm = $dbnm;

      // Initialize database connection
      $this->conn = new mysqli($host, $user, $pswd, $dbnm);

      // Initialize tables
      $this->init_tables();
    }

    function init_tables() {
      // Create tables if they don't exist
      if (!$this->table_exists(self::$statusTable)) {
        $this->create_status_table();
      }

      if (!$this->table_exists(self::$permTable)) {
        $this->create_permission_table();
      }

      if (!$this->table_exists(self::$joinTable)) {
        $this->create_join_table();
      }

      // Insert permissions into table, permissions are unique so can be ran every time
      $this->insert_permissions();
    }

    function table_exists($table) {
      // Checks if a table exists in database. Returns boolean
      $query = "SELECT * FROM INFORMATION_SCHEMA.Tables WHERE table_schema = '".$this->dbnm."' AND table_name = '".$table."'";
      $result = $this->conn->query($query);

      if ($result->num_rows >= 1) return true;
      return false;
    }

    function create_status_table() {
      // Creates status table
      $statusStmt = @$this->conn->prepare("CREATE TABLE ".self::$statusTable." (
        statuscode VARCHAR(5) PRIMARY KEY,
        status VARCHAR(200),
        share VARCHAR(7),
        date DATE
      )");
      $statusStmt->execute();
    }

    function create_permission_table() {
      // Creates permission table
      $permStmt = $this->conn->prepare("CREATE TABLE ".self::$permTable." (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(10) UNIQUE
      )");
      $permStmt->execute();
    }

    function create_join_table() {
      // Creates status_permission join table for mapping many-to-many relationship between status and permission
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
      // Inserts default permissions into permission table
      $insertPermStmt = $this->conn->prepare("INSERT INTO ".self::$permTable." (name) VALUES 
        ('like'),
        ('comment'),
        ('share')
      ");
      $insertPermStmt->execute();
    }

    static function process_values($values) {
      // Processes values into the desired format for creating a status
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
      // Validates data for new status

      // Error messages are added as key value pairs of invalid field and error message
      $errors = array();

      // Uses regex patterns and date_is_valid
      require_once("regex-patterns.php");
      require_once("validate-date.php");

      // Validate statuscode
      if (!$values['statuscode']) {
        $errors[] = ["statuscode", "Status code cannot be empty."];
      };
      if (!preg_match($STATUS_CODE_REGEXP, $values['statuscode'])) {
        $errors[] = ["statuscode", "Wrong format. The status code must start with an \"S\" followed by four digits, like \"S0001\". "];
      };

      // Validate status
      if (!$values['status']) {
        $errors[] = ["status", "Status cannot be empty."];
      };
      if (!preg_match($STATUS_REGEXP, $values['status'])) {
        $errors[] = ["status", "Wrong format. The status can only contain alphanumericals and spaces, comma, period, exclamation point and question mark."];
      };

      // Validate visibility
      if ($values['visibility'] !== "public" && $values['visibility'] !== "friends" && $values['visibility'] !== "me") {
        $errors[] = ["visibility", "Wrong format. Visibility must be 'public', 'friends', or 'me'."];
      }

      // Validate date
      if (!$values['date']) {
        $errors[] = ["date", "Wrong format. Date cannot be empty."];
      }
      if (!date_is_valid($values['date'])) {
        $errors[] = ["date", "Wrong format. Date must be in format 'yyyy-mm-dd'."];
      }

      // Variable (boolean) determining if provided assoc array is valid
      $valid = (count($errors) > 0 ? false : true);

      return [$valid, $errors];
    }

    function post_status($values) {
      // Check if statuscode already exists. statuscode is unique so this is redundant, but assignment spec asked us to check if status exists.
      $query = "SELECT * FROM ".self::$statusTable." WHERE statuscode = '".$values['statuscode']."'";
      $result = $this->conn->query($query);

      if ($result->num_rows >= 1) {
        return "Status code already exists. The status code must be unique. Please try another code.";
      };

      // Statement for inserting a new status
      $stmt = $this->conn->prepare("INSERT INTO ".self::$statusTable." (
        statuscode,
        status,
        share,
        date
      ) VALUES (?, ?, ?, ?)");

      // Bind variables to statement
      $stmt->bind_param("ssss", $values['statuscode'], $values['status'], $values['visibility'], $values['date']);
      $stmt->execute();

      if ($stmt->error) {
        return "An error occurred. Please try again. Error: ".$stmt->error;
      }

      // Insert mappings into join table for required permissions
      foreach ($values['permissions'] as $permission => $value) {
        if (!$value) continue;

        // Check if permission exists
        $query = "SELECT id FROM ".self::$permTable." WHERE name = '".$permission."'";
        $result = $this->conn->query($query);

        if ($result->num_rows < 1) {
          return "Permission ".$permission." does not exist.";
        }

        // Get id of provided permission because join table needs the permission id
        $permission_id = $result->fetch_assoc()['id'];

        // Statement for inserting a new many-to-many relationship to join table
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
      // Add wildcards to query string for use in SQL LIKE statement, since bind_param only supports direct variable input
      $q = "%$q%";

      // Statement to search database for statuses matching the query
      $statusStmt = $this->conn->prepare("SELECT * FROM ".self::$statusTable." WHERE status LIKE ?");
    
      // Bind query string to statement (cannot provide string as second argument, must be a variable)
      $statusStmt->bind_param("s", $q);
      $statusStmt->execute();
      $statusStmt->bind_result($statuscode, $status, $visibility, $date);
      $statusStmt->store_result();

      $statuses = array();

      // Map retrieved statuses into an array, as an assoc array of key value pairs
      while ($statusStmt->fetch()) {
        $statuses[] = ["statuscode" => $statuscode, "status" => $status, "visibility" => $visibility, "date" => $date];
      }

      // Return false if no statuses were retrieved matching the query
      if (count($statuses) < 1) return false;

      // Statement to get permission names of permissions assigned to a specific status
      $permStmt = $this->conn->prepare("SELECT p.name FROM ".self::$permTable." p, ".self::$joinTable." sp WHERE p.id = sp.permission_id AND sp.statuscode = ?");

      // Get permissions for each status and add to the status assoc array
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