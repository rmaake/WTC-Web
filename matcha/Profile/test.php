<?php
session_start();
require_once("../server.php");
require_once("../config/db_admin.php");
require_once("../config/db_setup.php");
require_once("../Control/validation.php");

$conn = db_conn($DB_NAME, $DB_USER, $DB_PASSWORD);

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

function get_review($conn, $img)
{
    if (isset($img['img']))
    {
        $sql = "SELECT User_Id, Image_Name, Profile_Pic FROM gallary";
        try
        {
            foreach ($conn->query($sql) as $row)
            {
                if ($row['Image_Name'] == $img['img'])
                {
                    $_SESSION['review']['usr'] = $row['User_Id'];
                    if (check_visits($conn, $row['User_Id'], $_SESSION['login']) == FALSE)
                        update_data($conn, $row['User_Id'], $_SESSION['login']);
                }
            }
            foreach ($conn->query($sql) as $row)
            {
                if ($row['Profile_Pic'] == "Yes" && $_SESSION['review']['usr'] == $row['User_Id'])
                {
                    $_SESSION['review']['img'] = $row['Image_Name'];
                    header("Location: viewpro.php");
                    return (TRUE);
                }
            }
        }
        catch (PDOException $e)
        {
            echo $e->getMessage();
        }
        return (FALSE);
    }
}
function is_reviewed($conn, $usr, $rev, $cde)
{
    $sql = "SELECT * FROM review";
    try
    {
        foreach($conn->query($sql) as $row)
        {
            if ($row['User_Id'] == $usr && $row['Review'] == $rev && $cde == 1)
                return  (TRUE);
            if ($row['User_Id'] == $usr && $row['Review'] == $rev && $row['Liked'] == "Yes" && $cde == 2 && $row['Blocked'] != "Yes")
                return(TRUE);
        }
    }
    catch(PDOException $e)
    {
        echo $e->getMessage();
    }
    return (FALSE);
}

function set_like($conn, $img)
{
    if (isset($img['img']))
    {
        $sql = "SELECT User_Id, Image_Name, Profile_Pic FROM gallary";
        try
        {
            foreach ($conn->query($sql) as $row)
            {
                if ($row['Image_Name'] == $img['img'])
                {
                    $usr = $row['User_Id'];
                    $rev = $_SESSION['login'];
                    if (is_reviewed($conn, $usr, $rev, 1) === TRUE)
                        $sql = "UPDATE review SET Liked='Yes' WHERE Review='$rev' AND User_Id='$usr'";
                    else
                        $sql = "INSERT INTO review(User_Id, Liked, Visited, Review) VALUES ('$usr', 'Yes', 'Yes', '$rev')";
                    $conn->exec($sql);
                    header("Location: viewpro.php");
                    return (TRUE);
                }
            }
        }
        catch (PDOException $e)
        {
            echo $e->getMessage();
        }
        return (FALSE);
    }
}

function unlike($conn, $img)
{
    if (isset($img['img']))
    {
        $sql = "SELECT User_Id, Image_Name, Profile_Pic FROM gallary";
        try
        {
            foreach ($conn->query($sql) as $row)
            {
                if ($row['Image_Name'] == $img['img'])
                {
                    $usr = $row['User_Id'];
                    $rev = $_SESSION['login'];
                    if (is_reviewed($conn, $usr, $rev, 1) === TRUE)
                        $sql = "UPDATE review SET Liked='Not' WHERE Review='$rev' AND User_Id='$usr'";
                    $conn->exec($sql);
                    header("Location: viewpro.php");
                    return (TRUE);
                }
            }
        }
        catch (PDOException $e)
        {
            echo $e->getMessage();
        }
        return (FALSE);
    }
}

function block_usr($conn, $img)
{
    if (isset($img['img']))
    {
        $sql = "SELECT User_Id, Image_Name, Profile_Pic FROM gallary";
        try
        {
            foreach ($conn->query($sql) as $row)
            {
                if ($row['Image_Name'] == $img['img'])
                {
                    $usr = $row['User_Id'];
                    $rev = $_SESSION['login'];
                    if (is_reviewed($conn, $usr, $rev, 1) === TRUE)
                    {
                        $sql = "UPDATE review SET Blocked='Yes' WHERE Review='$rev' AND User_Id='$usr'";
                        $conn->exec($sql);
                        $sql = "UPDATE review SET Visited='Not' WHERE Review='$rev' AND User_Id='$usr'";
                        $conn->exec($sql);
                        $sql = "UPDATE review SET Liked='No' WHERE Review='$rev' AND User_Id='$usr'";
                        $conn->exec($sql);
                        $sql = "DELETE FROM review WHERE User_Id='$rev' AND Review='$usr'";
                        $conn->exec($sql);
                        $sql = "DELETE FROM chats WHERE `From`='$rev' AND `To`='$usr'";
                        $conn->exec($sql);
                        $sql = "DELETE FROM chats WHERE `From`='$usr' AND `To`='$rev'";
                        $conn->exec($sql);
                    }
                    header("Location: ../browsing.php");
                    return (TRUE);
                }
            }
        }
        catch (PDOException $e)
        {
            echo $e->getMessage();
        }
        return (FALSE);
    }
}

if ($_GET['name'] == "rev")
    get_review($conn, $_GET);
else if ($_GET['name'] == "like")
    set_like($conn, $_GET);
else if ($_GET['name'] == "unlike")
    unlike($conn, $_GET);
else if ($_GET['name'] == "block")
    block_usr($conn, $_GET);
else if ($_GET['name'] == "chat")
{
    //for chats
}

$dir = $_SESSION['review']['img'];
$src = "../resources/like.png";
if (is_reviewed($conn, $_SESSION['review']['usr'], $_SESSION['login'], 2) === FALSE)
{
    $img = sprintf("<a href=\"test.php?img=%s&name=like\"><img src=\"%s\" width=\"70\" height=\"60\"></a>", $dir, $src);
    $src = "../resources/block.png";
    $img = sprintf("%s<a href=\"test.php?img=%s&name=block\"><img src=\"%s\" width=\"70\" height=\"60\"></a>", $img, $dir, $src);
}
else
{
    $src = "../resources/red.gif";
    $img = sprintf("<a href=\"test.php?img=%s&name=unlike\"><img src=\"%s\" width=\"70\" height=\"60\"></a>", $dir, $src);
    $src = "../resources/block.png";
    $img = sprintf("%s<a href=\"test.php?img=%s&name=block\"><img src=\"%s\" width=\"70\" height=\"60\"></a>", $img,  $dir, $src);
    if (is_reviewed($conn, $_SESSION['login'], $_SESSION['review']['usr'], 2) === TRUE)
        $img = sprintf("%s\n<a href=\"../chat_server.php?usr=%s&img=%s\"><img src=\"../resources/chats.png\" width=\"70\" height=\"65\"></a>", $img, $_SESSION['review']['usr'], $_SESSION['review']['img']);
}
echo $img;
?>