function mySubmit()
{
    if(window.XMLHttpRequest)
        xmlhttp = new XMLHttpRequest();
    else
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    xmlhttp.onreadystatechange = function()
    {
        if (this.readyState == 4 &&this.status == 200)
        {
            /*var dt = document.getElementById("t");
            var td = (dt.contentWindow || dt.contentDocument);
            if (td.document)td = td.document;
            document.getElementById("t").innerHTML = this.responseText;*/
        }
    };
    try
    {
        var x = document.getElementById("usr");
        var msg = document.getElementById("txt");
        x = x.value;
        msg = msg.value;
        xmlhttp.open("GET", "./control/text.php?usr=" + x + "&txt=" + msg, true);
        xmlhttp.send();
        document.getElementById("frm").reset();
    }
    catch(Exception){}
}


function getText(val)
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
        xmlhttp.open("GET", "./control/text.php?usr="+val, true);
        xmlhttp.send();
    }
    catch(Exception)
    {

    }
}
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
        xmlhttp.open("GET", "./control/not.php", true);
        xmlhttp.send();
    }
    catch(Exception)
    {

    }
}

window.setInterval(()=>{
    var x = document.getElementById("usr");
    x = x.value;
    getText(x);
    getNotif();
    //display.innerHTML += 'Hello<br/>'; 
}, 1000);