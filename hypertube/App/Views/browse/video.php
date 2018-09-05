<?php
session_start();
	require_once 'app/models/Movies.php';
	unset($_SESSION['movie']);
	unset($_SESSION['src']);
	$mv = new Movies();
	if ($mv->state !== FALSE)
		$mv->play_movie($_GET['id']);
	if (!isset($_SESSION['login']) || !isset($_SESSION['pwd']))
	{
		session_destroy();
		header("Location: ../forms/login");
	}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Video</title>
	<link rel="stylesheet" type="text/css" href="http://localhost:8080/ds/public/css/video.css">
</head>
<body>
	<div class="header">
		<div class="sitename">HyperTube</div>
		<div>
			<a href="../server/logout">LogOut</a>
			<a href="../browse/movies">Movies</a>
		</div>
		<div id="google_translate_element"></div>
	</div>
	<div class="container">
		<h1 class="h">Movies</h1><br/>
		<hr class="horizontal">
		<div class="movie"><?php if (isset($_SESSION['movie'])) echo $_SESSION['movie']; else echo "Name of the Movie";?></div>
		<br/>
		<div>
			<div  class="browsing block">
				<img src="../public/resources/loading.gif">
				<video id="vid" autoplay width="98%" height="98%" controls class="">
					<source src="<?php if (isset($_SESSION['src'])) echo $_SESSION['src'];?>" type="video/mp4">
					<source src="../public/resources/oops.mp4" type="video/mp4">
				</video>
			</div>
			<iframe class="block" src="../browse/comments" id="frame"></iframe>
		</div>
		<br/>
		<form action="../server/comments" method="post" id="tr">
			<input type="hidden" value="<?php echo $_GET['id'];?>" name="id" id="id" onkeypress="return check_key()"> 
			<input type="text" placeholder="Comment here" name="com" id="com" onkeypress="return check_key()"/>
			<button type="button" onclick="post_comm()" onkeypress="return check_key()">Post</button>
		</form>
	</div>
	<script>
		function check_key()
		{
			if (window.event && window.event.keyCode == 13)
			{
				post_comm();
				return false;
			}
			else
				return (true);
		}
		function post_comm()
		{
			var x = document.getElementById("id");
			var y = document.getElementById("com");
			x = x.value;
			y = y.value;
			if(window.XMLHttpRequest)
				xmlhttp = new XMLHttpRequest();
			else
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			try
			{
				document.getElementById("tr").reset();
				xmlhttp.open("GET", "../server/comments?id="+x+"&com="+y, true);
				xmlhttp.send();
			}
			catch(Exception)
			{
		
			}
		}
	</script>
	<script type="text/javascript">
        function googleTranslateElementInit()
        {
            new google.translate.TranslateElement({pageLanguage: 'en'}, 'google_translate_element');
        }
    </script>
    <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
	<?php require_once 'public/footer.php';?>
</body>
</html>