<?php
session_start();
require_once("../server.php");
require_once("../config/db_admin.php");
require_once("../config/db_setup.php");
require_once("../Control/validation.php");
require_once("../Control/profile.php");

$conn = db_conn($DB_NAME, $DB_USER, $DB_PASSWORD);
if (isset($_SESSION['login']))
    $_SESSION = FALSE;
if (isset($_POST['usr_id']))
{
    $_SESSION['login'] = "OK";
    if (!preg_match("/^[a-zA-Z ]*$/",$_POST['usr_id']))
        $_SESSION['err']['err_name'] = "This field can only contain alphabets and white spaces";
    if (strlen($_POST['usr_id']) > 15)
        $_SESSION['err']['err_usr_id'] = "Username cannot be greater than 15 characters";
}
if (!isset($_SESSION['err']) && $_SESSION['login'] == "OK")
{
    if (login($conn, $_POST) == TRUE)
    {
        $usr = $_SESSION['lst'];
        $_SESSION = FALSE;
        $_SESSION['login'] = $_POST['usr_id'];
        $_SESSION['pwd'] = hash('whirlpool', $_POST['pswd']);
        $_SESSION['lst'] = $usr;
        header("Location: ../browsing.php");
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="../css/forms_style.css">
</head>
<body id="login">
    <?php include("../header.php");?>
    <?php include("../footer.php");?>
    <div class="container">
        <div class="login">
            <h1 class="h">Log In</h1>
            <form action="login.php" method="post">
                <div>
                    <input type="text" placeholder="Username" name="usr_id" value="" required="" />
                    <?php session_start(); if (isset($_SESSION['err']['err_usr_id'])) echo $_SESSION['err']['err_usr_id']; else if (isset($_SESSION['err']['err_name'])) echo $_SESSION['err']['err_name']; ?>
                    <input type="password" placeholder="Password" name="pswd" required="" />
                    <?php session_start(); if (isset($_SESSION['err']['login'])) echo $_SESSION['err']['login'];?>
                    <br />
                    <br />
                    <nav class="links">
                        <a href="forgot_pswd.php">forgot password</a>
                    </nav>
                    <br />
                    <input class="submit" type="submit" name="login" value="Login" />
                </div>
            </form>
        </div>
    </div>
</body>
</html>