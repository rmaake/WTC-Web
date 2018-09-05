<?php
session_start();
require_once("./server.php");
require_once("./config/db_admin.php");
require_once("./config/db_setup.php");
require_once("./Control/validation.php");

$conn = db_conn($DB_NAME, $DB_USER, $DB_PASSWORD);
function get_users($conn, $data)
{
    $sql = "SELECT * FROM gallary";
    try
    {
        foreach($conn->query($sql) as $row)
        {
            if ($row['Profile_Pic'] == "Yes" && $row['Image_Name'] == $data['img'])
                return ($row['User_Id']);
        }
    }
    catch(PDOException $e)
    {
        echo $e->getMessage();
    }
    return (FALSE);
}

function check_visits($conn, $usr, $rev_pro)
{
    $sql = "SELECT * FROM review";
    try
    {
        foreach($conn->query($sql) as $row)
        {
            if ($row['User_Id'] == $usr && $row['Review'] == $rev_pro && $row['Visited'] == 'Yes')
                return (TRUE);
        }
    }
    catch(PDOException $e)
    {
        echo $e->getMessage();
    }
    return (FALSE);
}

function update_data($conn, $usr, $rev_pro)
{
    $sql = "INSERT INTO review(User_Id, Visited, Liked, Review) VALUES(
        '$usr', 'Yes', 'No', '$rev_pro')";
    try
    {
        $conn->exec($sql);
    }
    catch(PDOException $e)
    {
        echo $e->getMessage();
    }
}

function rating($conn, $usr)
{
    $i = 0;
    $j = 0;
    $sql = "SELECT User_Id FROM review WHERE User_Id='$usr' AND Visited='Yes'";
    try
    {
        foreach($conn->query($sql) as $row)
            $i++;
        $sql = "SELECT User_Id FROM users WHERE User_Id != '$usr'";
        foreach($conn->query($sql) as $row)
            $j++;
        if ($j != 0)
            $i = $i / $j * 100;
    }
    catch(PDOException $e)
    {
        echo $e->getMessage();
    }
    return($i);
}
function get_age($conn, $usr)
{
    $sql = "SELECT DOB FROM users WHERE User_Id = '$usr'";
    $yr = date("Y");
    $mnth = date("n");
    $day = date("j");
    try
    {
        foreach($conn->query($sql) as $row)
        {
            $r = explode("-", $row['DOB']);
            $yrs = $r[0];
            $mth = $r[1];
            $dy = $r[2];
            $yrs = $yr - $yrs;
            if ($mnth - $mth < 0)
                $yrs = $yrs - 1;
            else if ($day - $dy < 0)
                $yrs = $yrs - 1;
            return ($yrs);
        }
    }
    catch(PDOException $e)
    {
        echo $e->getMessage();
    }
    return (FALSE);
}
function recent_data($conn, $usr, $rev_pro)
{
    $a_u = get_age($conn, $usr);
    $a_r = get_age($conn, $rev_pro);
    $r_u = rating($conn, $usr);
    $r_r = rating($conn, $rev_pro);

    $sql = "UPDATE users SET Rating='$r_u', Age='$a_u' WHERE User_Id='$usr'";
    $sql2 = "UPDATE users SET Rating='$r_r' Age='$a_r' WHERE User_Id='$rev_pro'";
    try
    {
        $conn->exec($sql);
        $conn->exec($sql2);
    }
    catch(PDOException $e)
    {
        echo $e->getMessage();
    }
}
if (isset($_GET['img']) && isset($_GET['usr']) && check_user($conn, $_SESSION['login'], $_SESSION['pwd'], 2) === TRUE)
{
    $usr = get_users($conn, $_GET);
    $img = $_GET['img'];
    if (check_visits($conn, $usr, $_SESSION['login']) === FALSE)
    {
        update_data($conn, $usr, $_SESSION['login']);
        recent_data($conn, $usr, $_SESSION['login']);
        header("Location: ./profile/test.php?img=$img&name=rev");
        return (TRUE);
    }
    else
    {
        recent_data($conn, $usr, $_SESSION['login']);
        header("Location: ./profile/test.php?img=$img&name=rev");
    }
}
?>