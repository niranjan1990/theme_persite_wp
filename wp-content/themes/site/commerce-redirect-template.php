<?php 
/* Template Name: Commerece Redirect Template */ 
/* Author : Siva */
?>

<?php
//http://www.mtbr.com/commerceredirect.aspx?linkid=3961775&referrer=AllHotdeals
require_once(__DIR__."/../../../wp-config.php");
require_once(__DIR__."/../../../wp-config-extra.php");
require_once(__DIR__."/../../../wp-reviewconfig.php");

$con = mysqli_connect(DB_RHOST,DB_RUSER,DB_RPASSWORD,DB_RNAME) or die("Some error occurred during connection " . mysqli_error($con));  
									
$qry = mysqli_query($con, "select linkid,CampaignID, productid, categoryid, ChannelID, link_name, use_graphic, valid, tracking_url, Link_url, tagline, graphic, date_created, partner_product_text, match_type, sku, sale_price, original_price, venice_ids, forum_ids, blog_ids, gallery_ids, classified_ids from partner_links where LinkID='".$_GET['linkid']."'");
$res1=mysqli_fetch_assoc($qry);
$CRID = "";
$From_Paid = "";
$headers = "";

$botsarray = explode(',',get_option('bot_black_list'));

$string = $_SERVER['HTTP_USER_AGENT'];
foreach ($botsarray as $url) {
    if (strpos($string, $url) !== FALSE) 
	{ 

    }
	else
	{
		$notfound = 1;
	}
}

if($notfound == 1)
{
	//echo "<script>alert('No bot');</script>";
	$insertqry = mysqli_query($con, "INSERT INTO clicks(Referer, LinkID, CRID, Date, Destination, IPAddress,ChannelId,CampaignId,Browser,From_Paid,Valid,Headers)	VALUES	('".$_GET['referrer']."','".$_GET['linkid']."',NULL,'".date("Y-m-d h:i:s")."','".$res1['Link_url']."','".$_SERVER['REMOTE_ADDR']."','".$res1['ChannelID']."','".$res1['CampaignID']."','".$_SERVER['HTTP_USER_AGENT']."',NULL,'1','')");
	
	$update = mysqli_query($con, "UPDATE partner_campaigns SET total_clicks = total_clicks + 1,	monthly_clicks = monthly_clicks + 1, last_click_date = NOW() WHERE CampaignID='".$res1['CampaignID']."'");
	
	header('location:'.$res1['Link_url']);
		
}
else
{
	//echo "<script>alert('bot')</script>";
	header('location:'.$res1['Link_url']);
}

?>
