<?php
session_start();
require_once("./server.php");
require_once("./config/db_admin.php");
require_once("./config/db_setup.php");
require_once("./Control/validation.php");

$conn = db_conn($DB_NAME, $DB_USER, $DB_PASSWORD);
function check_entry($conn, $from, $to)
{
    $sql = "SELECT * FROM chats";
    try
    {
        foreach($conn->query($sql) as $row)
        {
            if ($row['From'] == $from && $row['To'] == $to)
                return (TRUE);
        }
    }
    catch(PDOException $e)
    {
        echo $e->getMessage();
    }
    return (FALSE);
}

function create_chat($conn, $from, $to, $msg)
{
    $sql = "INSERT INTO chats(`From`, `To`, `Msg`)
    VALUES('$from', '$to', '$msg')";
    try
    {
        $conn->exec($sql);
    }
    catch(PDOException $e)
    {
        echo $e->getMessage();
    }
}

if (isset($_GET['usr']))
{
    $usr = sprintf("Location: ./chat.php", $_GET['usr']);
    if (check_entry($conn, $_SESSION['login'], $_GET['usr']) === FALSE)
        create_chat($conn, $_SESSION['login'], $_GET['usr'], " ");
    $_SESSION['chat']['usr'] = $_GET['usr'];
    $_SESSION['chat']['img'] = $_GET['img'];
    header($usr);
}
?>