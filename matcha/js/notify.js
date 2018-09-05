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
            document.getElementById("t").innerHTML = this.responseText;
        }
    };
    try
    {
        xmlhttp.open("GET", "./control/notify.php", true);
        xmlhttp.send();
    }
    catch(Exception)
    {

    }
}

window.setInterval(()=>{
    getText();
}, 5000);