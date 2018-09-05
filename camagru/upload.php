<?php
    session_start();
    $img = explode("/", $_FILES['img']['type']);
    if (strcmp($img[0], "image") === 0 && getimagesize($_FILES['img']['tmp_name']) !== FALSE)
    {
        file_put_contents("tmp", file_get_contents($_FILES['img']['tmp_name']));
        $_SESSION['img'] = "tmp";
    }
    else
        $_SESSION['img'] = "";
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Welcome to Camagru</title>
    <link rel="stylesheet" type="text/css" href="main.css" />
</head>
    <body>
        <?php
            session_start();
            opcache_reset();
            require_once('./config/database.php');
            require_once('./config/db_conn.php');
            require_once('user_data.php');
            $db_name = "mysql:host=localhost;dbname=camagru";
            $conn = db_conn($db_name, $DB_USER, $DB_PASSWORD);
            if (check_user($conn, $_SESSION['login'], $_SESSION['pswd'], 1) === FALSE)
                header("Location: login.php");
        ?>
        <div class="header">
            <div class="cama"><h1>Camagru <img src="./resources/carmra.jpeg" width="20" height="20" /></h1></div>
            <div class="logout"><a href="index.php">Gallary</a> <a href="main.php">Camagru</a> <a href="logout.php">Logout</a></div>
        </div>
        <div class="upload">
            <form action = "upload.php" method = "post" enctype="multipart/form-data">
                <input class="button" type="submit" name="load" value="Upload" />
                <input class="button" type="file" name="img" placeholder="empty" accept="image/*"/>
            </form>
            <form action = "all.php" method = "post"><input class="v_button" type="submit" name="" value="View images"/></form>
            <?php
                $i;
                for($i = 1; $i <= 4; $i++)
                {
                    $str = sprintf("<img class=\"pose\" src=\"resources/%s.png\" alt=\"posable\" onclick=\"addSup(this)\">", $i, $i);
                    echo $str;
                }
            ?>
        </div>
        <div class="container">
            <div class="right">
                    <img src="" id="supImage" width="100%" height="100%" alt=""/>
                    <img src="<?php session_start(); echo $_SESSION['img'];?>" id="video" width="100%" height="100%" alt=""/>
                    <?php
                        session_start();
                        if ($_SESSION['img'] == "")
                        {
                            $str = sprintf("<p><font color=\"white\">File uploaded was not verified as an image</font></p>");
                            echo $str;
                        }
                    ?>                
            </div>
            <input type="button" id ="capture" class="booth-capture-buton" value = "Take photo" disabled>
            <div class="booth">
                <canvas id="canvas" width="400" height="300"></canvas>
                <?php
                    session_start();
                    require_once('./config/database.php');
                    require_once('./config/db_conn.php');
                    require_once('user_data.php');
                    $db_name = "mysql:host=localhost;dbname=camagru";
                    $conn = db_conn($db_name, $DB_USER, $DB_PASSWORD);
                    $usr = $_SESSION['login'];
                    $query = "SELECT Image_Name, User_Id, reg_date FROM galary ORDER BY reg_date DESC";
                    foreach($conn->query($query) as $row)
                    {
                        if ($row['User_Id'] == $usr)
                        {
                            $str = sprintf("<img class=\"photo\" src=\"%s\" alt=\"This is you\">", $row['Image_Name']);
                            echo $str;
                        }
                    }
                ?>
            </div>
            <div class="rght">
                <button class="lft" onclick="plusDivs(2)">&#10094;</button>
                <button class="rgt" onclick="plusDivs(-2)">&#10095;</button>
            </div>
        </div>
        <form id="capture-form" name="capture-form" method="post" action="save_images.php">
        <input type="hidden" name="picture" id="picture" value=""/>
        </form>
        <script src="upload.js"></script>
        <script>
            var slideIndex = 2;
            showDivs(slideIndex);
            function plusDivs(n)
            {
                showDivs(slideIndex += n);
            }
            function showDivs(n)
            {
                var i;
                var x = document.getElementsByClassName("photo");
                if (n > x.length)
                    slideIndex = 2;
                if (n < 1)
                    slideIndex = x.length;
                for(i = 0; i < x.length; i++)
                    x[i].style.display = "none";
                for(i = 2; i > 0; i--)
                {
                    try
                    {
                        x[slideIndex - i].style.display = "block";
                    }
                    catch(err)
                    {

                    }
                }
            }
            function addSup(el) {
                var imageSrc = el.src;
                var sup = document.getElementById('supImage');
                sup.setAttribute('src', imageSrc);
                document.getElementById('capture').disabled = false;
            }
        </script>
        <div class="footer">
            <p class="copyright">&copy;rmaake 2017</p>
        </div>
    </body>
</html>