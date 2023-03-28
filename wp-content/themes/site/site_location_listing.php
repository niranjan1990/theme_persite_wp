	<?Php /* Template Name: Location Listing/City Page */ ?> 
<?php
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;
use Aws\Common\Credentials\Credentials;


echo "<!--  /* Template Name: Location Listing/City Page */ site_location_listing.php  -->";
	/**
	 * The template for displaying site Review location listing by City or ZIP
	 *
	 * @package WordPress
	 * @subpackage SITEReview
	 * @since Site Review 1.0
	 */
 
	require $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php';
	require $_SERVER['DOCUMENT_ROOT'] . '/wp-config-extra.php';
		require $_SERVER['DOCUMENT_ROOT'] . '/wp-config-extra.php';


        $mtbr_cr_location_url = ($SITE_NAME=="golfreview") ? "cr_location_url" : "mtbr_cr_location_url";
        
        if($SITE_NAME == "golfreview") {
		
            site_review_location_listing();
        }
        else {
            mtbr_site_review_location_listing();            
        }
		
		
			$fullurl = explode('/',$_SERVER['REQUEST_URI']);

	set_site_review_location('course-listing');
	set_site_review_meta_vars($CourseList->state, $CourseList->city, $CourseList->list_count, $CourseList->zip);
	
	
	
	
	
	
	


function uploadmapFileToS3($fileKeyName, $srcFile, $fileContentType, $s3path)
 {

	include(__DIR__ .'/../../../wp-config-extra.php');

	$s3config = array(
		'region' => $PRODUCT_AWS_REGION,
		'version' => '2006-03-01'
	);

	// Check for explicit IAM credentials
	if (!empty($PRODUCT_AWS_KEY)) {
		$s3config = array(
		'region' => $PRODUCT_AWS_REGION,
		'version' => '2006-03-01',
				'credentials' => array('key'=>$PRODUCT_AWS_KEY,'secret'=>$PRODUCT_AWS_SECRET)
			);
		}

	$s3 = S3Client::factory($s3config);
	
	// Construct S3 key
	$key = $PRODUCTIMAGE_S3FOLDER .$s3path. $fileKeyName;
	$key_image = $PRODUCTIMAGE_S3FOLDER .$s3path. $fileKeyName;
	//echo $srcFile;
	try 
	{
			$result = $s3->putObject(array(
				'Bucket' => $PRODUCTIMAGE_S3BUCKET,
				'Key' => $key_image,
				'SourceFile' => $srcFile,
				'ContentType' => $fileContentType
			));
	} 
	catch (S3Exception $e) 
	{
		error_log($e->getMessage());
	}

}
	
	
	
	
	
?>

<?php
//$newp = explode('.html',$parts1[3]);
// For seo
global $seo_title, $seo_description, $seo_keywords;

$LOCATIONCITYTITLE_TEMP = str_replace("[city_name]",ucfirst(str_replace(".html","",$fullurl[4])),$LOCATIONCITYTITLE);
$LOCATIONCITYDESCRIPTION_TEMP = str_replace("[city_name]",ucfirst(str_replace(".html","",$fullurl[4])),$LOCATIONCITYDESCRIPTION);
$LOCATIONCITYKEYWORDS_TEMP = str_replace("[city_name]",ucfirst(str_replace(".html","",$fullurl[4])),$LOCATIONCITYKEYWORDS);


$seo_title = str_replace("[state_name]",ucfirst($fullurl[3]),$LOCATIONCITYTITLE_TEMP);
$seo_description = str_replace("[state_name]",ucfirst($fullurl[3]),$LOCATIONCITYDESCRIPTION_TEMP);
$seo_keywords = str_replace("[state_name]",ucfirst($fullurl[3]),$LOCATIONCITYKEYWORDS_TEMP);
?>
<?php	
	get_header();

