<!DOCTYPE html>
<html lang="en-Us">
<head>
    <meta charset="utf-8">
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="http://localhost:8080/ds/public/css/forms_style.css">
</head>
<body id="login">
    <div class="header">
        <div class="sitename">HyperTube</div>
        <div>
            <a href="../">SignUp</a>
            <?php
            session_start();
            if (isset($_SESSION['login']) && isset($_SESSION['pwd']))
                header("Location: ../browse/movies");
            ?>
        </div>
        <div id="google_translate_element"></div>
    </div>
   <?php require_once 'public/footer.php';?>
    <div class="container">
        <div class="login">
            <h1 class="h">Log In</h1>
            <form action="../server/sign_in" method="post">
                <div>
                    <input type="text" placeholder="Username" name="id" value="" required="" />
                    <input type="password" placeholder="Password" name="pswd" required="" />
                    <br />
                    <br />
                    <nav class="links">
                        <a href="../forms/get_code">forgot password</a>
                    </nav>
                    <br />
                    <?php session_start(); if (isset($_SESSION['err']['login'])) echo $_SESSION['err']['login'];?>
                    <input class="submit" type="submit" name="login" value="Login" />
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