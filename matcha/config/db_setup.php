<?php
function db_conn($db_name, $db_usr, $db_pswd)
{
    try
    {
        $conn = new PDO("mysql:host=localhost; dbname=$db_name", $db_usr, $db_pswd);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return ($conn);
    }
    catch (PDOException $e)
    {
        echo "Connection falied: ".$e->getMessage();
    }
    return (FALSE);
}

function db_create($db_name, $usr, $pswd)
{
    try
    {
        $conn = new PDO("mysql:host=localhost;", $usr, $pswd);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = "DROP DATABASE IF EXISTS $db_name; CREATE DATABASE $db_name";
        $conn->exec($query);
        return (TRUE);
    }
    catch (PDOException $e)
    {
        echo "Error creating database $db_name".$e->getMessage();
    }
    return (FALSE);
}

function table_create($conn, $sql)
{
    try
    {
        $query = file_get_contents($sql);
        $conn->exec($query);
        return (TRUE);
    }
    catch (PDOException $e)
    {
        echo "Error creating table: <br>".$e->getMessage();
    }
    return (FALSE);
}
?>
