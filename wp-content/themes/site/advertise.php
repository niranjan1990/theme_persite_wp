<?php
/**
 * Template Name: Site Advertise	
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */

require_once(__DIR__.'/../../../wp-config.php');

get_header('noad'); 
 
?>
<link rel='stylesheet' id='contact-form-7-css'  href='<?php echo home_url(); ?>/wp-content/plugins/contact-form-7/includes/css/styles.css' type='text/css' />
<script src="<?php echo home_url(); ?>/wp-content/plugins/contact-form-7/includes/js/scripts.js?ver=4.9"></script>
<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Abel" />

<style>
@media screen  and (max-width: 601px) {

.wpcf7 .rightcolumn{float:none !important;}

#wpcf7-f<?php echo get_option("advertiseformid"); ?>-o1 input[type="text"],
#wpcf7-f<?php echo get_option("advertiseformidIE"); ?>-o1 input[type="text"],
#wpcf7-f<?php echo get_option("advertiseformid"); ?>-o1 input[type="email"],
#wpcf7-f<?php echo get_option("advertiseformidIE"); ?>-o1 input[type="email"],
#wpcf7-f<?php echo get_option("advertiseformid"); ?>-o1 input[type="tel"],
#wpcf7-f<?php echo get_option("advertiseformidIE"); ?>-o1 input[type="tel"],
#wpcf7-f<?php echo get_option("advertiseformid"); ?>-o1 input[type="url"],
#wpcf7-f<?php echo get_option("advertiseformidIE"); ?>-o1 input[type="url"],
#wpcf7-f<?php echo get_option("advertiseformid"); ?>-o1 textarea,
#wpcf7-f<?php echo get_option("advertiseformidIE"); ?>-o1 textarea {

width:100% !important;
}

#wpcf7-f<?php echo get_option("advertiseformid"); ?>-o1 input[type="submit"],
#wpcf7-f<?php echo get_option("advertiseformidIE"); ?>-o1 input[type="submit"]{
width:100% !important;
}

}


.wpcf7-form label{
        color: #7F7F7F;
    text-align: left;
    font-size: 18px;
    line-height: 18px;
    font-family: abel, sans-serif;
    font-weight: 400;
}


#wpcf7-f<?php echo get_option("advertiseformid"); ?>-o1 input[type="text"],
#wpcf7-f<?php echo get_option("advertiseformidIE"); ?>-o1 input[type="text"],
#wpcf7-f<?php echo get_option("advertiseformid"); ?>-o1 input[type="email"],
#wpcf7-f<?php echo get_option("advertiseformidIE"); ?>-o1 input[type="email"],
#wpcf7-f<?php echo get_option("advertiseformid"); ?>-o1 input[type="tel"],
#wpcf7-f<?php echo get_option("advertiseformidIE"); ?>-o1 input[type="tel"],
#wpcf7-f<?php echo get_option("advertiseformid"); ?>-o1 input[type="url"],
#wpcf7-f<?php echo get_option("advertiseformidIE"); ?>-o1 input[type="url"],
#wpcf7-f<?php echo get_option("advertiseformid"); ?>-o1 textarea,
#wpcf7-f<?php echo get_option("advertiseformidIE"); ?>-o1 textarea {
   width: 328px;
    min-height: 45px;
    border-width: 1px;
    border-style: solid;
    border-color: #C4C4C4;
    background-color: #FFFFFF;
    padding: 6px;
    color: #7F7F7F;
    font-size: 18px;
    line-height: 12px;
    font-family: abel, sans-serif;
    font-weight: 400;
    font-style: italic;
}


#wpcf7-f<?php echo get_option("advertiseformid"); ?>-o1 input[type="submit"],
#wpcf7-f<?php echo get_option("advertiseformidIE"); ?>-o1 input[type="submit"] { 
    color: #fff;
    font-family: abel, sans-serif;
   margin-top: 10px;
       min-height: 61px;
    width: 328px;
    font-size:30px;
       background-color: #ED1C24;
}

.wpcf7-response-output{
display:none !important;
}


</style>
<div id="content-left" style="background-color: rgba(127,127,127,0.1);" class="col-md-12">
	 <div class="inner"> 
	 <div class="main-content">

		<main id="main" class="site-main" role="main">
			<section class="col-md-1"></section>
			<section class="col-md-10 terms">
				<div class="page-content" style="margin: 30px;">
<div class="title" style="    margin-bottom: 10px;    background-color: #fff;    padding: 5px;">
<p  style="color: #ED1C24;font-size: 36px;    line-height: 36px;font-family: abel, sans-serif;
    font-weight: 400;margin-bottom: 0px !important;">Let us help you with your next</p>
<p style="color: #ED1C24;    font-size: 48px;    line-height: 48px;font-family: abel, sans-serif;
    font-weight: 400;margin-bottom: 0px !important;">successful marketing campaign</p>
<br><br>
<p  style="font-weight:bold; color: black;   font-size: 18px;    line-height: 22px;font-family: abel, sans-serif;
    margin-bottom: 0px !important;">
Work with us on planning your next marketing campaign. Reach out for a quick response:</p><br>
</div>

								
								
<?php
if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 8.') != false || strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 9.') != false)
{
echo do_shortcode('[contact-form-7 id="'.get_option("advertiseformidIE").'" title="Contact form 1_copy"]');

}
else
{
echo do_shortcode('[contact-form-7 id="'.get_option("advertiseformid").'" title="Contact form 1_copy"]');
}?>


				</div>
			</section>
			<section class="col-md-1"></section>
		</main>
</div>
</div>
</div>

<?php //get_sidebar(); ?>
<?php get_footer(); ?>

