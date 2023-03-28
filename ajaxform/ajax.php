<?php
include('../wp-reviewconfig.php');
error_reporting(E_ALL ^ E_DEPRECATED);

$query=mysql_connect(DB_RHOST,DB_RUSER,DB_RPASSWORD);
mysql_select_db(DB_RNAME,$query);

if(isset($_POST['name']))
{
$name=trim($_POST['name']);


if($_POST['filter']=='golf-clubs')
{
		$query2=mysql_query("SELECT * FROM search WHERE search_term LIKE '%$name%' and category_path like '%golf-cl%' limit 10");
}
else
{
		$query2=mysql_query("SELECT * FROM search WHERE search_term LIKE '%$name%' and channelid='82' limit 10");	
}

echo "<ul id='results' style='display:block!important'>";
while($query3=mysql_fetch_array($query2))
{

?>


<li id='results' style="padding:7px" onclick='<?php 	echo $_POST['fill'];?>("<?php echo $query3['display_term']; ?>","<?php echo $query3['productid']; ?>")'>
	<?php echo $query3['display_term']; ?>
</li>






<?php
}
}
?>
</ul>