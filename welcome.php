
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

    function insert($flag){
      $conn =new mysqli('localhost', 'john', 'pass1234', 'registration');

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
          mysqli_query($conn,$query) or die('<div class="login_name">Problem in executing the SQL query <b>' . $query. '</b></div>');
          //mysqli_query($conn,$query);
          $query= '';
        }

      }
      if ($flag == 1) {
        echo '<div>Database was recreated</div>';
      } else {
        echo '<div>Database was initialized</div>';
      }
    }

    if(array_key_exists('test',$_POST)){
      $conn = new mysqli('localhost', 'john', 'pass1234', 'registration');
      $check = mysqli_query($conn," SELECT * FROM student ") ;
      $flag = 0;
      if ($check !== False) { $flag = 1; }
      insert($flag);
   }
?>
  </form>
        </div>
</body>
</html>
