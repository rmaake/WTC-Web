<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>User Profile</title>
	<link rel="stylesheet" type="text/css" href="../css/pro_style.css">
</head>
<body id="login">
	<div class="header">
		<div class="sitename">Matcha</div>
		<div>
			<?php
				session_start();
				if (isset($_SESSION['login']) && isset($_SESSION['pwd']))
					echo "<a href=\"../Control/logout.php\">LogOut</a><a id=\"notif\" class=\"head\" href=\"../notify.php\">Notifications</a><a href=\"../browsing.php\">Home</a>";
				else
				{
					header("Location: ../Forms/login.php");
					return (FALSE);
				}
			?>
		</div>
	</div>
    <?php include("../footer.php");?>
	<div class="container">
		<h1 class="h"><?php session_start(); if(isset($_SESSION['review']['usr'])) echo $_SESSION['review']['usr']."'s Profile"?></h1><br/>
		<hr class="horizontal" />
			<div>
				<div class="pro_pic block">
					<img class="pro_pic" src="<?php session_start(); if(isset($_SESSION['review']['img'])) echo $_SESSION['review']['img']; else echo '../resources/avatar.jpeg'; ?>">
				</div>
				<div class="personal block">
					<iframe src="user_info.php" height="100%" width="100%">About</iframe>	
				</div>
				<br />
			</div>
			<div>
				<div class="slideshow-container block">
					<?php include("pic_upload.php");?>
				</div>
				<div class="bio block">
					<iframe src="user_bio.php" height="100%" width="100%">Biography</iframe>
				</div>
			</div>
			<div class="interest">
			<iframe src="user_interest.php" height="100%" width="100%">Interests</iframe>
			</div>
			<div class="button">
				<?php include_once("test.php");?>
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
					document.getElementById("notif").innerHTML = "Notifications(" + this.responseText + ")";
				}
			};
			try
			{
				xmlhttp.open("GET", "../control/not.php", true);
				xmlhttp.send();
			}
			catch(Exception)
			{

			}
		}

		window.setInterval(()=>{
			getNotif();
		}, 1000);
	</script>
</body>
</html>