<?php /* Template Name: Site Hot Deals Full Page */ ?>
<?php
	require $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php';
global $referrer,$fmlreferrer;
$referrer="HD_Partner";
$fmlreferrer="FML_Partner";

global  $pagename;
$pagename = "ALLDEALS";

//echo $_SERVER['REQUEST_URI'];
$id = explode('/',$_SERVER['REQUEST_URI']);
$whtml = explode('.html',$id[2]);

$linkname1 = str_replace("-"," ",$whtml[0]);
$linkname = str_replace("_",".",$linkname1);

?>

<?php
//$newp = explode('.html',$parts1[3]);
// For seo

require $_SERVER['DOCUMENT_ROOT'] . '/wp-config-extra.php';
global $seo_title, $seo_description, $seo_keywords;


$seo_title = str_replace("[store_name]",$linkname,$HOTDEALSINDIVIDUALTITLE);
$seo_description = str_replace("[store_name]",$linkname,$HOTDEALSINDIVIDUALDESCRIPTION);
$seo_keywords = str_replace("[store_name]",$linkname,$HOTDEALSINDIVIDUALKEYWORDS);
?>
<?php

	get_header();
?>

<style>
@media screen and (max-width: 600px) and (min-width: 0px)
#mobiletable {
    display: block !important;
}

@media screen and (max-width: 601px)
#desktoptable {
    display: none !important;
}


.more{

    float: right;
    background-color: #DF0617 !important;
    color: #fff !important;
    font-size: 16px;
    width: 140px;
    height: 25px;
    margin-right: 10px;
    border: 1px solid #fff;
    text-align: center;
    line-height: 25px;
    background: url(<?php echo $PRODUCTIMAGE_CDNDOMAIN;?>/<?php echo $PRODUCTIMAGE_S3FOLDER; ?>/Styles/images/Hotdeals/arrow.png) no-repeat 125px 5px;

}

.mobilemore{

    float: right;
    background-color: #DF0617 !important;
    color: #fff !important;
    font-size: 13px;
    width: 90px;
    height: 25px;
    margin-top: 20px;
    border: 1px solid #fff;
    text-align: center;
    line-height: 25px;
    background: url(<?php echo $PRODUCTIMAGE_CDNDOMAIN;?>/<?php echo $PRODUCTIMAGE_S3FOLDER; ?>/Styles/images/Hotdeals/arrow.png) no-repeat 125px 5px;

}


.innerheader{
text-transform: uppercase;
    padding: 0 2px 0 0;
    font: 17px 'Oswald-Regular',sans-serif;
    letter-spacing: .5px;
}
.catheader{
    text-transform: uppercase;
    padding: 3px 2px 0 0;
    font: 17px 'Oswald-Regular',sans-serif;
    letter-spacing: .5px;
height: 60px; background-color : #000; color : #fff;border: solid 6px #ccc;
}
.mobilecatheader{
    text-transform: uppercase;
    padding: 3px 2px 0 0;
    font: 17px 'Oswald-Regular',sans-serif;
    letter-spacing: .5px;
height: 84px; background-color : #000; color : #fff;border: solid 3px #ccc;
}

.title{
text-align: center;
    margin-top: 23px;

}

.nopadding{
padding:0px !important;
}

.categoryimg{

}
</style>


<?php
require_once(__DIR__.'/../../../wp-reviewconfig.php');
$con = mysqli_connect(DB_RHOST,DB_RUSER,DB_RPASSWORD,DB_RNAME) or die("Some error occurred during connection " . mysqli_error($con));




//echo $whtml[0];
//echo $count = mysqli_num_rows($qry);

