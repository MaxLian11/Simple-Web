<?php
// Index.html is in a loop to test credentials
// include("index.html");

$db = mysqli_connect('localhost', 'john', 'pass1234', 'registration');

if ($db -> connect_errno) {
  echo "Failed to connect to MySQL: " . $db -> connect_error;
  exit();
}

if(!isset($_SESSION["username"])) {
    session_start();
}

function insert($flag) {

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
<html>
<head>
    <title>Home page</title>
    <link rel="stylesheet" href="./style.css">
</head>

<body>
<div class = "login">
    <div class= "row">
        <div class = "column">
            <?php
                if(!isset($_SESSION["username"])) {
                    session_start();
                }
                echo '<p class="hello" style="display:inline">Welcome </p>';
                echo ($_SESSION["username"]);
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

<hr>
<div class = "filter-menu">
    <div id = "row"> <!-- filter menu row -->

        <div class = "column-filter"> <!-- filter menu columns -->
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <title>jQuery UI Datepicker - Default functionality</title>
            <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
            <link rel="stylesheet" href="/resources/demos/style.css">
            <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
            <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
            <script>
                $( function() {
                    $( "#datepicker" ).datepicker({ dateFormat: 'yy-mm-dd' }).val();
                } );
            </script>
            <form>List most active user on this day:  <input type="text" name="date-input" autocomplete="off" id="datepicker" placeholder ="Choose Date" onchange='this.form.submit()'>
            </form>
        </div>

        <div class = "column-filter"> <!-- filter menu columns -->
            <p>Followed by these users: (task 3)</p>
            <input class="form-input" type="submit" type="submit" value="Input 1"/>
            <input class="form-input" type="submit" type="submit" value="Input 1"/>
            <input class="form-input" type="submit" type="submit" value="Submit"/>
        </div>

        <div class = "column-filter"> <!-- filter menu columns -->
            <p>List Haters (task 4)</p>
        </div>

        <div class = "column-filter"> <!-- filter menu columns -->
            <p>List non active users (task 5)</p>
        </div>

        <div class = "column-filter"> <!-- filter menu columns -->
            <p>List non popular users (taks 6)</p>
        </div>

        <div class ="column-filter">
            <?php
                echo "<a class='hello' name='create-post' href='users.php'>Reset</a>";
            ?>
        </div>
    </div> <!-- end row div -->
</div> <!-- end filter-menu div -->
<hr>
<div id = "user-section">
    
    <?php

    if(isset($_GET["date-input"])){

        $selected_date = $_GET["date-input"];

        $sql_users = "SELECT number_of_posts, username, date
                        FROM( SELECT COUNT(*) AS number_of_posts, users.username, blog.date, RANK() OVER (ORDER BY number_of_posts DESC) AS rk
						            FROM blog JOIN users ON users.id = blog.user_id 
                                    WHERE blog.date = '$selected_date'
                                    GROUP BY users.username 
                                    ORDER BY number_of_posts DESC ) t
                        WHERE rk <= 1";

        $result_users = mysqli_query($db, $sql_users);
        while($row_user = $result_users->fetch_assoc()) {

            echo "<div class = 'section-user'>";
            echo    "<br>";
            echo    "<p style='color:white'>Username: <span class='hello'>" . $row_user['username'] . "</span></p>";
            echo    "<p style='color:white'>Number of blogs posted on ". $row_user['date'] .": <span class='hello'>" . $row_user['number_of_posts'] . "</span></p>";
            echo "</div>";
        }
    
    }
    else{
        $sql_users = "SELECT * FROM users";
        $result_users = mysqli_query($db, $sql_users);
        while($row_user = $result_users->fetch_assoc()) {

            // get user id to search for number of blogs
            $user_id = $row_user['id'];
            $sql_blogs = "SELECT COUNT(*) as blog_count FROM blog WHERE user_id = $user_id";
            $result_blogs = mysqli_query($db, $sql_blogs);
            $row_blogs = $result_blogs->fetch_assoc();

            echo "<div class = 'section-user'>";
            echo    "<br>";
            echo    "<p style='color:white'>Username: <span class='hello'>" . $row_user['username'] . "</span></p>";
            echo    "<p style='color:white'>Number of blogs posted: <span class='hello'>" . $row_blogs['blog_count'] . "</span></p>";
            echo "</div>";
        }
    }
    ?>
</div>
</body>
</html>