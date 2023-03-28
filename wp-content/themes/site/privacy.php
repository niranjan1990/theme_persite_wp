<?php
/**
 * Template Name: Site for displaying privacy
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
				<h1 class="privacy">Privacy Policy</h1>
					<p><iframe class="privacy iframe" src="https://www.verticalscope.com/site-privacy-policy/index.php?site=<?php echo $PTCSITENAME; ?>&group=affiliate" width="900" height="2500"></iframe></p>
				</div>
			</section>

		</main><!-- .site-main -->

		<?php //get_sidebar( 'content-bottom' ); ?>

	</div><!-- .content-area -->

<?php //get_sidebar(); ?>
<?php get_footer(); ?>
