<?php
session_start();
opcache_reset();
require_once('./config/database.php');
require_once('./config/db_conn.php');
require_once('user_data.php');
$db_name = "mysql:host=localhost;dbname=camagru";
$conn = db_conn($db_name, $DB_USER, $DB_PASSWORD);
function validate($conn, $data)
{
    if ($_SESSION['login'] != $data['usr_id'])
    {
        $_SESSION['pswd'] = 'no';
        header('Location: login.php');
    }
    $pswd = $_SESSION['pswd'];
    if (check_user($conn, $data['usr_id'], $pswd, 1))
    {
        $_SESSION['clicked'] = $data['name'];
        header('Location: review.php');
    }
    else
    {
        $_SESSION['pswd'] = 'no';
        header('Location: login.php');
    }
}

if (isset($_GET['name']) && isset($_GET['usr_id']))
{
    $_POST = NULL;
    validate($conn, $_GET);
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Review</title>
	<link rel="stylesheet" type="text/css" href="review.css">
</head>
<body>
		<?php
            session_start();
            opcache_reset();
            require_once('./config/database.php');
            require_once('./config/db_conn.php');
            require_once('user_data.php');
            $db_name = "mysql:host=localhost;dbname=camagru";
            $conn = db_conn($db_name, $DB_USER, $DB_PASSWORD);
            if (!check_user($conn, $_SESSION['login'], $_SESSION['pswd'], 1))
                header("Location: login.php");
        ?>
	<div class="header">
		<div class="cama"><h1>Camagru <img src="./resources/carmra.jpeg" width="20" height="20" /></h1></div>
        <div class="logout"><a href="index.php">Gallary</a>   <a href="main.php">Camagru</a>   <a href="logout.php">Logout</a></div>
	</div>
	<div class="container">
		<div class="wrap">
			<div class="like">
				<img src = "<?php session_start(); echo $_SESSION['clicked'];?>" width="100%" height="100%"/>
			</div>
			<div class="likes">
				<iframe src="likes.php" width="100%" height="100%"></iframe>
			</div>
			<form action="add_review.php" method="post"><button class="buttn" type="submit" name="like" value="like">Like</button></form>
			<div class="comment">
				<iframe src="comments.php" width="100%" height="100%"></iframe>
			</div>
			<form action="add_review.php" method="post">
				<input class="text" type="text" placeholder="Add your comment." name="com"><br/>
				<button class="comm" type="submit" name="btn" value="com">Comment</button>
			</form>
		</div>
	</div>
	<div class="footer">
        <p class="copyright">&copy;rmaake 2017</p>
    </div>
</body>
</html>