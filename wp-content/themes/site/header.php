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
                /*$post = [];
                $ch = curl_init(VBULLETINURL.'/wp_bulletin_cookie_password.php?userid='.$userid.'&username');
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
                $response = curl_exec($ch);
                curl_close($ch);*/

                //my_setcookie_example1($userid,$response);
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



<?php if($TABLET==1){ ?>

<?php }else{ ?>
<meta name="viewport" content ="width=device-width,initial-scale=1,user-scalable=0" />
<?php } ?>

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
<meta property="og:url" content="<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>" />
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
<meta property="og:url" content="<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>" />
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

<link type="text/css" rel="stylesheet" href="<?php echo home_url() . "/wp-content/themes/site/ekko-lightbox.css" ?>" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">



<!--<link type="text/css" rel="stylesheet" href="<?php echo home_url() . "/wp-content/themes/site/font-awesome.min.css".$CSS_VERSION; ?>" />-->


<?php if($SITE_NAME && $REVIEWS==1 && is_home()){?>
	<link rel="stylesheet" href="<?php bloginfo('template_url') ?>/external.css"/>
	<!--<link rel="stylesheet" href="<?php bloginfo('template_url') ?>/organic-tabs-style.css"/>

	<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_url') ?>/advanced-slider-base.css" media="screen">
	<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_url') ?>/minimal-small.css" media="screen">-->

<?php } ?>







<?php
if($REVIEWS == 1)
{
?>
<link href="https://fonts.googleapis.com/css?family=Oswald" rel="stylesheet">
<?php
}
?>



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
</head>
<body>
<!-- Funding Choices -->
<?php global $vsFundingChoices, $vsGoogleTagManager, $vsFCAdProvider;
if($SITE_NAME == 'mtbr') {
	$vsFundingChoices = 'AGSKWxXsa4Csreu73uYi3h7eJKrHWd9-2CidaKeqw1obf0sOyRy8REC51YXSQSvf1ds-V-48m1UX_AM=';
	$vsGoogleTagManager = 'GTM-K79H85X';
	$vsFCVigLinkAdProvider = 'fc-7ddf1cc01fb91511';
 } elseif($SITE_NAME == 'roadbikereview') {
	$vsFundingChoices = 'AGSKWxXW3BNq23Pen8M5d8OnNvoiiNcS6xbmC27bfN2OJmczk0eBxzRmTfeehyn78cUBvgC1bqL1aPk=';
	$vsGoogleTagManager = 'GTM-KPZD7FP';
	$vsFCVigLinkAdProvider = 'fc-4ba50396a36efa00';
 } ?>
<script src="https://contributor.google.com/scripts/5e763cfe1b429dfd/loader.js"></script><script>window.googlefc = window.googlefc || {}; googlefc.callbackQueue = googlefc.callbackQueue || [];</script><script src="https://fundingchoices.google.com/f/<?php echo $vsFundingChoices ?>"></script><script>(function() {function signalGooglefcPresent() {if (!window.frames['googlefcPresent']) {if (document.body) {const iframe = document.createElement('iframe'); iframe.style = 'width: 0px; height: 0px; border: none; ' + 'z-index: -1000; left: -1000px; top: -1000px;'; iframe.style.display = 'none'; iframe.name = 'googlefcPresent'; document.body.appendChild(iframe);} else {setTimeout(signalGooglefcPresent, 0);}}}signalGooglefcPresent();})();</script>
<!-- End Funding Choices -->
<!-- VSTags Header -->
<script type="text/javascript">
var vsCFTagsEUFunctions = vsCFTagsEUFunctions || [];
var vsCFTagsNonEuFunctions = vsCFTagsNonEuFunctions || [];

vsCFExecuteEuTags();
vsCFExecuteNonEuTags();

//Execute Eu Tags Function
function vsCFExecuteEuTags() {
				vsCFTagsEUFunctions.forEach(function(f, index){
					f();
				})

				vsCFTagsEUFunctions = [];
}

