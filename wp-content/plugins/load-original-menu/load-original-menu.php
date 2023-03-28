<?Php
/*
Plugin Name: Load Original Menus
Plugin URI: http://www.invenda.com
Description: Save hardcoded pages into wordpress database and populate them on mouse hover for HOME & Golf Clubs using previous theme for the wordpress 4.5.2.
Author: ConsumerReview
Version: 1.0
Author URI: http://www.invenda.com
*/
	global $wp_version;
	if( version_compare( $wp_version, "2.9", "<" ) )
		
	exit( 'This plugin requires WordPress 2.9 or newer. <a href="http://codex.wordpress.org/Upgrading_WordPress">Please update!</a>' );
	
	function original_guid() {
	
        $protocol 	= ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $domainName = $_SERVER['HTTP_HOST'];
		
        return $protocol.$domainName;
    }
	
	function original_create_page_template($post_id, $page_title, $slug, $p_template, $post_parent, $menu_order ) {
		
		global $wpdb;
		
		$post = array(
		
			'ID'				=> $post_id,
			'post_author' 		=> 1,
			'post_date' 		=> date('Y-m-d H:i:s'),
			'post_date_gmt' 	=> date('Y-m-d H:i:s'),
			'post_content' 		=> '',
			'post_title' 		=> $page_title,
			'post_name' 		=> sanitize_title($slug),
			'post_excerpt' 		=> '',
			'post_status' 		=> 'publish',
			'comment_status' 	=> 'open',
			'ping_status' 		=> 'open',
			'post_modified' 	=> date('Y-m-d H:i:s'),
			'post_modified_gmt' => date('Y-m-d H:i:s'),
			'post_parent' 		=> $post_parent,
			'guid'				=> original_guid() .'/'.sanitize_title($page_title).'.html',
			'menu_order'		=> $menu_order,
			'post_type' 		=> 'page',
			'comment_count' 	=> 0
		);
		
		$wpdb->insert('wp_posts', $post, array( '%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s' ));
	
		update_post_meta( $post_id, '_wp_page_template', $p_template );		
		update_option( 'permalink_structure', '/reviews/%postname%.html' );
		update_option( 'rewrite_rules', '' );
	}
	
	
	function original_create_page_template_one_by_one() {
		
		//Accessories Landing Page
		original_create_page_template( 455371162, 'Accessories', 'accessories', 'page-golf-clubs-subpage.php', 451515731, 0 );		
		//Best Golf Courses Sub Page
		original_create_page_template( 451515668, 'Best Golf Courses', 'best-golf-courses', 'page-golf-courses.php', 451515617, 1 );
		//Golf Clubs - Driver Sub Page
		original_create_page_template( 451515623, 'Drivers', 'drivers', 'page-golf-clubs-subpage.php', 451515614, 0 );
		//Golf Clubs - Driving Ranges Sub Page
		original_create_page_template( 451515670, 'Driving Ranges', 'driving-ranges', 'page-golf-courses.php', 451515617, 2 );
		//Golf Clubs - Fairway Woods Sub Page
		original_create_page_template( 451515629, 'Fairway Woods', 'fairway-woods', 'page-golf-clubs-subpage.php', 451515614, 1 );		
		//Golf Clubs - Golf Bags Sub Page
		original_create_page_template( 451515654, 'Golf Bags', 'bags', 'page-golf-clubs-subpage.php', 451515731, 8 );
		//Golf Clubs - Golf Balls Sub Page
		original_create_page_template( 451515648, 'Golf Balls', 'balls', 'page-golf-clubs-subpage.php', 451515731, 6 );		
		//Golf Clubs Landing Page
		original_create_page_template( 451515614, 'Golf Clubs', 'golf-clubs', 'page-golf-clubs.php', 0, 0 );		
		//Golf Course Search - Landing Page
		original_create_page_template( 451515736, 'Golf Course Search', 'golf-course-search', 'golf_course_search.php', 0, 0 );
		//Golf Courses - Landing Page
		original_create_page_template( 451515617, 'Golf Courses', 'golf-courses', 'page-golf-courses.php', 0, 0 );
		//Golf Deals - Landing Page
		original_create_page_template( 451515620, 'Golf Deals', 'golf-deals', 'page-golf-deals.php', 0, 0 );
		//Golf Equipment - Landing Page
		original_create_page_template( 451515731, 'Golf Equipment', 'golf-equipment', 'page-golf-equipment.php', 0, 0 );
		//Golf Equipment - Golf Shoes Sub Page
		original_create_page_template( 451515651, 'Golf Shoes', 'shoes', 'page-golf-clubs-subpage.php', 451515731, 7 );
		//Golf Clubs - GPS & Range Finders Sub Page
		original_create_page_template( 455371159, 'GPS & Range Finders', 'gps-and-range-finders', 'page-golf-clubs-subpage.php', 451515731, 0 );
		//Golf Clubs - Hybrids Sub Page
		original_create_page_template( 455371164, 'Hybrids', 'hybrids', 'page-golf-clubs-subpage.php', 451515614, 2 );
		//Golf Clubs - Irons Sub Page
		original_create_page_template( 451515637, 'Irons', 'irons', 'page-golf-clubs-subpage.php', 451515614, 3 );		
		//Golf Courses - Public Golf Courses Sub Page
		original_create_page_template( 451515666, 'Public Golf Courses', 'public-golf-courses', 'page-golf-courses.php', 451515617, 0 );
		//Golf Clubs - Putters Sub Page
		original_create_page_template( 451515641, 'Putters', 'putters', 'page-golf-clubs-subpage.php', 451515614, 5 );
		//Review Index - Landing Page
		original_create_page_template( 451515677, 'Review Index', 'reviews', 'page-review-index.php', 0, 0 );
		//Review Index - Landing Page
		original_create_page_template( 451515639, 'Wedges', 'wedges', 'page-golf-clubs-subpage.php', 451515614, 4 );
		
		return true;
	}
	function original_menus_callback () { 
	
		if($_POST['saveoriginalmenus']) {
	
			if( original_create_page_template_one_by_one() ) {
			
				echo '<div id="message" class="updated fade"><p>Successfully Menus Set. Please visit the home page.</p></div>';
			}
		} ?>	
		<div class="wrap" id='pluginWrap'>
			<h2><?php _e( 'load the original menus', 'menus setting' ); ?></h2>
			<b>Hierarchy order of original menus : </b><br/>
			<form style='margin:0 auto; width:680px;' method="post" name="frm_original_menus" action="?page=load-original-menu.php">
				<ul>
					<li>Home</li>
					<li>&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;GOLF CLUBS</li>
					<li>&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;GOLF BALLS</li>
					<li>&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;GOLF BAGS</li>
					<li>&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;GOLF SHOES</li>
					<li>&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;OTHER GOLF EQUIPMENT</li>
					<li>GOLF CLUBS</li>
					<li>&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;Drivers</li>
					<li>&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;Fairway Woods</li>
					<li>&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;Hybrids</li>
					<li>&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;Irons</li>
					<li>&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;Wedges</li>
					<li>&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;Putters</li>
					<li>&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;Golf Balls</li>
					<li>&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;Golf Shoes</li>
					<li>&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;Golf Bags</li>
					<li>GOLF COURSES</li>
					<li>GOLF FORUMS</li>
					<li>GOLF DEALS</li>					
				</ul><br/>
				<div id='MssqlButton'><input id='displayMenu' class='button' name='saveoriginalmenus' type='Submit' value="<?php _e("Show Original Menus" ); ?>" /></div>
			</form>
			<h3><?php _e( 'Note: Please use one time installation of this plugin, colud crash menus by using more then one time.' ); ?></h3>
		</div><?Php	
	}
	
	function setting_original_menus() {	
		
		add_options_page( __('Original Menus', 'original'), __('Original Menus', 'original'), 'manage_options', basename(__FILE__), 'original_menus_callback');
	}
	
	if( is_admin() ) {		
		
		add_action('admin_menu', 'setting_original_menus');
	}
	
	// Filter page template
	add_filter('page_template', 'original_menu_custom_template');
	
	function original_menu_custom_template($template) {
		
		return $template;
	}
	
?>
