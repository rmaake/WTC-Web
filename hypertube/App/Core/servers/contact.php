<?php
session_start();
if (isset($_GET['server1']) && isset($_GET['script1']))
{
    $svr = $_GET['server1'];
    $scr = file_get_contents($_GET['script1']);
    $str = file_get_contents($svr);
    $scr = sprintf("<script>%s</script>", $scr);
    $str = sprintf("%s%s", $str, $scr);
    echo $str;
}
if (isset($_GET['server3']) && isset($_GET['script3']))
{
    $svr = $_GET['server3'];
    $scr = file_get_contents($_GET['script3']);
    $str = file_get_contents($svr);
    $scr = sprintf("<script>%s</script>", $scr);
    $str = sprintf("%s%s", $str, $scr);
    echo $str;
}
return ;
?>