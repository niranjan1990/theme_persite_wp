<?php 
/* Template Name: Brand Category Page Template */ 
/* Author : Siva */
echo "<!-- Template Name: Brand Category Page Template site-brand-cat-page.php -->";

?>

<?php
ob_start(); 
session_start();

global $pagename;
$pagename = "MPL";
?>
<?php
//$newp = explode('.html',$parts1[3]);
// For seo
require $_SERVER['DOCUMENT_ROOT'] . '/wp-config-extra.php';
global $seo_title, $seo_description, $seo_keywords;

$seo_title = str_replace("[brand_name]",$linkname,$BRANDSMANUFACTURERTITLE);
$seo_description = str_replace("[brand_name]",$linkname,$BRANDSMANUFACTURERTDESCRIPTION);
$seo_keywords = str_replace("[brand_name]",$linkname,$BRANDSMANUFACTURERTKEYWORDS);

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


<?php $parts1 = explode( '/', $_SERVER['REQUEST_URI']); ?>
<?php $parts2 = explode( '.html', $parts1[2] ); ?>
<?php $url = ucwords ( str_replace('-',' ', $parts2[0] ) ); ?>




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

if(function_exists('site_review_brand_cat_outdoor')){ 


  site_review_brand_cat_outdoor(); 
 
} /* get and return object array of all categories */ ?>


<?php $Category =  (object)array(); ?>
<?php $Category->product_count	= 0; /* initializing value 0  to product_count */ ?>
<?php $Category->category_name 	= $url; // Golf Clubs ?>
<?php $Category->url_safe_category_name  = $parts2[0]; // golf-clubs ?>
<?php //set_site_review_location(); ?>
<?php get_header();  ?>

<div id="content-left" class="col-sm-8">
<div class="inner">
<div class="main-content"> <!-- added new CSS class to make generic compound pages -->
<h1><?php echo $Category->category_name; ?></h1><!-- Page title will changed accordingly -->



<p class="subheadertext">
	Select the <?Php echo ucfirst($Category->category_name); ?> product you are looking for or choose a different brand
</p>




<?php 	$productCategories = $Categories; ?> 


<?php 
if(function_exists('site_review_main_category_list'))
{ 
	$menuLocations = get_nav_menu_locations(); 
	// Get our nav locations (set in our theme, usually functions.php)
    // This returns an array of menu locations ([LOCATION_NAME] = MENU_ID);

	$menuID = $menuLocations['primary']; // Get the *primary* menu ID

	$primaryNav = wp_get_nav_menu_items($menuID); // Get the array of wp objects, the nav items for our queried location.
	
	
	foreach ( $primaryNav as $navItem ) 
	{
		//print_r($navItem);
		if($navItem->menu_item_parent == 0)
		{	
			//$jsonprd = json_encode($navItem);
			//echo "<pre>";
			//var_dump($jsonprd);
			//echo "</pre>";
			//echo '<li><a href="'.$navItem->url.'" title="'.$navItem->title.'">'.$navItem->title.'</a></li>';
		}
	}
	site_review_main_category_list($primaryNav,$_SESSION['archive']); 
} 
/* get and return object array of all categories */ 

?>




















<div class="golf-clubs">
	<div class="parent" style="width:100%;">
			<?Php
				$NODEID = 2; 
				//echo "<pre>";
				//print_r($Categories);
				//echo "</pre>";
				$Categoriesbrand = $Categories;
				for($i=0;$i<count($Categoriesbrand);$i++) 
				{ 
					if($Categoriesbrand[$i]->node_level > 1 && $Categoriesbrand[$i]->node_level < 7) 
					{ 
							$BrandIdList[] = $Categoriesbrand[$i]->categoryid;						
							$CatName = strtolower( $Categoriesbrand[$i]->url_safe_category_name ); 
							$CatName = str_replace('&', 'and', $CatName );
							$BrandList[] = str_replace(' ', '-', $CatName );
					}
				}
			
			?>
	</div>
 



	
	<div style="clear:both;">
		<div class="golf-club-list"> Select the brand alphabetically 	
			<?Php 
			if($DEEPCATEGORIES=="1")
			{
				if(function_exists('site_review_brand_list_components_brand'))
				{ 
					site_review_brand_list_components_brand(1, "components", $BrandIdList); 
				}/*** print out brand list alphabetically and select the current brand */ 
			}
			else
			{
				if(function_exists('site_review_brand_list'))
				{ 
					site_review_brand_list(); /*** print out brand list alphabetically */ 
				} 
			}	
			?>		
		</div>
	</div>

	<br>

	
	
	
</div>



	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	

