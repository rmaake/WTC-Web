<?php
session_start();
require_once("../server.php");
require_once("../config/db_admin.php");
require_once("../config/db_setup.php");
require_once("validation.php");

$conn = db_conn($DB_NAME, $DB_USER, $DB_PASSWORD);

function push_msg($conn, $from, $to, $msg)
{
    $sql = "INSERT INTO chats(`From`, `To`, `Msg`) VALUES('$from', '$to', '$msg')";
    try
    {
        $conn->exec($sql);
    }
    catch(PDOException $e)
    {
        echo $e->getMessage();
    }
}
function get_img($conn, $usr)
{
    $sql = "SELECT * FROM gallary";
    try
    {
        foreach($conn->query($sql) as $row)
        {
            if ($row['User_Id'] == $usr && $row['Profile_Pic'] == "Yes")
                return ($row['Image_Name']);
        }
    }
    catch(PDOException $e)
    {
        echo $e->getMessage();
    }
    return (FALSE);
}
function get_texts($conn, $from, $to)
{
    $sql = "SELECT * FROM chats";
    try
    {
        $div = "<div id =\"u\" class=\"user\">";
        $div_ = "</div>";
        $img = get_img($conn, $to);
        $img = explode("/", $img);
        $str = sprintf("%s\n<img id=\"p\" class=\"pro_pic\" src=\"./gallary/%s\"><label id=\"l\" class=\"lab\">%s</label>\n%s", $div, $img[2], $to, $div_);
        echo $str."</br>"."</br>"."</br>"."</br>";
        foreach($conn->query($sql) as $row)
        {
            if ($row['From'] == $from && $row['To'] == $to && $row['Msg'] != ' ')
                $s = sprintf("%s</br><p class=\"txt\">%s</p></br>", $s, $row['Msg']);
            else if ($row['From'] == $to && $row['To'] == $from && $row['Msg'] != ' ')
                $s = sprintf("%s</br><p class=\"txts\">%s</p></br>",$s ,$row['Msg']);
        }
        return ($s);
    }
    catch(PDOException $e)
    {
        echo $e->getMessage();
    }
    return (FALSE);
}

if (check_user($conn, $_SESSION['login'], $_SESSION['pwd'], 2) === FALSE)
{
    header("Location: ../forms/login.php");
    return (FALSE);
}
if (isset($_GET['usr']) && $_GET['txt'] != '')
{
    if (check_user($conn, $_GET['usr'], "", 1) === TRUE)
    {
        push_msg($conn, $_SESSION['login'], $_GET['usr'], $_GET['txt']);
        $var = get_texts($conn, $_SESSION['login'], $_GET['usr']);
        echo $var;
    }
}

if (isset($_GET['usr']))
{
    if (check_user($conn, $_GET['usr'], "", 1) === TRUE)
    {
        $var = get_texts($conn, $_SESSION['login'], $_GET['usr']);
        if ($var === FALSE)
            echo "This is the beginning of your chat with ".$_GET['usr'].". Say something nice :)";
        else
            echo $var;
    }
}
?>