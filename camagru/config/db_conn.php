<?php
function db_conn($db_name, $db_user, $db_pass)
{   
    try
    {
        $con = new PDO($db_name, $db_user, $db_pass);
        $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch(PDOException $e)
    {
        echo "Connection failed: ".$e->getMessage();
    }
    return ($con);
}
function create_db($conn, $db_user, $db_pass)
{
    $db_name = "mysql:host=localhost;dbname=camagru";
    try
    {
        $query = "DROP DATABASE IF EXISTS camagru;
        CREATE DATABASE camagru";
        $conn->exec($query);
        echo "<br>camagru Database successfully created.<br>";
        $conn = db_conn($db_name, $db_user, $db_pass);
        return ($conn);
    }
    catch (PDOException $e)
    {
        echo $query."<br>".$e->getMessage();
    }
}
function create_tables($conn)
{
    $query = "CREATE TABLE users (
        User_Id VARCHAR(10) PRIMARY KEY,
        First_Name VARCHAR(255) NOT NULL,
        Last_Name VARCHAR(255) NOT NULL,
        Email VARCHAR(50) NOT NULL,
        Password VARCHAR(255) NOT NULL,
        reg_date TIMESTAMP)";
    $query2 = "CREATE TABLE galary (
        Image_Id INT(11) UNSIGNED AUTO_INCREMENT NOT NULL PRIMARY KEY,
        Image_Name TEXT NOT NULL,
        User_Id varchar(10) NOT NULL,
        reg_date TIMESTAMP)";
    $query3 = "CREATE TABLE user_comment (
        Comment_Id INT(11) UNSIGNED AUTO_INCREMENT NOT NULL PRIMARY KEY,
        User_Id VARCHAR(10) NOT NULL,
        Pic_Owner VARCHAR(10) NOT NULL,
        Image_Name TEXT NOT NULL,
        Comment MEDIUMTEXT NULL,
        reg_date TIMESTAMP)";
    $query4 = "CREATE TABLE user_like (
        Like_Id INT(11) UNSIGNED AUTO_INCREMENT NOT NULL PRIMARY KEY,
        User_Id varchar(10) NOT NULL,
        Pic_Owner VARCHAR(10) NOT NULL,
        Image_Name TEXT NOT NULL,
        reg_date TIMESTAMP)";
    try
    {
        $conn->exec($query);
        $conn->exec($query2);
        $conn->exec($query3);
        $conn->exec($query4);
        echo "<br>users TABLE successfully created.<br>";
    }
    catch(PDOException $e)
    {
        echo "Error creating tables<br>".$e->getMessage();
    }
}
?>