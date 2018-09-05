<!DOCTYPE html>
<html lang="en-Us">
<head>
    <meta charset="utf-8">
    <title>Reset password</title>
    <link rel="stylesheet" type="text/css" href="http://localhost:8080/ds/public/css/forms_style.css">
</head>
<body id="login">
    <div class="header">
        <div class="sitename">HyperTube</div>
        <div>
            <?php
            session_start();
            if (isset($_SESSION['login']) && isset($_SESSION['pwd']))
            {
                //must re-route to movies
            }
            else
                echo "<a href=\"../\">Home</a><a href=\"../forms/login\">LogIn</a>";
            ?>
        </div>
        <div id="google_translate_element"></div>
    </div>
   <?php require_once 'public/footer.php';?>
    <div class="container">
        <div class="login">
            <h1 class="h">Reset password</h1>
            <form action="../server/get_code" method="post">
                <div>
                    <input type="text" placeholder="Username" name="id" value="" required="" />
                    <br />
                    <?php session_start(); if (isset($_SESSION['err']['cde'])) echo $_SESSION['err']['cde'];?>
                    <br />
                    <input class="submit" type="submit" name="reset" value="Get Code" />
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