<?php global $Brand, $GRclass, $Categories,$Product,$Category,$Products; ?>
<?php $pageid=get_page_template_slug( $post_id ); ?>
<?php $parts1 = explode( '/', $_SERVER['REQUEST_URI']); ?>
<?php $parts2 = explode( '.html', $parts1[1] ); ?>
<?php $url = ucwords ( str_replace('-',' ', $parts2[0] ) ); ?>
<?php if(function_exists('site_review_category_list')){ site_review_category_list(); } /* get and return object array of all categories */ ?>
<?php $Category =  (object)array(); ?>
<?php $Category->product_count	= 0; /* initializing value 0  to product_count */ ?>
<?php $Category->category_name 	= $url; // Golf Clubs ?>
<?php $Category->url_safe_category_name  = $parts2[0]; // golf-clubs ?>
<?php set_site_review_location(); ?>
<?Php site_review_location_type();  /* get and return object array of all courses */ ?> 
<?php include(__DIR__.'/../../../wp-reviewconfig.php'); ?>
<style>
.productss{
border: 1px solid #F0EFDD;
}
.tt-dropdown-menu{
background-color:#F0EFDD;
width:100%;
}
.active{
 background-color:#ccc;
}
</style>
<script>
function clearMyField() {
if(document.getElementById('searchkey').value != "Please Enter your Product Name") {
document.getElementById('searchkey').value = "";
}
}
function abcd123(sdf){
	var oldData = $("#searchkey").data('brand');
	var newData = oldData + ' ' + sdf;
	$(".productss").val(newData);
	$("#cate").val(sdf);
}
function abcd(test){
	$("#brandurl").val(test);
	var valNew=test.split('/');
	var ss = valNew[5].split('.');
	$("#brands").val(ss[0]);
	
	var ans=document.getElementsByClassName('productss')[0].value;

	if(ans==''){
		$(".productss").val(ss[0]);
		var reg  = /.*\/(.*).html/g
		var brand = reg.exec(test)[1];
		$("#searchkey").data('brand',brand);
				
				
		        $.ajax({
                url: '<?php echo get_bloginfo('siteurl'); ?>/results.php',
                dataType: 'text',
                type: 'post',
                data: {brand:brand},
                success: function( data, textStatus, jQxhr ){
					$('#content-right .golf-club-list').html(data);
                },
                error: function( jqXhr, textStatus, errorThrown ){
                    console.log( errorThrown );
                }
            });

	}else{
		$("input:text").val(test);
	}
}
	</script>
<script>
	$(document).ready(function() {
		$("#kumar").click(function(event){ 
			event.preventDefault();
			var brandurl = $("#brandurl").val();
			var brands = $("#brands").val();
			var cate = $("#cate").val();
			var searchkey = $("#searchkey").val();
			var category = $("#category").val();
			var trailsorbike = $("#trailsorbike").val();
			//alert(searchkey);
			var dataString = 'brandurl='+ brandurl + '&brands='+ brands + '&cate='+ cate + '&searchkey=' + searchkey + '&category=' + category + '&trailsorbike=' + trailsorbike;
			$.ajax({
			type: "POST",
			url: <?php get_bloginfo('url')?>"/process.php",
			data: dataString,
			cache: false,
			success: function(result){
				window.location.href = result;
			}
			});
		});
	});
	
</script>		
	

	<div id="content-right" class="col-sm-4" >
		<?Php
			if(function_exists('Get_NodeID_By_Path')){
			$NODEID = Get_NodeID_By_Path( $Category->url_safe_category_name ); 
					}	
					
			for($i=0;$i<count($Categories);$i++) { /* NODEID is also database driven */
				
				if(strpos($Categories[$i]->nodeid, $NODEID ) !== false && $Categories[$i]->node_level == 3 && number_format( $Categories[$i]->product_count) != 0) { ?>
					<?php //if(function_exists('cr_category_name')){ cr_category_name($Categories[$i]->category_name);  } ?>
					<?Php $CatName = strtolower( $Categories[$i]->category_name ); ?>
					<?Php $CatName = str_replace('&', 'and', $CatName ); ?>
					<?Php $BrandList[] = str_replace(' ', '-', $CatName ); //print_r($BrandList);?>
					<?Php $Category->product_count += $Categories[$i]->product_count; ?>
				<?Php } } ?>
	
	
		<div>
		<div class="content">
		
		
		
		
		

		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
