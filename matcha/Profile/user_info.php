<?php
session_start();
require_once("../server.php");
require_once("../config/db_admin.php");
require_once("../config/db_setup.php");
require_once("../Control/validation.php");

$conn = db_conn($DB_NAME, $DB_USER, $DB_PASSWORD);
if (check_user($conn, $_SESSION['login'], $_SESSION['pwd'], 1) == TRUE)
{
    $usr = $_SESSION['login'];
    if (isset($_SESSION['review']['usr']) && check_user($conn, $_SESSION['review']['usr'], "", 1) === TRUE)
        $usr = $_SESSION['review']['usr'];
    $sql = "SELECT * FROM users WHERE User_Id='$usr'";
    try
    {
        foreach($conn->query($sql) as $row)
        {
            $str2 = sprintf("<label class =\"i\">First Name: %s</label>", $row['First_Name']);
            $str3 = sprintf("<label class =\"i\">Last Name: %s</label>", $row['Last_Name']);
            $str4 = sprintf("<label class =\"i\">Gender: %s</label>", $row['Gender']);
            $str4_3 = sprintf("<label class =\"i\">Age: %s</label>", $row['Age']);
            $str4_4 = sprintf("<label class =\"i\">Sexuality: %s</label>", $row['Sexuality']);
            $str4_5 = sprintf("<label class =\"i\">Rating: %s%s</label>", $row['Rating'], "%");
            $str4_5 = sprintf("%s<br/><label class =\"i\">Status: <label id=\"s\">%s</label></label>", $str4_5, $row['Status']);
            if (!isset($_SESSION['review']['usr']))
            {
                $str2 = sprintf("<label class =\"i\">Username: %s</label><br/>%s", $row['User_Id'], $str2);
                $str5 = sprintf("<label class =\"i\">Email: %s</label>", $row['Email']);
            }
            echo $str2."<br>".$str3."<br>".$str4."<br>".$str4_3."<br>".$str4_4."<br>".$str4_5."<br>".$str5;
        }
    }
    catch (PDOException $e)
    {
        echo $e->getMessage();
    }
}
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