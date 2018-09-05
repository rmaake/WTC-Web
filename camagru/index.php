<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Welcome to Camagru</title>
	<link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>
	<div class="header">
		<div class="cama"><h1>Camagru <img src="./resources/carmra.jpeg" width="20" height="20" /></h1></div>
		<div class="logout">
			<?php 
			session_start();
			if (isset($_SESSION['login']))
			{
				$str = sprintf("<a href=\"logout.php\">Logout</a><a href=\"main.php\">Camagru</a>");
				echo $str;
			}
			else
			{
				$str = sprintf("<a href=\"signup.php\">Create account</a><a href=\"login.php\">Login</a>");
				echo $str;
			}
			?>
		</div>
    </div>
    <div class="container">
		<?php
			session_start();
			require_once('./config/database.php');
			require_once('./config/db_conn.php');
			require_once('user_data.php');
			$db_name = "mysql:host=localhost;dbname=camagru";
			$conn = db_conn($db_name, $DB_USER, $DB_PASSWORD);
			$usr = $_SESSION['login'];
			$query = "SELECT Image_Id, Image_Name, User_Id, reg_date FROM galary ORDER BY reg_date DESC";
			foreach($conn->query($query) as $row)
			{
				if (isset($_SESSION['login']) && isset($_SESSION['pswd']))
				{
					$str = sprintf("<a href=\"review.php?name=%s&usr_id=%s\"><img class=\"myimg\" src=\"%s\" alt=\"this is you\"></a>", $row['Image_Name'], $_SESSION['login'], $row['Image_Name']);
					//$_SESSION['clicked'] = $row['Image_Name'];
				}
				else
					$str = sprintf("<img class=\"myimg\" src=\"%s\" alt=\"This is you\">", $row['Image_Name']);
				echo $str;
			}
		?>
	  <br />
	  <div>
		  <button class="but" onclick="plusDivs(12)">&#10094;</button>
		  <button class="buts" onclick="plusDivs(-12)">&#10095;</button>
	  </div>
	</div>
	<script>
		var slideIndex = 12;
		var y = document.getElementsByClassName("myimg");
		showDivs(slideIndex);
		if (slideIndex > y.length)
		{
			slideIndex = y.length;
		}
		
		function plusDivs(n) {
		  showDivs(slideIndex += n);
		}

		function showDivs(n)
		{
			var i;
			var x = document.getElementsByClassName("myimg");
			if (n > x.length)
			{
				slideIndex = 12;
			}    
			if (n < 1)
			{
				slideIndex = x.length;
			}
			for (i = 0; i < x.length; i++)
			{
				x[i].style.display = "none";  
			}
			for (i = 12; i > 0; i--)
			{
				try
				{
					x[slideIndex-i].style.display = "inline-block";
				}
				catch (err)
				{
					
				}
			}
		}
	</script>
    <div class="footer">
        <p class="copyright">&copy;rmaake 2017</p>
    </div>
</body>
</html>