<!-- Siva for form autocomplete -->		
		
		
		
		
		<!--

<script type="text/javascript">
function fill1(Value, gid)
{
	$('#name1').val(Value);
	$('#gid1').val(gid);
	$('#result1').hide();
}
function fill2(Value, gid)
{
	$('#name2').val(Value);
	$('#gid2').val(gid);
	$('#result2').hide();
}

function fill3(Value, gid)
{
	$('#name3').val(Value);
	$('#gid3').val(gid);
	$('#result3').hide();
}

function fill4(Value, gid)
{
	$('#name4').val(Value);
	$('#gid4').val(gid);
	$('#result4').hide();
}

$(document).ready(function()
{
	$("#name1").keyup(function() 
	{
		var name = $('#name1').val();
		var gid = $('#gid1').val();
		var fill = $('#fill1').val();
		var filter = $('#filter1').val();
		if(name=="")
		{
			$("#result1").html("");
		}
		else
		{
			$.ajax(
			{
				type: "POST",
				url: "/ajaxform/ajax.php",
				data: ({"name":name, "filter":filter, "fill":fill}),
				success: function(html)
				{
					$("#result1").html(html).show();
				}
			});
		}
	});

	$("#name2").keyup(function() 
	{
		var name = $('#name2').val();
		var gid = $('#gid2').val();
		var fill = $('#fill2').val();
		var filter = $('#filter2').val();
		
		if(name=="")
		{
			$("#result2").html("");
		}
		else
		{
			$.ajax(
			{
				type: "POST",
				url: "/ajaxform/ajax.php",
				data: ({"name":name,"filter":filter,"fill":fill}),
				success: function(html)
				{
					$("#result2").html(html).show();
				}
			});
		}
	});

	$("#name3").keyup(function() 
	{
		var name = $('#name3').val();
		var gid = $('#gid3').val();
		var fill = $('#fill3').val();
		var filter = $('#filter3').val();
		if(name=="")
		{
			$("#result3").html("");
		}
		else
		{
			$.ajax(
			{
				type: "POST",
				url: "/ajaxform/ajax.php",
				data: ({"name":name,"filter":filter,"fill":fill}),
				success: function(html)
				{
					$("#result3").html(html).show();
				}
			});
		}
	});

	$("#name4").keyup(function() 
	{
		var name = $('#name4').val();
		var gid = $('#gid4').val();
		var fill = $('#fill4').val();
		var filter = $('#filter4').val();
		if(name=="")
		{
			$("#result4").html("");
		}
		else
		{
			$.ajax(
			{
				type: "POST",
				url: "/ajaxform/ajax.php",
				data: ({"name":name, "filter":filter, "fill":fill}),
				success: function(html)
				{
					$("#result4").html(html).show();
				}
			});
		}
	});
});
</script>


<form method="post" action="">

Home Golf Course: 
<input type="text" style="    width: 100%;
    height: 25px;
    padding: 5px;
    margin-top: 15px;
    -webkit-border-radius: 2px;
    -moz-border-radius: 2px;
    border-radius: 2px;
    outline: none;
    border: 1px solid #ababab;
    font-size: 14px;
    line-height: 25px;
    color: #ababab;" name="name" id="name1" autocomplete="off" value="">
	
	
	
<input type="hidden" name="gid" id="gid1" value="">
<input type="hidden" name="fill" id="fill1" value="fill1">
<input type="hidden" name="filter" id="filter1" value="golf-courses">


<div id="result1"></div>
	
Fav Golf Course:	
	<input type="text" style="    width: 100%;
    height: 25px;
    padding: 5px;
    margin-top: 15px;
    -webkit-border-radius: 2px;
    -moz-border-radius: 2px;
    border-radius: 2px;
    outline: none;
    border: 1px solid #ababab;
    font-size: 14px;
    line-height: 25px;
    color: #ababab;" name="name" id="name2" autocomplete="off" value="">
	
	
	
<input type="hidden" name="gid" id="gid2" value="">
<input type="hidden" name="fill" id="fill2" value="fill2">
<input type="hidden" name="filter" id="filter2" value="golf-courses">

