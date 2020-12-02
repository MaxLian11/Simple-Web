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

function listUsers($db) {

    // TASK 2. List the users who posted the most number of blogs on 10/10/2020; if there is a tie, list all the users who have a tie. 
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

        usersHeader("Most actice user/users on <span style='color:#2eec71;'>".$selected_date."<span>");
        if ($result_users->num_rows > 0) {
            
            while($row_user = $result_users->fetch_assoc()) {

                echo "<div class = 'section-user'>";
                echo    "<br>";
                echo    "<p style='color:white'>Username: <span class='hello'>" . $row_user['username'] . "</span></p>";
                echo    "<p style='color:white'>Number of blogs posted on <span class='hello'>". $row_user['date'] ."</span>:&nbsp&nbsp". $row_user['number_of_posts'] . "</p>";
                echo "</div>";
            } 
        } else { // display "No users to display" if query result is empty
            echo "<div class = 'section-user'>";
            echo "<br><br><p>No blogs were posted on <span class='hello'>".$selected_date."</span></p>";
            echo "</div>";
        }
        
    
    }

    // TASK 3. List the users who are followed by both X and Y. Usernames X and Y are inputs from the user.
    else if (isset($_GET["submit-users"]) && ($_GET["user-1"] != "none") && ($_GET["user-2"] != "none")) {
        
        $selected_user_1 = $_GET["user-1"];
        $selected_user_2 = $_GET["user-2"];

        $sql_toId_1 = "SELECT id FROM users WHERE username = '$selected_user_1';";
        $result_user_1 = mysqli_query($db, $sql_toId_1);
        $row_user_1 = $result_user_1->fetch_assoc();
        $user_id_1 = $row_user_1['id'];

        $sql_toId_2 = "SELECT id FROM users WHERE username = '$selected_user_2';";
        $result_user_2 = mysqli_query($db, $sql_toId_2);
        $row_user_2 = $result_user_2->fetch_assoc();
        $user_id_2 = $row_user_2['id'];
        
        $sql_users = "SELECT *
                        FROM users 
                        WHERE id IN ( SELECT user_id AS id FROM follows WHERE follower_id = '$user_id_1') 
                        AND id IN ( SELECT user_id AS id FROM follows WHERE follower_id = '$user_id_2');";

        $result_users = mysqli_query($db, $sql_users);
        
        usersHeader("List of users who are followed by <span style='color:#2eec71;'>".$selected_user_1."</span> and <span style='color:#2eec71;'>".$selected_user_2."</span>");
        if ($result_users->num_rows > 0) {
            while($row_user = $result_users->fetch_assoc()) {

                echo "<div class = 'section-user'>";
                echo    "<br>";
                echo    "<p style='color:white'>Username: <span class='hello'>" . $row_user['username'] . "</span></p>";
                echo "</div>";
            }
        } else {
            echo "<div class = 'section-user'>";
            echo "<br><br><p>No users are followed by <span style='color:#2eec71;'>".$selected_user_1."</span> and <span style='color:#2eec71;'>".$selected_user_2."</span></p>";
            echo "</div>";
        }
    }

    // TASK 4. Display all the users who never posted a blog.
    else if (isset($_GET["non-active"])) {

        $sql_users = "SELECT * FROM users WHERE id NOT IN( SELECT user_id FROM blog );";

        $result_users = mysqli_query($db, $sql_users);
        usersHeader("List of users who never posted anything");
        if ($result_users->num_rows > 0) {
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
        } else {
            echo "<div class = 'section-user'>";
            echo "<br><br><p>No users found</p>";
            echo "</div>";
        }
    }

    // TASK 5. Display all the users who posted some comments, but each of them is negative.
    else if (isset($_GET["haters"])) {

        $sql_users = "SELECT id AS user_id, users.* 
                        FROM users WHERE id IN( SELECT DISTINCT (comment.user_id) 
                                                FROM comment 
                                                INNER JOIN blog USING(blog_id) 
                                                WHERE reaction = 'negative' AND comment.user_id NOT IN( SELECT DISTINCT (comment.user_id) 
                                                                                                        FROM comment INNER JOIN blog USING(blog_id) 
                                                                                                        WHERE reaction = 'positive' ) )";
                                                                                    

        $result_users = mysqli_query($db, $sql_users);

        usersHeader("List of users who only leave negative comments");
        if ($result_users->num_rows > 0) {
            while($row_user = $result_users->fetch_assoc()) {

                echo "<div class = 'section-user'>";
                echo    "<br>";
                echo    "<p style='color:white'>Username: <span class='hello'>" . $row_user['username'] . "</span></p>";
                echo "</div>";
            }
        } else {
            echo "<div class = 'section-user'>";
            echo "<br><br><p>No users found</p>";
            echo "</div>";
        }
    }

    //  TASK 6. Display those users such that all the blogs they posted so far never received any negative comments.
    else if (isset($_GET["respected"])) {

        $sql_users = "SELECT DISTINCT(users.id) as user_id, users.* 
                        FROM users INNER JOIN blog ON blog.user_id = users.id 
                        WHERE user_id IN (SELECT blog.user_id 
                                            FROM blog INNER JOIN comment ON comment.blog_id = blog.blog_id 
                                            WHERE blog.user_id NOT IN (SELECT DISTINCT(blog.user_id) 
                                                                        FROM comment INNER JOIN blog ON comment.blog_id = blog.blog_id 
                                                                        WHERE reaction = 'Negative'))";
                                                                                                

        $result_users = mysqli_query($db, $sql_users);

        usersHeader("List of users who only receive positive comments to their posts");
        if ($result_users->num_rows > 0) {
            while($row_user = $result_users->fetch_assoc()) {

                echo "<div class = 'section-user'>";
                echo    "<br>";
                echo    "<p style='color:white'>Username: <span class='hello'>" . $row_user['username'] . "</span></p>";
                echo "</div>";
            }
        } else {
            echo "<div class = 'section-user'>";
            echo "<br><br><p>No users found</p>";
            echo "</div>";
        }
    }
    
    // No filters applied
    else {
        $sql_users = "SELECT * FROM users";
        $result_users = mysqli_query($db, $sql_users);
        
        usersHeader("List of all users");
        while($row_user = $result_users->fetch_assoc()) {

            // get user id to search for number of blogs
            $user_id = $row_user['id'];
            $sql_blogs = "SELECT COUNT(*) as blog_count FROM blog WHERE user_id = $user_id";
            $result_blogs = mysqli_query($db, $sql_blogs);
            $row_blogs = $result_blogs->fetch_assoc();

            echo "<div class = 'section-user'>";
            echo    "<p style='color:white'>Username: <span class='hello'>" . $row_user['username'] . "</span></p>";
            echo    "<p style='color:white'>Number of blogs posted: <span class='hello'>" . $row_blogs['blog_count'] . "</span></p>";
            echo "</div>";
        }
    }
}

