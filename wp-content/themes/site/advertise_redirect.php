<?php
/**
 * Template Name: Site Advertise Redirect	
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */

require_once(__DIR__.'/../../../wp-config.php');

get_header('noad'); 
 
?>
<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Abel" />
<style>

@media screen and (min-width:0px) and (max-width:600px){
#main .message{font-size:19px !important;}

}

.wpcf7-form label{
        color: #7F7F7F;
    text-align: left;
    font-size: 18px;
    line-height: 18px;
    font-family: abel, sans-serif;
    font-weight: 400;
}





</style>
<div id="content-left" style="background-color: rgba(127,127,127,0.1);" class="col-md-12">
	 <div class="inner"> 
	 <div class="main-content">

		<main id="main" class="site-main" role="main" style="height:400px;">
			
				<div class="page-content" style="margin: 30px;">
<div class="title" style="    margin-bottom: 10px;    background-color: #fff;    padding: 5px;">

<p class="message" style="text-align:center;color: #ED1C24;    font-size: 48px;    line-height: 48px;font-family: abel, sans-serif;
    font-weight: 400;margin-bottom: 0px !important;">Your message was sent.<br> Return to<br><a href="/"style="color:blue;"><?php echo $_SERVER['SERVER_NAME']; ?></a></p>
<br><br>

</div>
			</div>
			
		</main>
</div>
</div>
</div>
<?php //get_sidebar(); ?>
<?php get_footer(); ?>
