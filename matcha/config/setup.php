<?php
include("db_setup.php");
include("db_admin.php");
$conn = db_create($DB_NAME, $DB_USER, $DB_PASSWORD);
function setup($conn, $db_name, $db_usr, $db_pass)
{   if ($conn !== FALSE)
    {
        $conn = db_conn($db_name, $db_usr, $db_pass);
        if ($conn !== FALSE)
        {
            table_create($conn, "./sql/users.sql");
            table_create($conn, "./sql/gallary.sql");
            table_create($conn, "./sql/review.sql");
            table_create($conn, "./sql/chat.sql");
            header("Location: ../forms/login.php");
        }
    }
}
if (isset($_POST['usr']) && isset($_POST['pwd']))
{
    if (strcmp($_POST['usr'], "rmaake") == 0 && strcmp($DB_PASSWORD, $_POST['pwd']) == 0)
        setup($conn, $DB_NAME, $DB_USER, $DB_PASSWORD);
    else
        echo "Restricted!</br>Contact administrator";
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="../css/forms_style.css">
</head>
<body id="login">
    <?php include("../footer.php");?>
    <div class="container">
        <div class="login">
            <h1 class="h">Log In</h1>
            <form action="setup.php" method="post">
                <div>
                    <input type="text" placeholder="Username" name="usr" value="" required="" />
                    <input type="password" placeholder="Password" name="pwd" required="" />
                    <br />
                    <br />
                    </nav>
                    <br />
                    <input class="submit" type="submit" name="login" value="Login" />
                </div>
            </form>
        </div>
    </div>
</body>
</html>