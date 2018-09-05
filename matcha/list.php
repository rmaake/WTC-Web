
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Lets chats</title>
        <link rel="stylesheet" type="text/css" href="./css/list.css">
    </head>
    <body>
            <?php
                session_start();
                require_once("./server.php");
                require_once("./config/db_admin.php");
                require_once("./config/db_setup.php");
                require_once("./Control/validation.php");

                $conn = db_conn($DB_NAME, $DB_USER, $DB_PASSWORD);

                function check_to($data, $to)
                {
                    foreach($data as $usr)
                    {
                        if ($usr == $to)
                            return (TRUE);
                    }
                    return (FALSE);
                }

                function get_list($conn, $usr)
                {
                    $sql = "SELECT * FROM chats ORDER BY reg DESC";
                    $data;
                    try
                    {
                        foreach($conn->query($sql) as $row)
                        {
                            if ($row['From'] == $usr && check_to($data, $row['To']) === FALSE)
                                $data[] = $row['To'];
                        }
                        return ($data);
                    }
                    catch(PDOException $e)
                    {
                        echo $e->getMessage();
                    }
                    return (FALSE);
                }
                $data = get_list($conn, $_SESSION['login']);
                $sql = "SELECT * FROM gallary WHERE Profile_Pic='Yes'";
                try
                {
                    foreach($conn->query($sql) as $row)
                    {
                        foreach($data as $usr)
                        {
                            $img = explode("/", $row['Image_Name']);
                            if ($usr == $row['User_Id'])
                            {
                                $str2 = "<div class=\"pros\">";
                                $str3 = "</div>";
                                $str = sprintf("<img class=\"pro_pic\" src=\"./gallary/%s\" alt=\"%s\" onclick=\"chnge(this.src, this.alt)\"/><label class=\"lab\">%s</label></br>", $img[2], $usr, $usr);
                                echo $str2.$str.$str3;
                            }
                        }
                    }
                }
                catch (PDOException $e)
                {
                    echo $e->getMessage();
                }
            ?>
        <script>
            function chnge(elm, val)
            {
                try
                {
                    var x = parent.document.getElementById("p");
                    var z = parent.document.getElementById("l");
                    var w = parent.document.getElementById("usr");
                    var y = elm;
                    x.setAttribute("src", y);
                    z.innerHTML = val;
                    w.setAttribute("value", val);
                    mySubmit(val);
                }
                catch(Exception){}
            }
            function mySubmit(val)
            {
                if(window.XMLHttpRequest)
                    xmlhttp = new XMLHttpRequest();
                else
                    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                xmlhttp.onreadystatechange = function()
                {
                    if (this.readyState == 4 &&this.status == 200)
                    {
                        parent.document.getElementById("t").innerHTML = this.responseText;
                    }
                };
                xmlhttp.open("GET", "./control/text.php?usr="+val, true);
                xmlhttp.send();
            }
        </script>

    </body>
</html>