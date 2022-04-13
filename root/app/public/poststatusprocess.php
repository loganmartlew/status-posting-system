<!DOCTYPE html>
<html lang="en">
  <head>
    <?php require_once("util/headcomponent.php") ?>
    <title>Post Status Process - Status Posting System</title>
  </head>
  <body>
    <?php require_once("util/headercomponent.php") ?>
    <main>
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
          $msg = $status_service->post_status($values);
          echo "<p>".$msg."</p>";
        }
      ?>
      <a href="http://tkj2567.cmslamp14.aut.ac.nz/assign1">Return to Home Page</a>
    </main>
  </body>
</html>