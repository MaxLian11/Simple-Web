<?php

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
  $firstname = mysqli_real_escape_string($db, $_POST['firstname']);
  $lastname = mysqli_real_escape_string($db, $_POST['lastname']);

    // Check for correctness
  if (empty($username)) { 
    array_push($errors, "Username is required"); 
    echo "<a class='fail2' >".end($errors)."</a>";
  }
  else if (empty($email)) { 
    array_push($errors, "Email is required"); 
    echo "<a class='fail2' >".end($errors)."</a>"; 
  }
  else if (empty($password_1)) { 
    array_push($errors, "Password is required"); 
    echo "<a class='fail2' >".end($errors)."</a>";
  }
  else if ($password_1 != $password_2) { 
    array_push($errors, "The two passwords do not match"); 
    echo "<a class='fail2' >".end($errors)."</a>";
  }

    // Check for existing email and username
  $user_check_query = " SELECT *
                        FROM users
                        WHERE username = '$username' OR email = '$email'";

  $result = mysqli_query($db, $user_check_query);
  $user = mysqli_fetch_assoc($result);

  if ($user) {
    if ($user['username'] === $username && count($errors) == 0) {
      array_push($errors, "This Username already exists");
      echo "<a class='fail2' >".end($errors)."</a>";
    }

    if ($user['email'] === $email && count($errors) == 0) {
      array_push($errors, "This Email already exists");
      echo "<a class='fail2' >".end($errors)."</a>";
    }
  }

    // If no errors -> register
  if (count($errors) == 0) {

    // md5 encryption function
    $query = "INSERT INTO users (username, firstname, lastname, email, password) VALUES('".$username."', '".$firstname."', '".$lastname."', '".$email."', '".md5($password_1)."')";

  	mysqli_query($db, $query);

    // Login after successful registration
    header('location: login.php');
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
        <input type="text" name="firstname" placeholder="First Name"/>
        <input type="text" name="lastname" placeholder="Last Name"/>
        <input type="email" name="email" placeholder="Email"/>
        <input type="password" name="password_1" placeholder="Password"/>
        <input type="password" name="password_2" placeholder="Repeat Password"/>
        <input class="submit" type="submit" class="btn" name="register_user" value="Submit"></button>
        <div class = "register">
           <?php
               echo "<a class='register' href='login.php'>Sign In</a>";
           ?>
    </div>
  </form>
</body>
</html>
