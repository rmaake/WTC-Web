<?php
session_start();
require_once("./server.php");
require_once("./config/db_admin.php");
require_once("./config/db_setup.php");
require_once("./control/validation.php");

$conn = db_conn($DB_NAME, $DB_USER, $DB_PASSWORD);
function get_img($conn, $usr)
{
    $sql = "SELECT * FROM gallary WHERE Profile_Pic='Yes'";
    try
    {
        foreach($conn->query($sql) as $row)
        {
            if ($row['User_Id'] == $usr)
                return ($row['Image_Name']);
        }
    }
    catch(PDOException $e)
    {
        echo $e->getMessage();
    }
    return (FALSE);
}
function get_rev($conn, $usr, $f_id)
{
    $sql = "SELECT * FROM review ORDER BY review_date DESC";
    try
    {
        foreach($conn->query($sql) as $row)
        {
            if (strcmp($f_id, $row['review_date']) < 0 && $row['User_Id'] == $usr)
            {
                $img = get_img($conn, $row['Review']);
                if ($img !== FALSE)
                {
                    $img = explode("/", $img);
                    if ($row['Liked'] == "Yes")
                        $str = sprintf("<a href=\"users.php?img=%s&usr=%s\"><p><img class=\"pro\" src=\"./gallary/%s\"/><label class=\"usr\">%s is interested in you</label></p></a>", implode("/", $img), $row['Review'], $img[2], $row['Review']);
                    else if ($row['Liked'] == "Not")
                        $str = sprintf("<a href=\"users.php?img=%s&usr=%s\"><p><img class=\"pro\" src=\"./gallary/%s\"/><label class=\"usr\">%s lost interest</label></p></a>", implode("/", $img), $row['Review'], $img[2], $row['Review']);
                    else if ($row['Blocked'] == "Yes")
                        $str = sprintf("<p><img class=\"pro\" src=\"./gallary/%s\"/><label class=\"usr\">%s has blocked you</label></p>", implode("/", $img), $row['Review'], $img[2], $row['Review']);
                    else
                        $str = sprintf("<a href=\"users.php?img=%s&usr=%s\"<p><img class=\"pro\" src=\"./gallary/%s\"/><label class=\"usr\">%s viewed your profile</label></p></a>", implode("/", $img), $row['Review'], $img[2], $row['Review']);
                    echo $str;
                }
            }
        }
    }
    catch(PDOException $e)
    {
        echo $e->getMessage();
    }
}
function get_cht($conn, $usr, $f_id)
{
    $sql = "SELECT * FROM chats ORDER BY reg DESC";
    try
    {
        foreach($conn->query($sql) as $row)
        {
            if (strcmp($f_id, $row['reg']) < 0 && $row['To'] == $usr)
            {
                $img = get_img($conn, $row['From']);
                if ($img !== FALSE)
                {
                    $img = explode("/", $img);
                    $str = sprintf("<a href=\"chat_server.php?usr=%s&img=./gallary/%s\"><p><img class=\"pro\" src=\"./gallary/%s\"/><label class=\"usr\">%s texted you</label></p></a>", $row['From'], $img[2], $img[2], $row['From']);
                    echo $str;
                }
            }
        }
    }
    catch(PDOException $e)
    {
        echo $e->getMessage();
    }
}
if (isset($_SESSION['cht']['fst']))
    get_cht($conn, $_SESSION['login'], $_SESSION['cht']['fst']);
if (isset($_SESSION['rev']['fst']))
    get_rev($conn, $_SESSION['login'], $_SESSION['rev']['fst']);
$_SESSION['ckd'] = TRUE;
?>