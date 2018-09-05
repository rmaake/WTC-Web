<?php
session_start();
require_once("../config/db_admin.php");
require_once("../config/db_setup.php");
require_once("validation.php");
require_once("profile.php");

$conn = db_conn($DB_NAME, $DB_USER, $DB_PASSWORD);
if (check_user($conn, $_SESSION['login'], $_SESSION['pwd'], 2) === TRUE)
{
    $date = time() + (60 * 60 * 10);
    $date = date("D, d M Y, H:i:s", $date);
    $usr = $_SESSION['login'];
    $sql = "UPDATE users SET Status='$date' WHERE User_Id='$usr'";
    try
    {
        $conn->exec($sql);
    }
    catch(PDOException $e)
    {
        echo $e->getMessage();
    }
}
session_destroy();
header("Location: ../Forms/login.php");
?>