function usersHeader($str) {
    echo "<div class = 'section-user'>";
        echo    "<br>";
        echo    "<p style='color:white;text-align:center;font-size:25px;'>".$str."</p>";
        echo "</div>";
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

        <!--  FILTER 1 -->
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
            <form id="date-picker">List the most active user/users on this day:  <input type="text" name="date-input" autocomplete="off" id="datepicker" placeholder ="Choose Date" onchange='this.form.submit()'>
            </form>
        </div>
        <!--  FILTER 1 -->

        <!--  FILTER 2 -->
        <div class = "column-filter"> <!-- filter menu columns -->
            <p>Followed by these users:</p>
            <form class="submit-fliter">
            <select  name='user-1' id='select-user'>;
                <option value="none">
                    <?php if(isset($_GET["menu"])){ echo $_GET["menu"];} else { echo "Select User 1"; } ?>
                </option>
                <?php
                    $sql_users = "SELECT * FROM users";
                    $result_users = mysqli_query($db, $sql_users);
                    while($row_user = $result_users->fetch_assoc()) {
                        if(isset($_GET["menu"])) {
                            if($_GET["menu"] != $row_user["username"]) {
                                echo "<option  value='".$row_user["username"]."'>".$row_user["username"]."</option>";
                            }
                        } else {
                            echo "<option  value='".$row_user["username"]."'>".$row_user["username"]."</option>";
                        }
                    }
                ?>
            </select>
            <select  name='user-2' id='select-user'>;
                <option value="none">
                    <?php if(isset($_GET["menu"])){ echo $_GET["menu"];} else { echo "Select User 2"; } ?>
                </option>
                <?php
                    $sql_users = "SELECT * FROM users";
                    $result_users = mysqli_query($db, $sql_users);
                    while($row_user = $result_users->fetch_assoc()) {
                        if(isset($_GET["menu"])) {
                            if($_GET["menu"] != $row_user["username"]) {
                                echo "<option  value='".$row_user["username"]."'>".$row_user["username"]."</option>";
                            }
                        } else {
                            echo "<option  value='".$row_user["username"]."'>".$row_user["username"]."</option>";
                        }
                    }
                ?>
            </select> <br>
            <input class="form-input" type="submit" name="submit-users" value="Show" onchange='this.form.submit()' /></form>
        </div>
        <!--  FILTER 2 -->

        <!--  FILTER 3 -->
        <div class = "column-filter"> <!-- filter menu columns -->
            <p>List non active users</p>
            <form class="submit-fliter"><input class="form-input" type="submit" name="non-active" value="Show" onchange='this.form.submit()' /></form>
        </div>
        <!--  FILTER 3 -->  

        <!--  FILTER 4 -->  
        <div class = "column-filter"> <!-- filter menu columns -->
            <p>List Haters</p>
            <form class="submit-fliter"><input class="form-input" type="submit" name="haters" value="Show" onchange='this.form.submit()' /></form>
        </div>
        <!--  FILTER 4 --> 
        
        <!--  FILTER 5 -->  
        <div class = "column-filter"> <!-- filter menu columns -->
            <p>List Respected Users</p>
            <form class="submit-fliter"><input class="form-input" type="submit" name="respected" value="Show" onchange='this.form.submit()' /></form>
        </div>
        <!--  FILTER 5 -->  

        <div class ="column-filter">
            <?php
                echo "<a class='hello' name='reset' href='users.php'>Reset</a>";
            ?>
        </div>
    </div> <!-- end row div -->
</div> <!-- end filter-menu div -->
<hr>
<div id = "user-section">
    <?php    
        listUsers($db);    
    ?>
</div>
</body>
</html>