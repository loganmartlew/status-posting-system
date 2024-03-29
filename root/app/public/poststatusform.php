<!DOCTYPE html>
<html lang="en">
  <head>
    <?php require_once("util/headcomponent.php") ?>
    <link rel="stylesheet" href="css/page-poststatus.css" />
    <title>Post Status - Status Posting System</title>
  </head>
  <body>
    <?php require_once("util/headercomponent.php") ?>
    <main>

      <h2>Post a Status</h2>
      <hr />

      <!-- Import RegEx patterns -->
      <?php require_once('util/regex-patterns.php') ?>

      <form action="poststatusprocess.php" method="POST" class="card">

        <!-- Status code input -->
        <div class="form-field">
          <label for="statuscode">Status Code (required):</label>
          <input
            class="textinput"
            type="text"
            name="statuscode"
            id="statuscode"
            pattern="<?php echo $STATUS_CODE_REGEXP_HTML; ?>"
            title="<?php echo $STATUS_CODE_DESC; ?>"
            required
          />
        </div>

        <!-- Status input -->
        <div class="form-field">
          <label for="status">Status (required):</label>
          <input
            class="textinput"
            type="text"
            name="status"
            id="status"
            pattern="<?php echo $STATUS_REGEXP_HTML; ?>"
            title="<?php echo $STATUS_DESC; ?>"
            required
          />
        </div>

        <!-- Visibility/Share Radio buttons -->
        <div class="form-field">
          <label>Visibility:</label>
          <div class="radio-group">
            <div class="radio-item">
              <input type="radio" name="visibility" id="public" value="public" checked />
              <label for="public">Public</label>
            </div>

            <div class="radio-item">
              <input type="radio" name="visibility" id="friends" value="friends" />
              <label for="friends">Friends</label>
            </div>

            <div class="radio-item">
              <input type="radio" name="visibility" id="me" value="me" />
              <label for="me">Only me</label>
            </div>
          </div>
        </div>

        <!-- Date input (defaults to server date) -->
        <div class="form-field">
          <?php require_once("util/dateconfig.php") ?>
          <label for="date">Date:</label>
          <input type="date" name="date" id="date" value="<?php echo date("Y-m-d") ?>" />
        </div>

        <!-- Checkboxes for status permissions -->
        <div class="form-field">
          <label for="permission">Permissions:</label>
          <div class="checkbox-group">
            <div class="checkbox-item">
              <input type="checkbox" name="like" id="like" />
              <label for="like">Allow Like</label>
            </div>

            <div class="checkbox-item">
              <input type="checkbox" name="comment" id="comment" />
              <label for="comment">Allow Comment</label>
            </div>

            <div class="checkbox-item">
              <input type="checkbox" name="share" id="share" />
              <label for="share">Allow Share</label>
            </div>
          </div>
        </div>

        <!-- Submit and Reset buttons -->
        <div>
          <button
            type="submit"
            class="btn btn-primary"
            style="margin-right: 0.5em"
          >
            Post
          </button>
          <button type="reset" class="btn btn-outline resetbtn">Reset</button>
        </div>
        
        <a href="http://tkj2567.cmslamp14.aut.ac.nz/assign1">Return to Home Page</a>
      </form>
    </main>
  </body>
</html>