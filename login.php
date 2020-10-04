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

    $username = $_POST['username'];
    $password = $_POST['password'];
    $sql_query = "	SELECT *
					FROM users
					WHERE username = '".$username."' AND password = '".$password."'";

    $result = mysqli_query($db, $sql_query);

    if (mysqli_num_rows($result) == 1){
        header("Location:./welcome.html");
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
	<form class="box" method="POST" action="#">
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