<div class="golf-clubs">
	<div class="parent" style="width:100%;">
			<?Php
				//echo "<pre>";
				//print_r($productCategories);
				//echo "</pre>";
				
				$catid=0;
				$pcount=0;
				for($i=0;$i<count($productCategories);$i++) 
				{ 	
					$productCategories[$i]->product_count = 0;

					if($catid==$productCategories[$i]->categoryid)	
					{
						$pcount= $pcount +1;
						$productCategories[$i]->product_count = $productCategories[$i]->product_count +1;
					}
					else
					{
						$pcount= $pcount +1;
						$productCategories[$i]->product_count = $productCategories[$i]->product_count +1;
						$productCategoriesnew[]=$productCategories[$i];
						
					}
					$catid=$productCategories[$i]->categoryid;
					

				}	


				$NODEID = 2; 
				for($i=0;$i<count($productCategoriesnew);$i++) 
				{ 
					
							$Category->product_count += $productCategoriesnew[$i]->product_count; 
						
						
							$BrandIdList[] = $productCategoriesnew[$i]->categoryid;						
							$CatName = strtolower( $productCategoriesnew[$i]->url_safe_category_name ); 
							$CatName = str_replace('&', 'and', $CatName );
							$BrandList[] = str_replace(' ', '-', $CatName );
				
				}

			
			
			
			?>
			</div>
 



	<?php  //if($COMPONENETSOTHERS == 1){ ?>
	
	
	
	
	
	
	
	
	<?Php 
	if($DEEPCATEGORIES == '1')
	{
		if(function_exists('site_review_product_accessory_list_brand_outdoor'))
		{
			site_review_product_accessory_list_brand_outdoor( $Categoriesnew, $BrandIdList); 
		}
	}
	else
	{
		if(function_exists('site_review_product_accessory_list'))
		{
			site_review_product_accessory_list( $Categoriesn, $BrandList); 
		}
	}
	?>	
	
	
	
	
	
	
	
	<?php if($Products) { ?>
	
		<div id="products" class="product-list" style="clear: both;">			
			
			
			<?php
			$Catpc->product_count = $pcount;
			if($DEEPCATEGORIES == '1')
			{
			?>
			
		<div class="product-list-nav clearfix">
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
			<div style="float:right;"><?php  $Catpc->url_safe_category_name = $parts2[0]; $GRclass->cr_category_pagination_components($Catpc,$_GET['pg']); ?></div>
				<div style="float:right;">Showing <?php $GRclass->cr_category_showing($Catpc, $_GET['pg']); ?> of <?php echo $pcount; ?> <!--<?php echo $Category->category_name;  ?>-->&nbsp;</div>
			</div>
			<?php
			}
			else
			{
			?>
		<div class="product-list-nav clearfix">
			<?php
			$redurl = $_SERVER['REQUEST_URI'];
			if(isset($_GET['sort']) && isset($_GET['pg']))
			{
				$sorturl = explode('&sort',$redurl);
				$fredurl = $sorturl[0]."&sort=";
			}
			else if(isset($_GET['sort']))
			{
				$sorturl = explode('?sort',$redurl);
				$fredurl = $sorturl[0]."?sort=";
			}
			else if(isset($_GET['pg']))
			{
				$fredurl = $redurl."&sort=";
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
			<div class="sorting" style="float:left;">Sort by
				<select onchange="GetSelectedTextValue(this)" style="    border: 1px solid #ccc;    padding: 2px;">
					<option value="score" <?php if($_SESSION['sort']=="score"){ echo "selected";}else{}?>>Best Score</option>
					<option value="maxreviews" <?php if($_SESSION['sort']=="maxreviews"){ echo "selected";}else{}?>>Most Reviews</option>
					<option value="reviews" <?php if($_SESSION['sort']=="reviews"){ echo "selected";}else{}?>>Latest Reviews</option>
					<option value="views" <?php if($_SESSION['sort']=="views"){ echo "selected";}else{}?>>Most Popular</option>
					<option value="asc" <?php if($_SESSION['sort']=="asc"){ echo "selected";}else{}?>>Alphabetical</option>
				</select>
			</div>
			<div style="float:right;"><?php  $Category->url_safe_category_name = "components"; $GRclass->cr_category_pagination_outdoor($Category1,$_GET['pg']); ?></div>
				<div style="float:right;">Showing <?php $GRclass->cr_category_showing($Category1, $_GET['pg']); ?> of <?php echo $Category1->product_count; ?> <!--<?php echo $Category->category_name;  ?>-->&nbsp;</div>
			</div>
			
			<?php
			}
			?>
			

			<?php include('product_listing_ui.php'); ?>


			<?php
			if($DEEPCATEGORIES == '1')
			{
			?>
			
		<div class="product-list-nav clearfix"><div style="float:right;"><?php  $Catpc->url_safe_category_name = $parts2[0]; $GRclass->cr_category_pagination_components($Catpc,$_GET['pg']); ?></div>
				<div style="float:right;">Showing <?php $GRclass->cr_category_showing($Catpc, $_GET['pg']); ?> of <?php echo $pcount; ?> <!--<?php echo $Category->category_name;  ?>-->&nbsp;</div>
			</div>
			<?php
			}
			else
			{
			?>
		<div class="product-list-nav clearfix"><div style="float:right;"><?php  $Category->url_safe_category_name = $parts3[0]; $GRclass->cr_category_pagination_outdoor($Category1,$_GET['pg']); ?></div>
				<div style="float:right;">Showing <?php $GRclass->cr_category_showing($Category1, $_GET['pg']); ?> of <?php echo $Category1->product_count; ?> <!--<?php echo $Category->category_name;  ?>-->&nbsp;</div>
			</div>
			
			<?php
			}
			?>
			
			
		</div>
		
		<?php } 
		else { 
			//header("Location: /404");?><!-- end product list -->
			<!-- if no products in brand/category exist -->
			<p class="subheadertext">No products found in this category.</p>	
		<?php } ?>	
	
	
	<?php  //} else {} ?>
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
</div>

	
	



</div><!-- end main content --> 
</div><!-- end inner --> 
</div><!-- end content left --> 
<?php get_sidebar(); ?> 
<?php get_footer(); ?>
