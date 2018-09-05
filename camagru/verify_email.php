<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Verify account</title>
        <link rel="stylesheet" type="text/css" href="style.css" />
    </head>
    <body>
        <div class="header">
            <div class="cama"><h1>Camagru <img src="./resources/carmra.jpeg" width="20" height="20" /></h1></div>
            <div class="logout"><a href="login.php">Login</a> <a href="index.php">Home</a> </div>
        </div>
        <div class="login">
            <h1 class="sec_h">Verify account</h1>
            <form action="server.php" method="post">
                <div class="container">
                    <label>Enter confirmation code from email.</label>
                    <input type="text" placeholder="confirmation code" name="code" required="" />
                    <?php session_start(); if ($_SESSION['err'] == 'err') {echo "Incorrect verification code!";}?>
                    <br />
                    <br />
                    <input class="log" type="submit" name="verify" value="verify" />
                </div>
            </form>
        </div>
        <div class="footer">
            <p class="copyright">&copy;rmaake 2017</p>
        </div>
    </body>
</html>