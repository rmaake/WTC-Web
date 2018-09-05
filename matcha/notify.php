<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Notifications</title>
	<link rel="stylesheet" type="text/css" href="./css/notify.css">
</head>
<body>
	<div class="header">
		<div class="sitename">Matcha</div>
		<div>
			<?php
				session_start();
				if (isset($_SESSION['login']) && isset($_SESSION['pwd']))
					echo "<a href=\"./Control/logout.php\">LogOut</a><a class=\"head\" href=\"./profile/profile.php\">Profile</a><a href=\"browsing.php\">Home</a>";
				else
				{
					header("Location: ./Forms/login.php");
					return (FALSE);
				}
			?>
		</div>
		<?php include("footer.php");?>
	</div>
	<div class="container">
		<div class="middle">
			<h1 class="h">Notifications</h1>
			<div id="notif" class="notify">
				<?php include_once("notes.php");?>
			</div>
		</div>
	</div>
	<script>
		function getNotif()
		{
			if(window.XMLHttpRequest)
				xmlhttp = new XMLHttpRequest();
			else
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			xmlhttp.onreadystatechange = function()
			{
				if (this.readyState == 4 &&this.status == 200)
				{
					document.getElementById("notif").innerHTML += this.responseText;
				}
			};
			try
			{
				xmlhttp.open("GET", "notes.php", true);
				xmlhttp.send();
			}
			catch(Exception)
			{

			}
		}

		window.setInterval(()=>{
			getNotif();
		}, 2000);
	</script>
</body>
</html>