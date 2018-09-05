<?php
    opcache_reset();
    session_start();
    $val = rand();
    require_once('./config/database.php');
    require_once('./config/db_conn.php');
    require_once('user_data.php');
    $db_name = "mysql:host=localhost;dbname=camagru";
    $conn = db_conn($db_name, $DB_USER, $DB_PASSWORD);
    if (isset($_POST['picture']))
    {
        if (!file_exists("./galary/"))
        {
            mkdir("./galary", 0777, TRUE);
        }
        $file = sprintf("./galary/%s%d.png", $_SESSION['login'], $val);
        if (file_exists($file))
        {
            while(file_exists($file))
            {
                $val = rand();
                $file = sprintf("./galary/%s%d.png", $_SESSION['login'], $val);
            }
        }
        $data = explode(',', $_POST['picture']);
        $data = base64_decode($data[1]);
        file_put_contents($file, $data);
        try
        {
            $u_id = $_SESSION['login'];
            $query = "INSERT INTO galary (Image_Name, User_Id)
                VALUES ('$file', '$u_id')";
            $conn->exec($query);
            header('Location: main.php');
        }
        catch(PDOException $e)
        {
            echo $query."<br>".$e->getMessage();
        }
        header("Location: main.php");
    }
?>