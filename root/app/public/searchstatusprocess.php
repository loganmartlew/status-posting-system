<!DOCTYPE html>
<html lang="en">
  <head>
    <?php require_once("util/headcomponent.php") ?>
    <link rel="stylesheet" href="css/page-searchresults.css" />
    <title>Search Results - Status Posting System</title>
  </head>
  <body>
    <div class="content">
      <?php require_once("util/headercomponent.php") ?>
      <main>
        <h2>Search Results</h2>
        <hr />
        <?php
          $links = "
          <div class=\"links\">
            <a href=\"http://tkj2567.cmslamp14.aut.ac.nz/assign1/searchstatusform.php\">Search for another status</a>
            <a href=\"http://tkj2567.cmslamp14.aut.ac.nz/assign1\">Return to Home Page</a>
          </div>
          ";

          require_once("util/StatusService.php");
          $status_service = new StatusService();

          $q = $_GET['Search'];

          if (!$q) die("<p>Search input cannot be empty. Try another search term.</p>".$links);

          $statuses = $status_service->search_status($q);

          if (!$statuses) die("<p>Status not found. Please try a different keyword.</p>".$links);

          echo "<div class=\"search-cards\">";

          foreach ($statuses as $status) {
            echo "<div class=\"card\">";
            echo "<h3>".$status['statuscode']."</h3>";
            echo "<p class=\"status\">".$status['status']."</p>";
            echo "<p>".date('d/m/Y', strtotime($status['date']))."</p>";
            echo "<p>Visibility: ".ucfirst($status['visibility'])."</p>";

            if (count($status['permissions']) > 0) {
              echo "<div>";
              echo "<p>Permissions:</p>";
              echo "<ul>";
              
              foreach ($status['permissions'] as $permission) {
                echo "<li>Allow ".ucfirst($permission)."</li>";
              }

              echo "</ul>";
              echo "</div>";
            }
            
            echo "</div>";
          }

          echo "</div>";

          echo $links;
        ?>
      </main>
    </div>
  </body>
</html>