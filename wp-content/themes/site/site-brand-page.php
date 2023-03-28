<?php 
/* Template Name: Brand Page Template */ 
/* Author : Siva */
echo "<!-- Template Name: Brand Page Template  site-brand-page.php-->";
?>


<?php
ob_start(); 
session_start();
//$newp = explode('.html',$parts1[3]);
// For seo
$parts1 = explode('/',$_SERVER[REQUEST_URI]);
$parts2 = explode('.',$parts1[1]);
require $_SERVER['DOCUMENT_ROOT'] . '/wp-config-extra.php';
global $seo_title, $seo_description, $seo_keywords;

$seo_title = $BRANDSTITLE;
$seo_description = $BRANDSDESCRIPTION;
$seo_keywords = $BRANDSKEYWORDS;






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

<?php get_header();  ?>



















<?php if(function_exists('site_review_main_category_list')){ 
			//echo "hi";
			$menuLocations = get_nav_menu_locations(); // Get our nav locations (set in our theme, usually functions.php)
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


site_review_main_category_list($primaryNav,$_SESSION['archive']); 
} /* get and return object array of all categories */ ?>
<?php $Category =  (object)array(); ?>
<?php $Category->product_count	= 0; /* initializing value 0  to product_count */ ?>
<?php $Category->category_name 	= $url; // Golf Clubs ?>
<?php $Category->url_safe_category_name  = $parts2[0]; // golf-clubs ?>
<?php set_site_review_location(); ?>
<?php get_header();  ?>

<div id="content-left" class="col-sm-8">
<div class="inner">
<div class="main-content"> <!-- added new CSS class to make generic compound pages -->
<h1><?php echo $Category->category_name; ?></h1><!-- Page title will changed accordingly -->



<h1><?php echo $BRANDSTITLEPAGE; ?></h1>

<p class="subheadertext">
	Select the product you are looking for
</p>



<div class="golf-clubs">
	<div class="parent" style="width:100%;">
			<?Php
				$NODEID = 2; 
				//echo "<pre>";
				//print_r($Categories);
				//echo "</pre>";
				for($i=0;$i<count($Categories);$i++) 
				{ 
					if($Categories[$i]->node_level > 1 && $Categories[$i]->node_level < 7) 
					{ 
						if($Categories[$i]->node_level == 2 && $Categories[$i]->product_count != 0)
						{
					?>
						<!--<div style="float:left;margin:5px 5px 5px 0px;border-radius: 5px;padding: 4px 4px 4px 0px;text-align: center;">
						<a class="BtnWriteReview" id="featured-nav" style="font-weight: normal;text-decoration:none;padding: 4px;border-radius: 2px;color: #fff;" href="/<?php echo $Categories[$i]->url_safe_category_name; ?>.html"><?php 

							if(function_exists('cr_category_name')){ cr_category_name($Categories[$i]->category_name); ?> 

							(<?php echo number_format($Categories[$i]->product_count); } ?>)</a>
						</div>-->
					<?Php 
							$Category->product_count += $Categories[$i]->product_count; 
						}
						
							$BrandIdList[] = $Categories[$i]->categoryid;						
							$CatName = strtolower( $Categories[$i]->url_safe_category_name ); 
							$CatName = str_replace('&', 'and', $CatName );
							$BrandList[] = str_replace(' ', '-', $CatName );
					}
					
				
				
				}

			
			
			
			?>
			</div>
 



	<?php  if($COMPONENETSOTHERS == 1){ ?>
	
	
	
	<div style="clear:both;">
	<div class="golf-club-list"> Select the brand alphabetically 	
		<?Php 
		if($DEEPCATEGORIES=="1")
		{
			if(function_exists('site_review_brand_list'))
			{ 
				site_review_brand_list_brands(1, "components", $BrandIdList); 
			}/*** print out brand list alphabetically */ 
		}
		else
		{
			if(function_exists('site_review_brand_list'))
			{ 
				site_review_brand_list(); /*** print out brand list alphabetically */ 
			} 
		}	
		?>		
	</div></div>

	
	
	
	
	
	
	
	
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
			site_review_product_accessory_list( $Categoriesn, $BrandList); 
		}
	}
	?>	
	
	
	
	
	
	<br>
	
	<?php if($Products) { ?>
	
		<div id="products" class="product-list">			
			
			
			<?php
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
		<div style="float:right;">
		
<?php $Category->url_safe_category_name = $parts2[0]; $GRclass->cr_category_pagination_components($Category,$_GET['pg']); ?></div>
				<div style="float:right;">Showing <?php $GRclass->cr_category_showing($Category, $_GET['pg']); ?> of <?php echo $Category->product_count; ?> <!--<?php echo $Category->category_name;  ?>-->&nbsp;
				</div>
			</div>
			<?php
			}
			else
			{
			?>
		<div class="product-list-nav clearfix"><div style="float:right;"><?php  $Category->url_safe_category_name = $parts2[0]; $GRclass->cr_category_pagination_outdoor($Category1,$_GET['pg']); ?></div>
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
			
		<div class="product-list-nav clearfix">
		
		
		
		
		<div style="float:right;"><?php  $Category->url_safe_category_name = $parts2[0]; $GRclass->cr_category_pagination_components($Category,$_GET['pg']); ?></div>
				<div style="float:right;">Showing <?php $GRclass->cr_category_showing($Category, $_GET['pg']); ?> of <?php echo $Category->product_count; ?> <!--<?php echo $Category->category_name;  ?>-->&nbsp;</div>
			</div>
			<?php
			}
			else
			{
			?>
		<div class="product-list-nav clearfix"><div style="float:right;"><?php  $Category->url_safe_category_name = $parts2[0]; $GRclass->cr_category_pagination_outdoor($Category1,$_GET['pg']); ?></div>
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
	
	
	<?php } else {} ?>

	
