<?php



/*

The DB ('registratino') was created using this SQL query:

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL
)

*/

//session_start();
$errors = array();
$db = mysqli_connect('localhost', 'root', '', 'registration');

if ($db -> connect_errno) {
  echo "Failed to connect to MySQL: " . $db -> connect_error;
  exit();
}

// REGISTER USER
if (isset($_POST['register_user'])) {

    // Recieve data from the registration form
  $username = mysqli_real_escape_string($db, $_POST['username']);
  $email = mysqli_real_escape_string($db, $_POST['email']);
  $password_1 = mysqli_real_escape_string($db, $_POST['password_1']);
  $password_2 = mysqli_real_escape_string($db, $_POST['password_2']);

    // Check for correctness
  if (empty($username)) { array_push($errors, "Username is required"); }
  if (empty($email)) { array_push($errors, "Email is required"); }
  if (empty($password_1)) { array_push($errors, "Password is required"); }
  if ($password_1 != $password_2) { array_push($errors, "The two passwords do not match"); }

    // Check for existing email and username
  $user_check_query = " SELECT *
                        FROM users
                        WHERE username = '$username' OR email = '$email'";

  $result = mysqli_query($db, $user_check_query);
  $user = mysqli_fetch_assoc($result);

  if ($user) {
    if ($user['username'] === $username) {
      array_push($errors, "Username already exists");
    }

    if ($user['email'] === $email) {
      array_push($errors, "Email already exists");
    }
  }

    // If no errors -> register
  if (count($errors) == 0) {

    // Optional: Password encryption
  	// $password_1 = md5($password_1);

  	$query = "INSERT INTO users (username, email, password) VALUES('$username', '$email', '$password_1')";
  	mysqli_query($db, $query);
  	//$_SESSION['username'] = $username;
    //$_SESSION['success'] = "You are now logged in";

    // After successful registration
    header('location: login.php');
    //echo "Welcome {$username}!";
    //    exit();
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Registration Page</title>
  <link rel="stylesheet" href="./style.css">
</head>
<body>
  <form class="box" method="post" action="register.php">
        <h1>Sign Up</h1>      
        <input type="text" name="username" placeholder="Usename"/>
        <input type="email" name="email" placeholder="Email"/>
        <input type="password" name="password_1" placeholder="Password"/>
        <input type="password" name="password_2" placeholder="Repeat Password"/>
        <input class="submit" type="submit" class="btn" name="register_user" value="Submit"></button>
        <div class = "register">
           <?php 
               echo "<a class='register' href='login.php'>Sign in instead</a>"; 
           ?> 
    </div>
  </form>
</body>
</html>
