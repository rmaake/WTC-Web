<!DOCTYPE html>
<html lang="en-Us">
<head>
    <meta charset="utf-8">
    <title>Reset</title>
    <link rel="stylesheet" type="text/css" href="http://localhost:8080/ds/public/css/forms_style.css">
</head>
<body id="login">
    <div class="header">
        <div class="sitename">HyperTube</div>
        <div>
            <?php
            session_start();
            if (isset($_SESSION['login']) && isset($_SESSION['pwd']))
                echo "<a href=\"home/movies\">Home</a><a href=\"../Control/logout.php\">LogOut</a>";
            else
                echo "<a href=\"../\">SignUp</a>";
            ?>
        </div>
        <div id="google_translate_element"></div>
    </div>
   <?php require_once 'public/footer.php';?>
    <div class="container">
        <div class="login">
            <h1 class="h">Reset Password</h1>
            <form action="../server/reset_pswd" method="post">
                <div>
                    <input type="text" placeholder="Username" name="id" value="" required="" />
                    <input type="text" placeholder="Confirmation code" name="cde" required="" />
                    <input type="password" placeholder="Password" name="pswd" required="" />
                    <input type="password" placeholder="Confirm Password" name="rpswd" required="" />
                    <br />
                    <?php session_start(); if(isset($_SESSION['err']['reset'])) echo $_SESSION['err']['reset'];?>
                    <br />
                    <input class="submit" type="submit" name="reset" value="Reset password" />
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