</div>

	
	





















<!--
 Old Brands.html code

<style>
.selection{
font-size:17px;
}
</style>
	


<?php
require_once(__DIR__.'/../../../wp-reviewconfig.php');
$con = mysqli_connect(DB_RHOST,DB_RUSER,DB_RPASSWORD,DB_RNAME) or die("Some error occurred during connection " . mysqli_error($con));  

$PSite = explode(":", get_option( 'PSiteName' ) );  


$qry = "select manufacturer_name,url_safe_manufacturer_name from manufacturers m, products p where p.manufacturerid = m.manufacturerid group by manufacturer_name order by manufacturer_name";
 
$result = mysqli_query($con,$qry);
$current_char = '';
?>


<table id="MiddleTable1" class="MiddleTable" cellspacing="0" cellpadding="0" align="Center" bordercolor="#00FF00" border="0" style="border-color:#00FF00;width:100%;border-collapse:collapse;">
	<tbody><tr>
		<td class="MiddleTableMiddleColumn" valign="top"> 
		<div id="content"> 
		<br><br>


<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tbody>
	<tr valign="top">
        <td>
			<table class="bodytext selection" width="100%" border="0" cellspacing="0" cellpadding="10">
				<tbody>
				<tr>
					<td>

						&nbsp;<a href="#_#">#</a>

						&nbsp;<a href="#_A">A</a>

						&nbsp;<a href="#_B">B</a>

						&nbsp;<a href="#_C">C</a>

						&nbsp;<a href="#_D">D</a>

						&nbsp;<a href="#_E">E</a>

						&nbsp;<a href="#_F">F</a>

						&nbsp;<a href="#_G">G</a>

						&nbsp;<a href="#_H">H</a>

						&nbsp;<a href="#_I">I</a>

						&nbsp;<a href="#_J">J</a>

						&nbsp;<a href="#_K">K</a>

						&nbsp;<a href="#_L">L</a>

						&nbsp;<a href="#_M">M</a>

						&nbsp;<a href="#_N">N</a>

						&nbsp;<a href="#_O">O</a>

						&nbsp;<a href="#_P">P</a>

						&nbsp;<a href="#_Q">Q</a>

						&nbsp;<a href="#_R">R</a>

						&nbsp;<a href="#_S">S</a>

						&nbsp;<a href="#_T">T</a>

						&nbsp;<a href="#_U">U</a>

						&nbsp;<a href="#_V">V</a>

						&nbsp;<a href="#_W">W</a>

						&nbsp;<a href="#_X">X</a>

						&nbsp;<a href="#_Y">Y</a>

						&nbsp;<a href="#_Z">Z</a>

					</td>
				</tr>
			</tbody>
			</table>
            <br>


	
	
	
	

