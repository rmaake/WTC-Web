<?php
session_start();
require_once("../server.php");
require_once("../config/db_admin.php");
require_once("../config/db_setup.php");
require_once("../Control/validation.php");
require_once("../Control/profile.php");

$conn = db_conn($DB_NAME, $DB_USER, $DB_PASSWORD);
if (isset($_SESSION['veri']))
    $_SESSION = FALSE;
if (isset($_POST['usr_id']) && isset($_POST['code']))
{
    $_SESSION['veri'] = "OK";
    $_SESSION['usr_id'] = $_POST['usr_id'];
    if (!preg_match("/^[a-zA-Z ]*$/",$_POST['usr_id']))
        $_SESSION['err']['err_name'] = "This field can only contain alphabets and white spaces";
    if (strlen($_POST['usr_id']) > 15)
        $_SESSION['err']['err_usr_id'] = "Username cannot be greater than 15 characters";
    if (!preg_match("/^[0-9]*$/",$_POST['code']))
        $_SESSION['err']['err_code'] = "Confirmation code only contains numbers";
}
if (!isset($_SESSION['err']) && $_SESSION['veri'] == "OK")
{
    if (verify_reg($conn, $_POST) == TRUE)
        header("Location: ../profile/profile.php");
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Verify account</title>
        <link rel="stylesheet" type="text/css" href="../css/forms_style.css" />
    </head>
    <body id="login">
        <?php include("../header.php");?>
        <?php include("../footer.php");?>
        <div class="container">
            <div class="login">
                <h1 class="h">Verify account</h1>
                <form action="verify_email.php" method="post">
                    <div>
                        <label>Enter confirmation code from email.</label>
                        <input type="text" placeholder="Username" name="usr_id" value="<?php session_start(); echo $_SESSION['usr_id'];?>" required="" />
                        <?php session_start(); if (isset($_SESSION['err']['err_usr_id'])) echo $_SESSION['err']['err_usr_id']; else if (isset($_SESSION['err']['err_name'])) echo $_SESSION['err']['err_name']; ?>
                        <input type="text" placeholder="confirmation code" name="code" required="" />
                        <?php session_start(); if (isset($_SESSION['err']['err_code'])) echo $_SESSION['err']['err_code']; ?>
                        <br />
                        <br />
                        <input class="submit" type="submit" name="verify" value="Verify" />
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>