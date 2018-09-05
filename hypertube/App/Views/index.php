<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
    <title>Welcome to HyperTube</title>
    <link rel="stylesheet" type="text/css" href="http://localhost:8080/ds/public/css/forms_style.css">
</head>
<body id="login">
    <div class="header">
        <div class="sitename">HyperTube</div>
        <div>
            <a href="forms/login">LogIn</a>
            <?php
                session_start();
                if (isset($_SESSION['login']) && isset($_SESSION['pwd']))
                    header("Location: browse/movies");
            ?>
        </div>
        <div id="google_translate_element"></div>
    </div>
   <?php require_once 'public/footer.php';?>
    <div class="container">
        <div class="login">
            <h1 class="h">SignUp Options</h1>
            <form action="server/reg_type" method="get">
                <div>
                    <input class="submit" type="submit" name="reg" value="Regular SignUp" />
                    <input class="submit" type="submit" name="42" value="SignUp using 42" />
                    <input class="submit" type="submit" name="face" value="SignUp using FaceBook" />
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
