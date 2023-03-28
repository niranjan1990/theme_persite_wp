<?php
	/**
	 * The template for displaying Site Review Brand Page.
	 *
	 * @package WordPress
	 * @subpackage Site Review
	 * @since Site Review 1.0
	 */
ob_start(); 
session_start();	 
echo "<!-- The template for displaying Site Review Brand Page site_brand_page.php -->";
	 
require $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php';


?>
<?php $parts1 = explode( '/', $_SERVER['REQUEST_URI']); 
?>


<?php
global $pagename;

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
$cat_id = Get_CategoryID_By_Path($parts1[count($parts1)-2]);
}else{
$cat_id = Get_CategoryID_By_Path($parts1[1]);
}

$pagename = "MPL";
?>


<?php
// For seo
if($DEEPCATEGORIES == "1")
{
$parts2 = $parts1;
$brand = explode('.html',$parts2[count($parts2)-1]);
require $_SERVER['DOCUMENT_ROOT'] . '/wp-config-extra.php';
global $seo_title, $seo_description, $seo_keywords;

$seo_title_temp = str_replace("[category_name]",str_replace('-',' ',$parts2[count($parts2)-2]),$CATEGORYBRANDTITLE);
$seo_description_temp = str_replace("[category_name]",str_replace('-',' ',$parts2[count($parts2)-2]),$CATEGORYBRANDDESCRIPTION);
$seo_keywords_temp = str_replace("[category_name]",str_replace('-',' ',$parts2[count($parts2)-2]),$CATEGORYBRANDKEYWORDS);
$seo_title = str_replace("[brand_name]",str_replace('-',' ',$brand[0]),$seo_title_temp);
$seo_description = str_replace("[brand_name]",str_replace('-',' ',$brand[0]),$seo_description_temp);
$seo_keywords = str_replace("[brand_name]",str_replace('-',' ',$brand[0]),$seo_keywords_temp);
}else{
$parts2 = $parts1;
$brand = explode('.html',$parts2[3]);
require $_SERVER['DOCUMENT_ROOT'] . '/wp-config-extra.php';
global $seo_title, $seo_description, $seo_keywords;

$seo_title_temp = str_replace("[category_name]",str_replace('-',' ',$parts2[1]),$CATEGORYBRANDTITLE);
$seo_description_temp = str_replace("[category_name]",str_replace('-',' ',$parts2[1]),$CATEGORYBRANDDESCRIPTION);
$seo_keywords_temp = str_replace("[category_name]",str_replace('-',' ',$parts2[1]),$CATEGORYBRANDKEYWORDS);
$seo_title = str_replace("[brand_name]",str_replace('-',' ',$brand[0]),$seo_title_temp);
$seo_description = str_replace("[brand_name]",str_replace('-',' ',$brand[0]),$seo_description_temp);
$seo_keywords = str_replace("[brand_name]",str_replace('-',' ',$brand[0]),$seo_keywords_temp);

}

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
 
	
	global $Brand;
	if($DEEPCATEGORIES == "1")
	{
		if(function_exists('site_review_product_brand_list_outdoor'))
		{
			site_review_product_brand_list_outdoor();
		}
	}
	else
	{
		if(function_exists('site_review_product_brand_list'))
		{
			site_review_product_brand_list();
		}
	}
	
	set_site_review_location('manufacturer-pages');
	set_site_review_meta_vars($Brand->manufacturer_name, $Brand->product_count);
	get_header();
	
?>
	
	<div id="content-left" class="col-sm-8">
		<div class="inner">
			<div class="main-content">

<?php
	if($DEEPCATEGORIES == "1")
	{
		?>			<h2><?php echo $Brand->manufacturer_name; echo " ".str_replace('-',' ',$parts2[2]); ?></h2>
					<p class="subheadertext">
						Select the <?Php echo ucfirst($Brand->manufacturer_name); ?> product you are looking for or choose a different brand
					</p>
<?php
	}
	else
	{
		?>			<h2><?php echo $Brand->manufacturer_name; echo " ".str_replace('-',' ',$parts2[1]); ?></h2>
					<p class="subheadertext">
						Select the <?Php echo ucfirst($Brand->manufacturer_name); ?> product you are looking for or choose a different brand
					</p>		
					<?php
	}
