
<?php
// Index.html is in a loop to test credentials
// include("index.html");

$db = mysqli_connect('localhost', 'john', 'pass1234', 'registration');

if ($db -> connect_errno) {
  echo "Failed to connect to MySQL: " . $db -> connect_error;
  exit();
}
#session_start();
mysqli_select_db($db, 'registration');
$sql_query = "SELECT subject, description, tags FROM blog WHERE blog_id = 1";
$result = mysqli_query($db, $sql_query);

$row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
  <title>Home page</title>
  <link rel="stylesheet" href="./style.css">
  
<style type='text/css'>
/* CSS Document */
body {
  font-family: "Lucida Grande","Lucida Sans Unicode",geneva,verdana,sans-serif;
  font-size:10px;
  color:#777;
}
#post-section {
    width:600px;
    margin:0 auto;
}
#content-section {
    background-color:black;
  margin:10px 185px 10px 10px;
}
#content-section h1 {
  font-family:Trebuchet MS, Geneva, Arial, Helvetica, sans-serif;
  font-size:14px;
  border-bottom:1px solid #eee;
  padding:5px;
  color:white;
}
#content-section p {
  padding:5px;
  line-height:18px;
  word-spacing: 0.1em;
  color:white;
}

#content-section p2 {
  padding:5px;
  line-height:18px;
  word-spacing: 0.1em;
}

#content-section .article_menu {
  text-align:right;
  padding:5px;
  margin:10px 0 20px 0;
  border-top:1px solid #eee;
}
#content-section .article_menu b {
  float:left;
  font-weight:normal;
}
#content-section .article_menu a {
  padding:0 0 0 15px;
  background-position:left;
  background-repeat:no-repeat;
  color:white;
  text-decoration:none;
}
</style>

</head>

<body>
<div class = "login">
  <div class= "row">
    <div class = "column">
        <?php
            session_start();
            echo '<p class="hello" style="display:inline">Welcome </p>';
            echo($_SESSION["username"]);
        ?>
    </div>
    <div class="column">
    <form class="box3" method="POST" action="#">
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
    <div class ="column">
          <?php
              echo "<a class='hello' name='logout' href='login.php' >Log Out</a>";
              // session will be destroyed when we logout
              if(!empty($_POST['logout'])) {
                session_destroy();
              }
          ?>
      </div>
  </div>
  </div>
<div id="post-section">
  <div id="content-section">
    <h1><?php echo $row["subject"]; ?></h1>
    <p> <?php echo $row["description"]; ?> </p>
    <p2><?php echo $row["tags"]; ?> </p2>
    <div class="article_menu"> <b>Date posted: 2020-11-15</b> <a href="./comments.php">2 Comments</a> </div>
</div>
<div><p></p></div>

</body>
</html>
