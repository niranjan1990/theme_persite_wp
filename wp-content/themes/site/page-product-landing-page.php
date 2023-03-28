<?php /* Template Name: Product Landing Page */ 
echo "<!-- Template Name: Product Landing Page ,page-product-landing-page.php -->";
?>
<?php 
ob_start(); 
session_start();

$parts1 = explode( '/', $_SERVER['REQUEST_URI']); ?>
<?php $parts2 = explode( '.html', $parts1[1] ); ?>
<?php $url = ucwords ( str_replace('-',' ', $parts2[0] ) ); ?>

<?php 	require $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php'; ?>



<?php
global  $cat_id, $pagename,$crrp;
$cat_id = Get_CategoryID_By_Path( $parts2[0] );
$pagename = "PLS";
$crrp ="SuperCat";
?>

<?php
// For seo
require $_SERVER['DOCUMENT_ROOT'] . '/wp-config-extra.php';
global $seo_title, $seo_description, $seo_keywords;

$seo_title = str_replace("[category_name]",str_replace('-',' ',$parts2[0]),$CATEGORYTITLE);
$seo_description = str_replace("[category_name]",str_replace('-',' ',$parts2[0]),$CATEGORYDESCRIPTION);
$seo_keywords = str_replace("[category_name]",str_replace('-',' ',$parts2[0]),$CATEGORYKEYWORDS);

//Set the Sort value 
if(!isset($_SESSION['sort']))
{
	$_SESSION['sort']=$DEFAULTSORT;
}
if(isset($_GET['sort']) && $_GET['sort'] != '')
{
	$_SESSION['sort']=$_GET['sort'];
}
?>

<?php 


if(isset($_GET['view']) && $_GET['view'] == 'older')
{
	
	$_SESSION['archive']='1';
}
else if(isset($_GET['view']) && $_GET['view'] == 'current')
{
	$_SESSION['archive']='0';
}
else if(!isset($_SESSION['archive']))
{
	$_SESSION['archive']='0';
}



if($DEEPCATEGORIES == "1")
{
	if(function_exists('site_review_category_list_outdoor'))
	{ 
		site_review_category_list_outdoor($_SESSION['archive']); 
	} 
}
else
{
	if(function_exists('site_review_category_list'))
	{ 
		site_review_category_list(); 
	} /* get and return object array of all categories */ 	
}
?>


<?php $Category =  (object)array(); ?>
<?php $Category->product_count	= 0; /* initializing value 0  to product_count */ ?>
<?php $Category->category_name 	= $url; // Golf Clubs ?>
<?php $Category->url_safe_category_name  = $parts2[0]; // golf-clubs 
if(function_exists('set_site_review_location')){
	set_site_review_location(); 
}

?>

	
<?php get_header();  ?>
	
<?php global $GRclass, $location; //echo $GRclass->get_site_review_location();
?>
<div id="content-left" class="col-sm-8">
<div class="inner">
<div class="main-content"> <!-- added new CSS class to make generic compound pages -->
<h1><?php echo str_replace('-',' ', $Category->category_name ); ?></h1><!-- Page title will changed accordingly -->


