<?php
session_start();
require_once("./server.php");
require_once("./config/db_admin.php");
require_once("./config/db_setup.php");
require_once("./Control/validation.php");

$conn = db_conn($DB_NAME, $DB_USER, $DB_PASSWORD);


?>
	