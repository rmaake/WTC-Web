<?PHP
session_start();
opcache_reset();
require_once('./config/database.php');
require_once('./config/db_conn.php');
require_once('user_data.php');
$db_name = "mysql:host=localhost;dbname=camagru";
$conn = db_conn($db_name, $DB_USER, $DB_PASSWORD);
if ($conn && $_POST['signin'] && $_POST['usr_id'] && $_POST['pswd'])
{
    $_SESSION = FALSE;
    sign_in($conn, $_POST);
}
else if ($conn && $_POST['reg'])
{
    $_SESSION = FALSE;
    sign_up($conn, $_POST);
}
else if ($conn && $_POST['veri'])
{
    $_SESSION = FALSE;
    verify_usr($conn, $_POST['usr_id']);
}
else if ($conn && $_POST['reset'])
{
    reset_password($conn, $_POST['code'], $_POST['pswd'], $_POST['rpswd']);
}
else if ($conn && $_POST['verify'])
{
    val($conn, $_POST['code']);
}

?>