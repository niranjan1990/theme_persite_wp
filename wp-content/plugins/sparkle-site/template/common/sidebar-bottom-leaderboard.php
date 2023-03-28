<?php
/**
 * The bottomleaderboard widget area.
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */
?>

<?php
	/* The bottomleaderboard widget area is triggered if any of the areas
	 * have widgets. So let's check that first.
	 *
	 * If none of the sidebars have widgets, then let's bail early.
	 */
	if (   ! is_active_sidebar( 'bottom-leaderboard-widget-area'  )
	)
		return;
	// If we get this far, we have widgets. Let do this.
?>

			<div id="bottomleaderboard-widget-area" role="complementary">

<?php if ( is_active_sidebar( 'bottom-leaderboard-widget-area' ) ) : ?>
				<div id="first" class="widget-area">
					<ul class="xoxo">
						<?php dynamic_sidebar( 'bottom-leaderboard-widget-area' ); ?>
					</ul>
				</div><!-- #first .widget-area -->
<?php endif; ?>
			</div><!-- #bottom-leaderboard-widget-area -->