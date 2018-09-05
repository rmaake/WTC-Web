<?php
session_start();
function update_data($conn, $data, $log)
{
    $f_n = $data['f_name'];
    $l_n = $data['l_name'];
    $email = $data['email'];
    $gen = $data['gen'];
    $bio = sprintf("%s", $data['bio']);
    $sex = $data['sex']; //sexuality
    $dob = $data['dob'];
    if ($sex == "")
        $sex = "Bisexual";
    $int = sprintf("%s, %s, %s, %s, %s", $data['v'], $data['g'], $data['p'], $data['m'], $data['mc']);
    $query ="UPDATE users 
    SET First_Name='$f_n', Last_Name='$l_n', Email='$email', Sexuality='$sex', Biography=\"$bio\", Gender='$gen', DOB='$dob', Interests='$int'
    WHERE User_Id='$log'";
    try
    {
        $conn->exec($query);
        return (TRUE);
    }
    catch (PDOException $e)
    {
        echo "Update error:<br> ".$e->getMessage();
    }
    return (FALSE);
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

function recent_data($conn, $usr)
{
    $a_u = get_age($conn, $usr);
    if ($a_u < 0)
        $a_u = 0;
    $r_u = rating($conn, $usr);

    $sql = "UPDATE users SET Rating='$r_u', Age='$a_u', Status='Online' WHERE User_Id='$usr'";
    try
    {
        $conn->exec($sql);
    }
    catch(PDOException $e)
    {
        echo $e->getMessage();
    }
}

function add_data($conn, $data, $code)
{
    $usr = $data['usr_id'];
    $f_n = $data['f_name'];
    $l_n = $data['l_name'];
    $email = $data['email'];
    $pswd = hash('whirlpool', $data['pswd']);
    $code = hash('whirlpool', $code);
    $gen = $data['gen'];
    $dob = $data['dob'];
    $query = "INSERT INTO users (User_Id, First_Name, Last_Name, Email, Password, Veri_Code, Gender, DOB)
    VALUES ('$usr', '$f_n', '$l_n', '$email', '$pswd', '$code', '$gen', '$dob')";
    try
    {
        $conn->exec($query);
        recent_data($conn, $usr);
        return (TRUE);
    }
    catch (PDOException $e)
    {
        echo "Error inserting data:<br> ".$e->getMessage();
    }
    return (FALSE);
}

function reset_password($conn, $usr, $pswd)
{
    $pswd = hash('whirlpool', $pswd);
    if (check_user($conn, $usr, "", 1) === TRUE)
    {
        $query = "UPDATE users SET Password='$pswd' WHERE User_Id ='$usr'";
        try
        {
            $_SESSION = FALSE;
            $_SESSION['login'] = $usr;
            $_SESSION['pwd'] = $pswd;
            $conn->exec($query);
            return (TRUE);
        }
        catch (PDOException $e)
        {
            echo "Password reset failed: <br>".$e->getMessage();
        }
    }
    else
        $_SESSION['err']['err_usr_id'] = "Invalid username!";
    return (FALSE);
}
function signin($conn, $usr, $pswd)
{
    $pswd = hash('whirlpool', $pswd);
    if (check_user($conn, $usr, $pswd, 2) == TRUE)
    {
        $sql = "SELECT User_Id, reg_date FROM users WHERE User_Id='$usr'";
        try
        {
            foreach($conn->query($sql) as $row)
            {
                $_SESSION['lst'] = $row['reg_date'];
            }
        }
        catch(PDOException $e)
        {
            echo $e->getMessage();
        }
        recent_data($conn, $usr);
        return (TRUE);
    }
    else
    {
        $_SESSION['err']['login'] = "Invalid username/password";
        return (FALSE);
    }
}
?>