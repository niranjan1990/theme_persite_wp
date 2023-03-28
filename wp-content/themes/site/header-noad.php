<?php ob_start();

global $NOADFOOTER,$MOBILEDETECT;

include(__DIR__.'/Mobile_Detect.php');
$detect = new Mobile_Detect();

// Check for any mobile device.
if ($detect->isMobile()){
$MOBILEDETECT=1;
}else{
$MOBILEDETECT=0;
}

//Check for tablet and load both the Desktop and Mobile Ads
if ($detect->isTablet()){
$TABLET=1;
}else{
$TABLET=0;
}

$NOADFOOTER=0;

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







//For auto login from cookies
include(__DIR__.'/../../../wp-reviewconfig.php');
include(__DIR__.'/../../../wp-config-extra.php');
if(isset($_COOKIE['bb_UserData']) && !isset($_COOKIE['bb_username']) && !isset($_SESSION['invaliduserid']))
{
               //get userid from bb_userdata
                $myXMLData =urldecode($_COOKIE['bb_UserData']);
                $xml = simplexml_load_string($myXMLData);
if ($xml === false) {
    echo "Failed loading XML: ";
    foreach(libxml_get_errors() as $error) {
        echo "<br>", $error->message;
    }
} else {
   $userid =(int)$xml->UserData->VBulletinId;
   $username =(string)$xml->UserData->User_Screen_Name;
}

        if($userid > 0 && $username != ''){
                my_setcookie_example1($userid,$username);
                header('location: '.$_SERVER[REQUEST_URI]);
        }else {
                $_SESSION['invaliduserid']=1;
        }
}
else
{

}

global $seo_title, $seo_description, $seo_keywords;

	?>
<!DOCTYPE html>


<html xmlns="http://www.w3.org/1999/xhtml" xmlns:og="http://opengraphprotocol.org/schema/" xmlns:fb="http://www.facebook.com/2008/fbml">
<head profile="http://gmpg.org/xfn/11">
<link rel="apple-touch-icon" sizes="152x152" href="<?php echo home_url() . "/apple-touch-icon.ico"; ?>" >
<link rel="shortcut icon" type="image/png" sizes="32x32" href="<?php echo home_url() . "/favicon-32x32.ico";?>" >
<link rel="shortcut icon" type="image/png" sizes="16x16" href="<?php echo home_url() . "/favicon-16x16.ico";?>" >
<link rel="manifest" href="<?php echo home_url() . "/manifest.json";?>" >
<link rel="mask-icon" href="<?php echo home_url() . "/safari-pinned-tab.svg";?>" color="#5bbad5">
<meta name="theme-color" content="#ffffff">

<?php if ($TABLET==1){ ?>

<?php }else { ?>
<meta name="viewport" content ="width=device-width,initial-scale=1,user-scalable=0" />
<?php }  ?>
<title><?php echo $seo_title; ?></title>





<style>
@supports (-ms-ime-align:auto) {
	#reviewAppOO7 .sub-menu {
    margin: 23px 0 0 0px!important;
}
}

@supports (-ms-accelerator:true) {
	#reviewAppOO7 .sub-menu {
    margin: 23px 0 0 0px!important;
}
}


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

<?php global $GRclass, $location , $locnew; //echo $GRclass->get_site_review_location();
$locnew = $location;
if(is_single() || $GRclass->get_site_review_location() == 'product-page'|| $GRclass->get_site_review_location() == 'course-page') { ?>
<meta property="og:title" content="<?php echo $seo_title; ?>" />
<meta property="og:type" content="article" />
<meta property="og:url" content="<?php echo 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>" />
<meta property="og:image" content="<?php echo home_url(); ?>/wp-content/themes/site/images/logo.png" />
<meta property="og:description" content="<?php echo $seo_description; ?>" />
<meta property="og:site_name" content="<?php echo $_SERVER[HTTP_HOST]; ?>" />
<meta itemprop="name" content="<?php echo $seo_title; ?>">
<meta itemprop="description" content="<?php echo $seo_description; ?>">
<link rel="image_src" href="<?php echo home_url();?>/wp-content/themes/site/images/logo.png" />
<?php } else if(is_home()) { ?>
<meta property="og:title" content="<?php echo $seo_title; ?>" />
<meta property="og:type" content="website" />
<meta property="og:url" content="<?php echo home_url(); ?>" />
<meta property="og:image" content="<?php echo home_url(); ?>/wp-content/themes/site/images/logo.png" />
<meta itemprop="name" content="<?php echo $SITE_NAME; ?>">
<meta itemprop="description" content="<?php echo $seo_description; ?>">
<link rel="image_src" href="<?php echo home_url(); ?>/wp-content/themes/site/images/logo.png" />
<?php }
else
{
	?>

<meta property="og:title" content="<?php echo $seo_title; ?>" />
<meta property="og:type" content="Category" />
<meta property="og:url" content="<?php echo 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>" />
<meta property="og:image" content="<?php echo home_url(); ?>/wp-content/themes/site/images/logo.png" />
<meta property="og:description" content="" />
<meta property="og:site_name" content="<?php echo $_SERVER[HTTP_HOST]; ?>" />
<meta itemprop="name" content="<?php echo $SITE_NAME; ?>">
<meta itemprop="description" content="<?php echo $seo_description; ?>">
<link rel="image_src" href="<?php echo home_url();?>/wp-content/themes/site/images/logo.png" />

	<?php
}
?>
<meta http-equiv=”X-UA-Compatible” content="IE=edge">
<meta name="description" content="<?php echo $seo_description; ?>" />
<meta name="keywords" content="<?php echo $seo_keywords; ?>" />

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>