?>




	<div id="content-left" class="col-sm-12">
		<div class="inner">
		<h1> <?php echo $HOTDEALSTITLESUBPAGE; ?> <?php echo $linkname;?></h1>

			<?php

				$PSite = explode(":", get_option( 'PSiteName' ) );

        $count = 0;
				$qry2 = mysqli_query($con, "SELECT    cp.PartnerID,cp.Partner_Title,cp.Partner_Graphic,LinkID,Link_Name,  cp.partner_name as Partner_Name, Graphic,REPLACE(Partner_Product_Text ,'|', ' ') as Partner_Product_Text,    Sale_Price,    Original_Price,	l.ChannelID	FROM commerce_partners cp    JOIN partner_campaigns pc On cp.PartnerID = pc.PartnerID		JOIN partner_links l  On pc.CampaignID = l.CampaignID  WHERE    l.ChannelID = $PSite[0]    And campaign_type = 25    And pc.valid = 1    And l.valid = 1 And l.linkID not in (3842291,3842290,3842288, 3842287 , 3842286, 3842285, 3842284, 3842282, 3842283) And partner_name like '".$linkname."%' ORDER BY     pc.Charge_Per_Click desc,    cp.PartnerID limit 10");
				while ($res2=mysqli_fetch_assoc($qry2))
				{
					$id = $res2['PartnerID'];
					$title=$res2['Partner_Title'];
					//$titleimg=$res2['Partner_Graphic'];


			?>
   <?php
 
$pnewimg1 = explode('/',$res2['Partner_Graphic']);
$pnewimg2 = count($pnewimg1);
$pcdn = $PRODUCTIMAGE_CDNDOMAIN."/".$PRODUCTIMAGE_S3FOLDER."/images/merchants/"; 
$pnewimg3 = $pcdn.$pnewimg1[$pnewimg2-1];
 
 ?>
<table id="desktoptable" class="table no-spacing hotdeals-table" cellpadding="0" cellspacing="0" border="0"  style="width:100%;border: solid 6px #ccc;">
				<thead><tr class=" catheader">
					<td class="nopadding col-sm-4">
		<img class="img-responsive categoryimg"  src="<?php echo $pnewimg3; ?>" style="height:60px;width:152px;	"></td>
					<td class="nopadding col-sm-4 title" ><b><?php echo $title; ?></b></td>
					<td class="nopadding col-sm-4" style="margin-top: 20px;"></td>
				</tr></thead><tbody><tr>
					<?php

//reset the data pointer for the resultset
mysqli_data_seek($qry2,0);
						//inner data
						while ($res2=mysqli_fetch_assoc($qry2))
						{
							$count++;
					?>
			<td target="_blank" data-href="/commerceredirect.html?linkid=<?php echo $res2['LinkID']; ?>&referrer=<?php echo $referrer; ?>" class="nopadding col-sm-4 clickable-row hotdeal" style="border : solid 6px #ccc">

					    <table border="0" cellspacing="0" cellpadding="0" style="width:100%;height:182px;word-wrap:break-word;" border-spacing="0">
					      <tbody><tr>
						<td class="nopadding" colspan="2" valign="top" style="color : #666;font-size: 14px;padding-top: 15px;"><div style="color: #666;padding-left: 10px;font-size: 17px;padding-top: 15px;"><?php echo $res2['Partner_Product_Text'] ?></div></td>
					      </tr>
					      <tr>
						<td class="nopadding" style="white-space: nowrap; height:80px; vertical-align:bottom;">

 <?php
 
$newimg1 = explode('/',$res2['Graphic']);
$newimg2 = count($newimg1);
$cdn = $PRODUCTIMAGE_CDNDOMAIN."/".$PRODUCTIMAGE_S3FOLDER."/images/HotDeals/"; 
$newimg3 = $cdn.$newimg1[$newimg2-1];

 ?>
    <img border="0" style="height:135px;width:135px;padding-left: 10px;" src="<?php echo $newimg3; ?>">

						</td>
						<td class="nopadding" style="vertical-align:middle;">
						  <div class="old-price" style="align:left;color: #666;font-size: 12px;margin-left: 10px;">
							    (was <strike><?php echo $res2['Original_Price'] ?></strike>)
							  </div>
						  <div class="hotdeals-price-all" style="margin-left: 10px;font-size: 20px;"><?php echo $res2['Sale_Price'] ?></div>
						  <br>

						    <div class="hotdeal-buy-all-mer" style="margin-left: 10px;">Buy Now</div>

						</td>
					      </tr>
					    </tbody></table>
					  </a>
					</td>
			<?php
			if ($count % 3 == 0) {
				   echo '</tr><tr>';
				}
				}
			?>
	</tr></tbody>
	</table>	</br>

	<table id="mobiletable" class="table no-spacing mobiletable" cellpadding="0"  cellspacing="0" border="0" style="border: solid 3px #ccc;border-collapse: collapse;display:none;">
			<thead>
			<tr class="mobilecatheader">
					<td width="75%"><img class="img-responsive categoryimg"  src="<?php echo $pnewimg3; ?>" style="height:66px;width:50%;float:left;	"><div style="font-size:12px;word-break: break-all;float:right;font-weight:bold;"><?php echo $title; ?></div></td>
					<td width="24%" class="title" ><!--<a class="mobilemore" href="/hot-deals/<?php echo $more; ?>.html">See more</a>--></td>
			</tr>
			</thead>
			<?php
$qry2 = mysqli_query($con, "SELECT    cp.PartnerID,cp.Partner_Title,cp.Partner_Graphic,LinkID,Link_Name,  cp.partner_name as Partner_Name, Graphic, REPLACE(Partner_Product_Text ,'|', ' ') as Partner_Product_Text ,    Sale_Price,    Original_Price,	l.ChannelID	FROM commerce_partners cp    JOIN partner_campaigns pc On cp.PartnerID = pc.PartnerID		JOIN partner_links l  On pc.CampaignID = l.CampaignID  WHERE    l.ChannelID = $PSite[0]    And campaign_type = 25    And pc.valid = 1    And l.valid = 1 And l.linkID not in (3842291,3842290,3842288, 3842287 , 3842286, 3842285, 3842284, 3842282, 3842283) And partner_name like '".$linkname."%' ORDER BY     pc.Charge_Per_Click desc,    cp.PartnerID limit 10");

						//inner data
						while ($res2=mysqli_fetch_assoc($qry2))
						{

					?>
				<tbody>
<tr class='clickable-row ipad' target="_blank" data-href="/commerceredirect.html?linkid=<?php echo $res2['LinkID']; ?>&referrer=<?php echo $referrer; ?>" align="center">
					<td><ul class="list-unstyled">
					<li style="font-size: 15px;font-weight: bold;"><?php echo $res2['Partner_Product_Text'] ?></li>
					<li><div class="old-price" style="align:left;color: #666;font-size: 12px;margin-left: 10px;">
							    (was <strike><?php echo $res2['Original_Price'] ?></strike>)
							  </div></li>
					<li> <div class="hotdeals-price-all" style="margin-left: 10px;"><?php echo $res2['Sale_Price'] ?></div></li>
					</ul></td>
					<td>
 <?php
 
$newimg1 = explode('/',$res2['Graphic']);
$newimg2 = count($newimg1);
$cdn = $PRODUCTIMAGE_CDNDOMAIN."/".$PRODUCTIMAGE_S3FOLDER."/images/HotDeals/"; 
$newimg3 = $cdn.$newimg1[$newimg2-1];
 
 ?>
<div class="imageBox"><img id="thumb-img-0" src="<?php echo $newimg3; ?>" /></div></br><div class="hotdeal-buy-all-mer" style="margin-left: 10px;">Buy Now</div></td>
					</tr>
				</tbody>
				<?php } ?>
			</table></br>
			<?php
				}
			?>