<div id="result2"></div>
	
	
Current Driver/Wood/Iron:	
	<input type="text" style="    width: 100%;
    height: 25px;
    padding: 5px;
    margin-top: 15px;
    -webkit-border-radius: 2px;
    -moz-border-radius: 2px;
    border-radius: 2px;
    outline: none;
    border: 1px solid #ababab;
    font-size: 14px;
    line-height: 25px;
    color: #ababab;" name="name" id="name3" autocomplete="off" value="">
	
	
	
<input type="hidden" name="gid" id="gid3" value="">
<input type="hidden" name="fill" id="fill3" value="fill3">
<input type="hidden" name="filter" id="filter3" value="golf-clubs">
	

<div id="result3"></div>
	
Past Driver/Wood/Iron:	
	<input type="text" style="    width: 100%;
    height: 25px;
    padding: 5px;
    margin-top: 15px;
    -webkit-border-radius: 2px;
    -moz-border-radius: 2px;
    border-radius: 2px;
    outline: none;
    border: 1px solid #ababab;
    font-size: 14px;
    line-height: 25px;
    color: #ababab;" name="name" id="name4" autocomplete="off" value="">
	
	
	
<input type="hidden" name="gid" id="gid4" value="">
<input type="hidden" name="fill" id="fill4" value="fill4">
<input type="hidden" name="filter" id="filter4" value="golf-clubs">

<div id="result4"></div>

	
	
</form>
-->
<!-- Siva for form autocomplete -->		


		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		


<!-- Login widget -->		
<?php 
$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
//echo $_SERVER["REQUEST_URI"];

if(isset($_GET['logout']))
{


    unset($_COOKIE['userid']);
    unset($_COOKIE['username']);
	    setcookie('userid','',0,'/',COOKIE_DOMAIN);
	    setcookie('username','',0,'/',COOKIE_DOMAIN);
		session_destroy();

		header("location: ".$_GET['logout']."");
		//echo "<script>window.location('".$_GET['logout']."');</script>";
}

if(isset($_COOKIE['userid'])) 
{
	//echo '<pre>';
	//print_r($_COOKIE);
	//echo '</pre>';
	echo "<div class='loginpanel' style='    margin-top: 20px;' ><strong style='margin-top:'>Welcome <a href='/user-profile.html'>".$_COOKIE['username']."</a>, <a style='text-decoration: underline;' href='/?logout=$actual_link'>Logout</a></strong></div>";
}

else if($_COOKIE['userid']=='deleted')
{
	//print_r($_COOKIE);
	echo "<div class='loginpanel' style='    margin-top: 20px;' ><strong>Welcome Guest, <a href='/user-login.html?rurl=$actual_link'>Login</a></strong>";
	echo " / ";
	echo "<strong><a href='/user-registration.html?rurl=$actual_link'>Register</a></strong></div> ";
}
else
{
	echo "<div class='loginpanel' style='    margin-top: 20px;' ><strong>Welcome Guest, <a href='/user-login.html?rurl=$actual_link'>Login</a></strong>";
	echo " / ";
	echo "<strong><a href='/user-registration.html?rurl=$actual_link'>Register</a></strong></div> ";
	
}

?>
<!-- Login widget -->				
		
		
		
		
		
		
		<br><br><br>
		<h3><b>Review your Products</b></h3>
		<!--<?php
			$output = array();
			foreach($BrandList as $term){
			  $output[] = $term;
			}
			$mari="'" . implode("','", $output) . "'";
		?>-->
		
		<style>
		.tt-suggestion
		{
			padding:7px!important;
		}
		</style>
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
<!-- New Ajax Search -->

		<!-- Load CSS -->
		<link href="/ajaxsearch/style/style.css" rel="stylesheet" type="text/css" />
		<!-- Load Fonts -->
		<!-- Load jQuery library -->
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
		<!-- Load custom js -->
		<script type="text/javascript" src="/ajaxsearch/scripts/custom.js"></script>

		<script type="text/javascript" src="/ajaxsearch1/scripts/custom.js"></script>

<script>
jQuery(document).ready(function(){
    jQuery('#hideshow').live('click', function(event) {        
         jQuery('#autosearchbar').toggle('show');
		 jQuery("body").removeClass("shiftnav-open");
		 jQuery(".shiftnav-left-edge").removeClass("shiftnav-open-target");
		 
    });
});
</script>