<p class="subheadertext" style="margin-bottom: 0px;">Select the type of <?Php echo ucfirst($Category->category_name); ?> or product you are looking for</p>
<div class="golf-clubs">
	
	<div class="parent" style="float: left;margin: 25px 0px;width:100%;"><?Php
			if(function_exists('Get_NodeID_By_Path'))
			{
				$NODEID = Get_NodeID_By_Path( $Category->url_safe_category_name ); 
			}	//Added By Zaved as on 26/Nov/2016
			for($i=0;$i<count($Categories);$i++) 
			{ /* NODEID is also database driven */
				if($DEEPCATEGORIES=="1")
				{
					$catcount = 0;
					if(strpos($Categories[$i]->nodeid, $NODEID ) !== false && strpos($Categories[$i]->nodeid, $NODEID ) == 0 && $Categories[$i]->node_level == 2)
					{
						$catcount = $catcount + $Categories[$i]->product_count;
						$BrandIdList[] = $Categories[$i]->categoryid;
					}
					if(strpos($Categories[$i]->nodeid, $NODEID ) !== false && strpos($Categories[$i]->nodeid, $NODEID ) == 0 && $Categories[$i]->node_level == 3 ) 
					{ 
							for($j=0;$j<count($Categories);$j++) 
							{ 
								if(strpos($Categories[$j]->nodeid, $Categories[$i]->nodeid ) !== false && strpos($Categories[$j]->nodeid, $NODEID ) == 0 && $Categories[$j]->node_level >= 3 ) 
								{
									$catcount = $catcount + $Categories[$j]->product_count;
									$BrandIdList[] = $Categories[$j]->categoryid;
									$CatName = strtolower( $Categories[$j]->url_safe_category_name );
									$CatName = str_replace('&', 'and', $CatName );
									$BrandList[] = str_replace(' ', '-', $CatName );
								}	
							}
						?>
						<?php
						if($catcount == '0')
						{
						}
						else
						{
						?>
						
						<div style="float:left;margin:5px 5px 5px 0px;border-radius: 5px;padding:4px 4px 4px 0px;"><a class="BtnWriteReview" id="featured-nav" style="font-weight: normal;text-decoration:none;padding: 4px;/*background-color: rgb(9, 111, 0);*/border-radius: 2px;color: #fff;" href="/<?php echo $Categories[$i]->category_path; ?>.html"><?php if(function_exists('cr_category_name')){ cr_category_name($Categories[$i]->category_name); ?> (<?php echo number_format($catcount); } ?>)</a></div>
						
						<?php
						}
						$CatName = strtolower( $Categories[$i]->url_safe_category_name ); 
						$CatName = str_replace('&', 'and', $CatName ); 
						$BrandList[] = str_replace(' ', '-', $CatName ); 
						$BrandIdList[] = $Categories[$i]->categoryid; 
					} 
					$Category->product_count = $Category->product_count + $catcount; 
				}
				else
				{
					if(strpos($Categories[$i]->nodeid, $NODEID ) !== false && strpos($Categories[$i]->nodeid, $NODEID ) == 0 && $Categories[$i]->node_level == 3 && number_format($Categories[$i]->product_count > 0) ) 
					{ ?>
						<div style="float:left;margin:5px 5px 5px 0px;border-radius: 5px;padding:4px 4px 4px 0px;"><a class="BtnWriteReview" id="featured-nav" style="font-weight: normal;text-decoration:none;padding: 4px;/*background-color: rgb(9, 111, 0);*/border-radius: 2px;color: #fff;" href="/<?php echo $Categories[$i]->category_path; ?>.html"><?php if(function_exists('cr_category_name')){ cr_category_name($Categories[$i]->category_name); ?> (<?php echo number_format($Categories[$i]->product_count); } ?>)</a></div>
						
						<?Php $CatName = strtolower( $Categories[$i]->url_safe_category_name ); ?>
						<?Php $CatName = str_replace('&', 'and', $CatName ); ?>
						<?Php $BrandList[] = str_replace(' ', '-', $CatName ); ?>
						<?Php $BrandIdList[] = $Categories[$i]->categoryid; ?>
						<?Php $Category->product_count += $Categories[$i]->product_count; ?>
					  <?Php 
					} 
				}
			} ?></div>
	<div >
	<p class='subheadertext' style="margin: 0px 0px 25px 0px !important;">or select the brand you are interested in </p>
	
	<div class="golf-club-list">Select the brand alphabetically 		
		<?Php 
	if($DEEPCATEGORIES == '1')
	{
			if(function_exists('site_review_brand_list_outdoor_first'))
			{ 
				site_review_brand_list_outdoor_first(1, $Category->url_safe_category_name, $BrandIdList); 
			}/*** print out brand list alphabetically */ 
	}
	else
	{
			if(function_exists('site_review_brand_list'))
			{ 
				site_review_brand_list(1, $Category->url_safe_category_name, $BrandIdList); 
			}/*** print out brand list alphabetically */ 
		
	}
	?> 
	</div></div>
	</div><!-- end product landing page -->




	
	<?Php 
	
	if($DEEPCATEGORIES == '1')
	{
		if(function_exists('site_review_product_accessory_list_outdoor'))
		{
			site_review_product_accessory_list_outdoor( $Categories, $BrandIdList); 
		}
	}
	else
	{
		if(function_exists('site_review_product_accessory_list'))
		{
			site_review_product_accessory_list( $Categories, $BrandList); 
		}
	}
	?>

	
	
 <?php if($Products) { 
 // echo count($Products);?>
	
	<div id="products" class="product-list"><div class="product-list-nav clearfix">
		<?php
		$redurl = $_SERVER['REQUEST_URI'];
		$nredurl = explode("?",$redurl);
		if(isset($_GET['sort']) && isset($_GET['pg']))
		{
			$sorturl = explode('?',$redurl);
			$fredurl = $sorturl[0]."?sort=";
		}
		else if(isset($_GET['sort']))
		{
			$sorturl = explode('?',$redurl);
			$fredurl = $sorturl[0]."?sort=";
		}
		else if(isset($_GET['pg']))
		{
			$fredurl = $nredurl[0]."?sort=";
		}
		else if(isset($_GET['view']))
		{
			$fredurl = $nredurl[0]."?sort=";
		}
		else
		{
			$fredurl = $redurl."?sort=";
		}
		?>

		<script type="text/javascript">
			function GetSelectedTextValue(ddlFruits) {
				var selectedText = ddlFruits.options[ddlFruits.selectedIndex].innerHTML;
				var selectedValue = ddlFruits.value;
				window.location = "<?php echo $fredurl; ?>"+selectedValue;
			}
		</script>
		<script type="text/javascript">
			function GetSelectedTextValue1(ddlFruits1) {
				var selectedText = ddlFruits1.options[ddlFruits1.selectedIndex].innerHTML;
				var selectedValue = ddlFruits1.value;
				window.location = "<?php echo $nredurl[0]; ?>?view="+selectedValue;
				//sessionStorage.setItem("SessionName","SessionData");
			}
		</script>
		<div class="sorting" style="float:left;">
					<?php if($ARCHIVED=='1'){ ?>
					
						Showing: 
						
						<a style="padding: 4px; <?php if($_SESSION['archive']!='0'){ echo ""; }else{echo "color:#000;text-decoration:none;";}?>"  href="<?php echo $nredurl[0]."?view=current";?>">Current Products</a>	
						
						<a style="padding: 4px; <?php if($_SESSION['archive']=='1'){ echo "color:#000;text-decoration:none;"; }else{echo "";}?>" href="<?php echo $nredurl[0]."?view=older";?>">Older Products</a>		
						
						<br><br>

					<?php } ?>


			Sort by
			<select onchange="GetSelectedTextValue(this)" style="    border: 1px solid #ccc;    padding: 2px;">
				<option value="reviews" <?php if($_SESSION['sort']=="reviews"){ echo "selected";}else{}?>>Latest Reviews</option>
				<?php
				if($latestproduct == 'enable')
				{
					?>
				<option value="latest" <?php if($_SESSION['sort']=="latest"){ echo "selected";}else{}?>>Latest Products</option>
					<?php
				}
				?>
				<option value="score" <?php if($_SESSION['sort']=="score"){ echo "selected";}else{}?>>Best Reviews</option>
				<option value="maxreviews" <?php if($_SESSION['sort']=="maxreviews"){ echo "selected";}else{}?>>Most Reviews</option>
				<option value="views" <?php if($_SESSION['sort']=="views"){ echo "selected";}else{}?>>Most Popular</option>
				<option value="asc" <?php if($_SESSION['sort']=="asc"){ echo "selected";}else{}?>>Alphabetical</option>
			</select>
		</div>
		<div style="float:right;"><?php  $GRclass->cr_category_pagination_components($Category, $_GET['pg']); ?></div>
		<div style="float:right;">Showing <?php $GRclass->cr_category_showing($Category, $_GET['pg']); ?> of <?Php echo $Category->product_count; ?> <!--<?php echo $Category->category_name;?>--> &nbsp;</div>
	</div>
	
	
	

			<?php include('product_listing_ui.php'); ?>

	<div class="product-list-nav clearfix"><div style="float:right;"><?php  $GRclass->cr_category_pagination_components($Category, $_GET['pg']); ?></div>
		<div style="float:right;">Showing <?php $GRclass->cr_category_showing($Category, $_GET['pg']); ?> of <?php echo $Category->product_count; ?> <!--<?php echo $Category->category_name; ?>-->&nbsp;</div>
	</div></div><?Php } ?><!-- end product list --> 
	
<!--<?php cr_site_deals_widget(1); ?> -->


</div><!-- end main content --> 
</div><!-- end inner --> 
</div><!-- end content left --> 
<?php get_sidebar(); ?> 
<?php get_footer(); ?>

