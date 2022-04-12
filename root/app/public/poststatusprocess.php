<?php
  require_once("util/StatusService.php");
  $status_service = new StatusService();
  
  $values = StatusService::process_values($_POST);

  list($valid, $errors) = StatusService::status_is_valid($values);

  if (!$valid) {
    echo "<p><strong>Provided data was not valid.</strong></p>";
    
    foreach ($errors as $error) {
      list($field, $msg) = $error;
      echo "<p>".$field.": ".$msg."</p>";
    }
  } else {
    
  }