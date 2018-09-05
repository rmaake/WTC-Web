<?php 
session_start();
class User
{
    private $db_name = "hypertube";
    private $db_usr = "root";
    private $db_pswd = "raps727cecil";
    private $conn;
    public $state;

    public function __construct()
    {
        $this->conn = $this->db_conn();
        if ($this->conn !== FALSE)
            $this->state = TRUE;
        else
            $this->state = FALSE;
    }
    public function authenticate($usr, $pswd)
    {
        $_SESSION = FALSE;
        $sql = "SELECT * FROM users";
        try
        {
            foreach ($this->conn->query($sql) as $row)
            {
                if ($row['User_Id'] == $usr && $pswd == $row['Password'] && $row['Veri_Code'] == "Verified")
                {
                    $_SESSION['login'] = $row['User_Id'];
                    $_SESSION['pwd'] = $row['Password'];
                    $_SESSION['pro_pic'] = $row['Pro_Pic'];
                    return (TRUE);
                }
                if ($row['User_Id'] == $usr && $pswd == $row['Password'] && $row['Veri_Code'] != "Verified")
                {
                    $_SESSION['err']['cde'] = "Account has not been confirmed yet. Use confirmation code from email.";
                    return (NULL);
                }
            }
        }
        catch (PDOException $e)
        {
            echo "Authentication failed:<br/>".$e->getMessage();
        }
        $_SESSION['err']['login'] = "Incorrect username or password";
        return (FALSE);
    }
    public function register($data)
    {
        $_SESSION = FALSE;
        $_SESSION['f_name'] = $data['f_name'];
        $_SESSION['l_name'] = $data['l_name'];
        $_SESSION['usr_id'] = $data['id'];
        $_SESSION['email'] = $data['email'];
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL))
            $_SESSION['err']['err_email'] = "Invalid email address";
        if (strlen($data['email']) > 50)
            $_SESSION['err']['err_email'] = "Sorry, email cannot be more than 50 characters.";
        if (!preg_match("/^[a-zA-Z ]*$/",$data['f_name']) || !preg_match("/^[a-zA-Z ]*$/",$data['l_name']) || !preg_match("/^[a-zA-Z ]*$/",$data['usr_id']))
            $_SESSION['err']['err_name'] = "This field can only contain alphabets and white spaces";
        if (strlen($data['id']) > 15)
            $_SESSION['err']['err_usr_id'] = "Username cannot be greater than 15 characters";
        else if ($this->check_user($data['id']) === FALSE)
            $_SESSION['err']['err_usr_id'] = "Username already exists";
        if ($data['pswd'] !== $data['rpswd'])
            $_SESSION['err']['err_pswd'] = "Passwords do not match";
        if ($this->pswd_strength($data['pswd']) === FALSE)
            $_SESSION['err']['err_pswd'] = "Password too weak";
        if (strlen($data['pswd']) < 6)
            $_SESSION['err']['err_pswd'] = "Password cannot be less than six characters long";
        if (isset($_SESSION['err']))
            return (FALSE);
        $var = rand(30000, 90000);
        $usr_id = $data['id'];
        $name = $data['f_name'];
        $l_name = $data['l_name'];
        $email = $data['email'];
        $pswd = hash('whirlpool', $data['pswd']);
        //mail to be sent to users
        $msg = sprintf("Welcome, %s, we're happy you're here.", $usr_id);
        $msg = sprintf("%s\n\nConfirmation code: %d \n\nUse it to finalize your registration\n\nKind regards\nHyperTube group", $msg, $var);
        $var = hash('whirlpool', $var);

        $sql = "INSERT INTO users(User_Id, First_Name, Last_Name, Email, Password, Veri_Code)
        VALUES('$usr_id', '$name', '$l_name', '$email', '$pswd', '$var')";
        try
        {
            mail($email, "HyperTube Account", $msg);
            $this->conn->exec($sql);
            return (TRUE);
        }
        catch (PDOException $e)
        {
            echo "Registration failed:<br/>".$e->getMessage();
        }
        return (FALSE);
    }
    public function get_code($usr)
    {
        $_SESSION = FALSE;
        $var = rand(30000, 90000);
        $sql = "SELECT * FROM users";
        $sql2 = "UPDATE users SET Veri_Code='$var' WHERE User_Id='$usr'";
        $msg = "We're sorry you forgot your password.";
        $msg = sprintf("%s\n\nConfirmation code: %d \n\nUse it to reset your password\n\n Kind regards\nHyperTube group", $msg, $var);
        $var = hash('whirlpool', $var);
        $sql2 = "UPDATE users SET Veri_Code='$var' WHERE User_Id='$usr'";
        try
        {
            foreach($this->conn->query($sql) as $row)
            {
                if ($row['User_Id'] == $usr)
                {
                    mail($row['Email'], "HyperTube password reset", $msg);
                    $this->conn->exec($sql2);
                    return (TRUE);
                }
            }
        }
        catch (PDOException $e)
        {
            echo "Reset code failed:<br/>".$e->getMessage();
        }
        $_SESSION['err']['cde'] = "Username does not exist";
        return (FALSE);
    }
    public function reset_password($usr, $code, $pswd, $new)
    {
        $_SESSION = FALSE;
        if ($pswd != $new)
        {
            $_SESSION['err']['reset'] = "Passowrds do not match";
            return (FALSE);
        }
        if ($this->pswd_strength($pswd) === FALSE)
        {
            $_SESSION['err']['reset'] = "Passowrd too weak";
            return (FALSE);
        }
        $new = hash('whirlpool', $new);
        $sql = "SELECT * FROM users";
        $sql2 = "UPDATE users SET Password='$new', Veri_Code='Verified' WHERE User_Id='$usr'";
        try
        {
            foreach($this->conn->query($sql) as $row)
            {
                if ($usr == $row['User_Id'] && $code == $row['Veri_Code'])
                {
                    $this->conn->exec($sql2);
                    return (TRUE);
                }
            }
        }
        catch (PDOException $e)
        {
            echo "Password reset failed:<br/>".$e->getMessage();
        }
        $_SESSION['err']['reset'] = "Incorrect details provided";
        return (FALSE);
    }
    public function confirm($usr, $cde)
    {
        $sql = "SELECT * FROM users";
        $sql2 = "UPDATE users SET Veri_code='Verified' WHERE User_Id='$usr'";
        try
        {
            foreach($this->conn->query($sql) as $row)
            {
                if ($row['User_Id'] == $usr && $row['Veri_Code'] == $cde)
                {
                    $this->conn->exec($sql2);
                    return (TRUE);
                }
            }
        }
        catch (PDOException $e)
        {
            echo "Account confirmation falied:<br/>".$e->getMessage();
        }
        $_SESSION['err']['cde'] = "Incorrect code/username";
        return (FALSE);
    }
    public function get_pro($usr)
    {
        $sql = "SELECT * FROM users";
        try
        {
            foreach($this->conn->query($sql) as $row)
            {
                if ($row['User_Id'] == $usr)
                    return ($row['Pro_Pic']);
            }
        }
        catch(PDOException $e)
        {
            echo "get user data failed: <br>".$e->getMessage();
        }
        return ("../public/resources/no_pic.jpg");
    }
    public function get_user($usr)
    {
        $sql = "SELECT * FROM users";
        try
        {
            foreach($this->conn->query($sql) as $row)
            {
                if ($row['User_Id'] == $usr)
                {
                    $_SESSION['f_name'] = $row['First_Name'];
                    $_SESSION['l_name'] = $row['Last_Name'];
                    $_SESSION['email'] = $row['Email'];
                    return (TRUE);
                }
            }
        }
        catch(PDOException $e)
        {
            echo "get user data failed: <br>".$e->getMessage();
        }
    }
    public function update_user($data)
    {
        $usr = $_SESSION['login'];
        $name = $data['f_name'];
        $l_name = $data['l_name'];
        $id = $data['usr_id'];
        $email = $data['email'];
        $str = "SELECT * FROM users";
        $sql = "UPDATE users SET User_Id='$id', Email='$email', First_Name='$name', Last_Name='$l_name' WHERE User_Id='$usr'";
        $sql2 = "UPDATE watched_movies SET User_Id='$id' WHERE User_Id='$usr'";
        $sql3 = "UPDATE comments SET Username='$id' WHERE Username='$usr'";
        try
        {
            foreach($this->conn->query($str) as $row)
            {
                if ($row['User_Id'] == $usr)
                {
                    $_SESSION['login'] = $id;
                    $this->conn->exec($sql);
                    $this->conn->exec($sql2);
                    $this->conn->exec($sql3);
                    return (TRUE);
                }
            }
        }
        catch (PDOException $e)
        {
            echo "error updating data: <br/>".$e->getMessage();
        }
        return (FALSE);
    }
    public function check_user($usr)
    {
        $sql = "SELECT * FROM users";
        try
        {
            foreach($this->conn->query($sql) as $row)
            {
                if ($row['User_Id'] == $usr)
                    return (FALSE);
            }
        }
        catch (PDOException $e)
        {
            echo "Authentication failed:<br/>".$e->getMessage();
        }
        return (TRUE);
    }
    public function confirm_session($usr, $pswd)
    {
        $sql = "SELECT * FROM users";
        try
        {
            foreach ($this->conn->query($sql) as $row)
            {
                if ($row['User_Id'] == $usr && $pswd == $row['Password'] && $row['Veri_Code'] == "Verified")
                    return (TRUE);
                if ($row['User_Id'] == $usr && $pswd == $row['Password'] && $row['Veri_Code'] != "Verified")
                    $_SESSION['err']['cde'] = "Account has not been confirmed yet. Use confirmation code from email.";
                    return (NULL);
            }
        }
        catch (PDOException $e)
        {
            echo "Authentication failed:<br/>".$e->getMessage();
        }
    }
    private function pswd_strength($pswd)
    {
        $new = str_split($pswd);
        $i = 0;
        foreach($new as $char)
        {
            if ($char >= '!' && $char <= '/')
                $i++;
            if ($char >= ':' && $char <= '@')
                $i++;
            if ($char >= '[' && $char <= '`')
                $i++;
            if ($char >= '{' && $char <= '~')
                $i++;
        }
        if($i >= 2)
            return (TRUE);
        else
            return (FALSE);
    }
    private function db_conn()
    {
        $db_usr = $this->db_usr;
        $db_pswd = $this->db_pswd;
        $db_name = $this->db_name;
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
}
?>