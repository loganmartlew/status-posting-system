<!DOCTYPE html>
<html lang="en">
  <head>
    <?php require_once("util/headcomponent.php") ?>
    <link rel="stylesheet" href="css/page-about.css" />
    <title>About - Status Posting System</title>
  </head>
  <body>
    <?php require_once("util/headercomponent.php") ?>
    <main>
      <h2>About Status Posting System</h2>
      <hr />
      <ul>
        <li>
          <strong>What special features have you done or attempted in creating the website? </strong>
          <span>I have created a modal system in Javascript that I used in the nav menu. It is only accessible at smaller screen sizes such as mobile for the navigation. To view it, change your browser window size to a smaller width until the nav changes to a hamburger button. I also experimented with some OOP in my code, such as the StatusService class which was used to interact with the database for anything status related.</span>
        </li>
        <li>
          <strong>Which part(s) did you have trouble with? </strong>
          <span>I had some trouble using prepared statements with mysqli. I was trying to use the mysqli_stmt::get_result() method, which is only available with the mysqlnd driver, which cmslamp14 does not have. This took me a long time to realise, after which I switched to using the bind_result and fetch() methods to retrieve data from prepared statements. I also had some problems implementing regular expressions into HTML elements. It turned out I was escaping characters with a \ when I didn't need to.</span>
        </li>
        <li>
          <strong>What would you like to do better next time? </strong>
          <span>I would like to clean up my code a bit next time. This would probably involve splitting my code into more classes and smaller classes. Helper functions would also help with organisation and would improve the codebase. I am also not entirely happy with the styling of my pages, as I have some redundant CSS classes and the look and feel could be better.</span>
        </li>
        <li>
          <strong>What you have learnt along the way? Did you use any references or resources during this project? If so, please include the sources. </strong>
          <span>I learnt regular expressions while working on this project. I can see how useful they can be while building software. I used the website <a href="https://regexr.com/">RegExr</a> to help me learn and write regular expressions. I also learnt a lot about PHP as a whole, as I had little experience in it beforehand. The <a href="https://www.php.net/docs.php">PHP Docs</a> were incredibly helpful whenever I ran into an error, or needed to find a solution to a problem. I also used <a href="https://stackoverflow.com/">Stack Overflow</a> a lot to help me when I had a bug or problem with my code.</span>
        </li>
      </ul>
      <a href="http://tkj2567.cmslamp14.aut.ac.nz/assign1" class="link">Return to Home Page</a>
    </main>
  </body>
</html>
