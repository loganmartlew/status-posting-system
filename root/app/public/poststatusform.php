<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;1,200;1,300;1,400&display=swap"
      rel="stylesheet"
    />
    <link
      href="https://fonts.googleapis.com/icon?family=Material+Icons"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="css/base.css" />
    <link rel="stylesheet" href="css/components.css" />
    <link rel="stylesheet" href="css/common.css" />
    <link rel="stylesheet" href="css/page-poststatus.css" />
    <script src="js/menu.js" defer></script>
    <script src="js/resetform.js" defer></script>
    <title>Post Status - Status Posting System</title>
  </head>
  <body>
    <header class="container">
      <h1>Status Posting System</h1>
      <nav>
        <a href="#">About this assignment</a>
        <a href="#" class="btn btn-primary">Post status</a>
        <a href="#" class="btn btn-outline">Search status</a>
      </nav>
      <button class="menubutton">
        <span class="material-icons">menu</span>
      </button>
      <div class="menu modal hidden content-hidden">
        <div class="modal-content">
          <a href="#">About this assignment</a>
          <a href="#" class="btn btn-primary btn-block">Post status</a>
          <a href="#" class="btn btn-outline btn-block">Search status</a>
        </div>
      </div>
    </header>
    <main>
      <h2>Post a Status</h2>
      <hr />
      <?php include_once('regex-patterns.php') ?>
      <form class="card">
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

        <div class="form-field">
          <label for="share">Visibility:</label>
          <div class="radio-group">
            <div class="radio-item">
              <input
                type="radio"
                name="share"
                id="public"
                value="public"
                checked
              />
              <label for="public">Public</label>
            </div>

            <div class="radio-item">
              <input type="radio" name="share" id="friends" value="friends" />
              <label for="friends">Friends</label>
            </div>

            <div class="radio-item">
              <input type="radio" name="share" id="me" value="me" />
              <label for="me">Only me</label>
            </div>
          </div>
        </div>

        <div class="form-field">
          <label for="date">Date:</label>
          <input type="date" name="date" id="date" value="2022-01-01" />
        </div>

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

        <div>
          <button
            type="submit"
            class="btn btn-primary"
            style="margin-right: 0.5em"
          >
            Post
          </button>
          <button type="button" class="btn btn-outline resetbtn">Reset</button>
        </div>
      </form>
    </main>
  </body>
</html>