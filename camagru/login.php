<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Welcome to Camagru</title>
        <link rel="stylesheet" type="text/css" href="style.css" />
    </head>
    <body>
        <div class="header">
            <div class="cama"><h1>Camagru <img src="./resources/carmra.jpeg" width="20" height="20" /></h1></div>
            <div class="logout"><a href="signup.php">Create account</a> <a href="index.php">Gallary</a> </div>
        </div>
        <div class="login">
            <h1 class="sec_h">Login</h1>
            <form action="server.php" method="post">
                <div class="container">
                    <input type="text" placeholder="Username" name="usr_id" required="" />
                    <input type="password" placeholder="Password" name="pswd" required="" />
                    <?php session_start(); if ($_SESSION['pswd'] == 'no'){echo "Incorrect username/password!";}?>
                    <br />
                    <br />
                    <nav class="links">
                        <a href="forgot.php">forgot password</a>
                    </nav>
                    <br />
                    <input class="log" type="submit" name="signin" value="Log ln" />
                </div>
            </form>
        </div>
        <div class="footer">
            <p class="copyright">&copy;rmaake 2017</p>
        </div>
    </body>
</html>