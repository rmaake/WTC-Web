<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <style>
      * {box-sizing:border-box}
      body {font-family: Verdana,sans-serif;margin:0}
      .mySlides {display:none}

      /* Slideshow container */
      .slideshow-container {
        max-width: 1000px;
      }

      /* Next & previous buttons */
      .prev, .next {
        cursor: pointer;
        position: absolute;
        top: 50%;
        width: auto;
        padding: 16px;
        margin-top: -22px;
        color: white;
        font-weight: bold;
        font-size: 18px;
        transition: 0.6s ease;
        border-radius: 0 3px 3px 0;
      }

      /* Position the "next button" to the right */
      .next {
        right: 0;
        border-radius: 3px 0 0 3px;
      }

      /* On hover, add a black background color with a little bit see-through */
      .prev:hover, .next:hover {
        background-color: rgba(0,0,0,0.8);
      }

      /* Caption text */
      .text {
        color: #ae9e78;
        font-size: 15px;
        padding: 8px 12px;
        position: absolute;
        bottom: 8px;
        width: 100%;
        text-align: center;
      }

      /* Number text (1/3 etc) */
      .numbertext {
        color: #f2f2f2;
        font-size: 12px;
        padding: 8px 12px;
        position: absolute;
        top: 0;
      }

      .active:hover {
        background-color: #717171;
      }

      /* Fading animation */
      .fade {
        -webkit-animation-name: fade;
        -webkit-animation-duration: 1.5s;
        animation-name: fade;
        animation-duration: 1.5s;
      }

      @-webkit-keyframes fade {
        from {opacity: .4} 
        to {opacity: 1}
      }

      @keyframes fade {
        from {opacity: .4} 
        to {opacity: 1}
      }

      /* On smaller screens, decrease text size */
      @media only screen and (max-width: 300px) {
        .prev, .next,.text {font-size: 11px}
      }
    </style>
  </head>
  <body>
  
    <div class="slideshow-container">
            <?php
                session_start();
                require_once("../server.php");
                require_once("../config/db_admin.php");
                require_once("../config/db_setup.php");
                require_once("../Control/validation.php");

                $conn = db_conn($DB_NAME, $DB_USER, $DB_PASSWORD);
                if (check_user($conn, $_SESSION['login'], $_SESSION['pwd'], 2) === FALSE)
                {
                    header("Location: ../Forms/login.php");
                    return (FALSE);
                }
                $usr = $_SESSION['login'];
                if (isset($_SESSION['review']['usr']) && check_user($conn, $_SESSION['review']['usr'], "", 1) === TRUE)
                  $usr = $_SESSION['review']['usr'];
                $i = 0;
                $j = 1;
                $sql = "SELECT Image_Name FROM gallary WHERE User_Id='$usr' AND Profile_Pic='No' ORDER BY Id DESC";
                try
                {
                    foreach($conn->query($sql) as $row)
                      $i++;
                    if ($i >= 4)
                      $_SESSION['max'] = "max";
                    else
                      $_SESSION['max'] = "min";
                    foreach($conn->query($sql) as $row)
                    {
                        $img = $row['Image_Name'];
                        $str = sprintf("<div class=\"numbertext\">%s / %s</div>", $j, $i);
                        if (!isset($_SESSION['review']['usr']))
                          $str4 = sprintf("<button class=\"chgphoto\" type=\"submit\" name=\"del\"><strong>Delete image</strong></button>");
                        $str3 = sprintf("<div class=\"text\"><form action=\"profile.php\" method=\"post\">%s<input type=\"hidden\" name=\"img\" value=\"%s\"/></form></div>", $str4, $row['Image_Name']);
                        echo "<div class=\"mySlides fade\">";
                        echo $str;
                        echo "<img src=\"$img\" width=\"100%\" height=\"199.25px\"/>";
                        echo $str3;
                        echo "</div>";
                        $j++;
                    }
                }
                catch (PDOException $e)
                {
                    echo $e->getMessage();
                }
                if ($i == 0)
                {
                    $str = sprintf("<div class=\"numbertext\">%s / %s</div>", $i, $i);
                    $str3 = sprintf("<div class=\"text\"><p>Upload some images</p></div>");
                    echo "<div class=\"mySlides fade\">";
                    echo $str;
                    echo "<img src=\"../resources/avatar.jpeg\" width=\"100%\" height=\"199.25px\"/>";
                    echo $str3;
                    echo "</div>";
                }
            ?>
      <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
      <a class="next" onclick="plusSlides(1)">&#10095;</a>
    </div>

    <script>
        var slideIndex = 1;
        showSlides(slideIndex);

        function plusSlides(n) {
          showSlides(slideIndex += n);
        }

        function currentSlide(n) {
          showSlides(slideIndex = n);
        }

        function showSlides(n) {
          var i;
          var slides = document.getElementsByClassName("mySlides");
          if (n > slides.length) {slideIndex = 1}    
          if (n < 1) {slideIndex = slides.length}
          for (i = 0; i < slides.length; i++) {
              slides[i].style.display = "none";  
          }
          try
          {
            slides[slideIndex-1].style.display = "block";
          }
          catch (Exception)
          {

          }
        }
    </script>

  </body>
</html> 
