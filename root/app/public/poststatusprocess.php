<!DOCTYPE html>
<html lang="en">
  <head>
    <?php require_once("util/headcomponent.php") ?>
    <title>Post Status Process - Status Posting System</title>
  </head>
  <body>
    <div class="content">
      <?php require_once("util/headercomponent.php") ?>
      <main>
        <?php
          // Initialize status service
          require_once("util/StatusService.php");
          $status_service = new StatusService();
          
          // Convert post request values to usable data
          $values = StatusService::process_values($_POST);

          // Check validity of input data
          list($valid, $errors) = StatusService::status_is_valid($values);

          if (!$valid) {
            // List errors coming from validation method
            echo "<p><strong>Provided data was not valid.</strong></p>";
            
            foreach ($errors as $error) {
              list($field, $msg) = $error;
              echo "<p>".$field.": ".$msg."</p>";
            }
          } else {
            // Create new status, print success or error message
            $msg = $status_service->post_status($values);
            echo "<p>".$msg."</p>";
          }
        ?>
        <a href="http://tkj2567.cmslamp14.aut.ac.nz/assign1">Return to Home Page</a>
      </main>
    </div>
  </body>
</html>