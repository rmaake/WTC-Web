<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Reset password</title>
        <link rel="stylesheet" type="text/css" href="style.css" />
    </head>
    <body>
        <div class="header">
            <a href="index.php">Login</a>
        </div>
        <div class="login">
            <h1 class="sec_h">Reset password</h1>
            <form action="server.php" method="post">
                <div class="container">
                    <label>Enter confirmation code from email and new password.</label>
                    <input type="text" placeholder="confirmation code" name="code" required="" />
                    <?php session_start(); if ($_SESSION['veri_code'] == 'nop') {echo "Incorrect verification code!";}?>
                    <input type="password" placeholder="Password" name="pswd" required="" />
                    <input type="password" placeholder="Cormfirm password" name="rpswd" required="" />
                    <?php session_start(); if ($_SESSION['veri_code'] == 'no') {echo "Passwords do not match!";} else if ($_SESSION['veri_code'] == 'nope'){echo "Password cannot be less than 6 characters long.";}?>
                    <br />
                    <br />
                    <input class="log" type="submit" name="reset" value="Reset" />
                </div>
            </form>
        </div>
        <div class="footer">
            <p class="copyright">&copy;rmaake 2017</p>
        </div>
    </body>
</html>