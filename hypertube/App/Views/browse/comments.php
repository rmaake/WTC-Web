<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Browsing</title>
	<link rel="stylesheet" type="text/css" href="http://localhost:8080/ds/public/css/comments.css">
</head>
<body id="body">
	<div class="header">
        <div class="sitename">Comments</div>
	</div>
	<div class="container">
		<div  class="browsing" id="browse">
		</div>
       
        <div class="usr" id="usr">
        
		</div>
		<input type="hidden" name="src" value="<?php session_start(); echo $_SESSION['src'];?>" id="t">
	</div>
    <script>
        var modal = document.getElementById("usr");
        var comment = document.getElementById("browse");
        function close_mod()
        {
            modal.style.display = "none";
            comment.style.display = "block";
        }
        function viewpro(usr)
        {
            usr = usr.innerHTML;
            
            comment.style.display = "none";
            modal.style.display = "block";

            
            if(window.XMLHttpRequest)
                xmlhttp = new XMLHttpRequest();
            else
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            xmlhttp.onreadystatechange = function()
            {
                if (this.readyState == 4 &&this.status == 200)
                {
                    document.getElementById("usr").innerHTML = this.responseText;
                }
            };
            try
            {
                xmlhttp.open("GET", "../browse/profile?usr="+usr, true);
                xmlhttp.send();
            }
            catch(Exception)
            {
        
            }
            return false;
        }
        function getNotif(x)
        {
            if(window.XMLHttpRequest)
                xmlhttp = new XMLHttpRequest();
            else
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            xmlhttp.onreadystatechange = function()
            {
                if (this.readyState == 4 &&this.status == 200)
                {
                    document.getElementById("browse").innerHTML = this.responseText;
                }
            };
            try
            {
                xmlhttp.open("GET", "../server/get_comment?src="+x, true);
                xmlhttp.send();
            }
            catch(Exception)
            {
        
            }
        }
        
        window.setInterval(()=>{
            var x = document.getElementById("t");
            x = x.value;
            getNotif(x);
        }, 1000);
    </script>
</body>
</html>