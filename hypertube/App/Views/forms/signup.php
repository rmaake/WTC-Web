<!DOCTYPE html>
<html lang="en-Us">
<head>
    <meta charset="utf-8">
    <title>Sign Up</title>
    <link rel="stylesheet" type="text/css" href="http://localhost:8080/ds/public/css/forms_style.css">
</head>
<body id="login">
    <div class="header">
        <div class="sitename">HyperTube</div>
        <div>
            <?php
            session_start();
            if (isset($_SESSION['login']) && isset($_SESSION['pwd']))
                header("Location: ./");
            else
                echo "<a href=\"../\">Home</a><a href=\"../forms/login\">LogIn</a>";
            ?>
        </div>
        <div id="google_translate_element"></div>
    </div>
    <?php require_once 'public/footer.php';?>
    <div class="container">
        <div class="login">
            <h1 class="h">Create Account</h1>
            <form action="../server/register" method="post">
                <div>
                    <input type="text" placeholder="First name" name="f_name" value="<?php session_start(); echo $_SESSION['f_name'];?>" required="" />
                    <?php session_start(); if (isset($_SESSION['err']['err_name'])) echo $_SESSION['err']['err_name'];?>
                    <input type="text" placeholder="Surname" name="l_name" value="<?php session_start(); echo $_SESSION['l_name'];?>" required="" />
                    <?php session_start(); if (isset($_SESSION['err']['err_name'])) echo $_SESSION['err']['err_name'];?>
                    <input type="text" placeholder="Username" name="id" value="<?php session_start(); echo $_SESSION['usr_id'];?>" required="" />
                    <?php session_start(); if (isset($_SESSION['err']['err_usr_id'])) echo $_SESSION['err']['err_usr_id'];?>
                    <input type="Email" placeholder="Email address" name="email" value="<?php session_start(); echo $_SESSION['email'];?>" required="" />
                    <?php session_start(); if (isset($_SESSION['err']['err_email'])) echo $_SESSION['err']['err_email'];?>
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
    <script type="text/javascript">
        function googleTranslateElementInit()
        {
            new google.translate.TranslateElement({pageLanguage: 'en'}, 'google_translate_element');
        }
    </script>
    <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
</body>
</html>