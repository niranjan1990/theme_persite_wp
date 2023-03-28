<?php ob_start(); 
	error_reporting(E_ALL ^ E_WARNING); 
	error_reporting(E_ALL ^ E_NOTICE); 



// for profile page session timeout checking  	
session_start();
$timeout = 900; // Number of seconds until it times out.
 
// Check if the timeout field exists.
if(isset($_SESSION['timeout'])) {

    $duration = time() - (int)$_SESSION['timeout'];
    if($duration > $timeout) {
	   unset($_SESSION['checkuser']);
	   unset($_SESSION['timeout']);
    }
}
 
$_SESSION['timeout'] = time();
	?>
<!DOCTYPE html>


<html xmlns="http://www.w3.org/1999/xhtml" xmlns:og="http://opengraphprotocol.org/schema/" xmlns:fb="http://www.facebook.com/2008/fbml">
<head profile="http://gmpg.org/xfn/11">
<meta name="viewport" content ="width=device-width,initial-scale=1,user-scalable=0" />

<title><?php site_review_title(); ?></title>



	

<style>
.bx-has-pager
{
	margin-bottom:34px;
}
@supports (-ms-ime-align:auto) {
  /* IE Edge 12+ CSS styles go here */
.sub-menu {
    margin: 20px 0 0 0px;
}
}
#shiftnav-toggle-main.shiftnav-toggle-main-entire-bar:before, #shiftnav-toggle-main .shiftnav-toggle-burger
{
	    padding-bottom: 0px!important;
		padding: 9px 20px!important;
}
#shiftnav-toggle-main .shiftnav-main-toggle-content {
    padding: 2px 35px!important;
}
.shiftnav-toggle-main-block img
{
	    width: 130px;
    height: 50px;
}

.autopopsearch
{
	    margin-top: 1px!important;
}
html {
overflow-y:scroll; 
overflow-x:hidden;
}
/* for form center align in firefox and edge */
.form-horizontal
{
	text-align:center;
}
</style>



<?php global $GRclass; if(is_single() || $GRclass->get_site_review_location() == 'product-page') { ?>
<meta property="og:title" content="Reviews and news on golf clubs, golf courses, golf bags, golf shoes and everything else golf related - GolfReview.com" />
<meta property="og:type" content="article" />
<meta property="og:url" content="<?php echo home_url(); ?>" />
<meta property="og:image" content="<?php echo home_url(); ?>/wp-content/themes/site/images/logo.png" />
<meta property="og:description" content="" />
<meta property="og:site_name" content="Reviews and news on golf clubs, golf courses, golf bags, golf shoes and everything else golf related - GolfReview.com" />
<meta itemprop="name" content="Reviews and news on golf clubs, golf courses, golf bags, golf shoes and everything else golf related - GolfReview.com">
<meta itemprop="description" content="GolfReview brings you the latest consumer reviews, editorial reviews and news on golf clubs, golf courses and many things golf related.">
<link rel="image_src" href="<?php echo home_url();?>/wp-content/themes/site/images/logo.png" />
<?php } else if(is_home()) { ?>
<meta property="og:title" content="Reviews and news on golf clubs, golf courses, golf bags, golf shoes and everything else golf related - GolfReview.com" />
<meta property="og:type" content="website" />
<meta property="og:url" content="<?php echo home_url(); ?>" />
<meta property="og:image" content="<?php echo home_url(); ?>/wp-content/themes/site/images/logo.png" />
<meta itemprop="name" content="Reviews and news on golf clubs, golf courses, golf bags, golf shoes and everything else golf related - GolfReview.com">
<meta itemprop="description" content="GolfReview brings you the latest consumer reviews, editorial reviews and news on golf clubs, golf courses and many things golf related.">
<link rel="image_src" href="<?php echo home_url(); ?>/wp-content/themes/site/images/logo.png" />
<?php } ?>

