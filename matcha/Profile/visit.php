<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Recent likes and visits</title>
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
		<h1 class="h">My Profile</h1><br/>
		<a href = "pro_form.php"><button class="update" type="button" name="update">Update Profile</button></a>
		<a href="profile.php"><button class="update" type="submit" name="userP"><strong>Profile</strong></button></a>
		<hr class="horizontal" />
		<label class="left">Recent likes</label> <label class="right">Recent visits</label>
		<div class="iframe">
			<iframe class="like_visit block" src="review.php"></iframe>
			<iframe class="like_visit block" src="reviews.php"></iframe>
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