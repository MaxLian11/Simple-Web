<?php

// Index.html is in a loop to test credentials
// include("index.html");

$db = mysqli_connect('localhost', 'root', '', 'registration');

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
    $sql_query = "SELECT * FROM users WHERE username = '".$username."' AND password = '".md5($password)."'";

    $result = mysqli_query($db, $sql_query);
      
      // Limit to 1 result, otherwise - incorrect
    if (mysqli_num_rows($result) == 1){

        session_start();
        $_SESSION["username"] = $username;
        header("Location:./welcome.php");
        exit();
    } else {
        echo "The credentials you entered are incorrect.";
        exit();
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
    <input class="form-input" type="submit" type="submit" value="Sign In" class="btn-login"/>
    <div class = "register">
       <?php
           echo   "<a class='register' href='register.php'>Sign Up</a>";
       ?>
    </div>
	</form>
</body>
</html>
