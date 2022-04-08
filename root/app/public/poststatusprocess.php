<?php
  require_once("util/StatusService.php");
  $status_service = new StatusService();
  
  $values = StatusService::process_values($_POST);

  if (!StatusService::status_is_valid($values)) {

  }