<!DOCTYPE html>
<html lang="en-Us">
<head>
    <meta charset="utf-8">
    <title>Admin</title>
    <link rel="stylesheet" type="text/css" href="http://localhost:8080/ds/public/css/forms_style.css">
</head>
<body id="login">
   <?php require_once 'public/footer.php';?>
    <div class="container">
        <div class="login">
            <h1 class="h">Authorize</h1>
            <form action="../admin/sign_in" method="post">
                <div>
                    <input type="text" placeholder="Username" name="usr" value="" required="" />
                    <input type="password" placeholder="Password" name="pswd" required="" />
                    <br />
                    <br />
                    <br />
                    <input class="submit" type="submit" name="login" value="Login" />
                </div>
            </form>
        </div>
    </div>
</body>
</html>