<!-- New Ajax Search -->

		
		
		
		
		
		<?php

		global $url1;
		//echo $url1[0];
	?>	
		
		
		
		
		
		
		
		
		
		<form method="post">
		<input type="hidden" name="brandurl" id="brandurl" value=""/>
		<input type="hidden" name="brands" id="brands" value=""/>
		<input type="hidden" name="cate" id="cate" value=""/>
		<input type="hidden" name="category" id="category" value="<?php echo $mari;?>"/>
		<input type="hidden" name="trailsorbike" id="trailsorbike" value="<?php 
		if ( is_home() ) {
    // This is the blog posts index
    echo "golf-clubs";
} else {
    // This is not the blog posts index
    print_r($parts2[0]);
}
?>"/>
		<!--<input type="text" name="searchkey" id="searchkey" data-brand="111" size="30" class="productss" placeholder="Search Product or Brand" title="For Product: type letter like 'A' to filter. For Brand type like 'brand: Maxfli'" style="    position: relative;    vertical-align: baseline!important;     margin-top: -5px;   padding: 4px;" value="" >  -->
		
		
				<!-- Main Input -->
				
				<input type="text" required name="searchkey" id="searchkey" data-brand="111" size="30" class="productss" placeholder="Enter Search Term..."  value="" autocomplete="off">
				<input type="hidden" name="urlvalue" id="urlvalue" value="<?php echo $url1[0]; ?>" >

		<!-- Show Results -->
		<!--h4 id="results-text" style="font-size:13px">Showing results for: <b id="search-string">Array</b></h4-->
		<ul id="results"></ul>
		
		
