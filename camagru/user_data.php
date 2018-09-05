<?PHP
session_start();
function check_user($conn, $usr, $pswd, $code)
{
    try
    {
        $query = "SELECT User_Id, Password FROM users";
        foreach($conn->query($query) as $row)
        {
            if ($row['User_Id'] == $usr && $row['Password'] == $pswd && $code == 1)
                return (TRUE);
            if ($row['User_Id'] == $usr && $code == 0)
                return (TRUE);
        }
        return (FALSE);
    }
    catch (PDOException $e)
    {
        echo $query."<br>".$e->getMessage();
    }
}
function sign_up($conn, $data)
{
    $u_id = $data['usr_id'];
    $pswd = hash('whirlpool', $data['pswd']);
    $name = $data['name'];
    $l_name = $data['l_name'];
    $email = $data['email'];
    
    if ($data['pswd'] != $data['rpswd'])
    {
        $_SESSION['name'] = $name;
        $_SESSION['l_name'] = $l_name;
        $_SESSION['email'] = $email;
        $_SESSION['pswd'] = 'no';
        $_SESSION['usr_id'] = $u_id;
        header('Location: signup.php');
        return ;
    }
    if (strlen($data['pswd']) < 6)
    {
        $_SESSION['name'] = $name;
        $_SESSION['l_name'] = $l_name;
        $_SESSION['email'] = $email;
        $_SESSION['pswd'] = 'nop';
        $_SESSION['usr_id'] = $u_id;
        header('Location: signup.php');
        return ;
    }
    if (strlen($data['email']) > 50)
    {
        $_SESSION['name'] = $name;
        $_SESSION['l_name'] = $l_name;
        $_SESSION['email'] = 'no';
        $_SESSION['usr_id'] = $u_id;
        header('Location: signup.php');
        return ;
    }
    if (strlen($data['usr_id']) > 10)
    {
        $_SESSION['name'] = $name;
        $_SESSION['l_name'] = $l_name;
        $_SESSION['email'] = $email;
        $_SESSION['pswd'] = '';
        $_SESSION['usr_id'] = 'nop';
        header('Location: signup.php');
        return ;
    }
    if (check_user($conn, $u_id, $pswd, 0))
    {
        $_SESSION['name'] = $name;
        $_SESSION['l_name'] = $l_name;
        $_SESSION['email'] = $email;
        $_SESSION['pswd'] = '';
        $_SESSION['usr_id'] = 'no';
        header('Location: signup.php');
        return ;
    }
    $code = rand(30000, 99999);
    $str = sprintf("Welcome %s %s to Camagru.\n\nWe are happy to have you with us.\nYour confirmation code is: %d\nUse it to finalise the registration.\n\nKind regards\n\nCamagru.", $name, $l_name, $code);
    $_SESSION['code'] = hash('whirlpool', $code);
    $_SESSION['usr_id'] = $u_id;
    $_SESSION['name'] = $name;
    $_SESSION['l_name'] = $l_name;
    $_SESSION['email']  = $email;
    $_SESSION['pswd'] = $pswd;
    if (mail($email, "Camagru Account", $str) === TRUE)
        header("Location: verify_email.php");
    else
    {
        $_SESSION['Error'] = "Email specified is invalid or cannot recieve mail.";
        header("Location: signup.php");
    }
}
function sign_in($conn, $data)
{
    $pswd = hash('whirlpool', $data['pswd']);
    if (check_user($conn, $data['usr_id'], $pswd, 1))
    {
        $_SESSION['login'] = $data['usr_id'];
        $_SESSION['pswd'] = $pswd;
        header('Location: main.php');
    }
    else
    {
        $_SESSION['pswd'] = 'no';
        header('Location: login.php');
    }
}
function reset_password($conn, $code, $pswd, $rpswd)
{
    $code = hash('whirlpool', $code);
    if ($_SESSION['veri_code']['code'] != $code)
    {
        $_SESSION['veri_code'] = 'nop';
        return ;
    }
    if ($pswd != $rpswd)
    {
        $_SESSION['veri_code'] = 'no';
        return ;
    }
    if (strlen($pswd) < 6)
    {
        $_SESSION['veri_code'] = 'nope';
        header('Location: reset_passwd.php');
        return ;
    }
    $code = $_SESSION['veri_code']['usr'];
    $pswd = hash('whirlpool', $pswd);
    $query = "UPDATE users SET Password='$pswd' WHERE User_Id = '$code'";
    if (check_user($conn, $code, '', 0))
    {
        try
        {
            $conn->exec($query);
            $_SESSION['login'] = $code;
            $_SESSION['pswd'] = $pswd;
            $_SESSION['veri_code'] = TRUE;
            header("Location: main.php");
            return ;
        }
        catch(PDOException $e)
        {
            echo "Update failed: ".$e->getMessage();
        }
    }
    header("Location: reset_passwd.php");
}

function verify_usr($conn, $usr_id)
{
    $query = "SELECT User_Id, Email FROM users";
    try
    {
        foreach($conn->query($query) as $row)
        {
            if ($row['User_Id'] == $usr_id)
            {
                $email = $row['Email'];
                $code = rand(30000, 99999);
                $str = sprintf("We have recieved password reset request.\n\nYour confirmation code is: %d\n\nKind regards\n\nCamagru.", $code);
                $code = hash('whirlpool', $code);
                mail($email, "Password reset", $str);
                $_SESSION['veri_code'] = array('code' => $code, 'usr' => $usr_id);
                header("Location: reset_passwd.php");
                return ;
            }
        }
        $_SESSION['veri_code'] = FALSE;
        header("Location: forgot.php");
    }
    catch (PDOException $e)
    {
        echo $query."<br>".$e->getMessage();
    }
}
function val($conn, $code)
{
    $code = hash('whirlpool', $code);
    if ($code != $_SESSION['code'])
    {
        $_SESSION['err'] = 'err';
        header('Location: verify_email.php');
        return (FALSE);
    }
    else
    {
        try
        {
            $u_id = $_SESSION['usr_id'];
            $pswd = $_SESSION['pswd'];
            $name = $_SESSION['name'];
            $l_name = $_SESSION['l_name'];
            $email = $_SESSION['email'];
            $query = "INSERT INTO users (User_Id, First_Name, Last_Name, Email, Password)
                VALUES ('$u_id', '$name', '$l_name', '$email', '$pswd')";
            $conn->exec($query);
            $_SESSION['login'] = $u_id;
            $_SESSION['name'] = '';
            $_SESSION['l_name'] = '';
            $_SESSION['email'] = '';
            $_SESSION['pswd'] = $pswd;
            $_SESSION['usr_id'] = '';
            $_SESSION['code'] = '';
            header('Location: main.php');
        }
        catch(PDOException $e)
        {
            echo $query."<br>".$e->getMessage();
        }
    }
}
?>