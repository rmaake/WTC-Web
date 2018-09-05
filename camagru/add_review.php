<?php
session_start();
opcache_reset();
require_once('./config/database.php');
require_once('./config/db_conn.php');
require_once('user_data.php');
$db_name = "mysql:host=localhost;dbname=camagru";
$conn = db_conn($db_name, $DB_USER, $DB_PASSWORD);
function validate($conn, $data)
{
    $pswd = $_SESSION['pswd'];
    if (check_user($conn, $_SESSION['login'], $pswd, 1))
        return (TRUE);
    else
    {
        $_SESSION['pswd'] = 'no';
        header('Location: login.php');
    }
}
function add_review($conn, $comm, $code)
{
    $usr = $_SESSION['login'];
    $img = $_SESSION['clicked'];
    try
    {
        $query = "SELECT User_Id, Image_Name FROM galary";
        foreach($conn->query($query) as $row)
        {
            if ($row['Image_Name'] == $img && $code == 1)
            {
                $p_o = $row['User_Id'];
                $query = "INSERT INTO user_comment (User_Id, Pic_Owner, Image_Name, Comment) VALUES ('$usr', '$p_o','$img', '$comm')";
                $conn->exec($query);
                $query = "SELECT Email FROM users WHERE User_Id='$p_o'";
                foreach($conn->query($query) as $row)
                {
                    $img = explode("/", $img);
                    $str = sprintf("Greetings %s, you have received new comment on this image: %s\nSign in for more details\nKind regards\n\nCamagru", $p_o, $img[2], $img);
                    mail($row['Email'], "Camagru Update", $str);
                }
                header('Location: review.php');
            }
            else if ($row['Image_Name'] == $img && $code == 2)
            {
                $p_o = $row['User_Id'];
                $query = "SELECT User_Id, Pic_Owner, Image_Name FROM user_like";
                foreach($conn->query($query) as $row)
                {
                    if ($row['User_Id'] == $usr && $row['Image_Name'] == $img && $row['Pic_Owner'] == $p_o)
                    {
                        $query = "DELETE FROM user_like WHERE User_Id = '$usr' AND Pic_Owner = '$p_o' AND Image_Name = '$img'";
                        $conn->exec($query);
                        header("Location: review.php");
                        return (TRUE);
                    }
                }
                $query = "INSERT INTO user_like (User_Id, Pic_Owner, Image_Name) VALUES ('$usr', '$p_o', '$img')";
                $conn->exec($query);
                header("Location: review.php");
            }
        }
        return (FALSE);
    }
    catch (PDOException $e)
    {
        echo $query."<br>".$e->getMessage();
    }
}
if (isset($_POST['com']))
{
    if (validate($conn, $_POST['com']) === TRUE)
        add_review($conn, $_POST['com'], 1);
    else
        echo "Comments";
}
if (isset($_POST['like']))
{
    if (validate($conn, $_POST) === TRUE)
        add_review($conn, $_POST, 2);
    else
        echo "Likes";
}
?>