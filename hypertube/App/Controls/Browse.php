<?php
class Browse extends Controller
{
    private $db_name = "hypertube";
    private $db_usr = "root";
    private $db_pswd = "raps727cecil";

    public function movies($data)
    {
        $this->view('browse/movies');
    }
    public function video($data)
    {
        $this->view('browse/video');
    }
    public function comments($data)
    {
        $this->view('browse/comments');
    }
    public function search($data)
    {
        require_once './app/views/browse/movies_list.php';
    }
    public function profile($data)
    {
        $dt = $this->model('User');
        if ($dt->state !== FALSE)
        {
            $dt->get_user($_GET['usr']);
            $span = sprintf("<span class=\"close\" onclick=\"close_mod()\">&times;</span>");
            $img = $dt->get_pro($_GET['usr']);
            $img = sprintf("<img src=\"%s\" id=\"pic\">", $img);
            $h1 = sprintf("<h1 class=\"h\">%s's Profile</h1>%s", $_GET['usr'], $img);
            $nme = sprintf("</br><label>Name: %s</label></br>", $_SESSION['f_name']);
            $l_name = sprintf("</br><label>Surname: %s</label></br>", $_SESSION['l_name']);
            $data = sprintf("%s\n%s\n", $nme, $l_name);
            $div = sprintf("<div>%s</div>", $data);
            echo $span.$h1.$div;
        }
    }
    public function upload($data)
    {
        $img = getimagesize($_FILES["img"]["tmp_name"]);
        if ($img !== FALSE && $_FILES['img']['size'] < 1000000)
        {
            $img = explode("/", $_FILES['img']['type']);
            if ($img['0'] == "image")
            {
                require_once './app/models/user.php';
                $dt = new User();
                if ($dt->state !== FALSE)
                {
                    $var = $dt->confirm_session($_SESSION['login'], $_SESSION['pswd']);
                    if ($var === FALSE)
                    {
                        header("Location: ../forms/login");
                        return (FALSE);
                    }
                }
                $img = sprintf("./public/galary/%s", $_FILES['img']['name']);
                $this->remove($data);
                file_put_contents($img, file_get_contents($_FILES['img']['tmp_name']));
                $usr = $_SESSION['login'];
                $sql = "UPDATE users SET Pro_Pic='.$img' WHERE User_Id='$usr'";
                try
                {
                    $conn = $this->db_conn();
                    $conn->exec($sql);
                    $_SESSION['pro_pic'] = ".".$img;
                    header("Location: ../forms/profile");
                    return (TRUE);
                }
                catch (PDOException $e)
                {
                    echo "Upload failed:<br>".$e->getMessage();
                }
            }
        }
    }
    public function remove($data)
    {
        require_once './app/models/user.php';
        $dt = new User();
        if ($dt->state !== FALSE)
        {
            $var = $dt->confirm_session($_SESSION['login'], $_SESSION['pswd']);
            if ($var === FALSE)
            {
                header("Location: ../forms/login");
                return (FALSE);
            }
        }
        $conn = $this->db_conn();
        $sql = "SELECT * FROM users";
        $img = "../public/resources/no_pic.jpg";
        $usr = $_SESSION['login'];
        try
        {
            foreach($conn->query($sql) as $row)
            {
                if ($_SESSION['login'] == $row['User_Id'] && $row['Pro_Pic'] != "../public/resources/no_pic.jpg")
                {
                    unlink("./app/".$row['Pro_Pic']);
                    $sql2 = "UPDATE users SET Pro_Pic='$img' WHERE User_Id='$usr'";
                    $conn->exec($sql2);
                    $_SESSION['pro_pic'] = $img;
                    return (TRUE);
                }
            }
        }
        catch(PDOException $e)
        {
            echo "Error removing pic:<br/>".$e-getMessage();
        }
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
