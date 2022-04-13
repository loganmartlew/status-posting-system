<!DOCTYPE html>
<html lang="en">
  <head>
    <?php require_once("util/headcomponent.php") ?>
    <link rel="stylesheet" href="css/page-searchstatus.css" />
    <title>Search Status - Status Posting System</title>
  </head>
  <body>
    <?php require_once("util/headercomponent.php") ?>
    <main>
      <h2>Search for a Status</h2>
      <hr />
      <form action="searchstatusprocess.php" method="GET">
        <label for="search">Status:</label>
        <div class="searchbar">
          <input class="textinput" type="text" name="Search" id="search" />
          <button class="btn btn-primary" type="submit">Show Results</button>
        </div>
        <a href="http://tkj2567.cmslamp14.aut.ac.nz/assign1">Return to Home Page</a>
      </form>
    </main>
  </body>
</html>
