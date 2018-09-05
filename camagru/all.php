<?php
session_start();
opcache_reset();
require_once('./config/database.php');
require_once('./config/db_conn.php');
require_once('user_data.php');
$db_name = "mysql:host=localhost;dbname=camagru";
$conn = db_conn($db_name, $DB_USER, $DB_PASSWORD);
if (!check_user($conn, $_SESSION['login'], $_SESSION['pswd'], 1))
    header('Location: login.php');
$query = "SELECT Image_Name, User_Id, reg_date FROM galary";
$img = $_POST['img'];
try 
{
    foreach($conn->query($query) as $row)
    {
        if ($row['Image_Name'] == $img)
        {
			$query = "DELETE FROM galary WHERE Image_Name = '$img'";
			$query2 = "DELETE FROM user_like WHERE Image_Name = '$img'";
			$query3 = "DELETE FROM user_comment WHERE Image_Name = '$img'";
			unlink($img);
			$conn->exec($query);
			$conn->exec($query2);
			$conn->exec($query3);
        }
    }
}
catch (PDOException $e)
{
    echo $e->getMessage();
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Images</title>
	<link rel="stylesheet" type="text/css" href="style.css">
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
				if (isset($_SESSION['login']) && isset($_SESSION['pswd']) && $row['User_Id'] == $_SESSION['login'])
				{
					$str = sprintf("<a href=\"review.php?name=%s&usr_id=%s\"><img class=\"myimg\" src=\"%s\" alt=\"this is you\"></a>", $row['Image_Name'], $_SESSION['login'], $row['Image_Name']);
					$str2 = sprintf("<button class=\"all\" type=\"submit\" name =\"img\" value=\"%s\">remove</button>", $row['Image_Name']);
					$str3 = sprintf("<form action = \"all.php\" method =\"post\">");
					$str4 = sprintf("</form>");
					echo $str.$str3.$str2.$str4;
				}
			}
		?>
	  <br />
	  <div>
		  <button class="try" onclick="plusDivs(4)">&#10094;</button>
		  <button class="trys" onclick="plusDivs(-4)">&#10095;</button>
	  </div>
	</div>
	<div class="footer">
        <p class="copyright">&copy;rmaake 2017</p>
    </div>
	<script>
		var slideIndex = 4;
		showDivs(slideIndex);
		var x = document.getElementsByClassName("myimg");
		var y = document.getElementsByClassName("all");
		if (slideIndex > x.length || slideIndex > y.length)
		{
			slideIndex = x.length;
		}
		
		function plusDivs(n)
		{
			showDivs(slideIndex += n);
		}

		function showDivs(n) 
		{
			var i;
			var z = document.getElementsByClassName("myimg");
			var y = document.getElementsByClassName("all");
			if (n > z.length)
			{
				slideIndex = 4;
			}    
			if (n < 1)
			{
				slideIndex = z.length;
			}
			for (i = 0; i < z.length; i++)
			{
				z[i].style.display = "none";
				y[i].style.display = "none";
			}
			for(i = 4; i > 0; i--)
			{
				try
				{
					z[slideIndex-i].style.display = "inline-block";
					y[slideIndex-i].style.display = "inline-block";
				}
				catch(err)
				{

				}
			}
		}
	</script>
</body>
</html>