//Execute None Eu Tags Function
function vsCFExecuteNonEuTags() {
				vsCFTagsNonEuFunctions.forEach(function(f, index){
					f();
				})

				vsCFTagsNonEuFunctions = [];
}

function vsQueueAd(adunit) {
		function vsEUAdUnit() {
				googletag.cmd.push(function() {
						googletag.display(adunit);
		});
		}
		function vsNonEUAdUnit() {
				googletag.cmd.push(function() {
				if (window.deployads) {
				deployads.push(function() {
						deployads.gpt.display(adunit);
						});
				} else {
						googletag.display(adunit);
				}
				});
		}
		vsCFTagsEUFunctions.push(vsEUAdUnit);
		vsCFTagsNonEuFunctions.push(vsNonEUAdUnit);
}
</script>
<!-- /VSTags Header -->
<script>
/* Custom FC functions here */
var gfchelper = window.gfchelper || {
	adProviders: {"dfp":"229","viglink":"<?php echo $vsFCVigLinkAdProvider; ?>"},
	isAdProviderAllowed: function( providerName ) {
		if(
			!providerName
			|| typeof googlefc === "undefined"
			|| typeof googlefc.getConsentedProviderIds === "undefined"
		) {
			return false;
		}
		// get google FC Consented provider Ids for the current site
		var consentedIds = googlefc.getConsentedProviderIds() || [];
		// Allow only those we want and are allowed
		return !!consentedIds.find( p => gfchelper.adProviders[ providerName.toLowerCase() ] && p === gfchelper.adProviders[ providerName.toLowerCase() ] );
	}
};
googlefc.callbackQueue.push(function () {
    dataLayer = window.dataLayer || [];
    switch (googlefc.getConsentStatus()) {
        case googlefc.ConsentStatusEnum.CONSENTED_TO_PERSONALIZED_ADS:
        case googlefc.ConsentStatusEnum.CONSENT_NOT_REQUIRED:
            dataLayer.push({
                "event": "event_fcconsent"
            });
            break;
        default:
            dataLayer.push({
                "event": "event_fcconsent_denied"
            });
            break;
    }
});
</script>
<?php global $vswrapperSrcrVal;
if($SITE_NAME == 'mtbr') {
	$vswrapperSrcrVal = "//tags-cdn.deployads.com/a/vs.mtbr.com.js";
 } elseif($SITE_NAME == 'roadbikereview') {
	$vswrapperSrcrVal ="//tags-cdn.deployads.com/a/vs.roadbikereview.com.js";
 } ?>
<!-- Sortable -->
<script type='text/javascript'>
function vsSortableNonEU() {
	// Initialize the deployads array, for asynchronous use
	window.deployads = window.deployads || [];
	// Load Sortable's library
	wrapperSrc = "<?php echo $vswrapperSrcrVal; ?>";
	(function() {
			var gads = document.createElement('script');
			gads.async = true;
			gads.type = 'text/javascript';
			var useSSL = 'https:' == document.location.protocol;
			gads.src = (useSSL ? 'https:' : 'http:') + wrapperSrc;
			var node = document.getElementsByTagName('script')[0];
			node.parentNode.insertBefore(gads, node);
	})();
}
vsCFTagsNonEuFunctions.push(vsSortableNonEU);
</script>
<!-- /Sortable -->
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start': new Date().getTime(),event:'gtm.js'});
var f=d.getElementsByTagName(s)[0], j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src= 'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);})(window,document,'script','dataLayer','<?php echo $vsGoogleTagManager ?>');</script>
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=<?php echo $vsGoogleTagManager ?>" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager  -->


<!-- for targeting ads -->
	<?php
		global  $cat_id, $pagename;
	?>


