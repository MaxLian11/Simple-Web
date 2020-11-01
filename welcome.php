
<!DOCTYPE html>
<html>
<head>
  <title>Welcome!</title>
  <link rel="stylesheet" href="./style.css">
</head>
<body>
<div class = "login">
       <?php
          echo "<a class='login' name='logout' href='login.php' >Log Out</a>";
          // session will be destroyed when we logout
          if(!empty($_POST['logout'])) {
            session_destroy();
          }
       ?>
  </div>
  <div>
<form class="box2" method="POST" action="#">
  <h1>Welcome
    <div class='login_name'>
    <?php
    session_start();
    echo($_SESSION["username"]);
    ?>
     <input type="submit" name="test" id="test" value="Initialize Database" />
     <?php

    function insert(){
      $conn =new mysqli('localhost', 'root', '' , 'registration');

      $query = '';
      $sqlScript = file('DDL.sql');
      foreach ($sqlScript as $line)	{

        $startWith = substr(trim($line), 0 ,2);
        $endWith = substr(trim($line), -1 ,1);

        if (empty($line) || $startWith == '--' || $startWith == '/*' || $startWith == '//') {
          continue;
        }

        $query = $query . $line;
        if ($endWith == ';') {
          //mysqli_query($conn,$query) or die('<div class="login_name">Problem in executing the SQL query <b>' . $query. '</b></div>');
          mysqli_query($conn,$query) or die('<div class="login_name"> Database was already created</div>');
          $query= '';
        }

      }
      echo '<div>Database was initialized</div>';
    }

    if(array_key_exists('test',$_POST)){
      insert();

   }
?>
  </form>
        </div>
</body>
</html>
