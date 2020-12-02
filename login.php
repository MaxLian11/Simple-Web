<?php
// Index.html is in a loop to test credentials
// include("index.html");

if(!empty($_POST['logout'])) {
  session_destroy();
}

$db = mysqli_connect('localhost', 'john', 'pass1234', 'registration');

if ($db -> connect_errno) {
  echo "Failed to connect to MySQL: " . $db -> connect_error;
  exit();
}

mysqli_select_db($db, 'registration');

if(isset($_POST['username'])){

    // SQL injection prevention script
    $username = mysqli_real_escape_string($db, $_POST["username"]);
    $password = mysqli_real_escape_string($db, $_POST["password"]);
    // md5 decryption function
    $sql_query = "SELECT * FROM users WHERE username = '$username' AND password = '".md5($password)."'";

    /* Unsafe SQL code

    //$username = $_POST["username"];
    //$password = $_POST["password"];
    //$sql_query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    // Injection example:  123' OR username = 'max' AND '1'='1 

    */

    $result = mysqli_query($db, $sql_query);

    // user id
    $id_sql_query = "SELECT id FROM users WHERE username = '$username'";
    $result_user_id = mysqli_query($db, $id_sql_query);
    $row_user_id = $result_user_id->fetch_assoc();
    if(isset($row_user_id['id'])) {
      $user_id = (int) $row_user_id['id'];
    }

      // Limit to 1 result, otherwise - incorrect
      if (mysqli_num_rows($result) == 1) {

        session_start();
        $_SESSION["username"] = $username;
        $_SESSION["user_id"] = $user_id;
        $_SESSION["error"] = false;
        header("Location:./home.php");
        exit();
      } else {
        echo "<a class='fail' >The credentials you entered are incorrect.</a>";
      }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title> Login Page</title>
	<link rel="stylesheet" href="./style.css">
</head>
<body>
	<form class="box2" method="POST" action="#">
		<h1>Sign In</h1>
		<input type="text" name="username" placeholder="Username"/>
		<input type="password" name="password" placeholder="Password"/>
    <input class="form-input" type="submit" value="Sign In" class="btn-login"/>
    <div class = "register">
       <?php
           echo   "<a class='register' href='register.php'>Sign Up</a>";
       ?>
    </div>
	</form>
</body>
</html>
