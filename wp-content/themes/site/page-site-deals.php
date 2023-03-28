<?php /* Template Name: Site Hot Deals Landing Page */ ?>
<?php
global  $cat_id, $pagename,$referrer;
$cat_id = "0";
$pagename = "HOTDEALS";
$referrer="HD_AllHotdeals";
?>



<?php
//$newp = explode('.html',$parts1[3]);
// For seo
require $_SERVER['DOCUMENT_ROOT'] . '/wp-config-extra.php';
global $seo_title, $seo_description, $seo_keywords;


$seo_title = $HOTDEALSTITLE;
$seo_description = $HOTDEALSDESCRIPTION;
$seo_keywords = $HOTDEALSKEYWORDS;
?>


<?php get_header(); ?>

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
    font: 17px 'Oswald',sans-serif;
    letter-spacing: .5px;
}
.catheader{
    text-transform: uppercase;
    padding: 3px 2px 0 0;
    font: 17px 'Oswald',sans-serif;
    letter-spacing: .5px;
height: 60px; background-color : #000; color : #fff;border: solid 6px #ccc;
}
.mobilecatheader{
    text-transform: uppercase;
    padding: 3px 2px 0 0;
    font: 17px 'Oswald',sans-serif;
    letter-spacing: .5px;
height: 84px; background-color : #000; color : #fff;border: solid 3px #ccc;
}

.nopadding{
padding:0px !important;
}

.title{
text-align: center;
    margin-top: 23px;

}
.categoryimg{

}
</style>


<?php
require_once(__DIR__.'/../../../wp-reviewconfig.php');
$con = mysqli_connect(DB_RHOST,DB_RUSER,DB_RPASSWORD,DB_RNAME) or die("Some error occurred during connection " . mysqli_error($con));

$PSite = explode(":", get_option( 'PSiteName' ) );

$qry = mysqli_query($con, "SELECT    cp.PartnerID,cp.Partner_Title,cp.Partner_Name as MerchantName, cp.Partner_Graphic,LinkID,Link_Name,Graphic, REPLACE(Partner_Product_Text ,'|', ' ') as Partner_Product_Text ,    Sale_Price,    Original_Price,  l.ChannelID  FROM commerce_partners cp    JOIN partner_campaigns pc On cp.PartnerID = pc.PartnerID    JOIN partner_links l  On pc.CampaignID = l.CampaignID  WHERE    l.ChannelID = $PSite[0]    And campaign_type = 25 And curdate() between pc.start_date and pc.end_date   And pc.valid = 1    And l.valid = 1 And l.linkID not in (3842291,3842290,3842288, 3842287 , 3842286, 3842285, 3842284, 3842282, 3842283) GROUP BY PartnerID order by pc.charge_per_click desc");



//echo $count = mysqli_num_rows($qry);

