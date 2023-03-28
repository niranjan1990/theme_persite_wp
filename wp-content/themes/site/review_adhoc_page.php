<?Php
	// Load WordPress core
	require $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php';
	// Set golfreview location
	set_site_review_location('adhoc-page');	
	get_header();
	
	$parts = explode('/',$_SERVER['REQUEST_URI']);
	
	if ( $parts[1] == 'reviews' ) {	
		
		if( count($parts) == 3 ) {		
			$returnparts = explode('.html', $parts[2]); // reviews/title.html
			$titlethml = $parts[2];
		}
		elseif( count($parts) == 4 ) {		
			$returnparts = explode('.html', $parts[3]); //reviews/golf-clubs/title.html
			$titlethml = $parts[3];
		}
		else if( count($parts) == 5 ) {		
			$returnparts = explode('.html', $parts[4]);//reviews/golf-clubs/callaway/title.html
			$titlethml = $parts[4];
		}
		else if( count($parts) == 6 ) {		
			$returnparts = explode('.html', $parts[5]);//reviews/golf-clubs/callaway/fairway-woods/title.html
			$titlethml = $parts[5];
		}
	}
	
	$args=array(
		'name' => $returnparts[0],
		'post_type' => 'post',
		'post_status' => 'publish',
		'posts_per_page' => 1,
		'caller_get_posts'=> 1
	);
	
	$my_query = null;
	$my_query = new WP_Query($args);
		
?>	
	<div id="content-left" class="col-md-8 col-sm-12">
		<div class="inner">
			
			
			
			
			
			
			
			<style>
			.item-box .text p
			{
				font-family: "Lucida Sans", sans-serif!important;
				color: #333;
				font-size: 12px!important;
			}
			</style>
			
			<div id="product-review">
			
				<?php  if( $my_query->have_posts() ) 
				{	
				?>
					<?php 
						while ($my_query->have_posts()) : $my_query->the_post(); 
					?>				
			
						<h1><?Php the_title()?></h1>
						<?php //the_permalink(); ?>
						<div class="item-box">
							<div style="font-family: 'Lucida Sans', sans-serif!important;    color: #333;    font-size: 12px!important;">		<div class="text">
										<?Php the_content(); ?>
									</div>
							</div>
						</div>
					<?php 
						endwhile; 
					?>
			<?php
				} 
				else 
				{
				?>
					<h1>Adhoc Article</h1>
					<div class="review-index-header"><span></span></div>
					<div class="item-box">
						<div class="text">
							<p>No Related Articles Found.</p>
						</div>
					</div>
				<?php 
				} 
				?>
					
				
			</div>			
			</div>	

		
<!--
			<div id="product-review">
				<h1>Adhoc Article</h1>
				<div class="review-index-header"><span></span></div>
				<div class="item-box"><p>				
				<?php  if( $my_query->have_posts() ) 
				{	
				?>
					<?php 
						while ($my_query->have_posts()) : $my_query->the_post(); 
					?>				
					<div class="title"><?Php the_title()?></div>
					<div class="text">
					<?Php the_content(); ?>
					<?php 
						endwhile; 
				} 
				else 
				{
				?>
						<div class="text">
						<p>No Related Articles Found.</p>
				<?php 
				} 
				?>
					<?php wp_reset_query(); ?>
					
						</div>
					</div>
				</div>
			</div>
		
-->		
		
	</div><!-- end content left -->		
<?php get_sidebar(); ?>
<?php get_footer(); ?>