<!--- bootstrap 4 --->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.4/css/bootstrap.min.css" integrity="sha384-2hfp1SzUoho7/TsGGGDaFdsuuDL0LX2hnUp6VkX3CUQ2K4K+xjboZdsXyp4oUHZj" crossorigin="anonymous">
<!--<link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css" rel="stylesheet">-->
<!-- bootstrap 4 -->

<meta name="description" content="<?php site_review_meta_description(); ?>" />
<meta name="keywords" content="<?php site_review_meta_keywords(); ?>" />
<link type="text/css" rel="stylesheet" href="<?php bloginfo('stylesheet_url') ?>" />

<link type="text/css" rel="stylesheet" href="<?php echo home_url() . "/wp-content/themes/site/style-gradient.css"; ?>" />
<link type="text/css" rel="stylesheet" href="<?php echo home_url() . "/wp-content/themes/site/mobile-style.css"; ?>" />

<link type="text/css" rel="stylesheet" href="<?php echo home_url() . "/wp-content/themes/site/jquery.bxslider.css"; ?>" />
<link type="text/css" rel="stylesheet" href="<?php echo home_url() . "/wp-content/themes/site/font-awesome.min.css"; ?>" />


<!--[if IE 7]> <link href="<?php echo home_url(); ?>/wp-content/themes/site/style-IE.css" rel="stylesheet" type="text/css" /> <![endif]-->
<script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
<script type="text/javascript" src="http://apis.google.com/js/plusone.js"></script>





<script type="text/javascript"> function plusone_vote( obj ) { _gaq.push(['_trackEvent','plusone',obj.state]); } </script>
<script type="text/javascript">
  (function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/plusone.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  })();
</script>
<script src="https://code.jquery.com/jquery-2.1.3.min.js" integrity="sha256-ivk71nXhz9nsyFDoYoGf2sbjrR9ddh+XDkCcfZxjvcM=" crossorigin="anonymous"></script>
<!--<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>-->
<script src="https://npmcdn.com/tether@1.2.4/dist/js/tether.min.js"></script>

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.4/js/bootstrap.min.js" integrity="sha384-VjEeINv9OSwtWFLAtmc4JCtEJXXBub00gtSnszmspDLCtC0I4z4nqz7rEFbIZLLU" crossorigin="anonymous"></script>

<script type="text/javascript" src="<?php bloginfo('template_url') ?>/js/site_review.js"></script>
<script src='https://www.google.com/recaptcha/api.js' async defer></script>
<script type="text/javascript" src="<?php bloginfo('template_url') ?>/js/slideshow.js"></script>

<!-- Google AdManager Scripts -->
<script type="text/javascript" src="http://partner.googleadservices.com/gampad/google_service.js"></script>
<script type="text/javascript">
	GS_googleAddAdSenseService("ca-pub-5877874171326976");
    GS_googleEnableAllServices();
