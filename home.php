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
    function postSection ($db) {
        // get sql query for blog
        mysqli_select_db($db, 'registration');

        // TASK 1. List all the blogs of user X, such that all the comments are positive for these blogs.
        if(isset($_GET["menu-positive-posts"]) && !($_GET["menu-positive-posts"] == " - None - ")) { 
            // store selected username in a variable
            $selected = $_GET["menu-positive-posts"];

            // query to find all blogs of the selected users which only have positive reviews
            $sql_query_blog = "SELECT DISTINCT blog.blog_id, blog.subject, blog.description, blog.tags, blog.date, blog.user_id   
                                FROM blog 
                                INNER JOIN comment USING (blog_id) 
                                INNER JOIN users ON blog.user_id = users.id 
                                WHERE users.username = '$selected' AND blog.blog_id NOT IN ( 
                                                            SELECT blog_id 
                                                            FROM comment 
                                                            WHERE reaction = 'negative' )
                                GROUP BY blog_id";
            
        }

        else if(isset($_GET["menu"]) && !($_GET["menu"] == " - None - ")) { 
            // store selected username in a variable
            $selected = $_GET["menu"];

            // query to find all blogs of the selected users which only have positive reviews
            $sql_query_blog = "SELECT DISTINCT blog.blog_id, blog.subject, blog.description, blog.tags, blog.date, blog.user_id   
                                    FROM blog
                                    INNER JOIN users ON blog.user_id = users.id 
                                    WHERE users.username = '$selected'
                                    GROUP BY blog_id";
            
        }

        // Display only blogs by users that are followed by a current user
        else if(isset($_GET["followed"])) {

            $current_user = $_SESSION['user_id'];
            $sql_query_blog = "SELECT * FROM blog JOIN follows USING(user_id) where follows.follower_id = $current_user";
        }

        // List all blogs
        else {
            $sql_query_blog = "SELECT blog_id, subject, description, tags, date, user_id FROM blog";
        }

        // sql for comment
        $sql_query_comment = "SELECT comment_id, comment, user_id, blog_id, date, reaction FROM comment";
        $result_comment = mysqli_query($db, $sql_query_comment);
        $row_comment = $result_comment->fetch_assoc();

        if(!isset($_SESSION["username"])) {
            session_start();
        }

        if(isset($_POST['submit'])) {
            $blog_id = $_POST['blog-id'];
            commentSubmit($blog_id);
        }

        $result_blog = mysqli_query($db, $sql_query_blog);
        
        if ($result_blog->num_rows > 0) {
            // loop to list all the blogs
            while($row_blog = $result_blog->fetch_assoc()) {
                // get info of the author of the blog
                $author = $row_blog['user_id'];
                $sql_author = "SELECT username, id FROM users WHERE id = $author";
                $result_author = mysqli_query($db, $sql_author);
                $author_info = $result_author->fetch_assoc();

                // get username and id of an author of a blog
                $author_username = $author_info['username'];
                $author_id = $author_info['id'];

                // get current user id
                $current_user_id = $_SESSION['user_id'];
                
                // post section BEGIN
                echo "<br><div class='content-section-blog'>";
                $blog_id = $row_blog['blog_id'];
                echo "<h1>" . $row_blog['subject'] . "</h1>";
                echo "&nbsp<p>" . $row_blog['description'] . "</p>";
                echo "&nbsp<p2>Tags:&nbsp " . $row_blog['tags'] . "</p2>";
                echo "<div align='right'> <b>Date posted: " . $row_blog['date'] . "</b></div>";
                // BEGIN dev
                echo "<div align='right'> <b>Author: " . $author_username . "</b><br>" ;

                // sql query to check if a current user follows the author of a blog
                $sql_follows_check = "SELECT * 
                                        FROM users INNER JOIN follows ON users.id = follows.user_id 
                                        WHERE follower_id = $current_user_id AND username = '$author_username'";
                $result_follows_check = mysqli_query($db, $sql_follows_check);

                // follow / unfollow button BEGIN
                
                echo "  <form action='home.php' method='post'>";
                if ($result_follows_check->num_rows > 0) { // if current user already follows an author
                    $follower_id = $result_follows_check->fetch_assoc();
                    echo "<input class='myButton' type='submit' value='Following' name='unfollow'></b>";
                }
                else{ // if current user doesn't follow an author
                    echo "<input class='myButton' type='submit' value='Follow' name='follow'></b>" ;
                }

                // keep track of what is the current author id
                echo "<input type='hidden' name='hidden-author-id' value=".$author_info['id'].">";
                echo"</form>";

                if(isset($_POST['follow']) || isset($_POST['unfollow'])) {
                    $db = mysqli_connect('localhost', 'john', 'pass1234', 'registration');
                    mysqli_select_db($db, 'registration');
                    $author_id = $_POST['hidden-author-id'];

                    if(isset($_POST['unfollow']))
                        $sql = "DELETE FROM `follows` WHERE `follows`.`user_id` = '$author_id' AND `follows`.`follower_id` = '$current_user_id'";
                    else
                        $sql = "INSERT INTO `follows` (`user_id`, `follower_id`) VALUES ($author_id, $current_user_id);";
                    
                    
                    if ($author_id == $current_user_id) {
                        echo "Error! You cannot subscribe to yourself.";
                        echo "<meta http-equiv='refresh' content='5'>";
                    } else if (mysqli_query($db, $sql)) {    
                        echo "<meta http-equiv='refresh' content='0'>";
                    } else {
                        echo "Error";
                        echo "<meta http-equiv='refresh' content='0'>";
                    }
                    mysqli_close($db);
                    break;
                }
                
                
                // Follow button END

                echo "</div>";
                // END dev
                // post section END

                // BEGIN comment section
                echo "<p2><p>&nbspComments:</p></p2>";

                // comments for the post
                $sql_query_comment = "SELECT comment_id, comment, user_id, blog_id, date, reaction FROM comment WHERE blog_id = $blog_id";
                // get comment and date 
                $result_comment = mysqli_query($db, $sql_query_comment);

                // get username
                $sql_query_comment_username = "SELECT username FROM users INNER JOIN comment ON users.id = comment.user_id WHERE comment.blog_id = $blog_id";
                $result_comment_username = mysqli_query($db, $sql_query_comment_username);

                // display comments
                if ($result_comment->num_rows > 0) {
                    while($row_comment = $result_comment->fetch_assoc()) {
                        $row_comment_username = $result_comment_username->fetch_assoc();
                        echo "<div class = 'commentary'>&nbspDate: " . $row_comment["date"] . "<br>&nbsp;User: " . $row_comment_username["username"] . "<br>&nbsp;Reaction: " .  $row_comment["reaction"] . "<br>&nbsp;&nbsp;&nbsp<p5 style='color:white'>" . $row_comment["comment"] . "<p5><br>" . "<br> </div>";
                    }
                } else { // display "no comments" if query result os empty
                    echo "No comments yet.<br></br>";
                }
                echo "</div>";
                leaveComment($blog_id);
                // END comment section
            }
        } 
        else { // display "No posts" if query result is empty
            echo "<br><br><p class='fail'>No posts yet.</p>";
        }
                    
    }


    function filterBlogs($db) {
        ?>
        <form>
            <p class="blog-filter-3">Display blogs published by user:<br>
                <select  name='menu' id='select-reaction' onchange='this.form.submit()'>;
                    <option value="None">
                        <?php if(isset($_GET["menu"])){ echo $_GET["menu"];} else { echo "Select User"; } ?>
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
                        if(isset($_GET["menu"]) && ($_GET["menu"] != " - None - ")){
                            echo "<option value=' - None - '> - None - </option>";
                        }
                    ?>
                </select>
            </p>
        </form>
            
        <?php
    } 

    function filterBlogsPositive($db) {
        ?>
        <form>
            <p class="blog-filter">Display blogs with only positive comments, published by user:<br>
                <select  name='menu-positive-posts' id='select-reaction' onchange='this.form.submit()'>;
                    <option value="None">
                        <?php if(isset($_GET["menu-positive-posts"])){ echo $_GET["menu-positive-posts"];} else { echo "Select User"; } ?>
                    </option>
                    <?php
                        $sql_users = "SELECT * FROM users";
                        $result_users = mysqli_query($db, $sql_users);
                        while($row_user = $result_users->fetch_assoc()) {
                            if(isset($_GET["menu-positive-posts"])) {
                                if($_GET["menu-positive-posts"] != $row_user["username"]) {
                                    echo "<option  value='".$row_user["username"]."'>".$row_user["username"]."</option>";
                                }
                            } else {
                                echo "<option  value='".$row_user["username"]."'>".$row_user["username"]."</option>";
                            }
                        }
                        if(isset($_GET["menu-positive-posts"]) && ($_GET["menu-positive-posts"] != " - None - ")){
                            echo "<option value=' - None - '> - None - </option>";
                        }
                    ?>
                </select>
            </p>
        </form>
            
        <?php
    } 

    function filterSubcriptions($db) {
        ?>
            <form>
                <p class="blog-filter-2"><br>
                    <input class="form-input-followed" type="submit" name="followed" value="Show Subscriptions" onclick='this.form.submit()' />
                    <br></br><a class='hello' name='reset' href='home.php'>Reset</a>
                </p>
            </form>
        <?php
    }

    function initializeDatabase($flag) {

        $db = new mysqli('localhost', 'john', 'pass1234', 'registration');

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
                mysqli_query($db, $query) or die('<div class="login_name">Problem in executing the SQL query <b>' . $query. '</b></div>');
                $query= '';
            }
        }

        if ($flag == 1) {
            echo '<div>Database was recreated</div>';
        } else {
            echo '<div>Database was initialized</div>';
        }
    }


    function leaveComment($blog_id) { 
        ?>
        <div class='comment-section'>
            <div class='comment-section-2'>
                <form class='box4' action='home.php' method='post'>
                    <label for='menu'> Comment reaction: </label>
                    <select name='menu' id='select-reaction'>
                        <option  value='Positive'>Positive</option>
                        <option  value='Negative'>Negative</option>
                    </select>
                    <div>
                        <textarea name='comments' id='comments' style='font-family:sans-serif;font-size:1.2em; width: 90%; max-width: 90%; margin-top:10px;' placeholder=' Type your comment here'></textarea>
                    </div>
                    <div>
                        <input class='myButton' type='submit' value='Submit' name='submit'>
                        <input type='hidden' name='blog-id' value='<?php echo $blog_id ?>'>
                    </div>
                </form>
            </div>
        </div>
        <?php
    }


    function commentSubmit($blog_id){
        $db = mysqli_connect('localhost', 'john', 'pass1234', 'registration');
        mysqli_select_db($db, 'registration');

        extract($_POST);
        // comment. SQL injection prevention applied
        $msg = mysqli_real_escape_string($db, $comments);
        // user id
        $id = $_SESSION["user_id"];
        // blog_id FK
        //$blog_id;
        // comment date
        date_default_timezone_set("America/Los_Angeles");
        $date = date("Y/m/d");
        // reaction
        $selected = $_POST['menu'];
        
        $sql = "INSERT INTO `comment` (`comment`, `user_id`, `blog_id`, `date`, `reaction`) 
        VALUES ('$msg', '$id', '$blog_id', '$date', '$selected');";

        if(!isset($_SESSION["username"])) {
            session_start();
        }
        if (mysqli_query($db, $sql)) {
            echo "New record created successfully";
            echo "<meta http-equiv='refresh' content='0'>";
        } else {
            echo "<div class='error-trigger'>Error: <br>" . mysqli_error($db) . "</div>";
        }
        mysqli_close($db);
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
                <div class = "column_home">
                    <?php
                        if(!isset($_SESSION["username"])) {
                            session_start();
                        }
                        echo "<p class='hello' style='display:inline'>Welcome </p>";
                        echo($_SESSION["username"]);
                        echo '!';
                    ?>
                </div>
                <div class ="column_home">
                    <?php
                        echo "<a class='hello' name='create-post' href='create-post.php'>New Post</a>";
                    ?>
                </div>
                <div class ="column_home">
                    <?php
                        echo "<a class='hello' name='users' href='users.php'>Users</a>";
                    ?>
                </div>
                <div class="column_home">
                    <form class="box3" method="POST" action="#">
                        <input type="submit" name="test" id="test" value="Initialize Database" />
                        <?php
                            if(array_key_exists('test',$_POST)){
                                $db = new mysqli('localhost', 'john', 'pass1234', 'registration');
                                $check = mysqli_query($db," SELECT * FROM blog ") ;
                                $flag = 0;
                            if ($check !== False) { $flag = 1; }
                                initializeDatabase($flag);
                            }
                        ?>
                    </form>
                </div>
                <div class ="column_home">
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
        <br>
        <div style="float:right;margin-right:30px;">
                <?php filterBlogsPositive($db); ?>
        </div>
        <div style="float:right;margin-right:30px;margin-top:250px;">
            <?php filterSubcriptions($db); ?>
        </div>
        <div style="float:right;margin-right:-255px;margin-top:140px;">
            <?php filterBlogs($db); ?>
        </div>

        <div id = "post-section">
            <div id="content-section">
                <?php
                    postSection($db);
                ?>
            </div> 
        </div>
    </body>
</html>