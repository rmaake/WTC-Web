<?php
class Forms extends Controller
{
    public function login($data)
    {
        $this->view('forms/login');
    }
    public function sign_up($data)
    {
        $this->view('forms/signup');
    }
    public function get_code($data)
    {
        $this->view('forms/get_code');
    }
    public function reset($data)
    {
        $this->view('forms/reset');
    }
    public function confirm($data)
    {
        $this->view('forms/confirm');
    }
    public function profile($data)
    {
        if (isset($_GET['file']))
        {
            $name = $_SESSION['login'].".jpeg";
            file_put_contents("./$name", file_get_contents($_GET['file']));
            echo $_GET['file'];
        }
        else
            $this->view('forms/profile');
    }
}
?>