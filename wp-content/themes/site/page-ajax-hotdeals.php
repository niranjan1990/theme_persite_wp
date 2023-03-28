<?php /* Template Name: Ajax Hotdeals Page */
	//Created by Siva on 11th Aug 2017
	require $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php';
	require $_SERVER['DOCUMENT_ROOT'] . '/wp-config-extra.php';
	require $_SERVER['DOCUMENT_ROOT'] . '/wp-reviewconfig.php';
	header("Content-Type: application/json; charset=UTF-8");

error_reporting(E_ALL);
$areaid = 0;
$area = "";
$numdeals = 3;

if(isset($_GET['numdeals']))
{
	$numdeals = $_GET['numdeals'];
	if(isset($_GET['areaid']))
	{
		$areaid = $_GET['areaid'];
	}
	else
	{
		$areaid = 0;
	}


	if(isset($_GET['area']))
	{
		$area = $_GET['area'];
		if($area == "reviews")
		{
			$areaids = "venice_ids";
		}
		else if($area == "forums")
		{
			$areaids = "forum_ids";
		}
		else if($area == "blogs")
		{
			$areaids = "blog_ids";
		}
		else if($area == "galleries")
		{
			$areaids = "gallery_ids";
		}
		else if($area == "classifieds")
		{
			$areaids = "classified_ids";
		}
		else
		{
		}
	}
	else
	{
		$area = "";
		$areaids = "";
	}
	$PSite = explode(":", get_option( 'PSiteName' ) );


	if($areaid > 0 && $areaids != '')
	{
		$loc_sql = "AND locate('$areaid',$areaids) > 0 order by locate('$areaid',$areaids), rand()";
	}
	else if($areaid == 0 && $areaids != '')
        {
		$loc_sql = "order by $areaids, rand()";
        }
        else
	{
		$loc_sql = "ORDER BY rand()";
	}


	$sql = "SELECT LinkID, Link_Name, Graphic, REPLACE(Partner_Product_Text ,'|', ' ') as Partner_Product_Text, Sale_Price, Original_Price, cp.Partner_Graphic, c.valid, l.valid, c.expired FROM partner_campaigns c JOIN partner_links l  On c.CampaignID = l.CampaignID JOIN commerce_partners cp  On cp.PartnerID = c.PartnerID WHERE campaign_type = 25 And l.channelid = $PSite[0] and c.valid = 1 and l.valid = 1 and curdate() Between start_date AND end_date $loc_sql LIMIT $numdeals";

						$con = mysqli_connect(DB_RHOST,DB_RUSER,DB_RPASSWORD,DB_RNAME) or die("Some error occurred during connection " . mysqli_error($con));

						$query=mysqli_query($con,$sql);
                                                $numberofrows = mysqli_num_rows($query);
        if ($numberofrows == 0)
        {
	$sql = "SELECT LinkID, Link_Name, Graphic, REPLACE(Partner_Product_Text ,'|', ' ') as Partner_Product_Text, Sale_Price, Original_Price, cp.Partner_Graphic, c.valid, l.valid, c.expired FROM partner_campaigns c JOIN partner_links l  On c.CampaignID = l.CampaignID JOIN commerce_partners cp  On cp.PartnerID = c.PartnerID WHERE campaign_type = 25 And l.channelid = $PSite[0] and c.valid = 1 and l.valid = 1 and curdate() Between start_date AND end_date Order by rand() LIMIT $numdeals";
						$query=mysqli_query($con,$sql);

        }

 $all = '<div class="mcl-logo">

<style>
.new-hot-deals {
    line-height: 1.2;
}
.new-hot-deals .hotdeals-price {
    color: #BF1733;
    font-size: 15px;
}
.new-hot-deals-text .old-price,
.new-hot-deals-text .hotdeals-price {
    display: block;
    margin-bottom: 0;
}
.new-hot-deals-text .old-price {
    color: #999;
}
.new-hot-deals-text .normal-weight img {
    width: 100px;
}
.firstfirstcol {
    height: 142px;
}
.firstfirstcol img{
    margin-bottom: 6px;
}
.new-hot-deals-text .hotdeal-link_name {
    display: block;
    margin: 5px 0;
}
.new-hot-deals-sidebar .new-hot-deals-img {
    margin-bottom: -4px;
}
.new-hot-deals-text .hotdeals-price, .new-hot-deals-text .old-price {
    display: block;
    margin-bottom: 0;
}
.new-hot-deals-text .old-price {
    color: #999;
}
.new-hot-deals-text .normal-weight img {
    width: 100px;
}
.firstfirstcol {
    height: 142px;
}
.firstfirstcol img {
    margin-bottom: 6px;
}
.new-hot-deals-text .hotdeal-link_name {
    display: block;
    margin: 5px 0;
}
.new-hot-deals h4{
    line-height: 1.2;
}
.hotdeal-buy-all-mer, .hotdeal-buy-all-mer-list {
    font: 16px Oswald-Regular, sans-serif;
}
.hotdeal-buy-all-mer, .hotdeal-buy-all-mer-fml, .hotdeal-buy-all-mer-list {
    height: 33px;
    color: #fff;
    background-color: #BF1733;
    position: relative;
    text-align: center;
}
.hotdeal-buy-all-mer {
    width: 77px;
    line-height: 33px;
}
#title-bar{width: 304px;background: #000;font: 16px Oswald ,sans-serif;text-transform: uppercase; height: 34px;}
.side-bar-box-hd #side-bar-box .new-hot-deals-sidebar tbody tr {
    border: solid 2px #999;
}
#content-right .new-hot-deals-sidebar .clickable-row {
    border: solid 2px #999;
    border-top: none;
}
#content-right .new-hot-deals-sidebar:first-child .clickable-row {
    border-top: solid 2px #999;
}.side-bar-box-hd #side-bar-box .new-hot-deals-sidebar tbody tr {border: solid 2px #999;} table {  border-collapse: collapse;}.hotdeal-one { width : 301px; clear : both;} .middlecol { width: 190px; padding-top: 10px; padding-left: 10px; } .firstfirstcol, .middlecol, .lastcol { vertical-align: top !important; height: 130px; } .middlecol h4 { font-size: 14px; font-weight: bold; color: #333333 !important; padding: 0; margin: 0 0 2px 0 !important; font-family: Helvetica, Lucida Sans, Arial, Sans Serif; } strike { text-decoration: none; } span.old-price { color: #999; } .hotdeals-price { color: #BF1733; font-size: 15px; } .new-hot-deals-text { font-size: 12px; font-weight: bold; color: #000; } .firstfirstcol { width: 90px; } .hotdeal-buy { text-transform: uppercase; padding: 0 2px 0 0; font: 16px \'Oswald-Regular\',sans-serif; letter-spacing: .5px; width: 77px; height: 33px; color: #fff; background-color: #BF1733; line-height: 33px; text-align: center; } .new-hot-deals-img { width: 80px; height: 80px; padding-left: 5px; } .hotdeals-logo { width: 110px; margin-top: 5px; </style>
<h3 id="title-bar"><a href="http://www.'.$SITE_NAME.'.com/hot-deals.html" style="color:#fff !important;margin: 5px 10px 5px;float:left;">Hot Deals</a><a href="http://www.'.$SITE_NAME.'.com/hot-deals.html" style="color:#fff !important;margin: 8px 11px 6px 6px;float:right;font-size: 11px;">See All Hot Deals &gt;&gt;</a></h3></div><div id="side-bar-box" style="border:none;margin-bottom:15px;width: 304px;">';



						while($res1=mysqli_fetch_assoc($query))
						{

								$pcdn = $PRODUCTIMAGE_CDNDOMAIN."/".$PRODUCTIMAGE_S3FOLDER;
								$pnewimg3 = $pcdn.$res1['Partner_Graphic'];

								$newimg1 = explode('/',$res1['Graphic']);
								$newimg2 = count($newimg1);
								$cdn = $PRODUCTIMAGE_CDNDOMAIN."/".$PRODUCTIMAGE_S3FOLDER."/images/HotDeals/";
								$newimg3 = $cdn.$newimg1[$newimg2-1];




                                                         $res2 = str_replace('"','',$res1['Partner_Product_Text']);
                                                         //$res1['Original_Price'] = round($res1['Original_Price'],2);

							$all .= '<a href="http://www.'.$SITE_NAME.'.com/commerceredirect.html?linkid='.$res1['LinkID'].'&referrer=Homepage" target="_blank"><table class="new-hot-deals-sidebar floatleft"><tbody><tr style="min-height:130px;"><td valign="top" class="middlecol" style="    width: 190px;    padding-top: 10px;    padding-left: 10px;"><h4 style="    font-size: 14px;    font-weight: bold;    color: #333333 !important;    padding: 0;    margin: 0 0 2px 0 !important;">'.$res2.'</h4><div class="new-hot-deals-text" style="    font-size: 12px;    font-weight: bold;    color: #000;"><span class="old-price">(was <strike>'.$res1['Original_Price'].'</strike>) </span><span class="hotdeals-price">'.$res1['Sale_Price'].'</span><span class="hotdeal-link_name"><img class="hotdeals-logo" style="width: 110px;margin-top: 5px;" src="'.$pnewimg3.'"></span></div></td><td valign="top" class="firstfirstcol" style="padding-right: 10px;padding-bottom: 10px;padding-top: 10px;vertical-align: top !important;width: 90px;  position : relative;"><img class="new-hot-deals-img" style="    width: 80px;    height: 80px;    padding-left: 5px;" src="'.$newimg3.'"></a><br><div class="hotdeal-buy-all-mer" style="position: absolute; bottom: 10px;">Buy Now</div></td></tr></tbody></table></a>';
						}
					$all .= '</div>';

echo 'document.write('.json_encode($all).');';



}
else
{
	echo "Invalid Input";
}
?>
