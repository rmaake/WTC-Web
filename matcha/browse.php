<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Browsing</title>
	<style>
        .container {
            position: absolute;
            width: 100%;
            height: 85%;
        }
        .browsing {
            margin: 0 auto;
            width: 100%;
            height: 90%;
            text-align: center;
            overflow-y: auto; 
        }
        img {
            margin: 10px;
            width: 100px;
            height: 100px;
            border-radius: 100%;
        }
        .button {
            position: relative;
            top: 95%;
            text-align: center;
        }

        .prev{
                background-color: #ae9e78;
                margin: 5px;
                width: 80px;
                height: 20px;
            }

        .next{
            background-color: #ae9e78;
            width: 80px;
            height: 20px;
        }
    </style>
</head>
<body>
    <div  class="browsing">
        <?php
            session_start();
            require_once("./server.php");
            require_once("./config/db_admin.php");
            require_once("./config/db_setup.php");
            require_once("./Control/validation.php");
            
            $conn = db_conn($DB_NAME, $DB_USER, $DB_PASSWORD);
            function check_blocked($conn, $usr, $i_usr)
            {
                $sql = "SELECT * FROM review";
                try
                {
                    foreach($conn->query($sql) as $row)
                    {
                        if ($row['User_Id'] == $usr && $row['Review'] == $i_usr && $row['Blocked'] == "Yes")
                            return (TRUE);
                        else if ($row['User_Id'] == $i_usr && $row['Review'] == $usr && $row['Blocked'] == "Yes")
                            return (TRUE);
                    }
                }
                catch (PDOException $e)
                {
                    $e->getMessage();
                }
                return (FALSE);
            }
            function user_data($conn, $usr)
            {
                $sql = "SELECT * FROM users";
                try
                {
                    foreach($conn->query($sql) as $row)
                    {
                        if ($row['User_Id'] == $usr)
                            return ($row);
                    }
                }
                catch (PDOException $e)
                {
                    $e->getMessage();
                }
                return (FALSE);
            }
            function echo_profiles($conn, $data)
            {
                $sql = "SELECT * FROM gallary";
                try
                {
                    foreach($conn->query($sql) as $row)
                    {
                        foreach($data as $usr)
                        {
                            if ($row['User_Id'] == $usr && $row['Profile_Pic'] == "Yes" && check_blocked($conn, $_SESSION['login'], $row['User_Id']) == FALSE)
                            {
                                $img = explode("/", $row['Image_Name']);
                                $str = sprintf("<a href=\"users.php?img=%s&usr=%s\" target=\"_parent\"><img class=\"myimg\" src=\"./gallary/%s\"></a>", $row['Image_Name'], $_SESSION['login'], $img[2]);
                                echo $str;
                            }
                        }
                    }
                }
                catch (PDOException $e)
                {
                    $e->getMessage();
                }
            }
            function match_pro($conn, $usr)
            {
                $sql = "SELECT * FROM users WHERE User_Id != '$usr'";
                $data;
                $usr = user_data($conn, $usr);
                if ($usr !== FALSE)
                {
                    try
                    {
                        foreach($conn->query($sql) as $row)
                        {
                            if ($usr['Gender'] != $row['Gender'] && ($usr['Sexuality'] == "Bisexual" || $usr['Sexuality'] == "Heterosexual") && ($row['Sexuality'] == "Bisexual" || $row['Sexuality'] == "Heterosexual") && $row['Rating'] >= 40)
                                $data[] = $row['User_Id'];
                            else if ($usr['Gender'] == $row['Gender'] && ($usr['Sexuality'] == "Bisexual" || $usr['Sexuality'] == "Homosexual") && ($row['Sexuality'] == "Bisexual" || $row['Sexuality'] == "Homosexual") && $row['Rating'] >= 40)
                                $data[] = $row['User_Id'];
                        }
                        echo_profiles($conn, $data);
                    }
                    catch (PDOException $e)
                    {
                        $e->getMessage();
                    }
                }
            }
            function dups($data, $src)
            {
                foreach($data as $cmp)
                {
                    if ($cmp == $src)
                        return (TRUE);
                }
                return (FALSE);
            }
            function match($filt, $usr)
            {
                $j = 0;
                $usr = explode(',', $usr['Interests']);
                foreach($usr as $int)
                {
                    foreach($filt as $val)
                    {
                        $int = trim($int, " ");
                        $val = trim($val, " ");
                        if (strcmp($int, $val) == 0 && strlen($int) > 1 && strlen($val) > 1)
                            $j++;
                    }
                }
                if ($j > 0)
                    return (TRUE);
                return FALSE;
            }
            function search($conn, $usr, $filt)
            {
                $a_min = $filt['age_min'];
                $a_max = $filt['age_max'];
                $f_min = $filt['r_min'];
                $f_max = $filt['r_max'];
                $data = FALSE;
                $sql3 = "SELECT * FROM users WHERE User_Id != '$usr'";
                $sql2 = "SELECT * FROM users WHERE Rating >= '$f_min' AND Rating <= '$f_max'";
                $sql = "SELECT * FROM users WHERE Age >= '$a_min' AND Age <= '$a_max' AND User_Id != '$usr'";
                $usr = user_data($conn, $usr);
                try
                {
                    if (!empty($a_min) && !empty($a_max))
                    {
                        foreach($conn->query($sql) as $row)
                        {
                            if ($usr['Gender'] != $row['Gender'] && ($usr['Sexuality'] == "Bisexual" || $usr['Sexuality'] == "Heterosexual") && ($row['Sexuality'] == "Bisexual" || $row['Sexuality'] == "Heterosexual"))
                                $data[] = $row['User_Id'];
                            else if ($usr['Gender'] == $row['Gender'] && ($usr['Sexuality'] == "Bisexual" || $usr['Sexuality'] == "Homosexual") && ($row['Sexuality'] == "Bisexual" || $row['Sexuality'] == "Homosexual"))
                                $data[] = $row['User_Id'];
                        }
                    }
                    if (!empty($f_min) && !empty($f_max))
                    {
                        foreach($conn->query($sql2) as $row)
                        {
                            if (dups($data, $row['User_Id']) === FALSE)
                            {
                                if ($usr['Gender'] != $row['Gender'] && ($usr['Sexuality'] == "Bisexual" || $usr['Sexuality'] == "Heterosexual") && ($row['Sexuality'] == "Bisexual" || $row['Sexuality'] == "Heterosexual"))
                                    $data[] = $row['User_Id'];
                                else if ($usr['Gender'] == $row['Gender'] && ($usr['Sexuality'] == "Bisexual" || $usr['Sexuality'] == "Homosexual") && ($row['Sexuality'] == "Bisexual" || $row['Sexuality'] == "Homosexual"))
                                    $data[] = $row['User_Id'];
                            }
                        }
                    }
                    foreach($conn->query($sql3) as $row)
                    {
                        if (dups($data, $row['User_Id']) === FALSE)
                        {
                            if ($usr['Gender'] != $row['Gender'] && ($usr['Sexuality'] == "Bisexual" || $usr['Sexuality'] == "Heterosexual") && ($row['Sexuality'] == "Bisexual" || $row['Sexuality'] == "Heterosexual"))
                            {
                                if (match($filt, $row) == TRUE)
                                    $data[] = $row['User_Id'];
                            }
                            else if ($usr['Gender'] == $row['Gender'] && ($usr['Sexuality'] == "Bisexual" || $usr['Sexuality'] == "Homosexual") && ($row['Sexuality'] == "Bisexual" || $row['Sexuality'] == "Homosexual"))
                            {
                                if (match($filt, $row) == TRUE)
                                    $data[] = $row['User_Id'];
                            }
                        }
                    }
                    echo_profiles($conn, $data);
                }
                catch(PDOException $e)
                {
                    echo $e->getMessage();
                }
            }
            function filter($conn, $usr, $filt)
            {
                $a_min = $filt['age_min'];
                $a_max = $filt['age_max'];
                $f_min = $filt['r_min'];
                $f_max = $filt['r_max'];
                $data = FALSE;
                $sql3 = "SELECT * FROM users WHERE User_Id != '$usr'";
                $sql2 = "SELECT * FROM users WHERE Rating >= '$f_min' AND Rating <= '$f_max' ORDER BY Rating DESC";
                $sql = "SELECT * FROM users WHERE Age >= '$a_min' AND Age <= '$a_max' AND User_Id != '$usr' ORDER BY Age DESC";
                $usr = user_data($conn, $usr);
                try
                {
                    if (!empty($a_min) && !empty($a_max) && $_GET['rad'] == "age")
                    {
                        foreach($conn->query($sql) as $row)
                        {
                            if ($usr['Gender'] != $row['Gender'] && ($usr['Sexuality'] == "Bisexual" || $usr['Sexuality'] == "Heterosexual") && ($row['Sexuality'] == "Bisexual" || $row['Sexuality'] == "Heterosexual"))
                                $data[] = $row['User_Id'];
                            else if ($usr['Gender'] == $row['Gender'] && ($usr['Sexuality'] == "Bisexual" || $usr['Sexuality'] == "Homosexual") && ($row['Sexuality'] == "Bisexual" || $row['Sexuality'] == "Homosexual"))
                                $data[] = $row['User_Id'];
                        }
                    }
                    if (!empty($f_min) && !empty($f_max) && $_GET['rad'] == "f_rating")
                    {
                        foreach($conn->query($sql2) as $row)
                        {
                            if (dups($data, $row['User_Id']) === FALSE)
                            {
                                if ($usr['Gender'] != $row['Gender'] && ($usr['Sexuality'] == "Bisexual" || $usr['Sexuality'] == "Heterosexual") && ($row['Sexuality'] == "Bisexual" || $row['Sexuality'] == "Heterosexual"))
                                    $data[] = $row['User_Id'];
                                else if ($usr['Gender'] == $row['Gender'] && ($usr['Sexuality'] == "Bisexual" || $usr['Sexuality'] == "Homosexual") && ($row['Sexuality'] == "Bisexual" || $row['Sexuality'] == "Homosexual"))
                                    $data[] = $row['User_Id'];
                            }
                        }
                    }
                    if ($_GET['rad'] == "int")
                    {
                        foreach($conn->query($sql3) as $row)
                        {
                            if (dups($data, $row['User_Id']) === FALSE)
                            {
                                if ($usr['Gender'] != $row['Gender'] && ($usr['Sexuality'] == "Bisexual" || $usr['Sexuality'] == "Heterosexual") && ($row['Sexuality'] == "Bisexual" || $row['Sexuality'] == "Heterosexual"))
                                {
                                    if (match($filt, $row) == TRUE && $_GET['rad'] == "int")
                                        $data[] = $row['User_Id'];
                                }
                                else if ($usr['Gender'] == $row['Gender'] && ($usr['Sexuality'] == "Bisexual" || $usr['Sexuality'] == "Homosexual") && ($row['Sexuality'] == "Bisexual" || $row['Sexuality'] == "Homosexual"))
                                {
                                    if (match($filt, $row) == TRUE && $_GET['rad'] == "int")
                                        $data[] = $row['User_Id'];
                                }
                            }
                        }
                    }
                    echo_profiles($conn, $data);
                }
                catch(PDOException $e)
                {
                    echo $e->getMessage();
                }
            }
           
            if (!preg_match("/^[0-9]*$/",$_GET['age_min']) || !preg_match("/^[0-9]*$/",$_GET['age_max']))
                return (FALSE);
            if (!preg_match("/^[0-9]*$/",$_GET['age_min']) || !preg_match("/^[0-9]*$/",$_GET['age_max']))
                return (FALSE);
            if (check_user($conn, $_SESSION['login'], $_SESSION['pwd'], 2) === TRUE && !isset($_GET['reg']) && !isset($_GET['fil']))
                match_pro($conn, $_SESSION['login']);
            else if (check_user($conn, $_SESSION['login'], $_SESSION['pwd'], 2) === TRUE && isset($_GET['reg']))
                search($conn, $_SESSION['login'], $_GET);
            else  if (check_user($conn, $_SESSION['login'], $_SESSION['pwd'], 2) === TRUE && isset($_GET['fil']))
                filter($conn, $_SESSION['login'], $_GET);
            else
                header("Location: ./forms/login.php");
        ?>
    </div>
    <div class="button">
				<button class="next" onclick="plusSlides(20)">&#10094;</button>
				<button class="prev" onclick="plusSlides(-20)">&#10095;</button>
    </div>
    <script>
        var slideIndex = 20;
        var y = document.getElementsByClassName("myimg");
        showDivs(slideIndex);
        if (slideIndex > y.length)
        {
            slideIndex = y.length;
        }
        
        function plusSlides(n) {
            showDivs(slideIndex += n);
        }

        function showDivs(n)
        {
            var i;
            var x = document.getElementsByClassName("myimg");
            if (n > x.length)
            {
                slideIndex = 20;
            }    
            if (n < 1)
            {
                slideIndex = x.length;
            }
            for (i = 0; i < x.length; i++)
            {
                x[i].style.display = "none";  
            }
            for (i = 20; i > 0; i--)
            {
                try
                {
                    x[slideIndex-i].style.display = "inline-block";
                }
                catch (err){}
            }
        }
    </script>
</body>