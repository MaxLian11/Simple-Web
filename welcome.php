<!DOCTYPE html>
<html>
<head>
  <title>Welcome!</title>
  <link rel="stylesheet" href="./style.css">
</head>
<body>
  <div class="container">
    <h1>Welcome 
    <div class='login_name'>
    <?php 
    session_start();
    echo($_SESSION["username"]);
    session_destroy();
    ?>
    </div>
    </h1>
  </div>
  <div class = "login">
       <?php
          echo "<a class='login' href='login.php'>Log Out</a>";
       ?>
  </div>
</body>
</html>