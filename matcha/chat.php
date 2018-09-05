<?php
session_start();
require_once("server.php");
require_once("./config/db_admin.php");
require_once("./config/db_setup.php");
require_once("./control/validation.php");

$conn = db_conn($DB_NAME, $DB_USER, $DB_PASSWORD);

function get_img($conn, $usr)
{
	$sql = "SELECT * FROM gallary WHERE Profile_Pic='Yes'";
	try
	{
		foreach($conn->query($sql) as $row)
		{
			if ($row['User_Id'] == $sur)
				return ($row['Image_Name']);
		}
	}
	catch(PDOException $e)
	{
		echo $e->getMessage();
	}
}
function set_it($conn, $usr)
{
	$sql = "SELECT * FROM chats";
	try
	{
		foreach($conn->query($sql) as $row)
		{
			if ($row['From'] == $usr)
			{
				$_SESSION['chat']['usr'] = $row['To'];
				$_SESSION['chat']['img'] = get_img($conn, $row['To']);
			}
		}
	}
	catch(PDOException $e)
	{
		echo $e->getMessage();
	}
}
if (!isset($_SESSION['chat']['usr']))
	set_it($conn, $_SESSION['login']);
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Lets chats</title>
	<link rel="stylesheet" type="text/css" href="./css/chat.css">
</head>
<body>
	<div class="header">
		<div class="sitename">Matcha</div>
		<div>
			<?php
			session_start();
			if (isset($_SESSION['login']) && isset($_SESSION['pwd']))
				echo "<a class=\"head\" href=\"./Control/logout.php\">LogOut</a><a id=\"notif\" class=\"head\" href=\"notify.php\">Notification</a><a class=\"head\" href=\"./profile/profile.php\">Profile</a><a class=\"head\" href=\"browsing.php\">Home</a>";
			else
			{
				header("Location: ./Forms/login.php");
				return (FALSE);
			}
			?>
		</div>
	</div>
	<?php include("footer.php");?>
	<div class="container">
		<div id="t" class="talk frame">
			<div id ="u" class="user">
				<?php
					session_start();
					if (isset($_SESSION['chat']['usr']))
					{
						$str = sprintf("<img id=\"p\" class=\"pro_pic\" src=\"./gallary/%s\"><label id=\"l\" class=\"lab\">%s</label>", $_SESSION['chat']['img'], $_SESSION['chat']['usr']);
						echo $str;
					}
				?>
			</div>
		</div>
		<iframe class="side frame" src="list.php"></iframe>
		<br/>
		<form id="frm">
			<input id="usr" type="hidden" name="usr" value="<?php session_start(); echo $_SESSION['chat']['usr'];?>"/>
			<input id="txt" class="box" type="textarea" name="txt" placeholder="Type message"/>
			<button class="button" type="button" onclick="mySubmit()" name="send">Send</button>
		</form>
	</div>
	<script src="./js/talk.js"></script>
</html>