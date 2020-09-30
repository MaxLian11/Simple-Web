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
        echo "Welcome {$username}!";
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
	<div class="login_form">
		<form method="POST" action="#">
			<div class="form-input">
				<input type="text" name="username" placeholder="Username"/>	
			</div>
			<div class="form-input">
				<input type="password" name="password" placeholder="Password"/>
			</div>
			<div class="form-input">
				<input class="form-input" type="submit" type="submit" value="Log In" class="btn-login"/>
			</div>
		</form>
	</div>
</body>
</html>