</script>          
	<?php  $url = 'http://' . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]; ?>
    <?php  $tokens = explode('/', $url); ?>
    <script language="javascript">
          GA_googleAddAttr("SITE","GOLF");
        <?php if(is_home() || is_front_page()) { ?>
          GA_googleAddAttr("AREA","HOME");
          GA_googleAddAttr("PAGENAME","DEFAULT");
        <?php } if (strpos($_SERVER['REQUEST_URI'], "golf-clubs") > 0) { ?>
          GA_googleAddAttr("PAGENAME","GOLF_CLUBS");
        <?php } if (strpos($url, "drivers") !== false) { ?>
          GA_googleAddAttr("TITLE","DRIVERS");
        <?php } if (strpos($url, "fairway-woods") !== false) { ?>
          GA_googleAddAttr("TITLE","FAIRWAY_WOODS");
        <?php } if (strpos($url, "hybrids") !== false) { ?>
          GA_googleAddAttr("TITLE","HYBRIDS");
        <?php } if (strpos($url, "irons") !== false) { ?>
          GA_googleAddAttr("TITLE","IRONS");
        <?php } if (strpos($url, "wedges") !== false) { ?>
          GA_googleAddAttr("TITLE","WEDGES");
        <?php } if (strpos($url, "putters") !== false) { ?>
          GA_googleAddAttr("TITLE","PUTTERS");
        <?php } if (strpos($_SERVER['REQUEST_URI'], "golf-equipment") > 0) { ?>
          GA_googleAddAttr("PAGENAME","GOLF_EQUIP");
        <?php } if ((strpos($_SERVER['REQUEST_URI'], "golf-equipment") > 0) && (strpos($url, "balls") !== false)) { ?>
          GA_googleAddAttr("TITLE","GOLF_BALLS");
        <?php } if ((strpos($_SERVER['REQUEST_URI'], "golf-equipment") > 0) && (strpos($url, "bags") !== false)) { ?>
          GA_googleAddAttr("TITLE","GOLF_BAGS");
        <?php } if ((strpos($_SERVER['REQUEST_URI'], "golf-equipment") > 0) && (strpos($url, "shoes") !== false)) { ?>
          GA_googleAddAttr("TITLE","GOLF_SHOES");
        <?php } if ((strpos($_SERVER['REQUEST_URI'], "golf-equipment") > 0) && (strpos($url, "gps-and-range-finders") !== false)) { ?>
          GA_googleAddAttr("TITLE","GPS_RANGEFINDERS");
        <?php } if ((strpos($_SERVER['REQUEST_URI'], "golf-equipment") > 0) && (strpos($url, "accessories") !== false)) { ?>
          GA_googleAddAttr("TITLE","ACCESSORIES");
        <?php } if (strpos($_SERVER['REQUEST_URI'], "golf-deals") > 0)  { ?>
          GA_googleAddAttr("PAGENAME","GOLF_DEALS");
        <?php } if (strpos($_SERVER['REQUEST_URI'], "reviews") > 0)  { ?>
          GA_googleAddAttr("PAGENAME","REVIEWS");
        <?php } if (strpos($_SERVER['REQUEST_URI'], "golf-courses") > 0) { ?>
          GA_googleAddAttr("PAGENAME","GOLF_COURSES");
        <?php } if (strpos($url, "California") !== false) { ?>
          GA_googleAddAttr("STATE","CA");
        <?php } if (strpos($_SERVER['REQUEST_URI'], "callaway") > 0) { ?>
          GA_googleAddAttr("BRAND","callaway");
        <?php } ?>
    </script>
    <script type="text/javascript">
          GA_googleAddSlot("ca-pub-5877874171326976", "GOLF_300X250_1");
          GA_googleAddSlot("ca-pub-5877874171326976", "GOLF_300X250_2");
          GA_googleAddSlot("ca-pub-5877874171326976", "GOLF_728x90");
          GA_googleAddSlot("ca-pub-5877874171326976", "GOLF_728x90_2");
          GA_googleAddSlot("ca-pub-5877874171326976", "GOLF_193x90");
    </script>
    <script type="text/javascript">GA_googleFetchAds();</script>
    <!-- /Google AdManager Scripts -->
	<?php wp_head() ?>
	
	<script src="http://cdnjs.cloudflare.com/ajax/libs/modernizr/2.6.2/modernizr.min.js"></script>
	<!--<?php if (function_exists(clean_custom_menu1('primary'))) clean_custom_menu1('primary'); ?>-->
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
	<script src="<?php bloginfo('template_url') ?>/js/jquery.rwdImageMaps.min.js"></script>	
		
	
	
	
	
	




	
<div id="fb-root"></div>
<script>(function(d, s, id) {
	
	var js, fjs = d.getElementsByTagName(s)[0];
	if (d.getElementById(id)) return;
	js = d.createElement(s); js.id = id;
	js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=230466627003041";
	fjs.parentNode.insertBefore(js, fjs);
	
}(document, 'script', 'facebook-jssdk'));
	
</script>