<!--
	<center>	
	<input style="background: #137211;    padding: 6px;    color: white;    text-transform: capitalize;    border-radius: 5px;" type="submit" value="Find Product" name="kumar" id="kumar">
	</center>
	
	-->
		</form>
		</div>

		</div>
		
		
		
		
		<!--
		
		<div class="inner">
	
			<?php  if(function_exists('Get_NodeID_By_Path')){
			$NODEID = Get_NodeID_By_Path( $Category->url_safe_category_name ); }	//Added By Zaved as on 26/Nov/2016
			for($i=0;$i<count($Categories);$i++) { /* NODEID is also database driven */
				
				if(strpos($Categories[$i]->nodeid, $NODEID ) !== false && $Categories[$i]->node_level == 3 && number_format( $Categories[$i]->product_count) != 0) { ?>
					<?Php $CatName = strtolower( $Categories[$i]->category_name ); ?>
					<?Php $CatName = str_replace('&', 'and', $CatName ); ?>
					<?Php $BrandList[] = str_replace(' ', '-', $CatName ); ?>
					<?Php $Category->product_count += $Categories[$i]->product_count; ?>
				<?Php } } ?>

		<?php //echo $pageid;
		if($pageid=="page-product-landing-page.php"){ ?>
		<div class="golf-club-list"><?php
			if(function_exists('site_review_brand_list_search')){ site_review_brand_list_search(1, $Category->url_safe_category_name, $BrandList); }?>
		</div><?php	} ?> 

		<?php //echo $pageid;
		if($pageid=="page-sub-category-subpage.php"){ ?>
		<div class="golf-club-list"><?php
			if(function_exists('site_review_brand_list_search')){ site_review_brand_list_search(); }?>
		</div><?php	} ?> 

		
		
		<div class="ramesh" style="display:none;"> 
		<?php /* foreach($Products as $Product) { ?>
			<a href="<?php echo $Product->product_name;?>" id="link" onclick="doalert(this); return false;" ><?php echo $Product->product_name; ?></a>
		<?php } */?>
		<?php
		//echo "<pre>";print_r($Products);echo "<pre>";
		?>
		
		</div>
		
		
		<?php //echo $pageid;
		//echo "<pre>"; print_r($Categories); echo "</pre>";
		/* if(!(is_home())){
		if($pageid=="" and $sp_product!='prod'){ ?>
		<div class="golf-club-list">Select the Products 
		<div>
		<?php foreach($Products as $Product) { ?>
			<a href="<?php cr_product_url($Product); ?>"><?php //echo $Product->product_name; ?></a>
		<?php } ?>
		</div>
		<div>
			<a href="<?php echo site_url(); ?>/<?php echo $parts2[0].'.html';?>">Back</a>
		</div>
		</div><?php	} else { ?> 
		<div class="golf-club-list">
			<div>
				<a href="<?php echo site_url(); ?>/<?php echo $parts2[0].'.html';?>">Back</a>
			</div>
		</div>	
		<?php } } */4444444444444444444?>

		</div>		
		
		-->
		
		
		
		
		<div class="inner">		
			<div class="sidebox">
				<ul class="network-share">
					<li class="t"><img src="<?php bloginfo('template_url') ?>/images/on_fb.png" /></li>
					<style>
					//iframe{
					//	margin-top: 3px;
					//}
					</style>
					<li class="s"><style>
					//span{
					//	margin-bottom: 6px;
					//}
					</style><div class="fb-like" data-href="http://reviews.golfreview.com" data-send="false" data-layout="button_count" data-width="90" data-show-faces="false"></div></li>
					<li class="t"><img src="<?php bloginfo('template_url') ?>/images/on_google.png" /></li>
					<li class="s"><g:plusone size="medium" width="90" data-href="<?php bloginfo('url') ?>"></g:plusone></li>
				</ul>
			</div>
		
			<?php if(function_exists('cr_product_partner_links')){ cr_product_partner_links(); }?>
			<?php if( Live_Ads()) { // A second sidebar for widgets, just because.
			if ( is_active_sidebar( 'sidebar-widget-area' ) ) : ?>
				<div id="secondary" class="widget-area" role="complementary">
					<ul class="xoxo"><?php dynamic_sidebar( 'sidebar-widget-area' ); ?></ul>
				</div><!-- #secondary .widget-area -->				
			<?php endif; ?>
			<?php } 
			else { ?>
			<div class="sidebox">
				<div class="ad-box"><a href="#"><img src="<?php bloginfo('template_url') ?>/images/ad-img.png" /></a></div>
			</div>
		<?php }
	
		global $Article;
		$param = ( is_home() ) ? 'top-products2' : 'top-products';
		$atype = strtolower( get_site_review_category( $param ) ); // Edit by Zaved as on 26/Nov/2016
		if(function_exists('site_review_get_articles')){site_review_get_articles( $atype); }
		if($Article[$atype]) { ?>
			
			<div class="sidebox top-courses hl">
				<<?php echo $Article['tag'][$atype]; ?> class="lwr"><?php echo $Article['title'][$atype]; ?></<?php echo $Article['tag'][$atype]; ?>>
				<ul>
				<?php foreach($Article[$atype] as $art) { ?>
					<li class="clearfix"><?php if($art->image_small) { ?><img class="course" src="<?php echo $art->image_small; ?>" /><?php } ?>
						<p><a href="<?php echo $art->permalink; ?>"><?php echo $art->title; ?></a></p>
						<p><?php if(function_exists('site_review_trim_content')){echo site_review_trim_content($art->content, 15);} ?></p>
					</li>
				<?php } ?>					
				</ul>
			</div>
<?php }	else {
	
	$tag = ($Brand->url_safe_category_name != '') ? $Brand->url_safe_manufacturer_name . '-' . $Brand->url_safe_category_name . '-' . $param : $Brand->url_safe_manufacturer_name . '-' . $param;
	query_posts('tag='.$atype.'&posts_per_page=3');
	if( have_posts() ) {
	
?>
	<div class="sidebox top-courses hl">
	<h2 class="lwr">Top Products</h2>
	<ul><?php while ( have_posts() ) { the_post(); ?>
		<li class="clearfix">
			<p><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></p>
			<p><?php if(function_exists('site_review_trim_content')){ echo site_review_trim_content(get_the_content(), 25); }?> <a style="font-size:12px;" href="<?php the_permalink(); ?>">read more</a></p>
		</li>
<?php } ?>
	</ul>
	</div>
<?php } ?>			
<?php } ?>
<?php if( Live_Ads()) {	
	// A second sidebar for widgets, just because.
	if ( is_active_sidebar( 'secondary-sidebar-widget-area' ) ) : ?>
		<div id="secondary" class="widget-area" role="complementary">
			<ul class="xoxo"><?php dynamic_sidebar( 'secondary-sidebar-widget-area' ); ?></ul>
		</div><!-- #secondary .widget-area -->
<?php endif; ?>
<?php } else { ?>
	<div class="sidebox">
		<div class="ad-box">
			<a href="#"><img src="<?php bloginfo('template_url') ?>/images/ad-img2.png" /></a>
		</div>
	</div>
<?php } ?>					
	</div><!-- end inner -->

	</div><!-- end content right -->
