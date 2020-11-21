<?php
// Index.html is in a loop to test credentials
// include("index.html");

$db = mysqli_connect('localhost', 'john', 'pass1234', 'registration');

if ($db -> connect_errno) {
  echo "Failed to connect to MySQL: " . $db -> connect_error;
  exit();
}

// sql for blog
mysqli_select_db($db, 'registration');
$sql_query_blog = "SELECT blog_id, subject, description, tags, date, user_id FROM blog";
$result_blog = mysqli_query($db, $sql_query_blog);
$row_blog = $result_blog->fetch_assoc();

// sql for comment
$sql_query_comment = "SELECT comment_id, comment, user_id, blog_id, date, reaction FROM comment";
$result_comment = mysqli_query($db, $sql_query_comment);
$row_comment = $result_comment->fetch_assoc();


if(!isset($_SESSION["username"])) {
    session_start();
}

if(isset($_POST['submit'])) {
    $a = $_POST['hiden'];
    button2($a);
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
        <div class="column_home">
            <form class="box3" method="POST" action="#">
                <input type="submit" name="test" id="test" value="Initialize Database" />
                <?php
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

<div id = "post-section">
    <div id="content-section">
        <?php
            $result_blog = mysqli_query($db, $sql_query_blog);

           
            if ($result_blog->num_rows > 0) {
                $reply_flag = 0;
                $reply_flag_2 = 0;
                $i = 0;
                while($row_blog = $result_blog->fetch_assoc()) {

                    $user = $row_blog['user_id'];
                    $sql = "SELECT username FROM users WHERE id = $user";
                    $res = mysqli_query($db, $sql);
                    $username = $res->fetch_assoc(); 
                    echo "<br></br>";
                    
                    echo "<div class='content-section-blog'>";
                    // post
                    $blog_id = $row_blog['blog_id'];
                    echo "<h1>"; echo $row_blog["subject"]; echo "</h1>";
                    echo "&nbsp"; echo "<p>"; echo $row_blog["description"]; echo "</p>";
                    echo "&nbsp"; echo "<p2>"; echo $row_blog["tags"]; echo "</p2>";
                    echo "<div align='right'> <b>Date posted: "; echo $row_blog["date"]; echo "</b>" ;
                    echo "</div>";
                    echo "<div align='right'> <b>Author: "; echo $username['username']; echo "</b>" ;
                    echo "</div>";
                    echo "<p2><p>&nbspComments:</p></p2>";

                    // comments for the post
                    $sql_query_comment = "SELECT comment_id, comment, user_id, blog_id, date, reaction FROM comment WHERE blog_id = $blog_id";
                    $sql_query_comment_username = "SELECT username FROM users INNER JOIN comment ON users.id = comment.user_id WHERE comment.blog_id = $blog_id";
                    
                    // get comment and date
                    $result_comment = mysqli_query($db, $sql_query_comment);
                    // get username
                    $result_comment_username = mysqli_query($db, $sql_query_comment_username);

                    if ($result_comment->num_rows > 0) {
                        while($row_comment = $result_comment->fetch_assoc()) {
                            $row_comment_username = $result_comment_username->fetch_assoc();
                            echo "<div class = 'commentary'>&nbspDate: "; echo $row_comment["date"] . "<br>&nbsp;User: " . $row_comment_username["username"] . "<br>&nbsp;Reaction: " .  $row_comment["reaction"] . "<br>&nbsp;&nbsp;&nbsp" . $row_comment["comment"] . "<br>" . "<br> </div>";
                        }
                    } else {
                        echo "No comments yet.";
                        echo "<br></br>";
                    }
                    echo "</div>";
                    button1($blog_id);
                    
                }
            } else {
                echo "<br></br><br></br><p class='fail'>No posts yet.</p>";
            }
                
            function button1($b) { 
                echo "<div class='comment-section'>";
                echo "<div class='comment-section-2'>";
                echo "  <form class='box4' action='home.php' method='post'>";
                echo "      <label for='menu'> Comment reaction: </label>";
                echo "      <select name='menu' id='select-reaction'>";
                echo "          <option  value='Positive'>Positive</option>";
                echo "          <option  value='Negative'>Negative</option>";
                echo "      </select>";
                echo "      <div>";
                echo "          <textarea name='comments' id='comments' style='font-family:sans-serif;font-size:1.2em; width: 90%; max-width: 90%; margin-top:10px;' placeholder=' Type your comment here'></textarea>";
                echo "      </div>";
                echo "      <div>";
                echo "          <input class='myButton' type='submit' value='Submit' name='submit'>";
                echo "          <input type='hidden' name='hiden' value="; echo $b; ">";
                echo "      </div>";
                echo"   </form>";
                echo "</div>";
                echo "</div>";
            }

            function button2($a){
                $conn = mysqli_connect('localhost', 'john', 'pass1234', 'registration');
                mysqli_select_db($conn, 'registration');

                extract($_POST);
                // comment. SQL injection prevention applied
                $msg = mysqli_real_escape_string($conn, $comments);
                // user id
                $id = $_SESSION["user_id"];
                // blog_id FK
                $blogID = $a;
                // comment date
                date_default_timezone_set("America/Los_Angeles");
                $date = date("Y/m/d");
                // reaction
                $selected = $_POST['menu'];
                
                $sql = "INSERT INTO `comment` (`comment`, `user_id`, `blog_id`, `date`, `reaction`) 
                VALUES ('$msg', '$id', '$blogID', '$date', '$selected');";
            
                if(!isset($_SESSION["username"])) {
                    session_start();
                }
                if (mysqli_query($conn, $sql)) {
                    echo "New record created successfully";
                    echo "<meta http-equiv='refresh' content='0'>";
                } else {
                    echo "<div class='error-trigger'>Error: <br>" . mysqli_error($conn); echo "</div>";
                }
                mysqli_close($conn);
            }
        ?>
    </div> 
</div>
</body>
</html>