<?php
session_start();
require_once("../server.php");
require_once("../config/db_admin.php");
require_once("../config/db_setup.php");
require_once("../Control/validation.php");
require_once("../Control/profile.php");

$conn = db_conn($DB_NAME, $DB_USER, $DB_PASSWORD);
function get_data($conn, $usr, $pswd)
{
    if (check_user($conn, $usr, $pswd, 2) === TRUE)
    {
        try
        {
            $sql = "SELECT * FROM users WHERE User_Id='$usr'";
            foreach($conn->query($sql) as $row)
            {
                $_SESSION['f_name'] = $row['First_Name'];
                $_SESSION['l_name'] = $row['Last_Name'];
                $_SESSION['email'] = $row['Email'];
                $_SESSION['gen'] = $row['Gender'];
                $_SESSION['dob'] = $row['DOB'];
                $_SESSION['sex'] = $row['Sexuality'];
                $_SESSION['bio'] = $row['Biography'];
                $_SESSION['int'] = explode(",", $row['Interests']);
            }
        }
        catch(PDOException $e)
        {
            echo $e->getMessage();
        }
        return (TRUE);
    }
    return (FALSE);
}
if (isset($_POST['f_name']) && isset($_POST['l_name']) && isset($_POST['email']) && isset($_POST['dob']))
{
    if (!isset($_POST['gen']))
        $_POST['gen'] = $_SESSION['gen'];
    if (!isset($_POST['sex']))
        $_POST['sex'] = $_SESSION['sex'];
    $_SESSION['f_name'] = $_POST['f_name'];
    $_SESSION['l_name'] = $_POST['l_name'];
    $_SESSION['email'] = $_POST['email'];
    $_SESSION['up'] = "OK";
    $_SESSION['err'] = FALSE;
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
        $_SESSION['err']['err_email'] = "Invalid email address";
    if (strlen($_POST['email']) > 50)
        $_SESSION['err']['err_email'] = "Sorry, email cannot be more than 50 characters.";
    if (!preg_match("/^[a-zA-Z ]*$/",$_POST['f_name']) || !preg_match("/^[a-zA-Z ]*$/",$_POST['l_name']))
        $_SESSION['err']['err_name'] = "This field can only contain alphabets and white spaces";
    if (!$_POST['dob'])
        $_SESSION['err']['err_dob'] = "Please enter your date of birth";
}

if (get_data($conn, $_SESSION['login'], $_SESSION['pwd']) === TRUE)
{
    if ($_SESSION['err'] == FALSE && $_SESSION['up'] == "OK")
    {
        $_SESSION['up'] = FALSE;
        if (update_data($conn, $_POST, $_SESSION['login']) === TRUE)
            header("Location: profile.php");
    }
}
else
    header("Location: ../Forms/login.php");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Sign Up</title>
    <link rel="stylesheet" type="text/css" href="../css/pro_style.css">
</head>
<body id="login">
    <div class="header">
        <div class="sitename">Matcha</div>
        <div>
            <?php
                session_start();
                if (isset($_SESSION['login']) && isset($_SESSION['pwd']))
                    echo "<a href=\"../Control/logout.php\">LogOut</a><a id=\"notif\" class=\"head\" href=\"../notify.php\">Notifications</a><a href=\"../browsing.php\">Home</a>";
                else
                {
                    header("Location: ../Forms/login.php");
                    return (FALSE);
                }
            ?>
        </div>
    </div>
    <?php include("../footer.php");?>
    <div class="container">
        <h1 class="h">Update Profile</h1><br/>
        <a href="visit.php"><button class="update" type="submit" name="visit">Visits</button></a>
        <a href="profile.php"><button class="update" type="submit" name="userP">Profile</button></a>
        <hr class="horizontal" />
        <div class="login">
            <div class="pro_pic">
                    <img class="pic" src="<?php session_start(); if (isset($_SESSION['img'])) echo $_SESSION['img']; else echo "../resources/avatar.jpeg"?>">
                </div>
            <form action="pro_form.php" method="post">
                <div>
                    <input type="text" placeholder="First name" name="f_name" value="<?php session_start(); if (isset($_SESSION['f_name'])) echo $_SESSION['f_name'];?>" required="" />
                    <input type="text" placeholder="Surname" name="l_name" value="<?php session_start(); if (isset($_SESSION['l_name'])) echo $_SESSION['l_name'];?>" required="" />
                    <?php session_start(); if (isset($_SESSION['err']['err_name'])) echo $_SESSION['err']['err_name'];?>
                    <input type="Email" placeholder="Email address" name="email" value="<?php session_start(); if (isset($_SESSION['email'])) echo $_SESSION['email'];?>" required="" />
                    <?php session_start(); if (isset($_SESSION['err']['err_email'])) echo $_SESSION['err']['err_email'];?>
                    <select name="gen" required="">
                        <option  value="<?php session_start(); if (isset($_SESSION['gen']) && !empty($_SESSION['sex'])) echo $_SESSION['gen']; else echo "none";?>" selected disabled><?php session_start(); if (isset($_SESSION['gen']) && !empty($_SESSION['sex'])) echo $_SESSION['gen']; else echo "Gender";?></option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                    <select name="sex" required="">
                        <option  value="<?php session_start(); if (isset($_SESSION['sex']) && !empty($_SESSION['sex'])) echo $_SESSION['sex']; else echo "none";?>" selected disabled><?php session_start(); if (isset($_SESSION['sex']) && !empty($_SESSION['sex'])) echo $_SESSION['sex']; else echo "Sexual Prefence";?></option>
                        <option value="Bisexual">Bisexual</option>
                        <option value="Homosexual">Homosexual</option>
                        <option value="Heterosexual">Heterosexual</option>
                    </select></br>
                    <label>Date of birth:</label><input type="date" name="dob" value="<?php session_start(); if (isset($_SESSION['dob'])) echo $_SESSION['dob'];?>">
                    <textarea name="bio" placeholder="Biography" required=""><?php session_start(); if (isset($_SESSION['bio'])) echo $_SESSION['bio'];?></textarea>
                    <textarea name="int" placeholder="<?php session_start(); if (empty($_SESSION['int'])) echo "Your interests";?>" disabled><?php session_start(); if (isset($_SESSION['int']) && !empty($_SESSION['int'])) foreach($_SESSION['int'] as $int){if (strlen($int) > 1) echo trim($int, " ")."\n";};?></textarea>
                    <label>Interest:</label><br>
                    <input type="checkbox" name="v" value="#Vegan"/>#Vegan
                    <input type="checkbox" name="g" value="#Geek"/>#Geek
                    <input type="checkbox" name="p" value="#Piercing"/>#Piercing
                    <input type="checkbox" name="m" value="#Movies"/>#Movies
                    <input type="checkbox" name="mc" value="#Music"/>#Music<br/>
                    <input class="submit" type="submit" name="up" value="Update"/>
                </div>
            </form>
        </div>
    </div>
    <script>
		function getNotif()
		{
			if(window.XMLHttpRequest)
				xmlhttp = new XMLHttpRequest();
			else
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			xmlhttp.onreadystatechange = function()
			{
				if (this.readyState == 4 &&this.status == 200)
				{
					document.getElementById("notif").innerHTML = "Notifications(" + this.responseText + ")";
				}
			};
			try
			{
				xmlhttp.open("GET", "../control/not.php", true);
				xmlhttp.send();
			}
			catch(Exception)
			{

			}
		}

		window.setInterval(()=>{
			getNotif();
		}, 1000);
	</script>
</body>
</html>