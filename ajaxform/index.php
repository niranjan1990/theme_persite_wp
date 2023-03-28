<?php
$query=mysql_connect("localhost","root","");
mysql_select_db("forum",$query);
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Freeze Search engine</title>
<link rel="stylesheet" href="style.css" type="text/css">
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>

<?php $query=mysql_connect("localhost","root",""); mysql_select_db("freeze",$query); ?> 
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head> 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
<title>Untitled Document</title> 
<link rel="stylesheet" href="style.css" type="text/css">
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<script type="text/javascript">
function fill(Value, gid)
{
$('#name').val(Value);
$('#gid').val(gid);
$('#display').hide();
}

$(document).ready(function(){
$("#name").keyup(function() {
var name = $('#name').val();
var gid = $('#gid').val();
if(name=="")
{
$("#display").html("");
}
else
{
$.ajax({
type: "POST",
url: "ajax.php",
data: "name="+ name ,
success: function(html){
$("#display").html(html).show();
}
});
}
});
});
</script>
</head>
<body>
<div id="content">
<?php
$val='';
$gid='';
if(isset($_POST['submit']))
{
if(!empty($_POST['name']))
{
$val=$_POST['name'];
$gid=$_POST['gid'];
}
else
{
$val='';
$gid='';
}
}
?>
<center><img src="freeze.PNG"></center>
<form method="post" action="index.php">
Search : <input type="text" name="name" id="name" autocomplete="off" value="<?php echo $val;?>">
<input type="text" name="gid" id="gid" autocomplete="off" value="<?php echo $gid;?>">
<input type="submit" name="submit" id="submit" value="Search">
</form>

<div id="display"></div>
<?php
if(isset($_POST['submit']))
{
if(!empty($_POST['name']))
{
$name=$_POST['name'];
$query3=mysql_query("SELECT * FROM usergroup WHERE title LIKE '%$name%' OR usertitle LIKE '%$name%'");
while($query4=mysql_fetch_array($query3))
{
echo "<div id='box'>";
echo "<b>".$query4['title']."</b>";
echo "<div id='clear'></div>";
echo $query4['usertitle'];
echo "</div>";
}
}
else
{
echo "No Results";
}
}
?>
</div>
</body>
</html>