<?php
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Browsing</title>
	<link rel="stylesheet" type="text/css" href="./css/browsing.css">
</head>
<body>
	<div class="header">
		<div class="sitename">Matcha</div>
		<div>
			<?php
				session_start();
				if (isset($_SESSION['login']) && isset($_SESSION['pwd']))
					echo "<a class=\"head\" href=\"./Control/logout.php\">LogOut</a><a class=\"head\" id=\"notif\" href=\"notify.php\">Notifications</a><a class=\"head\" id=\"cht\" href=\"chat.php\">Chat</a><a class=\"head\" href=\"./profile/profile.php\">Profile</a>";
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
		<h1 class="h">Users</h1><br/>
		<hr class="horizontal" >
		<div class="login">
            <h1 class="rh">Quick Search</h1>
            <form action="browse.php" target="results" method="get">
                <div>
                	<label><strong>Age:</strong></label>
                	<input type="text" name="age_min">
                	<label><strong>to</strong></label>
                	<input type="text" name="age_max">
                	<br/>
                	<label><strong>Fame rate:</strong></label>
                	<input type="text" name="r_min">
                	<label><strong>to</strong></label>
                	<input type="text" name="r_max"><label><strong>%</strong></label>
                	<br/>
                	
                	<label><strong>Interest:</strong></label>
                	<input type="checkbox" name="movie" value="#Movies"><label><strong>#Movies</strong></label>
                	<input type="checkbox" name="music" value="#Music"><label><strong>#Music</strong></label>
                	<input type="checkbox" name="geek" value="#Geek"><label><strong>#Geek</strong></label>
					<br/>
					<input type="checkbox" name="vegan" value="#Vegan"><label><strong>#Vegan</strong></label>
					<input type="checkbox" name="piercing" value="#Piercing"><label><strong>#Piercing</strong></label>
                    <br />
					<br />
					<input class="submit" type="submit" name="reg" value="Search" />
					<br/>
					<h1 class="rh">Filter</h1>
					<br/>
					<input type="radio" name="rad" value="age"><label><strong>Age</strong></label>
					<br/>
					<input type="radio" name="rad" value="int"><label><strong>Interest</strong></label>
					<br/>
					<input type="radio" name="rad" value="f_rating"><label><strong>Fame rating</strong></label>
					<br/>
					<input class="submit" type="submit" name="fil" value="Filter" />
                </div>
            </form>
        </div>
		<iframe name="results" src="browse.php"></iframe>
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
				xmlhttp.open("GET", "./control/not.php", true);
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