<?php
function db_conn($db_name, $db_usr, $db_pswd)
{
    try
    {
        $conn = new PDO("mysql:host=localhost;dbname=$db_name", $db_usr, $db_pswd);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return($conn);
    }
    catch (PDOException $e)
    {
        echo "Connection falied:<br/>".$e->getMessage();
    }
    return (FALSE);
}
function check_movie($conn, $name, $server)
{
    $sql = "SELECT * FROM movies";
    try
    {
        foreach($conn->query($sql) as $row)
        {
            if($row['Movie_Name'] == $name && $row['Server_Dir'] != $server)
                return (FALSE);
        }
        return (TRUE);
    }
    catch(PDOException $e)
    {
        echo $e->getMessage();
    }
    return (FALSE);
}

$conn = db_conn("hypertube", "root", "raps727cecil");
if ($conn && isset($_GET['server']) && isset($_GET['movie']))
{
    $name = $_GET['movie'];
    $server = $_GET['server'];
    $str = "INSERT INTO movies(Movie_Name, Server_Dir) VALUES ('$name', '$server')";
    try
    {
        if (check_movie($conn, $name, $server) === TRUE)
            $conn->exec($str);
    }
    catch(PDOException $e)
    {
        echo $e->getMessage();
    }
}
return ;
?>