<div id="top-bar" class="hidden-md-down">
	<div class="inner">
		<div class="nw">Part of the <strong>
			<a href="http://www.consumerreview.com">ConsumerReview Network</a>:</strong>
			<a href="http://www.mtbr.com">Mountain Bikes</a> | 
			<a href="http://www.roadbikereview.com">Road Bikes</a> | 
			<a href="http://www.carreview.com">Cars</a> | Golf | 
			<a href="http://www.audioreview.com">Audio</a> | 
			<a href="http://www.consumerreview.com">more</a>
		</div>
		<div class="rl hidden-md-down"><div class='search_div'>Search: <form method="get" action="/"><input type="text" name="s" /></form></div></div>
	</div>
</div>

<div id="top-bar1" class="hidden-md-up" style="height:30px;display:none;">
	<div class="inner" style="display:none;">
		<div class="nw">Part of the <strong>
			<a href="http://www.consumerreview.com">ConsumerReview Network</a>:</strong>
			<a href="http://www.mtbr.com">Mountain Bikes</a> | 
			<a href="http://www.roadbikereview.com">Road Bikes</a> | 
			<a href="http://www.carreview.com">Cars</a> | Golf | 
			<a href="http://www.audioreview.com">Audio</a> | 
			<a href="http://www.consumerreview.com">more</a>
		</div>
		<div class="rl hidden-md-down"><div class='search_div'>Search: <form method="get" action="/"><input type="text" name="s" /></form></div></div>
	</div>
</div>

<div id="container">
	<div id="header"  class="hidden-md-down">
		<div class="inner">	
			<nav id="site-navigation" class="main-navigation" role="navigation">
				<div id="navigation"><?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_class' => 'nav-menu' ) ); ?></div>
				<!--<div class='search_box'><form method="get" action="/"><input type="text" name="s" /></form></div>-->
			</nav><!-- #site-navigation -->
			<a href="<?php bloginfo('url') ?>"><img src="<?php bloginfo('template_url') ?>/images/logo.png" /></a>
		</div>
	</div>
	
	
	
	
	
	
	
	
	
	
	
	
	
	
<link href="/ajaxsearch1/style/style.css" rel="stylesheet" type="text/css" />



<div class="onlymobile1" style="position: fixed;
    top: 54px;
    z-index: 9999999999;">		

	
	<!--
<i style="font-size:15px;padding-top: 5px; position: fixed;    top: 84px;    right: 0px;    z-index: 999999999999999!important;" id='hideshow'  class="fa fa-search"><input type='button'   value='Show / Hide'></i>
-->


<div class="content" id="autosearchbar" style="display:none">
<link href="/ajaxsearch1/style/style.css" rel="stylesheet" type="text/css" />
<?php
	global $url1;
?>	
		<div class="shiftnav-searchbar-drop shiftnav-searchbar-drop-open" style="box-shadow: 0 0 8px rgba(0,0,0,.2);     border: 4px solid #4CAF50;">

		<form method="post" class="shiftnav-searchform">
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
		
				
				<input type="text" required name="searchkey1" id="searchkey1" data-brand="111" size="30" class="shiftnav-search-input" placeholder="Enter Search Term..."  value="" autocomplete="off" style="background: #f3f3f3;">
				<input type="hidden" name="urlvalue1" id="urlvalue1" value="<?php echo $url1[0]; ?>" >

		

		</form>
		
		</div>
		
		<ul id="results1" style="background: aliceblue;border-bottom: 2px solid #1d1d20;"></ul>
		
		
		</div>

		</div>
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
				<div id="top-ad" class="hidden-md-down">
					<ul>
						<li class="editorial">
							<?php get_sidebar( 'small-promo' ); ?>
						</li>
						<li class="leaderboard">
							<?php get_sidebar( 'top-leaderboard' ); ?>
						</li>
					</ul>
				</div>
		
	<div id="content" class="clearfix">
	
	
		
	
