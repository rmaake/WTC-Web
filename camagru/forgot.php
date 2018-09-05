<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Reset password</title>
        <link rel="stylesheet" type="text/css" href="style.css" />
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
            if (check_user($conn, $_SESSION['login'], $_SESSION['pswd'], 1) === FALSE)
                header("Location: index.php");
        ?>
        <div class="header">
            <div class="cama"><h1>Camagru <img src="./resources/carmra.jpeg" width="20" height="20" /></h1></div>
            <div class="logout"><a href="index.php" text-color = "white">Home</a> <a href="login.php">Login</a></div>
        </div>
        <div class="login">
            <h1 class="sec_h">Forgot password</h1>
            <form action="server.php" method="post">
                <div class="container">
                    <label>Enter username and proceed to verify.</label>
                    <input type="text" placeholder="Username" name="usr_id" required="" />
                    <?php session_start(); if ($_SESSION['veri_code'] === FALSE) {echo "Username specified doesn't exist!";}?>
                    <input class="log" type="submit" name="veri" value="Verify" />
                </div>
            </form>
        </div>
        <div class="footer">
            <p class="copyright">&copy;rmaake 2017</p>
        </div>
    </body>
</html>