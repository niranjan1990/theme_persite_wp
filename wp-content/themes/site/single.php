<?php 
	
	if( have_posts() ) : while ( have_posts() ) : the_post();
		$title = get_the_title();
		$meta_desc = "";
	endwhile; endif;

	set_site_review_location('article-page');
	set_site_review_meta_vars($title,$meta_desc);
	get_header();
?>
	<div id="content-left">
		<div class="inner">
			<div id="article-page" class="main-content">
			<?php if( have_posts() ) : while ( have_posts() ) : the_post(); ?>
			<div class="article-page clearfix">
				<h2><?php the_title(); ?></h2>					
				<div class="item-box">
					<div class="text"><?php the_content(); ?></div>
					<div class="product-share meta clearfix">
					
						<div class="sbg f"><iframe src="http://www.facebook.com/plugins/like.php?href=<?php the_permalink(); ?>&amp;send=false&amp;layout=button_count&amp;width=90&amp;show_faces=false&amp;action=like&amp;colorscheme=light&amp;font&amp;height=21" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:90px; height:21px;" allowTransparency="true"></iframe></div>
						<div class="sbg"><a href="http://twitter.com/share" class="twitter-share-button" data-url="<?php the_permalink() ?>" data-count="horizontal">Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script></div>
						<div class="sbg"><div class="g-plusone" data-size="medium" href="<?php the_permalink() ?>"></div></div>
						<div class="sbg cc l"><a href="#comments"><img style="margin-bottom:-4px;" src="<?php bloginfo('template_url') ?>/images/comments-icon.png" /> <?php comments_number( '0 Comments', '1 Comment', '% Comments' ); ?></a>	</div>
						
					</div>
				</div>
			</div>
		<?php endwhile; endif; ?>
	</div><!-- end main content -->	
	<div id="comments">
		<h2><?php comments_number( '0 Comments', '1 Comment', '% Comments' ); ?></h2>		
		<?php comments_template( '', true ); ?>
	</div>	
	<div class="related-articles">	
		<div class="text-header"><h2>RELATED ARTICLES & REVIEWS</h2></div>				
<?php
		
	foreach((get_the_category()) as $catname) {
	
    	$cat_id[] = $catname->cat_ID; 
	} 
	$terms = implode(",",$cat_id);
	$args = array( 'post__not_in' => array(get_the_ID()), 'cat' => $terms, 'posts_per_page' => 5	);
	query_posts($args);
?>
<?php if( have_posts() ) : while ( have_posts() ) : the_post(); ?>
		<div class="item-list-box clearfix">
			<div class="item-box">
				<div class="title"><a href="<?php the_permalink() ?>"><?php the_title() ?></a></div>
				<div class="meta"><div class="comments"><?php comments_number( '0 Comments', '1 Comment', '% Comments' ); ?></div>
					by <?php the_author() ?> &nbsp; <?php the_time('M d, Y') ?>
				</div>
				<div class="text"><?php the_excerpt() ?></div>
			</div>
		</div>
<?php
	
	endwhile; else: ?>
	<p>No Related Articles Found.</p>
<?php endif; ?>
<?php wp_reset_query(); ?>

	</div>			
</div><!-- end inner -->
</div><!-- end content left -->		
<?php get_sidebar(); ?>
<?php get_footer(); ?>