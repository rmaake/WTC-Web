<?php
session_start();
require_once("../server.php");
require_once("../config/db_admin.php");
require_once("../config/db_setup.php");
require_once("../Control/validation.php");

$conn = db_conn($DB_NAME, $DB_USER, $DB_PASSWORD);
$str = sprintf("<h1 class=\"i\">Interests</h1>");
echo $str;
if (check_user($conn, $_SESSION['login'], $_SESSION['pwd'], 2) == TRUE)
{
    $usr = $_SESSION['login'];
    if (isset($_SESSION['review']['usr']) && check_user($conn, $_SESSION['review']['usr'], "", 1) === TRUE)
        $usr = $_SESSION['review']['usr'];
    $sql = "SELECT Interests FROM users WHERE User_Id='$usr'";
    try
    {
        foreach($conn->query($sql) as $row)
        {
            $str = explode(",", $row['Interests']);
            foreach($str as $int)
            {
                if (strlen($int) > 1)
                {
                    $str = sprintf("<label class =\"i\">%s</label>", trim($int, " "));
                    echo $str."</br>";
                }
            }
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