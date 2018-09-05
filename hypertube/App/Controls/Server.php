<?php
session_start();
class Server extends Controller
{
    public function sign_in($data)
    {
       $dt = $this->model('User');
       if ($dt->state !== FALSE)
       {
           $pswd = hash('whirlpool', $_POST['pswd']);
           $val = $dt->authenticate($_POST['id'], $pswd);
            if ($val === FALSE)
               header("Location: ../forms/login");
            else if ($val === NULL)
               header("Location: ../forms/confirm");
            else
                header("Location: ../browse/movies");
       }
    }
    public function logout($data)
    {
        session_destroy();
        header("Location: ../forms/login");
    }
    public function confirm($data)
    {
        $dt = $this->model('User');
        if ($dt->state !== FALSE)
        {
            $cde = hash('whirlpool', $_POST['cde']);
            $val = $dt->confirm($_POST['id'], $cde);
            if ($val === FALSE)
                header("Location: ../forms/confirm");
            else
                header("Location: ../browse/movies");
        }
    }
    public function reg_type($data)
    {
        if (isset($_GET['reg']))
            header("Location: ../forms/sign_up");
        if (isset($_GET['42']))
            header("Location: https://api.intra.42.fr/oauth/authorize");
        if (isset($_GET['face']))
            header("Location: https://graph.facebook.com/oauth/authorize");
    }

    public function register($data)
    {
        $dt = $this->model('User');
        $var = $dt->register($_POST);
        if ($var === TRUE)
            header("Location: ../forms/confirm");
        else
            header("Location: ../forms/sign_up");
    }
    public function get_code($data)
    {
        $dt = $this->model('User');
        $var = $dt->get_code($_POST['id']);
        if ($var === TRUE)
            header("Location: ../forms/reset");
        else
            header("Location: ../forms/get_code");

    }
    public function reset_pswd($data)
    {
        $dt = $this->model('User');
        $var = $dt->reset_password($_POST['id'], hash('whirlpool', $_POST['cde']), $_POST['rpswd'], $_POST['pswd']);
        if ($var === TRUE)
            header("Location: ../browse/movies");
        else
            header("Location: ../forms/reset");
    }
    public function comments($data)
    {
        $dt = $this->model('Movies');
        if ($dt->state !== FALSE)
        {
            $usr = $this->model('User');
            if ($usr->state !== FALSE)
            {
                $val = $usr->authenticate($_SESSION['login'], $_SESSION['pwd']);
                 if ($val === FALSE)
                    header("Location: ../forms/login");
                 else if ($val === NULL)
                    header("Location: ../forms/confirm");
                 else
                    $dt->add_comment($_GET);
            }
        }
    }
    public function get_comment($data)
    {
        $dt = $this->model('Movies');
        if ($dt->state !== FALSE)
        {
            $dt->get_comment($_GET['src']);
        }
    }
    public function get_user_data($data)
    {
        $dt = $this->model('User');
        if ($dt->state !== FALSE)
        {
            $val = $dt->authenticate($_SESSION['login'], $_SESSION['pwd']);
            if ($val === FALSE)
               header("Location: ../forms/login");
            else if ($val === NULL)
               header("Location: ../forms/confirm");
            else
            {
                $dt->get_user($_SESSION['login']);
                header("Location: ../forms/profile");
            }
        }
    }
    public function update($data)
    {
        $dt = $this->model('User');
        if ($dt->state !== FALSE)
        {
            if ($this->check_data($dt, $_GET) === FALSE)
                echo "false";
            else
                echo "true";
        }
    }
    public function verify_email($data)
    {
        $cd = hash('whirlpool', $_GET['cde']);
        if ($cd != $_SESSION['cde'])
        {
            echo "Incorrect confirmation code";
            return (FALSE);
        }
        $dt = $this->model('User');
        if ($dt->state !== FALSE)
        {
            $dt->update_user($_SESSION);
        }
            
    }
    private function check_data($dt, $data)
    {
        $usr = $_SESSION['login'];
        $pswd = $_SESSION['pwd'];
        $img = $_SESSION['pro_pic'];
        //clearing the session;
        $_SESSION = FALSE;
        $_SESSION['login'] = $usr;
        $_SESSION['pwd'] = $pswd;
        $_SESSION['pro_pic'] = $img;
        //form validation
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
        if ($_SESSION['login'] != $data['id'])
            $chr = $dt->check_user($data['id']);
        if (strlen($data['id']) > 15)
            $_SESSION['err']['err_usr_id'] = "Username cannot be greater than 15 characters";
        else if ($chr == FALSE && $_SESSION['login'] != $data['id'])
            $_SESSION['err']['err_usr_id'] = "Username already exists";
        if (isset($_SESSION['err']))
            return (FALSE);
        $var = rand(30000, 90000);
        //data to be updated
        $usr_id = $data['id'];
        $name = $data['f_name'];
        $l_name = $data['l_name'];
        $email = $data['email'];
        $pswd = hash('whirlpool', $data['pswd']);
        //mail to be sent to users
        $msg = sprintf("Greetings, %s", $usr_id);
        $msg = sprintf("%s\n\nConfirmation code: %d \n\nUse it to verify your email\n\nKind regards\nHyperTube group", $msg, $var);
        $_SESSION['cde'] = hash('whirlpool', $var);
        mail($email, "Profile Update", $msg);
        return (TRUE);
    }
}
?>