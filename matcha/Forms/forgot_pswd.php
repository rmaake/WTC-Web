<?php
session_start();
require_once("../server.php");
require_once("../config/db_admin.php");
require_once("../config/db_setup.php");
require_once("../Control/validation.php");
require_once("../Control/profile.php");

$conn = db_conn($DB_NAME, $DB_USER, $DB_PASSWORD);
if (isset($_SESSION['forgot']))
    $_SESSION = FALSE;
if (isset($_POST['usr_id']))
{
    $_SESSION['forgot'] = "OK";
    $_SESSION['usr_id'] = $_POST['usr_id'];
    if (!preg_match("/^[a-zA-Z ]*$/",$_POST['usr_id']))
        $_SESSION['err']['err_name'] = "This field can only contain alphabets and white spaces";
    if (strlen($_POST['usr_id']) > 15)
        $_SESSION['err']['err_usr_id'] = "Username cannot be greater than 15 characters";
}
if (!isset($_SESSION['err']) && $_SESSION['forgot'] == "OK")
{
    if (forgot($conn, $_POST) == TRUE)
        header("Location: reset_passwd.php");
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Reset password</title>
        <link rel="stylesheet" type="text/css" href="../css/forms_style.css" />
    </head>
    <body id="login">
        <?php include("../header.php");?>
        <?php include("../footer.php");?>
        <div class="container">
            <div class="login">
                <h1 class="h">Forgot password</h1>
                <form action="forgot_pswd.php" method="post">
                    <div>
                        <label>Enter username and proceed to verify.</label>
                        <input type="text" placeholder="Username" name="usr_id" required="" />
                        <?php session_start(); if (isset($_SESSION['err']['err_usr_id'])) echo $_SESSION['err']['err_usr_id']; else if (isset($_SESSION['err']['err_name'])) echo $_SESSION['err']['err_name']; ?>
                        <input class="submit" type="submit" name="forgot" value="Reset" />
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>