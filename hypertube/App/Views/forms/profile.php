<!DOCTYPE html>
<html lang="en-Us">
<head>
    <meta charset="utf-8">
    <title>Update Profile</title>
    <link rel="stylesheet" type="text/css" href="http://localhost:8080/ds/public/css/forms_style.css">
</head>
<body id="login">
    <div class="header">
        <div class="sitename">HyperTube</div>
        <div>
            <?php
            session_start();
            if (!isset($_SESSION['login']) && !isset($_SESSION['pwd']))
                header("Location: ../forms/login");
            else
                echo "<a href=\"../server/logout\">LogOut</a><a href=\"../browse/movies\">Movies</a>";
            ?>
        </div>
        <div id="google_translate_element"></div>
    </div>
    <?php require_once 'public/footer.php';?>
    <div class="container">
        <div class="login">
            <h1 class="h">Update Profile</h1>
                <img src="<?php echo $_SESSION['pro_pic'];?>" id="pic"/>
            <div>
                <form method="post" action="../browse/upload" id="file" enctype="multipart/form-data">
                    <input type="file" class="submit" accept="image/*" name="img" onchange="upload()"/>
                </form>
                <button class="submit" onclick="remove()">remove</button>
                <input id="nme" type="text" placeholder="First name" name="f_name" value="<?php session_start(); echo $_SESSION['f_name'];?>" required="" />
                <?php session_start(); if (isset($_SESSION['err']['err_name'])) echo $_SESSION['err']['err_name'];?>
                <input id="l_nme" type="text" placeholder="Surname" name="l_name" value="<?php session_start(); echo $_SESSION['l_name'];?>" required="" />
                <?php session_start(); if (isset($_SESSION['err']['err_name'])) echo $_SESSION['err']['err_name'];?>
                <input id="usr" type="text" placeholder="Username" name="id" value="<?php session_start(); echo $_SESSION['login'];?>" required="" />
                <?php session_start(); if (isset($_SESSION['err']['err_usr_id'])) echo $_SESSION['err']['err_usr_id'];?>
                <input id="eml" type="Email" placeholder="Email address" name="email" value="<?php session_start(); echo $_SESSION['email'];?>" required="" />
                <?php session_start(); if (isset($_SESSION['err']['err_email'])) echo $_SESSION['err']['err_email'];?>
                <br />
                <br />
                <button class="submit" onclick="update()">UPDATE</button>
            </div>
        </div>
        <div class="log">
            <h1 class="h">Verfiy email</h1>
            <div>
                <input id="cde" type="text" placeholder="Enter confirmation code" name="cde" required="" />
                <?php session_start(); if (isset($_SESSION['err']['cde'])) echo $_SESSION['err']['cde'];?>
                <br />
                <br />
                <button class="submit" name="reg" onclick="verify()">Verify</button>
            </div>
        </div>
    </div>
    <script>
        function upload()
        {
            document.getElementById("file").submit();
        }
        function remove()
        {
            document.getElementById("pic").src = "../public/resources/no_pic.jpg";
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.open("GET", "../browse/remove", true);
            xmlhttp.send();
        }
        function update() 
		{
            var xmlhttp = new XMLHttpRequest();
            var nme = document.getElementById("nme").value;
            var l_nme = document.getElementById("l_nme").value;
            var id = document.getElementById("usr").value;
            var eml = document.getElementById("eml").value;
            var cde = document.getElementById("cde").value;
            var res;
            var tr = "true";
            xmlhttp.onreadystatechange = function() 
            {
                if (this.readyState == 4 && this.status == 200) {
                    res = this.responseText;
                    var r = tr.localeCompare(res);
                    if (r == 0)
                    {
                        document.querySelector(".login").style.display = "none";
                        document.querySelector(".log").style.display = "block";
                        return (false);
                    }
                }
            };
            xmlhttp.open("GET", "../server/update?f_name="+nme+"&l_name="+l_nme+"&email="+eml+"&id="+id+"&cde="+cde, true);
            xmlhttp.send();
		}
        function verify()
        {
            var cde = document.getElementById("cde").value;
            var xmlhttp = new XMLHttpRequest();

            xmlhttp.onreadystatechange = function() 
            {
                if (this.readyState == 4 && this.status == 200) {
                    res = this.responseText;
                    if (res.length == 0)
                        window.location.href = '../browse/movies';
                    else
                        document.getElementById("cde").value = '';
                }
            };
            xmlhttp.open("GET", "../server/verify_email?cde="+cde, true);
            xmlhttp.send();
        }
    </script>
    <script type="text/javascript">
        function googleTranslateElementInit()
        {
            new google.translate.TranslateElement({pageLanguage: 'en'}, 'google_translate_element');
        }
    </script>
    <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
</body>
</html>