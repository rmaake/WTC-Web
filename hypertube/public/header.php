<div class="header">
	<div class="sitename">HyperTube</div>
	<div>
		<?php
		session_start();
		if (isset($_SESSION['login']) && isset($_SESSION['pwd']))
			echo "<a href=\"home/movies\">Home</a><a href=\"../Control/logout.php\">LogOut</a>";
		else
			echo "<a href=\"../home/movies\">Home</a><a href=\"../forms/login\">LogIn</a>";
		?>
	</div>
</div>