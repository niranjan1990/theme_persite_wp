<?php
/**
 * Template Name: Site for displaying forum rules
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */

get_header('noad'); 

require $_SERVER['DOCUMENT_ROOT'] . '/wp-config-extra.php';

?>
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" type="text/css" rel="stylesheet">

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<section class="privacy">

				<div class="page-content" style="margin: 30px;">
				<h1>Forum Rules</h1>	
				<p>Registration to this forum is free! We do insist that you abide by the rules and policies detailed below. If you agree to the terms, please check the 'I agree' checkbox and press the 'Complete Registration' button below. If you would like to cancel the registration, click here to return to the forums index.</p>
<p>
<br />Although the administrators and moderators of Forums - <?php echo $PTCSITENAME; ?>.com will attempt to keep all objectionable messages off this site, it is impossible for us to review all messages. All messages express the views of the author, and neither the owners of Forums - <?php echo $PTCSITENAME; ?>.com, nor vBulletin Solutions, Inc. (developers of vBulletin) will be held responsible for the content of any message.</p>

<p>By agreeing to these rules, you warrant that you will not post any messages that are obscene, vulgar, sexually-oriented, hateful, threatening, or otherwise violative of any laws. </p>


The owners of Forums - <?php echo $PTCSITENAME; ?>.com reserve the right to remove, edit, move or close any content item for any reason.

						
					
				</div>
			</section>

		</main><!-- .site-main -->

		<?php //get_sidebar( 'content-bottom' ); ?>

	</div><!-- .content-area -->

<?php //get_sidebar(); ?>
<?php get_footer(); ?>
