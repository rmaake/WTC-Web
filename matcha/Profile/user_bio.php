<?php
session_start();
require_once("../server.php");
require_once("../config/db_admin.php");
require_once("../config/db_setup.php");
require_once("../Control/validation.php");

$conn = db_conn($DB_NAME, $DB_USER, $DB_PASSWORD);
$str = sprintf("<h1 class=\"h2\">Biography</h1>");
echo $str;
if (check_user($conn, $_SESSION['login'], $_SESSION['pwd'], 1) == TRUE)
{
    $usr = $_SESSION['login'];
    if (isset($_SESSION['review']['usr']) && check_user($conn, $_SESSION['review']['usr'], "", 1) === TRUE)
        $usr = $_SESSION['review']['usr'];
    $sql = "SELECT Biography FROM users WHERE User_Id='$usr'";
    try
    {
        foreach($conn->query($sql) as $row)
        {
            $str = sprintf("<p class=\"h2\">%s</p>", $row['Biography']);
            echo $str."<br>";
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