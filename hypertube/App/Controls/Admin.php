<?php
class Admin extends Controller
{
    protected $db_name = "hypertube";
    protected $db_usr = "root";
    protected $db_pswd = "raps727cecil";
    public function sign_in($data)
    {
        $this->view('forms/admin');
        if (($_POST['usr'] == "rmaake" || $_POST['usr'] == "pmorifi" || $_POST['usr'] == "mndlovu") && $_POST['pswd'] == "raps727cecil")
        {
            $this->db_setup();
            $this->get_movies();
            mkdir("./public/galary/", 0777, true);
        }
    }

    protected function db_setup()
    {
        $var = $this->db_create($this->db_name, $this->db_usr, $this->db_pswd);
        if ($var !== FALSE)
        {
            $var = $this->db_conn($this->db_name, $this->db_usr, $this->db_pswd);
            if ($var !== FALSE)
            {
                $this->table_create($var, "App/Core/sql/users.sql");
                $this->table_create($var, "App/Core/sql/movies.sql");
                $this->table_create($var, "App/Core/sql/comment.sql");
                $this->table_create($var, "App/Core/sql/watched_movies.sql");
            }
        }
    }

    protected function db_conn($db_name, $db_usr, $db_pswd)
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

    protected function db_create($db_name, $usr, $pswd)
    {
         try
         {
             $conn = new PDO("mysql:host=localhost;", $usr, $pswd);   
             $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
             $query = "DROP DATABASE IF EXISTS $db_name; CREATE DATABASE $db_name";
             $conn->exec($query);
             return (TRUE);
         }
         catch (PDOException $e)                                         
         {
             echo "Error creating database $db_name".$e->getMessage();               
         }
         return (FALSE);                     
    }

    protected function table_create($conn, $sql)                                          
    {
        try
        {
            $query = file_get_contents($sql);
            $conn->exec($query);
            return (TRUE);
        }
        catch (PDOException $e)
        {
            echo "Error creating table: <br>".$e->getMessage();
        }
        return (FALSE);
    }
    protected function get_movies()
    {
        $server1 = "http://103.67.198.6/uploaded-videos/";
        $server3 = "http://www.filefries.com/movies/";
        $_GET['server1'] = $server1;
        $_GET['script1'] = "./public/js/server1.js";
        $_GET['server3'] = $server3;
        $_GET['script3'] = "./public/js/server3.js";
        $_GET['server4'] = " ";
        require_once './app/core/servers/contact.php';
    }
}
?>