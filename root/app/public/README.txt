The project should work out of the box, excluding database credentials.

In my directory in cmslamp14, settings.php is under conf/settings.php where conf is a sibling to public_html.

settings.php should look similar to this:

<?php
  $host = "hostname";
  $user = "username";
  $pswd = "password";
  $dbnm = "database";
?>

Substitute these values for your own credentials