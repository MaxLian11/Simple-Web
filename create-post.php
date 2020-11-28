<?php

$db = mysqli_connect('localhost', 'john', 'pass1234', 'registration');
if ($db -> connect_errno) {
  echo "Failed to connect to MySQL: " . $db -> connect_error;
  exit();
}
mysqli_select_db($db, 'registration');

if(isset($_POST['submit'])) {
    createPost();
}

function createPost() {
    $db = mysqli_connect('localhost', 'john', 'pass1234', 'registration');
    mysqli_select_db($db, 'registration');
    
    if(!empty($_POST['subject'])) {
        $subject = mysqli_real_escape_string($db, $_POST["subject"]);
        $description = mysqli_real_escape_string($db, $_POST["description"]);
        $tags = mysqli_real_escape_string($db, $_POST["tag"]);
    }
    date_default_timezone_set("America/Los_Angeles");
    $date = date("Y/m/d");
    session_start();
    $user_id = $_SESSION["user_id"];

    $sql_blog_post = "INSERT INTO `blog` (`subject`, `description`, `tags`, `user_id`, `date`) VALUES ('$subject', '$description', '$tags', '$user_id', '$date');";


    if (mysqli_query($db, $sql_blog_post)) {
        echo "New record created successfully";
        header("Location:./home.php");
    } else {
        echo "<div class='error-trigger'>Error: <br>" . mysqli_error($db)."</div>";
    }
    //echo "<meta http-equiv='refresh' content='0'>";
    mysqli_close($db);
}

function initializeDatabase($flag) {

    $conn = new mysqli('localhost', 'john', 'pass1234', 'registration');

    $query = '';
    $sqlScript = file('blog.sql');
    foreach ($sqlScript as $line)	{

        $startWith = substr(trim($line), 0 ,2);
        $endWith = substr(trim($line), -1 ,1);

        if (empty($line) || $startWith == '--' || $startWith == '/*' || $startWith == '//') {
            continue;
        }

        $query = $query . $line;
        if ($endWith == ';') {
            mysqli_query($conn,$query) or die('<div class="login_name">Problem in executing the SQL query <b>' . $query. '</b></div>');
        $query= '';
        }

    }

    if ($flag == 1) {
        echo '<div>Database was recreated</div>';
    } else {
        echo '<div>Database was initialized</div>';
    }
}


?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Blog Page</title>
        <link rel="stylesheet" href="style.css">
    </head>

    <body>
        <div class = "login">
            <div class= "row">
                <div class = "column">
                    <?php
                        if(!isset($_SESSION["username"]))
                        {
                          session_start();
                        }
                        echo '<p class="hello" style="display:inline">Welcome </p>';
                        echo($_SESSION["username"]);
                        echo '!';
                    ?>
                </div>
                
                <div class ="column">
                    <?php
                        echo "<a class='hello' name='create-post' href='home.php'>Home</a>";
                    ?>
                </div>
                <div class="column">
                    <form class="box3" method="POST" action="#">
                        <input type="submit" name="test" id="test" value="Initialize Database" />
                        <?php
                            
                            if(array_key_exists('test',$_POST)){
                                $conn = new mysqli('localhost', 'john', 'pass1234', 'registration');
                                $check = mysqli_query($conn," SELECT * FROM blog ") ;
                                $flag = 0;
                            if ($check !== False) { $flag = 1; }
                                initializeDatabase($flag);
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
    <div class="postblog"><h1>Create New Post</h1></div>
        <div class="main"> 
        <form class="box4" method="POST">
        <br></br>
            <div class="row">
                <div class="col-25">
                    <h1 class="name">Subject</h1>
                </div>
                <div class="col-75">
                    <input class="subject" type="text" name="subject" placeholder="Post subject">
                </div>
            </div>
            <br></br>
            <div class="row">
                <div class="col-25">
                    <h1 class="name">Description</h1>
                </div>
                <div class="col-75">
                    <textarea class="description" type="text" name="description" placeholder="Post description"></textarea>
                </div>
            </div>
            <br></br>
            <div class="row">
                <div class="col-25">
                    <h1 class="name">Tag</h1>
                </div>
                <div class="col-75">
                    <input class="tag" type="text" name="tag" placeholder="Tags (separated by coma)">
                </div>
            </div>
            <br></br>
                <input class='submit-post' type='submit' value='Submit' name='submit'>";
            <br></br>
            </form>
        </div>
  </body>
  <br></br>
  <br></br>
</html>