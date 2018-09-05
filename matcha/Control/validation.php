<?php
function check_user($conn, $user, $pswd, $code)
{
    $query = "SELECT User_Id, Password, Veri_Code FROM users";
    try
    {
        foreach($conn->query($query) as $row)
        {
            if($row['User_Id'] == $user && $code == 1)
                return (TRUE);
            if ($row['User_Id'] == $user && $row['Password'] == $pswd && $row['Veri_Code'] == "Verified" && $code == 2)
                return (TRUE);
        }
    }
    catch (PDOException $e)
    {
        echo "Error validating user: <br>".$e->getMessage();
    }
    return (FALSE);
}

function verify_code($conn, $usr, $code)
{
    $code = hash('whirlpool', $code);
    $query = "SELECT User_Id, Veri_Code FROM users";
    try
    {
        foreach($conn->query($query) as $row)
        {
            if ($usr == $row['User_Id'] && $code == $row['Veri_Code'])
            {
                $query = "UPDATE users SET Veri_Code='Verified' WHERE User_ID ='$usr'";
                $conn->exec($query);
                return (TRUE);
            }
        }
    }
    catch (PDOException $e)
    {
        echo "Error occured during verification:<br>".$e->getMessage();
    }
    return (FALSE);
}
?>