<?php if($GOOGLEAD==1){?>


	<script async="async" src="https://www.googletagservices.com/tag/js/gpt.js"></script>
		<script>
		var googletag = googletag || {};
		googletag.cmd = googletag.cmd || [];
	</script>
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





<!--<script src="https://code.jquery.com/jquery-2.1.3.min.js" integrity="sha256-ivk71nXhz9nsyFDoYoGf2sbjrR9ddh+XDkCcfZxjvcM=" crossorigin="anonymous"></script>-->


<!-- INLINE SITEREVIEW JS FOR BRAND SELECTION-->
<script type="text/javascript">
    function $(e) {
        return document.getElementById(e)
    }

    function showLetter(e) {
        for (var t = ["All", "1", "2", "3", "4", "5", "6", "7", "8", "9", "0", ".", "A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z"], n = 0; n < t.length; n++)(null != document.getElementById("let-" + t[n]) || void 0 != document.getElementById("let-" + [n])) && (document.getElementById(t[n]).style.display = "none", document.getElementById("let-" + t[n]).removeAttribute("class"));
        document.getElementById(e).style.display = "", document.getElementById("let-" + e).setAttribute("class", "sel")
    }

    function showLetter1(e) {
        for (var t = ["All", "A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z", "1", "2", "3", "4", "5", "6", "7", "8", "9", "0", "."], n = 0; n < t.length; n++)(null != document.getElementById("let1-" + t[n] + "xyz") || void 0 != document.getElementById("let1-" + t[n] + "xyz")) && (document.getElementById(t[n] + "xyz").style.display = "none", document.getElementById("let1-" + t[n] + "xyz").removeAttribute("class"));
        document.getElementById(e).style.display = "", document.getElementById("let1-" + e + "xyz").setAttribute("class", "sel")
    }

    function getHTTPObject() {
        return "undefined" == typeof XMLHttpRequest && (XMLHttpRequest = function() {
            try {
                return new ActiveXObject("Msxml2.XMLHTTP.6.0")
            } catch (e) {}
            try {
                return new ActiveXObject("Msxml2.XMLHTTP.3.0")
            } catch (e) {}
            try {
                return new ActiveXObject("Msxml2.XMLHTTP")
            } catch (e) {}
            return !1
        }), new XMLHttpRequest
    }
</script>

<?php if($SITE_NAME && $REVIEWS==1 && is_home()){?>



<?php }?>










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
global $pagename,$pagenamejquery;
 if($pagename=='PRD' || $pagenamejquery=='RVF' || $pagename=='RVF'){ ?>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>

<?php } ?>


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


include(__DIR__."/header_top_bar.php");

?>

<div id="container" class="mainContent">

    <?php include(__DIR__ . "/header_widget.php") ?>





				<div id="top-ad" class="hidden-md-down" style="position:relative;">
					<!--<style>
					ul {
					margin-bottom: 0px !important;
					}
					</style>-->
				<?php if($SITE_NAME == 'golfreview' || $SITE_NAME == 'outdoorreview' || $SITE_NAME == 'mtbr' || $SITE_NAME == 'roadbikereview' ||  $SITE_NAME == 'audioreview' || $SITE_NAME == 'photographyreview' || $SITE_NAME == 'carreview'){ ?>
						<ul>
				<li class="leaderboard" style="padding: 0px;display: table;margin: 0px auto 0px auto;float:none;">
							<?php get_sidebar( 'top-leaderboard' ); ?>
						</li>
						</ul>
					<?php }else{ ?>
						<ul>
						<li class="editorial">
							<?php get_sidebar( 'small-promo' ); ?>
						</li>
						<li class="leaderboard">
							<?php get_sidebar( 'top-leaderboard' ); ?>
						</li>
					</ul>

					<?php } ?>
				</div>

			<?php if($pagename == "RVF"){
				    // dont show mobile ad
				} else {
				?>
	<div class="onlymobile" style="position:relative;width:100%;">
			<center><?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("Header Mobile Ad 320x50") ) :
	 endif; ?></center>
	</div>
<?php } ?>
	<div id="content" class="clearfix ">