<!--- bootstrap 4 --->
<?php
if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 8.') != false || strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 9.') != false)
{
?>
	<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_url') ?>/bootstrap.css">
<?php
}
else
{
?>
	<link rel="stylesheet" href="<?php bloginfo('template_url') ?>/bootstrap.min.css">
<?php
}
?>

<!-- bootstrap 4 -->

<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if IE 8]>
<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->

<style>@import url("<?php echo $CDN_DOMAIN."/".$S3_FOLDER; ?>/header-widget.css<?php echo $CSS_VERSION;?>");</style>

<link type="text/css" rel="stylesheet" href="<?php bloginfo('stylesheet_url') ?><?php echo $CSS_VERSION;?>" />




<link type="text/css" rel="stylesheet" href="<?php echo $CDN_DOMAIN."/".$S3_FOLDER."/style-gradient.css".$CSS_VERSION; ?>" />
<link type="text/css" rel="stylesheet" href="<?php echo home_url() . "/wp-content/themes/site/mobile-style.css".$CSS_VERSION; ?>" />


<!--<link type="text/css" rel="stylesheet" href="<?php echo home_url() . "/wp-content/themes/site/font-awesome.min.css".$CSS_VERSION; ?>" />-->


<?php if($SITE_NAME && $REVIEWS==1 && is_home()){?>
	<link rel="stylesheet" href="<?php bloginfo('template_url') ?>/external.css"/>
	<!--<link rel="stylesheet" href="<?php bloginfo('template_url') ?>/organic-tabs-style.css"/>

	<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_url') ?>/advanced-slider-base.css" media="screen">
	<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_url') ?>/minimal-small.css" media="screen">-->

<?php } ?>








<!-- for google analytics -->
<?php if($GOOGLEANALYTICS==1){
$domain_name = explode(".",$_SERVER['SERVER_NAME']);
?>
<script type="text/javascript">
 var _gaq = _gaq || [];
 _gaq.push(['_setAccount', '<?php echo $GOOGLEANALYTICS_ID; ?>']);
 _gaq.push(['_setDomainName', '<?php echo $domain_name[1].".".$domain_name[2]; ?>']);
 _gaq.push(['_addIgnoredRef', '<?php echo $domain_name[1].".".$domain_name[2]; ?>']);
 _gaq.push(['_trackPageview']);
 (function() {
 var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
 ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
 var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);})();
</script>
<?php } ?>


<!-- for targeting ads -->
	<?php
		global  $cat_id, $pagename;
	?>


<?php if($GOOGLEAD==1){?>
<?php if ((strpos($SITE_NAME, 'golf') !== false) || (strpos($SITE_NAME, 'photo') !== false) || (strpos($SITE_NAME, 'outdoor') !== false)) { ?>
	<script async="async" src="https://www.googletagservices.com/tag/js/gpt.js"></script>
		<script>
		var googletag = googletag || {};
		googletag.cmd = googletag.cmd || [];
	</script>
<?php } else { ?>
	<script type="text/javascript">
		(function () {
			var useSSL = 'https:' == document.location.protocol;var src = (useSSL ? 'https:' : 'http:') + '//www.googletagservices.com/tag/js/gpt.js';
			document.write('<scr' + 'ipt src="' + src + '"></scr' + 'ipt>');
		})();
	</script>
<?php } ?>
<?php

// Check for any mobile device.
if ($MOBILEDETECT==1){
   // mobile content
include(__DIR__.'/../../../Mobile_SITE_ADS.php');
   //If Tablet load Desktop Ads also
   if ($TABLET==1){
     include(__DIR__.'/../../../Desktop_SITE_ADS.php');
   }
}else{
 include(__DIR__.'/../../../Desktop_SITE_ADS.php');
} ?>
<?php } ?>
	<!-- for targeting ads -->





<?php
if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 8.') != false || strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 9.') != false)
{

}
else
{
?>
<script src="https://www.google.com/recaptcha/api.js"></script>
<?php
}
?>




	<?php wp_head() ?>




<!-- Google AdManager Scripts -->

<?php

if($MOBILEDETECT==1){
	}else if($GOOGLEAD==1){
		if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("Site Skin Ad 1680x800") ) :
			endif;
		if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("Interstitial Ad 1x1") ) :
			endif;
?>
<?php
}
?>

<style>
.home1 {
overflow: hidden !important;
}
</style>
 <!-- /Google AdManager Scripts -->




<?php
$current_user = wp_get_current_user();
if (user_can( $current_user, 'administrator' )) {?>
<style>
.stickyBar{
margin-top:32px;
}
</style>
<?php }?>

<?php

if($MOBILEDETECT==1 && $TABLET == 0){

}else{
include(__DIR__."/header_top_bar.php");
}
?>


<div id="container" class="mainContent">

    <?php include(__DIR__ . "/header_widget.php") ?>




	<div id="content" class="clearfix ">