?>









			<!-- //Articles -->
			<div class="golf-clubs">
			
			<?php
		
				$tag = $Brand->url_safe_manufacturer_name . '-featured';
				query_posts('tag='.$tag.'&posts_per_page=1');	
				if( have_posts() ) { while ( have_posts() ) { the_post(); ?>
				
				<div id="article-page" class="article-page clearfix">
					<div class="item-box">
					<h1 class="title lwr"><?php the_title(); ?></h1>
						<div class="text"><?php the_excerpt(); ?></div>
						<div class="text-right"><a class="arrow-right" href="<?php the_permalink(); ?>">read more</a> &nbsp;</div>
						
						<div class="product-share meta clearfix">
							<div class="sbg f"><iframe src="http://www.facebook.com/plugins/like.php?<?php the_permalink(); ?>&amp;send=false&amp;layout=button_count&amp;width=90&amp;show_faces=false&amp;action=like&amp;colorscheme=light&amp;font&amp;height=21" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:90px; height:21px;" allowTransparency="true"></iframe></div>
							<div class="sbg"><a href="http://twitter.com/share" class="twitter-share-button" data-url="<?php the_permalink(); ?>" data-count="horizontal">Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script></div>
							<div class="sbg"><div class="g-plusone" data-size="medium" href="<?php the_permalink(); ?>"></div></div>
							<div class="sbg cc l">
								<a href="<?php the_permalink(); ?>#comments"><img style="margin-bottom:-4px;" src="<?php bloginfo('template_url') ?>/images/comments-icon.png" /> <?php comments_number( '0 Comments', '1 Comment', '% Comments' ); ?></a>
							</div>
						</div>
					</div>
				</div>
				<?php } } ?>

			</div>
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			

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
	site_review_main_category_list($primaryNav); 
} 
/* get and return object array of all categories */ 

?>




<div class="golf-clubs">
	<div class="parent" style="width:100%;">
			<?Php
				$Categoriesbrand = $Categories;
				for($i=0;$i<count($Categoriesbrand);$i++) 
				{ 
					if($Categoriesbrand[$i]->node_level > 1 && $Categoriesbrand[$i]->node_level < 7) 
					{ 
						if(strpos($Categoriesbrand[$i]->category_path, $parts1[count($parts1)-2] ) !== false)
						{
							$BrandIdList[] = $Categoriesbrand[$i]->categoryid;						
							$CatName = strtolower( $Categoriesbrand[$i]->url_safe_category_name ); 
							$CatName = str_replace('&', 'and', $CatName );
							$BrandList[] = str_replace(' ', '-', $CatName );
						}
					}
				}
			?>
	</div>
 



	
	<div style="clear:both;">
		<div class="golf-club-list"> Select the brand alphabetically 
			<?Php 
			if($DEEPCATEGORIES=="1")
			{
				if(function_exists('site_review_brand_list_components_brand_category_one'))
				{ 
					site_review_brand_list_components_brand_category_one(1, "components", $BrandIdList); 
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
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			<?php			
			/*** If brand products exist, list all products */
			if($Products) {	?>			
			<div id="products" class="product-list">
					<?php
					if($DEEPCATEGORIES == '1')
					{
						$pagination = "cr_brand_pagination_outdoor_brand_one";
					}
					else
					{
						$pagination = "cr_brand_pagination";
					}
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
					<div style="float:right;">&nbsp;<?php $pagination($Brand,$_GET['pg']); ?></div><div style="float:right;">Showing <?php $GRclass->cr_category_showing($Brand, $_GET['pg']); ?> of <?Php echo $Brand->product_count; ?> <!--<?php echo $Category->category_name;?>--> &nbsp;</div>

						<?php 
						$i=0;
						foreach($Products as $Product) {
						$i= $i+1;
						}
						?>
						<?php if($i > 50) { ?>
						<div style="float:right;">Showing <?php cr_product_count_showing($Brand,$_GET['pg']); ?> of <?php echo $Brand->product_count; ?> <!--<?php echo $Brand->manufacturer_name; ?> Products--></div>
						<?php } ?>
						
					</div>					
			<?php include('product_listing_ui.php'); ?>
				
				<div class="product-list-nav clearfix"><div style="float:right;">&nbsp;<?php $pagination($Brand,$_GET['pg']); ?></div><div style="float:right;">Showing <?php $GRclass->cr_category_showing($Brand, $_GET['pg']); ?> of <?Php echo $Brand->product_count; ?> <!--<?php echo $Category->category_name;?>--> &nbsp;</div>
					<?php if($i > 50) { ?>

						<div style="float:right;">Showing <?php cr_product_count_showing($Brand,$_GET['pg']); ?> of <?php echo $Brand->product_count; ?> <!--<?php echo $Brand->manufacturer_name; ?> Products--></div>
					<?php } ?>
						
				</div>
				
			</div>
		<?php } ?><!-- end product list -->	
		
	
	
	
	</div><!-- end main content -->
	</div><!-- end inner -->
	</div><!-- end content left -->
	
<?php get_sidebar(); ?>
<?php get_footer(); ?>
