<?php
session_start();
require_once("../server.php");
require_once("../config/db_admin.php");
require_once("../config/db_setup.php");
require_once("../Control/validation.php");

$conn = db_conn($DB_NAME, $DB_USER, $DB_PASSWORD);
$_SESSION['review'] = FALSE;
function save_image($conn, $img_name, $code)
{
	$val = rand();
	if (!file_exists("../gallary/"))
		mkdir("../gallary", 0777, TRUE);
	$file = sprintf("../gallary/%s%d.jpeg", $_SESSION['login'], $val);
	if (file_exists($file))
	{
		while(file_exists($file))
		{
			$val = rand();
			$file = sprintf("../gallary/%s%d.jpeg", $_SESSION['login'], $val);
		}
	}
	file_put_contents($file, file_get_contents($img_name));
	try
	{
		$u_id = $_SESSION['login'];
		if (check_user($conn, $u_id, "", 1) === FALSE)
			return (FALSE);
		if ($code == 1)
		{
			$query = "DELETE FROM gallary WHERE User_Id ='$u_id' AND Profile_Pic='Yes'";
			$conn->exec($query);
			$query = "INSERT INTO gallary (Image_Name, User_Id, Profile_Pic)
			VALUES ('$file', '$u_id', 'Yes')";
		}
		else
		{
			$query = "INSERT INTO gallary (Image_Name, User_Id, Profile_Pic)
			VALUES ('$file', '$u_id', 'No')";
		}
		$conn->exec($query);
		return (TRUE);
	}
	catch(PDOException $e)
	{
		echo $query."<br>".$e->getMessage();
	}
	return (FALSE);
}
function get_pro_pic($conn)
{
	$usr = $_SESSION['login'];
	$query = "SELECT Image_Name, User_Id FROM gallary WHERE Profile_Pic='Yes'";
	$sql = "UPDATE users SET Status='Online' WHERE User_Id='$usr'";
	try
	{
		$conn->exec($sql);
		foreach($conn->query($query) as $row)
		{
			if ($row['User_Id'] == $_SESSION['login'])
				$_SESSION['img'] = $row['Image_Name'];
		}
	}
	catch(PDOException $e)
	{
		echo $e->getMessage();
	}
}
function delete_img($conn, $data)
{
	$sql = "SELECT Image_Name, User_Id FROM gallary";
	try
	{
		foreach($conn->query($sql) as $row)
		{
			if ($row['Image_Name'] == $data['img'] && $row['User_Id'] == $_SESSION['login'])
			{
				$img = $data['img'];
				$sql = "DELETE FROM gallary WHERE Image_Name='$img'";
				$conn->exec($sql);
				unlink($data['img']);
				header("Location: profile.php");
				return (TRUE);
			}
		}
	}
	catch(PDOException $e)
	{
		echo $e->getMessage();
	}
	return (FALSE);
}
if (isset($_POST['del']))
{
	delete_img($conn, $_POST);
	return (TRUE);
}
if (isset($_FILES['img']['tmp_name']))
{
	$img = explode("/", $_FILES['img']['type']);
	if (strcmp($img[0], "image") === 0 && getimagesize($_FILES['img']['tmp_name']) !== FALSE)
	{
		if (isset($_POST['upload']))
			save_image($conn, $_FILES['img']['tmp_name'], 2);
		else
		{
			save_image($conn, $_FILES['img']['tmp_name'], 1);
			get_pro_pic($conn);
		}
	}
		
}
else
	get_pro_pic($conn);
?>
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
		<h1 class="h">My Profile</h1><br/>
		<a href = "pro_form.php"><button class="update" type="button" name="update">Update Profile</button></a>
		<a href="visit.php"><button class="update" type="button" name="visit">Visits</button></a>
		<hr class="horizontal">
			<div>
				<div class="pro_pic block">
					<img class="pro_pic" src="<?php session_start(); if(isset($_SESSION['img'])) echo $_SESSION['img']; else echo '../resources/avatar.jpeg'; ?>">
				</div>
				<div class="personal block">
					<iframe src="user_info.php" height="100%" width="100%">About</iframe>
				</div>
				<br />
				<form action="profile.php" method="post" enctype="multipart/form-data">
					<input id="up" type="file" name="img" accept="image/*"/><br>
					<button class="chgphoto" id="ups" type="file" name="pic"><strong>Change Photo</strong></button>
				</form>
			</div>
			<div>
				<div class="slideshow-container block">
					<?php include("pic_upload.php");?>
				</div>
				<div class="bio block">
					<iframe src="user_bio.php" height="100%" width="100%">Biography</iframe>
				</div>
			</div>
			<form action="profile.php" method="post" enctype="multipart/form-data">
				<input id="up_i" type="file" name="img" accept="image/*" <?php session_start(); if ($_SESSION['max'] == "max") echo "disabled";?>/>
				<button class="chgphoto" type="submit" name="upload" <?php session_start(); if ($_SESSION['max'] == "max") echo "disabled";?>><strong>Upload image</strong></button>
			</form>
			<div class="interest">
				<iframe src="user_interest.php" height="100%" width="100%">Biography</iframe>
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