<?php
$p =0;

while ($res1=mysqli_fetch_assoc($result))
{
	$fir = substr($res1['manufacturer_name'],0,1);
	if($fir == '0' || $fir == '1' || $fir == '2' || $fir == '3' || $fir == '4' || $fir == '5' || $fir == '6' || $fir == '7' || $fir == '8' || $fir == '9')
	{
		$nrecords[] = $res1['manufacturer_name'];
		$nlink[] =  $res1['url_safe_manufacturer_name'];
	}
	else
	{
		$records[] =  $res1['manufacturer_name'];
		$link[] =  $res1['url_safe_manufacturer_name'];
	}
}

?>
	
	
<table class="bodytext" width="100%" border="0" cellspacing="0" cellpadding="10">
<tbody><tr>
<td align="left" valign="top"><a name="_#"><font style="font-size:14px" width="20%"><b>#</b></font></a></td><td align="left" valign="top" width="60px">	</td>
<td>
	
<?php
    for($i=0;$i<count($nrecords);$i++)
    {

		?>

<div class="col-sm-4">•&nbsp;<a href="<?php echo "/brands/".$nlink[$i].".html"; ?>"><?php echo $nrecords[$i]; ?> </a></div>
	<?php }
	?>
</td>
</tr>
<tr>
<td colspan="5"><hr></td>
</tr>
</tbody></table>	
	
	
	
	


<?php





    $temp=array();    
    $first_char="";
    for($i=0;$i<count($records);$i++)
    {

				$first_char= strtoupper ($records[$i][0]);             


	?>		 
	<?php
	if(!in_array($first_char, $temp))
    {
		if($i==0)
		{
			
		}
		else
		{
			echo '
				</td>

				</tr>
				<tr>
					<td colspan="5"><hr></td></tr>
				</tbody></table>

			
			
			';
		}



			echo '<table class="bodytext" width="100%" border="0" cellspacing="0" cellpadding="10">
				<tbody>

				<tr>

				<td align="left" valign="top" style="width:60px">
				<a name="_'.strtoupper($first_char).'">
				<font style="font-size:14px" width="20%"><b>'.strtoupper($first_char).'</b></font>
				</a>
				</td>			
				<td align="left" valign="top">';
			
			
	}
	
    $temp[]=  $first_char;
	?>		
			<div class="col-sm-4">•&nbsp;<a href="<?php echo "/brands/".$link[$i].".html"; ?>"><?php echo $records[$i]; ?> </a></div>
			 
	<?php		 
			 
			 
			 
			 
			 
	}
			echo '
				</td>

				</tr>
				<tr>
					<td colspan="5"><hr></td></tr>
				</tbody></table>

			
			
			';
	
?>








	
	
	
	
	
	
	
	
	
	





		</td>
	</tr>
</tbody>
</table>



</div></td>
	</tr>
</tbody></table>


-->
















</div><!-- end main content --> 
</div><!-- end inner --> 
</div><!-- end content left --> 
<?php get_sidebar(); ?> 
<?php get_footer(); ?>
