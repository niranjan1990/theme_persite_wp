<?php /* Template Name: Site Partners Landing Page */ ?>

<?php
global  $cat_id, $pagename;
$cat_id = "0";
$pagename = "PARTNERS";
?>


<?php
//$newp = explode('.html',$parts1[3]);
// For seo
require $_SERVER['DOCUMENT_ROOT'] . '/wp-config-extra.php';
global $seo_title, $seo_description, $seo_keywords;


$seo_title = $HOTDEALSPARTNERSTITLE;
$seo_description = $HOTDEALSPARTNERSDESCRIPTION;
$seo_keywords = $HOTDEALSPARTNERSKEYWORDS;
?>





<?php get_header(); ?>

<?php
require_once(__DIR__.'/../../../wp-reviewconfig.php');
$PSite = explode(":", get_option( 'PSiteName' ) );

$con = mysqli_connect(DB_RHOST,DB_RUSER,DB_RPASSWORD,DB_RNAME) or die("Some error occurred during connection " . mysqli_error($con));

$qry = mysqli_query($con, "SELECT cp.PartnerID AS MerchantID,cp.Partner_Name AS MerchantName, cp.Partner_Graphic AS MerchantLogo,Max(pc.Charge_per_Click) AS CPC,Link_Name FROM commerce_partners cp JOIN partner_campaigns pc  ON cp.PartnerID = pc.PartnerID  JOIN partner_links pl  ON pc.campaignid = pl.campaignid WHERE pl.channelid = $PSite[0] And campaign_type = 13 And pc.valid = 1 And pl.valid = 1 And pl.linkID not in (3842291,3842290,3842288, 3842287 , 3842286, 3842285, 3842284, 3842282, 3842283) and curdate() between pc.start_date and pc.end_date and cp.PartnerID != 78 and cp.partnerid != 660 GROUP BY cp.PartnerID, cp.Partner_Name, cp.Partner_Graphic ORDER BY Max(pc.Charge_per_click) DESC;");

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

</style>

  <div id="content-left" class="col-sm-12">
    <div class="inner">
    <div id="page-header"><h1><?php echo strtoupper($SITE_NAME); ?> Partner Stores</h1></div>
<div class="partners" style="min-height:500px;">
<table id="desktoptable" width="100%" bgcolor="white" cellpadding="40" cellspacing="0" border="0" style="margin-bottom:60px;position: relative;">
    <tbody><tr><?php

    $count = 0;

while ($res1=mysqli_fetch_assoc($qry))
{

  $merchantID = $res2['MerchantID'];
  $merchant_Name = $res2['MerchantName'];

  $res_merchant_name_1=str_replace(' ', '-', $res1['MerchantName']);
  $res_merchant_name_2=str_replace('.', '_', $res_merchant_name_1);

//$res2=str_replace(' ', '-', $res1['Link_Name']);
//$more=str_replace('.', '_', $res2);
?>
 <?php

$pcdn=$PRODUCTIMAGE_CDNDOMAIN."/".$PRODUCTIMAGE_S3FOLDER;
$pnewimg3 = $pcdn.$res1['MerchantLogo'];


$count++;
 ?>

      <td align="center" bgcolor="#FFFFFF" style="vertical-align: middle; border: solid 12px #f0f0f0;">
        <a href="/hot-deals/<?php echo $res_merchant_name_2; ?>.html"target="_blank">
          <img src="<?php echo $pnewimg3; ?>" style="width:146px;height:60px;" border="0" align="center">
          <img src="/wp-content/themes/site/images/gray_arrow-right.png" style="vertical-align: middle;padding-left: 5px;">
        </a>
        <span>
        </span>
      </td>

      <?php

        if ($count % 4 == 0) {
           echo '</tr><tr>';
        }
}
?>
 </tr>
  </tbody></table>

<?php
$qry = mysqli_query($con, "SELECT cp.PartnerID AS MerchantID,cp.Partner_Name AS MerchantName, cp.Partner_Graphic AS MerchantLogo,Max(pc.Charge_per_Click) AS CPC,Link_Name FROM commerce_partners cp JOIN partner_campaigns pc  ON cp.PartnerID = pc.PartnerID  JOIN partner_links pl  ON pc.campaignid = pl.campaignid WHERE pl.channelid = $PSite[0] And campaign_type = 13 And pc.valid = 1 And pl.valid = 1 And pl.linkID not in (3842291,3842290,3842288, 3842287 , 3842286, 3842285, 3842284, 3842282, 3842283) and curdate() between pc.start_date and pc.end_date and cp.PartnerID != 78 and cp.partnerid != 660 GROUP BY cp.PartnerID, cp.Partner_Name, cp.Partner_Graphic ORDER BY Max(pc.Charge_per_click) DESC;");

?>

<table id="mobiletable" class="table no-spacing" cellpadding="0"  cellspacing="0" border="0" style="border-collapse: collapse;display:none">
			<thead>
			</thead><tbody>
				  <?php
while ($res1=mysqli_fetch_assoc($qry))
{
$merchantID = $res2['MerchantID'];
$merchant_Name = $res2['MerchantName'];

$res_merchant_name_1=str_replace(' ', '-', $res1['MerchantName']);
$res_merchant_name_2=str_replace('.', '_', $res_merchant_name_1);

//$res2=str_replace(' ', '-', $res1['Link_Name']);
//$more=str_replace('.', '_', $res2);
?>

 <?php

$pcdn = $PRODUCTIMAGE_CDNDOMAIN."/".$PRODUCTIMAGE_S3FOLDER;
$pnewimg3 = $pcdn.$res1['MerchantLogo'];

 ?>
				<tr align="center">
				<td align="center" bgcolor="#FFFFFF" style="vertical-align: middle; border: solid 12px #f0f0f0;">
        <a href="/hot-deals/<?php echo $res_merchant_name_2; ?>.html"target="_blank">
          <img src="<?php echo $pnewimg3;?>" style="width:146px;height:60px;" border="0" align="center">
          <img src="/wp-content/themes/site/images/gray_arrow-right.png" style="vertical-align: middle;padding-left: 5px;">
        </a>
        <span>
        </span>
      </td> </tr>

				<?php } ?>
	</tbody>		</table></br>

</div>


    </div><!-- end inner -->
    </div><!-- end content left -->
















<?php get_footer(); ?>
