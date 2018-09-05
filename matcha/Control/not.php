<?php
session_start();
require_once("../server.php");
require_once("../config/db_admin.php");
require_once("../config/db_setup.php");
require_once("validation.php");

$conn = db_conn($DB_NAME, $DB_USER, $DB_PASSWORD);
function check_review($conn, $usr, $l_id)
{
    $sql = "SELECT * FROM review ORDER BY review_date ASC";
    $str = 0;
    try
    {
        foreach($conn->query($sql) as $row)
        {
            if (strcmp($l_id, $row['review_date']) < 0 && $row['User_Id'] == $usr)
            {
                $str++;
                $_SESSION['rev']['lst'] = $row['review_date'];
            }
        }
        if (!isset($_SESSION['ckd']) || $_SESSION['ckd'] === TRUE)
            $_SESSION['rev']['fst'] = $_SESSION['rev']['lst'];
        return ($str);
    }
    catch(PDOException $e)
    {
        echo $e->getMessage();
    }
    return ($str);
}
function check_chat($conn, $usr, $l_id)
{
    $sql = "SELECT * FROM chats ORDER BY reg ASC";
    $str = 0;
    try
    {
        foreach($conn->query($sql) as $row)
        {
            if (strcmp($l_id, $row['reg']) < 0 && $row['To'] == $usr)
            {
                $str++;
                $_SESSION['cht']['lst'] = $row['reg'];
            }
        }
        if (!isset($_SESSION['ckd']) || $_SESSION['ckd'] === TRUE)
            $_SESSION['cht']['fst'] = $_SESSION['cht']['lst'];
        return ($str);
    }
    catch(PDOException $e)
    {
        echo $e->getMessage();
    }
    return ($str);
}
if (isset($_SESSION['lst']) && $_SESSION['lst'] !== FALSE)
{
    $str = check_review($conn, $_SESSION['login'], $_SESSION['lst']);
    $str += check_chat($conn, $_SESSION['login'], $_SESSION['lst']);
    $_SESSION['rev']['lst'] = $_SESSION['lst'];
    $_SESSION['cht']['lst'] = $_SESSION['lst'];
    $_SESSION['lst'] = FALSE;
}
else
{
    $str = check_review($conn, $_SESSION['login'], $_SESSION['rev']['lst']);
    $str += check_chat($conn, $_SESSION['login'], $_SESSION['cht']['lst']);
}
if (!isset($_SESSION['ckd']) || $_SESSION['ckd'] === TRUE)
    $_SESSION['ckd'] = $str;
else
{
    $str += $_SESSION['ckd'];
    $_SESSION['ckd'] = $str;
}
echo $str;
?>