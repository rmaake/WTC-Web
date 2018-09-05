function store(str)
{
    if (window.XMLHttpRequest)
    {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    }
    else
    {
        // code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.open("GET","http://localhost:8080/ds/movies.php?movie="+str+"&server=http://103.67.198.6/uploaded-videos/",true);
    xmlhttp.send();
}

var x = document.getElementsByTagName("a");
var i;
var y;
for(i = 5; i < x.length; i++)
{
    y = x[i].getAttribute("href");
    store(y);
}