?>

	<div id="content-left" class="col-sm-12">
		<div class="inner">
		<div id="page-header"><h1><?php echo $HOTDEALSTITLEPAGE; ?></h1></div>
			<?php
				while ($res1=mysqli_fetch_assoc($qry))
				{
					$title=$res1['Partner_Title'];
					$titleimg=$res1['Partner_Graphic'];
					//$res2=str_replace(' ', '-', $res1['Link_Name']);
					//$more=str_replace('.', '_', $res2);

					$res_merchant_name_1=str_replace(' ', '-', $res1['MerchantName']);
					$res_merchant_name_2=str_replace('.', '_', $res_merchant_name_1);
          $partnerId = $res1['PartnerID'];


					?>

    <?php

$pnewimg1 = explode('/',$res1['Partner_Graphic']);
$pnewimg2 = count($pnewimg1);
$pcdn = $PRODUCTIMAGE_CDNDOMAIN."/".$PRODUCTIMAGE_S3FOLDER."/images/merchants/";
$pnewimg3 = $pcdn.$pnewimg1[$pnewimg2-1];


 ?>
<table id="desktoptable" class="table no-spacing hotdeals-table" cellpadding="0" cellspacing="0" border="0"  style="width:100%;border: solid 6px #ccc;">
				<thead><tr class=" catheader">
					<td class="nopadding col-sm-4">
						<img class="img-responsive categoryimg"  src="<?php echo $pnewimg3; ?>" style="height:60px;width:152px;	">
					</td>
					<td class="nopadding title" ><b><?php echo $title; ?></b></td>
          <?php if($partnerId != 646 ) { ?>
					<td class="nopadding" style="margin-top: 20px;"><a class="more" href="/hot-deals/<?php echo $res_merchant_name_2; ?>.html">See all deals</a></td>
          <?php } else { ?>
          <td class="nopadding" style="margin-top: 20px;"></td>
          <?php } ?>
        </tr></thead><tbody>
				<tr>
					<?php
					$count = 0;
$qry2 = mysqli_query($con, "SELECT    cp.PartnerID,cp.Partner_Title,cp.Partner_Graphic,LinkID,Link_Name,Graphic, REPLACE(Partner_Product_Text ,'|', ' ') as Partner_Product_Text,    Sale_Price,    Original_Price,	l.ChannelID	FROM commerce_partners cp    JOIN partner_campaigns pc On cp.PartnerID = pc.PartnerID		JOIN partner_links l  On pc.CampaignID = l.CampaignID  WHERE    l.ChannelID = $PSite[0]    And campaign_type = 25    And pc.valid = 1    And l.valid = 1 And l.linkID not in (3842291,3842290,3842288, 3842287 , 3842286, 3842285, 3842284, 3842282, 3842283) And cp.PartnerID = '".$res1['PartnerID']."' ORDER BY     pc.Charge_Per_Click desc,    cp.PartnerID limit 9");

						//inner data
						while ($res2=mysqli_fetch_assoc($qry2))
						{

						$count++;
					?>


						<td  target="_blank" data-href="/commerceredirect.html?linkid=<?php echo $res2['LinkID']; ?>&referrer=<?php echo $referrer; ?>" class="nopadding clickable-row hotdeal" style="border : solid 6px #ccc;">
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
					</td>


			<?php

				if ($count % 3 == 0) {
				   echo '</tr><tr>';
				}

			}

			?>
		</tr></tbody>
	</table>	</br>


	<table id="mobiletable" class="table no-spacing mobiletable" cellpadding="0"  cellspacing="0" border="0" style="border-collapse: collapse;display:none;border: solid 6px #ccc;">
			<thead>
			<tr class=" mobilecatheader">
					<td width="75%">

    <?php


$pnewimg1 = explode('/',$res1['Partner_Graphic']);
$pnewimg2 = count($pnewimg1);
$pcdn = $PRODUCTIMAGE_CDNDOMAIN."/".$PRODUCTIMAGE_S3FOLDER."/images/merchants/";
$pnewimg3 = $pcdn.$pnewimg1[$pnewimg2-1];


		?>

 <img class="img-responsive categoryimg"  src="<?php echo $pnewimg3; ?>" style="width:124px;height:66px;">
					<div style="font-size:12px;word-break: break-all;width:100%;float:right;font-weight:bold;line-height:66px"><?php echo $title; ?></div></td>
					<td width="24%" class="title" ><a class="mobilemore" href="/hot-deals/<?php echo $res_merchant_name_2; ?>.html">See more</a></td>
			</tr>
			</thead>
			<?php
$qry2 = mysqli_query($con, "SELECT    cp.PartnerID,cp.Partner_Title,cp.Partner_Graphic,LinkID,Link_Name,Graphic,  REPLACE(Partner_Product_Text ,'|', ' ') as Partner_Product_Text ,    Sale_Price,    Original_Price,	l.ChannelID	FROM commerce_partners cp    JOIN partner_campaigns pc On cp.PartnerID = pc.PartnerID		JOIN partner_links l  On pc.CampaignID = l.CampaignID  WHERE    l.ChannelID = $PSite[0] And campaign_type = 25    And pc.valid = 1    And l.valid = 1 And l.linkID not in (3842291,3842290,3842288, 3842287 , 3842286, 3842285, 3842284, 3842282, 3842283) And cp.PartnerID = '".$res1['PartnerID']."' ORDER BY     pc.Charge_Per_Click desc,    cp.PartnerID limit 3");

						//inner data
						while ($res2=mysqli_fetch_assoc($qry2))
						{

					?>

				  <tr class='clickable-row ipad' target="_blank" data-href="/commerceredirect.html?linkid=<?php echo $res2['LinkID']; ?>&referrer=<?php echo $referrer; ?>" align="center">
					<td width="75%"><ul class="list-unstyled">
					<li style="font-size: 15px;font-weight: bold;"><?php echo $res2['Partner_Product_Text'] ?></li>
					<li><div class="old-price" style="align:left;color: #666;font-size: 12px;margin-left: 10px;">
							    (was <strike><?php echo $res2['Original_Price'] ?></strike>)
							  </div></li>
					<li> <div class="hotdeals-price-all" style="margin-left: 10px;"><?php echo $res2['Sale_Price'] ?></div></li>
					</ul></td>
					<td width="24%">
 <?php

$newimg1 = explode('/',$res2['Graphic']);
$newimg2 = count($newimg1);
$cdn = $PRODUCTIMAGE_CDNDOMAIN."/".$PRODUCTIMAGE_S3FOLDER."/images/HotDeals/";
$newimg3 = $cdn.$newimg1[$newimg2-1];

 ?>
<div class="imageBox"><img id="thumb-img-0" src="<?php echo $newimg3; ?>" /></div></br><div class="hotdeal-buy-all-mer" style="margin-left: 10px;">Buy Now</div></td>
					</tr>

				<?php } ?>
			</table></br>

			<?php
				}
			?>

		</div><!-- end inner -->
		</div><!-- end content left -->


<?php get_footer(); ?>
