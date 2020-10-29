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
          mysqli_query($conn,$query) or die('<div class="error-response sql-import-response">Problem in executing the SQL query <b>' . $query. '</b></div>');
          $query= '';		
        }
      }
      echo '<div class="success-response sql-import-response">SQL file imported successfully</div>';
    }

    if(array_key_exists('test',$_POST)){
      insert();

   }
?>

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
    
    <div class = "create_db">
      <form method="post">
          <input type="submit" name="test" id="test" value="RUN" />
      </form>

    </div>
  </div>
  <div class = "login">
       <?php
          echo "<a class='login' href='login.php'>Log Out</a>";
       ?>
  </div>
</body>
</html>