?>
	<div id="content-left" class="col-sm-8">
	<div class="inner">			
		<div class="main-content">
		<div id="golf-courses">
		<!--<h2><?php echo $CourseType['course_type_name']; ?> <span class="lwr">in <?php echo strtoupper(str_replace(array("trails-",".html","-"),array("",""," "),$fullurl[4])).", ".strtoupper(str_replace(array($fullurl[2],"trails-",".html","-"),array("","",""," "),$fullurl[3])); ?> (<?php echo number_format($CourseList->list_count); ?>)</span></h2>-->
		
		
                   <?php  if($SITE_NAME=="golfreview"): ?>
                        <h2><?php echo $CourseType['course_type_name']; ?> <span class="lwr">in <?php echo strtoupper(str_replace(array("trails-",".html","-"),array("",""," "),$fullurl[4])).", " ; echo strtoupper(str_replace(array($fullurln,"trails",".html","-"),array("","",""," "),$fullurl[3])); ?> (<?php echo number_format($CourseList->list_count); ?>)</span></h2>
                    <?php else: ?>
						<h2>
							<?php 
							if($fullurl[1]=='bikeshops')
							{
								echo $CourseType['course_type_name'];
							}
							else
							{
								//echo $CourseType['course_type_name'];
								echo "Mountain Bike Trails";
							} 
							?> 
							<span class="lwr">in <?php echo strtoupper(str_replace(array("trails-",".html","-"),array("",""," "),$fullurl[4])).", " ; echo strtoupper(str_replace(array($fullurln,"trails",".html","-"),array("","",""," "),$fullurl[3])); ?> (<?php echo number_format($CourseList->list_count); ?>)</span></h2>	
	            <?php endif ?>


				
				
				
				
				
				
				







				<p class="subheadertext">Select a city below to see a list of <?php echo $fullurl[1];?> in the vicinity
					
					<!--<?php echo $CourseType['course_type_name']; ?>--></p>	
				<div class="golf-club-list"> Select the city alphabetically
					<?php if($SITE_NAME=="golfreview") site_review_city_list_course_new(); else mtbr_site_review_location_list_new(); // print out city list alphabetically ?>
				</div>








				
				
				
				
				
				
				
				
				
				

				
		<?php if($Courses) { ?>	
			<p class="subheadertext">Select a <?php echo $CourseType['singuler_type_name']; ?> below to view the details</p>
			<div id="products" class="product-list">
				<div class="product-list-nav">
					<?php if($SITE_NAME == "golfreview")
					{
					?>
						Showing <?php echo number_format($CourseList->list_count); ?> <?php echo ucwords($CourseType['course_type_name']); ?> in <?php echo ucfirst(str_replace(array("trails-",".html","-"),array("",""," "),$fullurl[4]));echo ",";echo ucfirst(str_replace(array($fullurln,"trails",".html","-"),array("","",""," "),$fullurl[3])); ?>
					<?php
					}
					else
					{
					?>
					Showing <?php echo number_format($CourseList->list_count); ?> <?php echo ucwords($CourseType['course_type_name']); ?> in <?php echo $CourseList->location; echo ucfirst(str_replace(array($fullurln,"trails",".html","-"),array("","",""," "),$fullurl[3]));?>
					<?php
					}
					?>
				</div>
				<table id="mobiletable" class="table" cellpadding="0"  cellspacing="0" border="0" style="border-collapse: collapse;display:none;">
				<thead></thead>
				<?php $i=0; 
				foreach($Courses as $Course) 
				{
					if(strpos($Course->product_image, "map_") === false && $Course->latitude != "" && $Course->longitude !="")
					{
						$url='https://maps.googleapis.com/maps/api/staticmap?center='.$Course->latitude.','.$Course->longitude.'&zoom=12&size=200x200&maptype=roadmap&key='.$GOOGLESTATICMAPAPIKEY.'&markers=color:blue|label:1|'.$Course->latitude.','.$Course->longitude;
						file_put_contents(sys_get_temp_dir().'/map_'.$Course->productid.'.png',file_get_contents($url));

						$con = mysqli_connect(DB_RHOST,DB_RUSER,DB_RPASSWORD,DB_RNAME) or die("Some error occurred during connection " . mysqli_error($con));
						$qry = mysqli_query($con, "UPDATE products set Product_Image = 'map_".$Course->productid.".png' where ProductID =".$Course->productid);
						
						//Upload to s3
						$path = sys_get_temp_dir().'/map_'.$Course->productid.'.png';
						uploadmapFileToS3("map_".$Course->productid.".png", $path, "image/png", "/images/products/");
						unlink($path);						
					}
				?>
				
					<tbody style="border: 1px solid black;display: inherit;">
					<tr class='clickable-row' align="center" data-href="<?php $mtbr_cr_location_url($Course, $Course->url_safe_product_name,0,1); ?>">						
						<td style="width:25%;"><div class="imageBox"><img id="thumb-img-0" src="<?php echo $PRODUCTIMAGE_CDNDOMAIN.'/'.$PRODUCTIMAGE_S3FOLDER.'/images/products/map_'.$Course->productid.'.png'; ?>" /></div></td>
						<td style="width:75%;">
							<ul class="list-unstyled">
								<li style="font-size: 15px;font-weight: bold;"><a style="color:black !important;" href="<?php mtbr_cr_location_url($Course, $Course->url_safe_product_name,0,1); ?>"><?php echo $Course->product_name; ?></a></li>
								<li><?php echo $Course->city; echo ", "; echo ucwords(str_replace("-"," ",$fullurl[3])); ?></li>
								<li style="color:#0275d8"><?php echo $Course->total_rating_count; ?> Reviews</li>
								<li><strong><?php echo round($Course->average_rating,1); ?> of 5</strong></li>
								<li>
		<?php $ratePerc = ($Course->average_rating/5)*100 ?>
									<div class="star-ratings-css-listing">
									  <div class="star-ratings-css-top" style="width: <?php echo $ratePerc ?>%"><span>★</span><span>★</span><span>★</span><span>★</span><span>★</span></div>
									  <div class="star-ratings-css-bottom"><span>★</span><span>★</span><span>★</span><span>★</span><span>★</span></div>
									</div>
								</li>
							</ul>

						</td></tr>
					</tbody>

<?php if($i==20){ echo '<tbody style="display: inherit;"><tr><td colspan="2">';if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("Product listing Mobile Ad 300x250") ) : 
	 endif;echo'</td></tr></tbody>';}  ?>

<?php if($i==40){ echo '<tbody style="display: inherit;"><tr><td colspan="2">'; if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("Product listing Mobile Ad-1 300x250") ) : 
	endif;echo'</td></tr></tbody>';}  ?>


				<?php $i++; }?>
				</table>
				
				<table id="desktoptable" class="table" cellpadding="0"  cellspacing="0" border="0" style="border-collapse: collapse;">
					<thead></thead>
					<?php foreach($Courses as $Course) { 
					?>
						<tbody style="border: 1px solid black;">
						<tr class='clickable-row' align="center" data-href="<?php $mtbr_cr_location_url($Course, $Course->url_safe_product_name,0,1); ?>">
							<td><div class="imageBox"><img id="thumb-img-0" src="<?php echo $PRODUCTIMAGE_CDNDOMAIN.'/'.$PRODUCTIMAGE_S3FOLDER.'/images/products/map_'.$Course->productid.'.png'; ?>"/></div></td>
							<td>
								<ul class="list-unstyled">
									<li style="font-size: 15px;font-weight: bold;"><a style="color:black !important;" href="<?php mtbr_cr_location_url($Course, $Course->url_safe_product_name,0,1); ?>"><?php echo $Course->product_name; ?></a></li>
									<li><?php echo ucfirst($Course->city); echo ", "; echo ucwords(str_replace("-"," ",$fullurl[3]));?></li>
								</ul>
							</td>
							<td><ul class="list-unstyled"><li style="color:#0275d8"><?php echo $Course->total_rating_count; ?> Reviews</li>
							<li><strong><?php echo round($Course->average_rating,1); ?> of 5</strong></li>
							<li><?php $ratePerc = ($Course->average_rating/5)*100 ?>
							<div class="star-ratings-css-listing">
							  <div class="star-ratings-css-top" style="width: <?php echo $ratePerc ?>%"><span>★</span><span>★</span><span>★</span><span>★</span><span>★</span></div>
							  <div class="star-ratings-css-bottom"><span>★</span><span>★</span><span>★</span><span>★</span><span>★</span></div>
							</div>
</li>
							</ul></td>
						</tr>
						</tbody>
					<?php } ?>
					
				</table>					
				<div class="product-list-nav">
					<?php if($SITE_NAME == "golfreview")
					{
					?>
						Showing <?php echo number_format($CourseList->list_count); ?> <?php echo ucwords($CourseType['course_type_name']); ?> in <?php echo ucfirst(str_replace(array("trails-",".html","-"),array("",""," "),$fullurl[4]));echo ",";$CourseList->location;echo ucfirst(str_replace(array($fullurln,"trails",".html","-"),array("","",""," "),$fullurl[3])); ?>
					<?php
					}
					else
					{
					?>
					Showing <?php echo number_format($CourseList->list_count); ?> <?php echo ucwords($CourseType['course_type_name']); ?> in <?php echo $CourseList->location; echo ucfirst(str_replace(array($fullurln,"trails",".html","-"),array("","",""," "),$fullurl[3]));?>
					<?php
					}
					?>
				</div>
			</div>	
		<?php } else { 
		header("Location: /404");?>
			<p class="subheadertext">No <?php echo $CourseType['course_type_name']; ?> found, please try searching again, or <a href="<?php bloginfo('url') ?>/<?php echo str_replace( ' ', '-', $CourseType['course_type_name'] ); ?>.html"><u>search by state</u></a>.</p>
						
		<?php } 
			
	 ?>
	</div>
	</div><!-- end main content -->
</div><!-- end inner -->
</div><!-- end content left -->
<?php get_sidebar(); ?>
<?php get_footer(); ?>
