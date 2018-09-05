<?php
session_start();
require_once("../server.php");
require_once("../config/db_admin.php");
require_once("../config/db_setup.php");
require_once("../Control/validation.php");

$conn = db_conn($DB_NAME, $DB_USER, $DB_PASSWORD);
/*if (check_user($conn, $_SESSION['login'], $_SESSION['pwd'], 1) == TRUE)
{
    $usr = $_SESSION['login'];
    $sql = "SELECT * FROM review WHERE User_Id='$usr'";
    try
    {
        foreach($conn->query($sql) as $row)
        {
            $str = sprintf("<div width=\"100%\" height=\"200px\">");
            $pro = sprintf("<div class=\"block\" width=\"200px\" height=\"100%\">");
            $img = sprintf("<img class=\"pro_pic\" src=\"%s\">", $dir);
            $pro_e = "</div>";
            $str_e = "</div>";
        }
    }
    catch (PDOException $e)
    {
        echo $e->getMessage();
    }
}*/
function get_pic($conn, $rev)
{
    $sql = "SELECT Image_Name FROM gallary WHERE User_Id='$rev' AND Profile_Pic='Yes'";
    try
    {
        foreach($conn->query($sql) as $row)
        {
            return ($row['Image_Name']);
        }
    }
    catch (PDOException $e)
    {
        echo $e->getMessage();
    }
    return (FALSE);
}
function get_data($conn, $rev)
{
    $sql = "SELECT First_Name, Last_Name FROM users WHERE User_Id='$rev'";
    try
    {
        foreach($conn->query($sql) as $row)
        {
            $str = sprintf("<label class =\"z\">%s %s</label>", $row['Last_Name'], $row['First_Name']);
            return ($str);
        }
    }
    catch (PDOException $e)
    {
        echo $e->getMessage();
    }
    return (FALSE);
}
function likes($conn, $usr)
{
    $sql = "SELECT * FROM review WHERE User_Id='$usr' ORDER BY Id DESC";
    try
    {
        foreach($conn->query($sql) as $row)
        {
            if ($row['Visited'] == "Yes" && $row['Blocked'] != "Yes")
            {
                $dir = get_pic($conn, $row['Review']);
                $rev = get_data($conn, $row['Review']);
                $str = "<div width=\"100%\" height=\"150px\">";
                $pro = "<div class=\"block\" width=\"150px\" height=\"100%\">";
                $img = sprintf("<a href=\"test.php?img=%s&name=rev\" target=\"_parent\"><img class=\"pro_pic\" src=\"%s\"></a>", $dir, $dir);
                $pro_e = "</div>";
                $per = "<div class=\"block\" width=\"50px\" height=\"100%\">";
                $txt = sprintf("%s", $rev);
                $per_e = "</div>";
                $str_e = "</div>";
                echo $str.$pro.$img.$pro_e.$per.$txt.$per_e.$str_e;
            }
        }
    }
    catch (PDOException $e)
    {
        echo $e->getMessage();
    }
}
if (TRUE)
    likes($conn, $_SESSION['login']);
?>
<html>
<head>
	<meta charset="utf-8">
	<title>User Profile</title>
	<link rel="stylesheet" type="text/css" href="../css/pro_style.css">
</head>
<body>
</body>
</html>