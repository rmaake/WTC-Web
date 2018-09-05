<?php
session_start();
require_once("../server.php");
require_once("../config/db_admin.php");
require_once("../config/db_setup.php");
require_once("../Control/validation.php");
require_once("../Control/profile.php");

$conn = db_conn($DB_NAME, $DB_USER, $DB_PASSWORD);
if (isset($_SESSION['reg']))
    $_SESSION = FALSE;
if (isset($_POST['f_name']) && isset($_POST['l_name']) && isset($_POST['usr_id']) && isset($_POST['email']) && isset($_POST['gen']) && isset($_POST['dob']) && isset($_POST['pswd']) && isset($_POST['rpswd']))
{
    $_SESSION['f_name'] = $_POST['f_name'];
    $_SESSION['l_name'] = $_POST['l_name'];
    $_SESSION['usr_id'] = $_POST['usr_id'];
    $_SESSION['email'] = $_POST['email'];
    $_SESSION['reg'] = "OK";
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
        $_SESSION['err']['err_email'] = "Invalid email address";
    if (strlen($_POST['email']) > 50)
        $_SESSION['err']['err_email'] = "Sorry, email cannot be more than 50 characters.";
    if (!preg_match("/^[a-zA-Z ]*$/",$_POST['f_name']) || !preg_match("/^[a-zA-Z ]*$/",$_POST['l_name']) || !preg_match("/^[a-zA-Z ]*$/",$_POST['usr_id']))
        $_SESSION['err']['err_name'] = "This field can only contain alphabets and white spaces";
    if (strlen($_POST['usr_id']) > 15)
        $_SESSION['err']['err_usr_id'] = "Username cannot be greater than 15 characters";
    if ($_POST['pswd'] !== $_POST['rpswd'])
        $_SESSION['err']['err_pswd'] = "Passwords do not match";
    else if (strlen($_POST['pswd']) < 6)
        $_SESSION['err']['err_pswd'] = "Password cannot be less than six characters long";
    if (!$_POST['dob'])
        $_SESSION['err']['err_dob'] = "Please enter your date of birth";
}
if (!isset($_SESSION['err']) && $_SESSION['reg'] == "OK")
{
    if (register($conn, $_POST) === TRUE)
        header("Location: verify_email.php");
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Sign Up</title>
    <link rel="stylesheet" type="text/css" href="../css/forms_style.css">
</head>
<body id="login">
    <?php include("../header.php");?>
    <?php include("../footer.php");?>
    <div class="container">
        <div class="login">
            <h1 class="h">Create Account</h1>
            <form action="signup.php" method="post">
                <div>
                    <input type="text" placeholder="First name" name="f_name" value="<?php session_start(); echo $_SESSION['f_name'];?>" required="" />
                    <?php session_start(); if (isset($_SESSION['err']['err_name'])) echo $_SESSION['err']['err_name'];?>
                    <input type="text" placeholder="Surname" name="l_name" value="<?php session_start(); echo $_SESSION['l_name'];?>" required="" />
                    <?php session_start(); if (isset($_SESSION['err']['err_name'])) echo $_SESSION['err']['err_name'];?>
                    <input type="text" placeholder="Username" name="usr_id" value="<?php session_start(); echo $_SESSION['usr_id'];?>" required="" />
                    <?php session_start(); if (isset($_SESSION['err']['err_usr_id'])) echo $_SESSION['err']['err_usr_id'];?>
                    <input type="Email" placeholder="Email address" name="email" value="<?php session_start(); echo $_SESSION['email'];?>" required="" />
                    <?php session_start(); if (isset($_SESSION['err']['err_email'])) echo $_SESSION['err']['err_email'];?>
                    <label>Gender:</label><select name="gen">
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                    <br/>
                    <label>Date of Birth<input type="date" name="dob" value=""/></label>
                    <?php session_start(); if (isset($_SESSION['err']['err_dob'])) echo $_SESSION['err']['err_dob'];?>
                    <input type="password" placeholder="Password" name="pswd" required="" />
                    <input type="password" placeholder="Cormfirm password" name="rpswd" required="" />
                    <?php session_start(); if (isset($_SESSION['err']['err_pswd'])) echo $_SESSION['err']['err_pswd'];?>
                    <br />
                    <br />
                    <input class="submit" type="submit" name="reg" value="CREATE"/>
                </div>
            </form>
        </div>
    </div>
</body>
</html>