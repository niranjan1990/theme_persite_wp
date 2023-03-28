<?php /* Template Name: Review Index */ ?>
<?php

	set_site_review_location('reviews');
	get_header();
?>
	<div id="content-left" class="col-sm-8">
		<div class="inner">
			<div id="review-index" class="main-content">
				<h1>SITE REVIEW ARTICLE INDEX</h1>
				<div class="review-index-header"><span></span></div><?php 
				$cp = (get_query_var('paged')) ? get_query_var('paged') : 1;
				$posts_per_page = 50;
				query_posts("posts_per_page=$posts_per_page&paged=$cp");?>
		
				<?php if( have_posts() ) : while ( have_posts() ) : the_post(); ?>
					<div class="item-list-box clearfix">				
						<div class="item-box">
							<div class="title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></div>
								<div class="meta"><div class="comments"><?php comments_number( '0 comments', '1 comment', '% comments' ); ?> </div>
									by <?php the_author(); ?> &nbsp; <?php the_time('F d, Y'); ?>
								</div>
								<div class="text"><?php the_excerpt(); ?></div>
						</div>
					</div>
				<?php endwhile; ?>	
				<div class="article-index-nav clearfix"><div>show <?php posts_nav_link(' | ','prev '.$posts_per_page,'next '.$posts_per_page); ?></div>
				Showing <?php
				$totalarticles = wp_count_posts();
				$article_count = $totalarticles->publish;
				$pages = ceil($article_count/$posts_per_page);
				$s = $cp * $posts_per_page - $posts_per_page + 1;				
					if($cp == $pages) $e = $article_count;
					else $e = $cp * $posts_per_page;
					echo $s . '-' . $e . ' of ' . $article_count . ' Articles'; ?>
				</div>
			<?php else : ?>	No results found <?php endif; ?>
			<?php wp_reset_query(); ?>
			</div><!-- end main content -->
		</div><!-- end inner -->
	</div><!-- end content left -->
<?php get_sidebar(); ?>
<?php get_footer(); ?>