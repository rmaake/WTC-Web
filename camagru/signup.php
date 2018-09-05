<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Sign Up</title>
        <link rel="stylesheet" type="text/css" href="style.css" />
    </head>
    <body>
        <div class="header">
            <div class="cama"><h1>Camagru <img src="./resources/carmra.jpeg" width="20" height="20" /></h1></div>
            <div class="logout"><a href="index.php" text-color = "white">Gallary</a> <a href="login.php">Login</a></div>
        </div>
        <div class="login">
            <h1 class="sec_h">Create account</h1>
            <form action="server.php" method="post">
                <div class="container">
                    <input type="text" placeholder="First name" name="name" value="<?php session_start(); echo $_SESSION['name'];?>"required="" />
                    <input type="text" placeholder="Surname" name="l_name" value="<?php session_start(); echo $_SESSION['l_name'];?>"required="" />
                    <input type="text" placeholder="Username" name="usr_id" value="<?php session_start(); if ($_SESSION['usr_id'] != 'no' && $_SESSION['usr_id'] != 'nop') echo $_SESSION['usr_id'];?>"required="" /> <?php session_start(); if ($_SESSION['usr_id'] == 'no'){echo "Username already exists in database\n";}
                    else if ($_SESSION['usr_id'] = 'nop'){echo "Username cannot be greater than 10 characters long.";}?>
                    <input type="Email" placeholder="Email address" name="email" value="<?php session_start(); if ($_SESSION['email'] != 'no') echo $_SESSION['email'];?>"required="" /><?php session_start(); if ($_SESSION['email'] == 'no'){echo "Email cannot be longer than 50 characters\n";}?>
                    <input type="password" placeholder="Password" name="pswd" required="" /><?php session_start(); if ($_SESSION['pswd'] == 'nop'){echo "Password should be atleast 6 characters long.\n";}?>
                    <input type="password" placeholder="Cormfirm password" name="rpswd" required="" />
                    <?php
                    session_start();
                    if ($_SESSION['pswd'] == 'no')
                        echo "Password don't match\n";
                    if (isset($_SESSION['error']))
                        echo $_SESSION['error']."<br>";
                    ?>
                    <br />
                    <br />
                    <input class="log" type="submit" name="reg"/>
                </div>
            </form>
        </div>
        <div class="footer">
            <p class="copyright">&copy;rmaake 2017</p>
        </div>
    </body>
</html>