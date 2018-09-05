<?php
session_start();
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
	<title>Browsing</title>
	<link rel="stylesheet" type="text/css" href="http://localhost:8080/ds/public/css/Movies.css">
</head>
<body>
	<div class="header">
		<div class="sitename">HyperTube</div>
		<div>
			<a href="../server/logout">LogOut</a>
			<a href="../server/get_user_data"><?php echo $_SESSION['login'];?></a>
			<img src="<?php echo $_SESSION['pro_pic'];?>" id="pic"/>
			
		</div>
		<div id="google_translate_element"></div>
	</div>
	<div class="container">
		<h1 class="h">Movies</h1><br/>
		<div class="s">Search: <input type="text" placeholder="Search" name="mov" onkeyup="get_movie(this.value)"></div>
		<hr class="horizontal">
		<div  class="browsing" id="res">
			<?php require_once './app/views/browse/movies_list.php';?>
		</div>
		<br/>
		<div class="button">
			<button class="next" onclick="plusSlides(20)">&#10094;</button>
			<button class="prev" onclick="plusSlides(-20)">&#10095;</button>
		</div>
		<script>
			var slideIndex = 20;
			var y = document.getElementsByClassName("myimg");
			var modal = document.getElementById('modal');
			var vid = document.getElementById('vid');

			showDivs(slideIndex);
			if (slideIndex > y.length)
			{
				slideIndex = y.length;
			}
			
			function get_movie(str) 
			{
					var xmlhttp = new XMLHttpRequest();
					xmlhttp.onreadystatechange = function() 
					{
						if (this.readyState == 4 && this.status == 200) {
							document.getElementById("res").innerHTML = this.responseText;
							if (str.length == 0)
								showDivs(slideIndex);
						}
					};
					if (str.length != 0)
						xmlhttp.open("GET", "../browse/search?search=" + str, true);
					else
						xmlhttp.open("GET", "../browse/search?q=", true);
					xmlhttp.send();
			}

			function plusSlides(n) {
			  showDivs(slideIndex += n);
			}

			function showDivs(n)
			{
				var i;
				var x = document.getElementsByClassName("myimg");
				if (n > x.length)
				{
					slideIndex = 20;
				}    
				if (n < 1)
				{
					slideIndex = x.length;
				}
				for (i = 0; i < x.length; i++)
				{
					x[i].style.display = "none";  
				}
				for (i = 20; i > 0; i--)
				{
					try
					{
						x[slideIndex-i].style.display = "inline-block";
					}
					catch (err){}
				}
			}
		</script>
	</div>
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