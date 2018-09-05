<?php
session_start();
class Movies
{
    private $db_name = "hypertube";
    private $db_usr = "root";
    private $db_pswd = "raps727cecil";
    private $conn;
    public $state;

    function __construct()
    {
        $this->conn = $this->db_conn();
        if ($this->conn !== FALSE)
            $this->state = TRUE;
        else
            $this->state = FALSE;
    }

    public function get_movies()
    {
        $sql = "SELECT * FROM movies ORDER BY Movie_Name ASC";
        try
        {
            foreach($this->conn->query($sql) as $row)
            {
                $mov = explode(".", $row['Movie_Name']);
                $mov = $this->validate_movie($mov);
                if ($mov === TRUE)
                    $this->show_movie($row['Movie_Name'], $row['Server_Dir'], $row['Id']);
            }
        }
        catch (PDOException $e)
        {
            echo "Something went wrong with movies: <br/>".$e->getMessage();
        }
    }
    public function search($key)
    {
        $sql = "SELECT * FROM movies ORDER BY Movie_Name ASC";
        try
        {
            foreach($this->conn->query($sql) as $row)
            {
                $mov = explode(".", $row['Movie_Name']);
                $mov = $this->validate_movie($mov);
                if ($mov === TRUE)
                    $this->reply($key, $row['Movie_Name'], $row['Server_Dir'], $row['Id']);
            }
        }
        catch(PDOException $e)
        {
            echo "search has falied:<br/>".$e->getMessage();
        }
    }
    public function play_movie($id)
    {
        require_once 'app/models/user.php';
        $dt = new User();
        $var = $dt->Authenticate($_SESSION['login'], $_SESSION['pwd']);
        if ($var === FALSE)
        {
            header("Location: ../forms/login");
            return (FALSE);
        }
        $sql = "SELECT * FROM movies";
        try
        {
            foreach($this->conn->query($sql) as $row)
            {
                if ($row['Id'] == $id)
                {
                    $str = sprintf("%s%s", $row['Server_Dir'], $row['Movie_Name']);
                    $_SESSION['src'] = $str;
                    $_SESSION['movie'] = $row['Movie_Name'];
                    $this->set_movie($row['Movie_Name'], $row['Server_Dir']);
                    return (TRUE);
                }
            }
        }
        catch (PDOException $e)
        {
            echo "Something went wrong with playing the movie: <br/>".$e->getMessage();
        }
        return (FALSE);
    }
    public function add_comment($data)
    {
        $usr = $_SESSION['login'];
        $mov = $this->get_movie($data['id']);
        $com = htmlspecialchars(strip_tags($data['com']));
        $com = addslashes($com);
        if ($mov === FALSE)
            return (FALSE);
        $str = "INSERT INTO comments(Username, Movie_Name, Comment) VALUES('$usr', '$mov', '$com')";
        try
        {
            $this->conn->exec($str);
        }
        catch(PDOException $e)
        {
            echo "Adding comment failed: <br>".$e->getMessage();
        }
    }
    public function get_comment($name)
    {
        $sql = "SELECT * FROM comments";
        try
        {
            foreach($this->conn->query($sql) as $row)
            {
                if ($row['Movie_Name'] == $name)
                {
                    $str = sprintf("<p>%s\n<strong>(comment by:<a href=\"%s\" onclick=\"return viewpro(this)\">%s</a>)</strong></p>\n", $row['Comment'], $row['Username'], $row['Username']);
                    echo $str;
                }
            }
            return (FALSE);
        }
        catch(PDOException $e)
        {
            echo "Error locating comments: <br/>".$e->getMessage();
        }
    }
    protected function reply($key, $name, $server, $id)
    {
        for($i = 0; $i < strlen($name); $i++)
        {
            if (strcasecmp($key, substr($name, $i, strlen($key))) === 0)
                $this->show_movie($name, $server, $id);
        }
    }
    protected function set_movie($nme, $server)
    {
        $usr = $_SESSION['login'];
        $str = "SELECT * FROM watched_movies";
        $sql = "INSERT INTO watched_movies(Movie_Name, Server_Dir, User_Id) VALUES('$nme', '$server', '$usr')";
        try
        {
            foreach($this->conn->query($str) as $row)
            {
                if ($row['Movie_Name'] == $nme && $row['Server_Dir'] == $server && $row['User_Id'] == $usr)
                    return (TRUE);
            }
            $this->conn->exec($sql);
        }
        catch(PDOException $e)
        {
            echo "Couldn't update movie:<br/>".$e->getMessage();
        }
    }
    protected function show_movie($name, $svr, $id)
    {
        if ($this->watched($name, $svr) === TRUE)
            $lab = sprintf("<label id=\"wtch\">%s</label>", $name);
        else
            $lab = sprintf("<label>%s</label>", $name);
        $in = sprintf("<a href=\"../browse/video?id=%s\"><img src=\"../public/resources/no_cover.jpeg\"/></a>%s", $id, $lab);
        $str = sprintf("<div class=\"myimg\">%s</div>", $in);
        echo $str;
    }
    protected function watched($name, $svr)
    {
        $sql = "SELECT * FROM watched_movies";
        try
        {
            foreach($this->conn->query($sql) as $row)
            {
                if ($row['Movie_Name'] == $name && $row['Server_Dir'] == $svr && $_SESSION['login'] == $row['User_Id'])
                    return (TRUE);
            }
        }
        catch(PDOExeption $e)
        {
            echo "Check watched_movies: <br/>".$e->getMessage();
        }
        return (FALSE);
    }
    protected function get_movie($id)
    {
        $sql = "SELECT * FROM movies";
        try
        {
            foreach($this->conn->query($sql) as $row)
            {
                if ($row['Id'] == $id)
                    return (sprintf("%s%s", $row['Server_Dir'], $row['Movie_Name']));
            }
            return (FALSE);
        }
        catch(PDOException $e)
        {
            echo "Error locating movie: <br/>".$e->getMessage();
        }
        return (FALSE);
    }
    protected function validate_movie($mov)
    {
        $i = 0;

        foreach($mov as $part)
            $i++;
        if ($mov[$i - 1] == "mp4" || $mov[$i - 1] == "MP4")
            return (TRUE);
        else
            return (FALSE);
    }
    protected function db_conn()
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