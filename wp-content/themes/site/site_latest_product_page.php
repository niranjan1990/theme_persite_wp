<?php /* Template Name: Site Latest Product Page */ ?>
<?php
	/**
	 * The template for displaying Site Top Reviews.
	 * permalink structure: /topreviews.html
	 *
	 * @package WordPress
	 * @subpackage SiteReview
	 * @since Site Review 1.0
	 */
	require $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php';
?>
	
<?php $parts1 = explode( '/', $_SERVER['REQUEST_URI']); ?>
<?php $parts2 = explode( '.html', $parts1[2] ); ?>
<?php $url = ucwords ( str_replace('-',' ', $parts2[0] ) ); ?>

<?php
global $seo_title, $seo_description, $seo_keywords;
$seo_title = $SITE_TITLE;
$seo_description = $SITE_DESC;
$seo_keywords = $SITE_KEYWORD;
?>

<?php
global  $cat_id, $pagename;
$cat_id = "0";
$pagename = "RVF";
?>
<?php	
	//site_review_product_brand_list(1);
	$latestproductcount = get_option('latestproductscount');
	$latestproducts = get_option('latestproducts');
	site_latest_product_list($latestproductcount);
	set_site_review_location('reviews');
	//set_site_review_meta_vars($Brand->category_name, $Brand->manufacturer_name, $Brand->product_count);
	get_header();
?>

<div id="content-left" class="col-sm-8">
	<div class="inner">			
		<div class="main-content">
			<h2><?php echo $latestproducts ; ?></h2>

<?php
	
	/*** If brand products exist, list all products ***/
	
	if($Products) { ?>
	
	<div id="products" class="product-list">
	
		<table id="mobiletable" class="table no-spacing" cellpadding="0"  cellspacing="0" border="0" style="border-collapse: collapse;display:none">
		<thead></thead>
		<?php $i=0; 
		 foreach($Products as $Product) { 

                $category_url = $Product->category_path;
                $url_safe_category_name = $Product->url_safe_category_name;
                $url_brand= $Product->url_safe_manufacturer_name;
                $url_pname = $Product->url_safe_product_name;

                $maincat = explode('/',$category_url);
                $purl="/product/".$category_url."/".$url_brand."/".$url_pname.".html";		 

		 
		 ?>
			<tbody style="border: 1px solid black;display: inherit;">  	        
				<tr class='clickable-row' align="center" data-href="<?php echo $purl; ?>">
				<td style="width:25%;"><div class="imageBox"><img id="thumb-img-0" src="<?php echo cr_product_image($Product->product_image,'product'); ?>" /></div></td>
				<td style="width:75%;"><ul class="list-unstyled">
				<li style="font-size: 15px;font-weight: bold;"><?php echo $Product->product_name; ?>&nbsp;<?php echo $Product->manufacturer_name; ?></li>
                                <li class="product_latestReview" style="font-size:15px;">(<?php echo date('Y', strtotime($Product->Date_Created)); ?>)</li>

				<li class="product_reviewButton" style="margin-top: 20px;"><span><a href="<?php echo $purl; ?>">WRITE REVIEWS</a></span></li>
				</ul></td></tr>
			</tbody>
<?php if($i==20){ echo '<tbody style="display: inherit;"><tr><td colspan="2">';if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("Product listing Mobile Ad 300x250") ) : 
	 endif;echo'</td></tr></tbody>';}  ?>

<?php if($i==40){ echo '<tbody style="display: inherit;"><tr><td colspan="2">'; if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("Product listing Mobile Ad-1 300x250") ) : 
	endif;echo'</td></tr></tbody>';}  ?>


		<?php $i++; } ?>		
		</table>
		
		<table id="desktoptable" class="table no-spacing" cellpadding="0"  cellspacing="0" border="0" style="border-collapse: collapse;">
		<thead></thead>	
		<?php foreach($Products as $Product) { ?>
		
		<tbody style="border: 1px solid black;">  	   
		<?php 

 $category_url = $Product->category_path;
                $url_safe_category_name = $Product->url_safe_category_name;
                $url_brand= $Product->url_safe_manufacturer_name;
                $url_pname = $Product->url_safe_product_name;

                $maincat = explode('/',$category_url);
                $purl="/product/".$category_url."/".$url_brand."/".$url_pname.".html";

	?>
		<tr class='clickable-row' align="center" data-href="<?php echo $purl; ?>">
			<td><div class="imageBox"><img id="thumb-img-0" src="<?php echo cr_product_image($Product->product_image,'product'); ?>" /></div></td>
			<td><ul class="list-unstyled">
			<li style="font-size: 15px;font-weight: bold;"><?php echo $Product->product_name; ?>&nbsp;<?php echo $Product->manufacturer_name; ?></li>
                                <li class="product_latestReview" style="font-size:15px;">(<?php echo date('Y', strtotime($Product->Date_Created)); ?>)</li>

			</ul></td><td><ul><li class="product_reviewButton"><span><a href="<?php echo $purl; ?>">WRITE REVIEWS</a></span></li></ul></td></tr>
		</tbody>
		<?php } ?>		
		</table>			
	<?php } else { ?><!-- end product list -->	
	<p class="subheadertext">No Reviews found in this category</p>
	<?php } ?>
	
	
	</div><!-- end main content -->
</div><!-- end inner -->
</div><!-- end content left -->
</div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>
