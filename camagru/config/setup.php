<?PHP
require_once('database.php');
require_once('db_conn.php');
$conn = db_conn($DB_DSN, $DB_USER, $DB_PASSWORD);
$conn = create_db($conn, $DB_USER, $DB_PASSWORD);
create_tables($conn);
if ($conn)
    header('Location: ../index.php');
?>