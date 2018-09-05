<?php
session_start();
opcache_reset();
require_once('./config/database.php');
require_once('./config/db_conn.php');
require_once('user_data.php');
$db_name = "mysql:host=localhost;dbname=camagru";
$conn = db_conn($db_name, $DB_USER, $DB_PASSWORD);
if (!check_user($conn, $_SESSION['login'], $_SESSION['pswd'], 1))
    echo "please sign in first<br>";
$query = "SELECT Image_Name, User_Id, Comment, reg_date FROM user_comment ORDER BY reg_date DESC";
$img = $_SESSION['clicked'];
try 
{
    foreach($conn->query($query) as $row)
    {
        if ($row['Image_Name'] == $img)
        {
            $str = sprintf("%s <br>(<font size=\"2\">comment by <i><strong>%s</strong> on %s</i></font>)", $row['Comment'], $row['User_Id'], $row['reg_date']);
            echo $str."<br>";
        }
    }
}
catch (PDOException $e)
{
    echo $e->getMessage();
}
?>