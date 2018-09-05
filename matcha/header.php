<div class="header">
	<div class="sitename">Matcha</div>
	<div>
		<?php
		session_start();
		if (isset($_SESSION['login']) && isset($_SESSION['pwd']))
			echo "<a href=\"../index.php\">Home</a><a href=\"../Control/logout.php\">LogOut</a>";
		else
			echo "<a href=\"signup.php\">SignUp</a><a href=\"login.php\">LogIn</a>";
		?>
	</div>
</div>