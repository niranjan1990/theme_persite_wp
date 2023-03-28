<?php get_header(); ?>
<div id="content-left">
<div class="inner">			
	<div id="search-results" class="main-content">
		<form method="get" action="<?php bloginfo('url'); ?>">
			<div class="text-header"><h2>SEARCH RESULTS: <input class="search-page-box" type="text" name="s" value="<?php the_search_query(); ?>" /></h2></div>
		</form>
	<?php require_once("sphinxsearch.php");

	if (isset($_GET["s"])) {
	
		$query = $_GET["s"];
	}
	else {
		
		$query = 'callaway';  // demo search string if none is given
	}

	$date_sort = false;
	
	if (isset($_GET["d"])) {
		
		$date_sort = true;
	}
	
	$r = new SphinxSearch;
	$result = $r->GetCRProducts($query, false, 3);
	if ($result) {
		
		echo "<h2>Golf Products:</h2>";
		foreach ($result as $value) { ?>
			<div class="item-list-box clearfix">
				<div class="item-box">
					<div class="title"><a href="<?php echo $value->url; ?>"><?php echo $value->title; ?></a></div>
					<div class="meta"><div class="comments"></div>
						<?php echo $value->date; ?></div>
					<div class="text"><?php echo $value->text; ?></div>
				</div>
			</div>
	<?php }
	}

	$result = $r->GetForumPosts($query, true, 3);
	if ($result) {
		
		echo "<br /><h2>Golf Forum Posts:</h2>";
		foreach ($result as $value){ ?>
			<div class="item-list-box clearfix">
				<div class="item-box">
					<div class="title"><a href="<?php echo $value->url; ?>"><?php echo $value->title; ?></a></div>
					<div class="meta"><div class="comments"></div>
						<?php echo $value->date; ?></div>
					<div class="text"><?php echo $value->text; ?></div>
				</div>
			</div>
	<?php }
	}

	$result = $r->GetWPPosts($query, true, 3);
	
	if ($result) {
		
		echo "<br /><h2>Golf Wordpress Posts:</h2>";
		foreach ($result as $value) { ?>
			<div class="item-list-box clearfix">
				<div class="item-box">
					<div class="title"><a href="<?php echo $value->url; ?>"><?php echo $value->title; ?></a></div>
					<div class="meta"><div class="comments"></div>
						<?php echo $value->date; ?></div>
					<div class="text"><?php echo $value->text; ?></div>
				</div>
			</div>
	<?php }
	} ?>
	</div><!-- end main content -->			
</div><!-- end inner -->
</div><!-- end content left -->		
<?php get_sidebar(); ?>
<?php get_footer(); ?>