<style>
@import url('https://fonts.googleapis.com/css?family=Oswald');

.mobiletable td, .table th{
padding:0px !important;
}
.read-reviews
{
	text-transform: uppercase;
    padding: 0 2px 0 0;
    font: 16px 'Oswald',sans-serif;
    letter-spacing: .5px;
    width: 117px;
    height: 33px;
    color: #fff;
    background-color: #999999;
    line-height: 33px;
    text-align: center;
    margin: 5px;
}

</style>



<?php


	//$qry4 = mysqli_query($con, "select distinct c.categoryID, p.productid, p.product_name,category_name, total_reviews, average_rating, Graphic, average_rating as Stars, linkid from partner_campaigns pc inner join partner_links pl on pc.campaignid = pl.campaignid inner join categories c on pl.categoryid = c.categoryid inner join products p on p.productid = pl.productid where pc.campaign_type = 13 and pc.partnerid = $id and curdate() between pc.start_date and pc.end_date and pl.valid = 1 and pl.channelid = $PSite[0] order by category_name");

	$query_FML = mysqli_query($con, "select distinct cp.PartnerID, cp.partner_name, cp.partner_graphic from commerce_partners cp join partner_campaigns  pc on pc.partnerid = cp.partnerid join partner_links pl on pl.campaignid = pc.campaignid where pl.channelid = $PSite[0]  And pc.campaign_type = 13   And pc.valid = 1 And pl.valid = 1  and partner_name = '".$linkname."' and curdate() between pc.start_date and pc.end_date;");
	while ($result_FML=mysqli_fetch_assoc($query_FML))
	{
	//$result_FML =mysqli_fetch_assoc($query_FML);
	$id_FML = $result_FML['PartnerID'];



	$qry4 = mysqli_query($con, "select c.categoryID, p.productid, p.product_name,category_name, total_reviews, average_rating, Graphic,IF(ROUND(average_rating,1) = '0.0','0',ROUND(average_rating,1)) as Stars, max(linkid) as linkid, p.url_safe_product_name,p.categoryid,p.manufacturerid,c.url_safe_category_name, ch.category_path,m.url_safe_manufacturer_name from partner_campaigns pc, partner_links pl, categories c, products p, manufacturers m, channel_categories ch where pc.campaignid = pl.campaignid and p.categoryid = c.categoryid and p.productid = pl.productid and p.manufacturerid = m.manufacturerid and ch.categoryid = c.categoryid and pc.campaign_type = 13 and pc.partnerid = $id_FML and curdate() between pc.start_date and pc.end_date and pl.valid = 1 and pl.channelid = $PSite[0] group by productid order by category_name,linkid desc;");
	$precatid = "";
	echo "<div style='margin-top:15px;'>";
	while ($res4=mysqli_fetch_assoc($qry4))
	{
			/*if($res4['Stars'] >= 1.1 && $res4['Stars'] <= 1.9)
			{
				$stars = "15";
			}
			else if($res4['Stars'] >= 2.1 && $res4['Stars'] <= 2.9)
			{
				$stars = "25";
			}
			else if($res4['Stars'] >= 3.1 && $res4['Stars'] <= 3.9)
			{
				$stars = "35";
			}
			else if($res4['Stars'] >= 4.1 && $res4['Stars'] <= 4.9)
			{
				$stars = "45";
			}
			else
			{
				$stars = $res4['Stars'];
			}*/

			$ratePerc = ($res4['Stars']/5)*100 ;

			$catid = $res4['categoryID'];

			//$product_link = get_productlink_by_id($res4['productid']);
			//print_r($product_link);
			$categoryname = explode("/",$res4['category_path']);
			$manufacturername = $res4['url_safe_manufacturer_name'];
			$productname = $res4['url_safe_product_name'];
			$final_link = "/product/".$res4['category_path']."/".$manufacturername."/".$productname.".html";


			if($catid != $precatid)
			{

			$tableStyle = "firstTable";
			echo "<br><br>";
			echo '
			<table  class="table no-spacing" cellpadding="0" cellspacing="0" border="0">
			  <tbody><tr>
				<td style="color: #666;  padding-left: 10px;  font-size: 18px;  font-weight: bold;" width="50%">'.$res4['category_name'].'</td>
			  </tr>
			</tbody></table>
			';
			};



			echo '
				<table id="desktoptable" class="table no-spacing dealList '.$tableStyle.'" cellpadding="0" cellspacing="0" border="0">
					  <tbody><tr>
						<td style="color: #666; padding-left: 10px;font-size: 14px;width: 320px; padding-right: 20px;">'.$res4['product_name'].'</td>
						<td style="font-size: 14px; width: 110px;">'.$res4['total_reviews'].' Reviews
							</td>
						<td style="width: 80px;">
						  <div class="star-ratings-css-listing" >
							  <div class="star-ratings-css-top" style="width:'.$ratePerc.'%"><span>★</span><span>★</span><span>★</span><span>★</span><span>★</span></div>
							  <div class="star-ratings-css-bottom"><span>★</span><span>★</span><span>★</span><span>★</span><span>★</span></div>
							</div>
						</td>
						<td style="font-size: 14px;width: 110px;">'.$res4['Stars'].' out of 5
							</td>
						<td class="sectionbox-td-5">
						  <a href="'.$final_link.'" target="_blank">
							<div class="read-reviews">Read Reviews</div>
						  </a>
						</td>
						<td class="sectionbox-td-6">
						  <a rel="nofollow" onclick="" href="/commerceredirect.html?linkid='.$res4['linkid'].'&referrer='.$fmlreferrer.'" target="_blank">
							<div class="hotdeal-buy-all-mer-list">Buy Now</div>
						  </a>
						</td>
					  </tr>
					</tbody>
				</table>



			<table id="mobiletable" class="mobiletable no-spacing dealList" cellpadding="0" cellspacing="0" border="0" style="display:none; width:100%">
					  <tbody><tr>
						<td class="ipad" style="color: #666; padding-left: 10px;font-size: 14px; padding-right: 20px;width:40%">'.$res4['product_name'].'</td>
						<td style="text-align:center;font-size: 14px; width:20%"><a href="'.$final_link.'" target="_blank">'.$res4['total_reviews'].' Reviews	</a></td>
						<td style="width:20%"> <div class="star-ratings-css-listing" >
							  <div class="star-ratings-css-top" style="width:'.$ratePerc.'%"><span>★</span><span>★</span><span>★</span><span>★</span><span>★</span></div>
							  <div class="star-ratings-css-bottom"><span>★</span><span>★</span><span>★</span><span>★</span><span>★</span></div>
							</div></td>
						<td class="sectionbox-td-6">
							<a rel="nofollow" onclick="" href="/commerceredirect.html?linkid='.$res4['LinkID'].'&referrer='.$fmlreferrer.'" target="_blank">
								<div class="hotdeal-buy-all-mer-list">Buy Now</div>
							</a>
						</td>
					  </tr>
					</tbody>
				</table>
				<br>
			';
			$precatid = $catid;
		    $tableStyle = "middleTable";
	}
	echo "</div>";
}
?>



















		</div><!-- end inner -->
		</div><!-- end content left -->

<?php //get_sidebar(); ?>
<?php get_footer(); ?>
