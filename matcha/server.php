<?php
session_start();
//Handles Registration
function register($conn, $data)
{
    $code = rand(30000, 99999);
    $var = check_user($conn, $data['usr_id'], "", 1);
    $str = sprintf("Welcome %s %s to Matcha: WTC's Dating site.\n\nWe are happy to have you with us.\nYour confirmation code is: %d\nUse it to finalise the registration.\n\nKind regards\n\nMatcha.", $data['f_name'], $data['l_name'], $code);
    if ($var === TRUE)
    {
        $_SESSION['err']['err_usr_id'] = "Username exists, pick a different one";
        return (FALSE);
    }
    else
    {
        mail($data['email'], "Matcha Account", $str);
        if (add_data($conn, $data, $code) === TRUE)
            return (TRUE);
    }
}
//verifies email and finalises registration
function verify_reg($conn, $data)
{
    if (verify_code($conn, $data['usr_id'], $data['code']) === TRUE)
    {
        $usr = $data['usr_id'];
        $_SESSION = FALSE;
        $sql = "SELECT Password FROM users WHERE User_Id='$usr'";
        try
        {
            $_SESSION['login'] = $usr;
            foreach($conn->query($sql) as $row);
            {
                $_SESSION['pwd'] = $row['Password'];
            }
        }
        catch(PDOException $e)
        {
            echo $e->getMessage();
        }
        return (TRUE);
    }
    else
    {
        $_SESSION['err']['code'] = "Invalid code/username";
        return (FALSE);
    }
}
//verifies and signs in the user
function login($conn, $data)
{
    if (signin($conn, $data['usr_id'], $data['pswd']) === TRUE)
        return (TRUE);
    else
        return (FALSE);
}
//function resets the user's password;
function reset_pass($conn, $data)
{
    if (verify_code($conn, $data['usr_id'], $data['code']) === TRUE)
    {
        if (reset_password($conn, $data['usr_id'], $data['pswd']) === TRUE)
            return(TRUE);
    }
}
//sends email to the verified user for password reset
function forgot($conn, $data)
{
    $code = rand(30000, 99999);
    if (check_user($conn, $data['usr_id'], "", 1) == TRUE)
    {
        $usr = $data['usr_id'];
        $query = "SELECT First_Name, Last_Name, Email FROM users WHERE User_Id='$usr'";
        foreach($conn->query($query) as $mail)
        {
            $str = sprintf("Greetings %s %s\n\n\nYour confirmation code is: %d\nUse it to finalise the password reset.\n\nKind regards\n\nMatcha.", $mail['First_Name'], $mail['Last_Name'], $code);
            mail($mail['Email'], "Matcha Password Reset", $str);
            $code = hash('whirlpool', $code);
            $sql = "UPDATE users SET Veri_Code='$code' WHERE User_Id='$usr'";
            try
            {
                $conn->exec($sql);
                $_SESSION['usr_id'] = $usr;
                return (TRUE);
            }
            catch (PDOException $e)
            {
                echo "Reset error: ".$e->getMessage();
            }
        }
    }
    $_SESSION['err']['err_usr_id'] = "Username does not exist!";
    return (FALSE);
}
?>