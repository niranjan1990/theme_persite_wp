<?php
	
	//add_action( 'init', 'my_setcookie_example' );
	function my_setcookie_example($username_value,$username) {
		$path = parse_url(get_option('siteurl'), PHP_URL_PATH);
		$host = parse_url(get_option('siteurl'), PHP_URL_HOST);
		setcookie( "userid", $username_value, time() + 12000, COOKIEPATH, COOKIE_DOMAIN  );
		setcookie( "username", $username, time() + 12000, COOKIEPATH, COOKIE_DOMAIN  );
		}


	
	function clean_custom_menu1( $theme_location ) {
		
		if ( ($theme_location) && ($locations = get_nav_menu_locations()) && isset($locations[$theme_location]) ) {
			
			$menu = get_term( $locations[$theme_location], 'nav_menu' );
			$menu_items = wp_get_nav_menu_items($menu->term_id); 
			$menu_list = '<ul id="menu">' ."\n"; 
			$count = 0;
			$submenu = false;         
			foreach( $menu_items as $menu_item ) {
             
				$link = $menu_item->url;
				$title = $menu_item->title;
             
				if ( !$menu_item->menu_item_parent ) {
				
					$parent_id = $menu_item->ID;                 
					$menu_list .= '<li>' ."\n";
					$menu_list .= '<a href="'.$link.'">'.$title.'</a>' ."\n";
				}
 
				if ( $parent_id == $menu_item->menu_item_parent ) {
 
					if ( !$submenu ) {
						$submenu = true;
						$menu_list .= '<ul>' ."\n";
					}
					$menu_list .= '<li>' ."\n";
					$menu_list .= '<a href="'.$link.'">'.$title.'</a>' ."\n";
					$menu_list .= '</li>' ."\n";
					if ( $menu_items[ $count + 1 ]->menu_item_parent != $parent_id && $submenu ){
					
						$menu_list .= '</ul>' ."\n";
						$submenu = false;
					}
				}
 
				if ( $menu_items[ $count + 1 ]->menu_item_parent != $parent_id ) { 
				
					$menu_list .= '</li>' ."\n";      
					$submenu = false;
				}
				$count++;
			}
			$menu_list .= '</ul>' ."\n";
		} 
		else {
			
			$menu_list = '<!-- no menu defined in location "'.$theme_location.'" -->';
		}
		echo $menu_list;
	}
	
	
	// custom menu example @ https://digwp.com/2011/11/html-formatting-custom-menus/
	
	function clean_custom_menus() {
		
		$menu_name = 'primary'; // specify custom menu slug
		if (($locations = get_nav_menu_locations()) && isset($locations[$menu_name])) {
			
			$menu = wp_get_nav_menu_object($locations[$menu_name]);
			$menu_items = wp_get_nav_menu_items($menu->term_id);
			$menu_list = '<nav  class="[ navbar navbar-fixed-top ][ navbar-bootsnipp animate ]" role="navigation">' ."\n";
			$menu_list .= '<div class="[ container ]">		
			<div class="[ navbar-header ]">
				<button type="button" class="[ navbar-toggle ]" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="true">
					<span class="[ sr-only ]">Toggle navigation</span>
					<span class="[ icon-bar ]"></span>
					<span class="[ icon-bar ]"></span>
					<span class="[ icon-bar ]"></span>
				</button>
				<div class="[ animbrand ]">
					<a class="[ navbar-brand ][ animate ]" href="#">Bootsnipp</a>
				</div>
			</div>
			<div class="[ navbar-collapse ] collapse in" id="bs-example-navbar-collapse-1" aria-expanded="true">';
			$menu_list .= "\t\t\t\t". '<ul class="[ nav navbar-nav navbar-right ]">' ."\n";
			foreach ((array) $menu_items as $key => $menu_item) {
				$title = $menu_item->title;
				$url = $menu_item->url;			
				$menu_list .= "\t\t\t\t\t". '<li><a class="animate" href="'. $url .'">'. $title .'</a></li>' ."\n";
			}
			$menu_list .= "\t\t\t\t". '</ul>' ."\n";
			$menu_list .= "\t\t\t". '</nav>' ."\n";
		} 
		else {
			
			// $menu_list = '<!-- no list defined -->';
		}
		echo $menu_list;
	}
	
	/**** Golf Deals widget output ******/
	
	function cr_site_deals_widget($id=1,$header=true) {
		
		if($header) {
			
			echo '<div id="featured-golf-deals" class="clearfix">';
			echo '<div class="lwr">Featured Golf Deals <span><a class="arrow-right" href="/golf-deals.html">see all</a></span></div>';
		}
		echo <<< EOT
		<script type="text/javascript" language="javascript">
			random = Math.random(Math.random() * 10000000);
			area = 'reviews';
			areaid = '$id';
			numdeals = '4';
			document.write('<SCR'+'IPT SRC="http://www.golfreview.com/ajax/hotdeals/gethotdeal.aspx?area=' +
			area + '&areaid=' + areaid + '&numdeals=' + numdeals + '&cr_random=' +
			random + '">');
			document.write('</SCR'+'IPT>');
		</script>
EOT;
	
		if($header) { echo '</div>'; }
	}

	/*** comment output function ****/
	
	function site_review_comment( $comment, $args, $depth ) {
		
		$GLOBALS['comment'] = $comment;
		switch ( $comment->comment_type ) :
			
			case '' :
		?>
		<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
			<div id="comment-<?php comment_ID(); ?>" class="comment-box">
				<table><tr><td class="type" valign="top"><?php echo get_avatar( $comment, 40 ); ?></td>					
				<td><div class="item-box">
					<div class="title"><?php printf( __( '%s <span class="says">says:</span>' ), sprintf( '<cite class="fn">%s</cite>', get_comment_author_link() ) ); ?></div>
					<div class="meta">
					<?php if ( $comment->comment_approved == '0' ) : ?>
						<em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'twentyten' ); ?></em><br />
					<?php endif; ?>
					<?php printf( __( '%1$s at %2$s', 'twentyten' ), get_comment_date(),  get_comment_time() ); ?>
					</div>
					<div class="text"><?php comment_text(); ?></div>
				</div>
				</td></tr></table>
			</div>
		<?php
			break;
			case 'pingback'  :
			case 'trackback' :
		?>
		<li class="post pingback">
			<p><?php _e( 'Pingback:', 'twentyten' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __( '(Edit)', 'twentyten' ), ' ' ); ?></p>
		<?php
		break;
		endswitch;
	}
	
	if ( function_exists( 'add_theme_support' ) ) {
		
		add_theme_support( 'post-thumbnails' );
	}
	
	if ( function_exists( 'add_image_size' ) ) { 
		
		add_image_size( 'homepage-featured', 396, 9999 );
		add_image_size( 'homepage-best', 170, 9999 );
	}

	/*** checkes if displaying a specific page, or sub page of specific page ****/
	
	function is_cur_page($id) {
		
		global $post;
		
		if ( is_page($id) || $post->post_parent == $id ) {
		
			return true;
			
		} 
		else {
			
			return false;
		}
	}

	/*** Function to check SERVER IP *****/
	function Live_Ads() {
		
		if($_SERVER['SERVER_ADDR'] != '172.20.10.217') {
			
			return true;
		} 
		else {
			
			return false;
		}
	}


/**
 * Register widgetized areas, including two sidebars and four widget-ready columns in the footer.
 * To override site_review_widgets_init() in a child theme, remove the action hook and add your own
 * function tied to the init hook.
 * @since GolfReview 1.0
 * @uses register_sidebar
 */

	function site_review_widgets_init() {

		// Area 1, located at the top of the sidebar.
		register_sidebar( array(
		
			'name' => __( '1: Sidebar Widget Area', 'sitereview' ),
			'id' => 'sidebar-widget-area',
			'description' => __( 'The primary sidebar widget area', 'sitereview' ),
			'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
			'after_widget' => '</li>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>',

		) );
	
		// Area 2, located below the Primary Widget Area in the sidebar. Empty by default.

		register_sidebar( array(

			'name' => __( '2: Sidebar Widget Area', 'sitereview' ),
			'id' => 'secondary-sidebar-widget-area',
			'description' => __( 'Secondary sidebar widget area', 'sitereview' ),
			'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
			'after_widget' => '</li>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>',
		) );

		// Area 3, located below logo for editorial/advertising space.

		register_sidebar( array(

			'name' => __( 'Small Promo 194x90', 'sitereview' ),
			'id' => 'small-promo-widget-area',
			'description' => __( 'Small Promo 194x90', 'sitereview' ),
			'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
			'after_widget' => '</li>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>',
		
		));
	
		// Area 4, located below navigation.

		register_sidebar( array(

			'name' => __( 'Top Leaderboard 728x90', 'sitereview' ),
			'id' => 'top-leaderboard-widget-area',
			'description' => __( 'Top Leaderboard 728x90', 'sitereview' ),
			'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
			'after_widget' => '</li>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>',
			
		));
	
		// Area 5, located before footer.

		register_sidebar( array(

			'name' => __( 'Popular in Network Widget Area', 'sitereview' ),
			'id' => 'popular-widget-area',
			'description' => __( 'Network wide links area', 'sitereview' ),
			'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
			'after_widget' => '</li>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>',
		) );

		// Area 6, located below network wide links Empty by default.

		register_sidebar( array(
	
			'name' => __( 'Bottom Leaderboard 728x90', 'sitereview' ),
			'id' => 'bottom-leaderboard-widget-area',
			'description' => __( 'Bottom leaderboard 728x90', 'sitereview' ),
			'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
			'after_widget' => '</li>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>',

		));

		// Area 7, located in the footer. Empty by default.

		register_sidebar( array(

			'name' => __( 'Footer Links Widget Area', 'sitereview' ),
			'id' => 'footer-links-widget-area',
			'description' => __( 'The first footer widget area', 'sitereview' ),
			'before_widget' => '<div class="widget-container">',
			'after_widget' => '</div>',
			'before_title' => '<h5 class="widget-title">',
			'after_title' => '</h5>',

		));
	
		// Area 8, located in the body. Empty by default.

		register_sidebar( array(

			'name' => __( 'Top ToC Links Area', 'sitereview' ),
			'id' => 'top-toc-links-widget-area',
			'description' => __( 'The first ToC widget area', 'sitereview' ),
			'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
			'after_widget' => '</li>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>',
	
		));
    
		
		// Area 9, located in the body. Empty by default.
	
		register_sidebar( array(

			'name' => __( 'Sponsor Links Widget Area', 'sitereview' ),
			'id' => 'Sponsor-links-widget-area',
			'description' => __( 'The first Sponsor Links widget area', 'sitereview' ),
			'before_widget' => '<div class="widget-container">',
			'after_widget' => '</div>',
			'before_title' => '<h5 class="widget-title">',
			'after_title' => '</h5>',
		) );

		// Area 10, located in loop.

		register_sidebar( array(

			'name' => __( 'BOTTOM VTSB Links Area', 'sitereview' ),
			'id' => 'bottom-vtsb-links-widget-area',
			'description' => __( 'BOTTOM VTSB Link Box Area', 'sitereview' ),
			'before_widget' => '<div class="widget-container">',
			'after_widget' => '</div>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>',
		
		) );

		// Area 11, located in loop.

		register_sidebar( array(

			'name' => __( 'Quick Nav Links Area', 'sitereview' ),
			'id' => 'quick-nav-links-widget-area',
			'description' => __( 'Quick Nav Area', 'sitereview' ),
			'before_widget' => '<div class="widget-container">',
			'after_widget' => '</div>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>',
			
		));
	}


	/** Register sidebars by running site_review_widgets_init() on the widgets_init hook. */

	add_action( 'widgets_init', 'site_review_widgets_init' );
	
	function wp_seo_header_reader(){
	
		global $post;
		
		$meta = '';
		
		$custom_fields = get_post_custom($post->ID);
		
		$seometa_title			= $custom_fields['_seometa_title'];
		$seometa_keyword 	 	= $custom_fields['_seometa_keyword'];
		$seometa_description	= $custom_fields['_seometa_description'];
		
		$meta	.=	"<meta name='title' content='".trim($seometa_title[0])."' />\n";
		$meta	.=	"<meta name='keyword' content='".trim($seometa_keyword[0])."' />\n";
		$meta	.=	"<meta name='description' content='".trim($seometa_description[0])."' />\n";

		echo $meta;
	}
	
	add_action('wp_head', 'wp_seo_header_reader');
	
	function site_init_jquery() {
	
		if (!is_admin()) { wp_enqueue_script('jquery'); }
	}
	
	add_action('init', 'site_init_jquery');
	
	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array( 'primary' => __( 'Primary Navigation', 'sitereview' ), ) );		
	
		
	
	function virtual_nav_menu_item( $parentID ) {
		
		$ran = rand(1, 99);		
		$v_menu =	(object) array(
			
			'ID' => 0, 'post_author' => 1, 'post_date' => date('Y-m-d H:i:s'),
			'post_date_gmt' => date('Y-m-d H:i:s'), 'post_content' => '' ,
			'post_title' => '', 'post_excerpt' => '', 'post_status' => 'publish',
			'comment_status' => 'open', 'ping_status' => 'open', 'post_password' => '',
			'post_name' => '', 'to_ping' => '', 'pinged' => '', 'post_modified' => date('Y-m-d H:i:s'),
			'post_modified_gmt' => date('Y-m-d H:i:s'), 'post_content_filtered' => '',
			'post_parent' => 0 , 'guid' => '', 'menu_order' => $ran,
			'post_type' => 'nav_menu_item',	'post_mime_type' => '',	'comment_count' => 0 ,
			'filter' => 'raw', 'db_id' => ($parentID-1), 'menu_item_parent' => $parentID,
			'object_id' => '', 'object' => '', 'type' => 'post_type', 'type_label' => '',
			'url' => '', 'title' => '',	'target' => '',	'attr_title' => '', 
			'description' => '', 'classes' => Array ( 0 => '', 1 => '', 2 => '' ), 'xfn' => '',
		);
		
		return $v_menu;
	}
	
	//add_filter( 'wp_get_nav_menu_items', 'injecting_virtual_nav_item', 10, 2 );	
	function injecting_virtual_nav_item( $items, $args ) {
			
		$nav_list	= array();
		$patentids	= array();
		$itemsIds	= array();
		$resultIds	= array();
		
		foreach ( $items as $k => $item ) {
			
			$p_id =  get_post_meta( $item->ID, '_menu_item_menu_item_parent', true );
			$patentids[] = ( $p_id != 0 ) ? get_post_meta( $item->ID, '_menu_item_menu_item_parent', true ) : '';
			$itemsIds[] = ( $item->menu_item_parent == 0 ) ? $item->ID : '';
			$nav_list[] = (object) get_object_vars( $item );
		}
		
		$patentids	= array_unique( array_filter( $patentids ) );
		$itemsIds	= array_unique( array_filter( $itemsIds ) );
		$resultIds	= array_diff( $itemsIds, $patentids );
			
		foreach ( $resultIds as $k => $id ) {
			
			$nav_list[] = virtual_nav_menu_item( $id );
		}
		
		return ( is_plugin_page() || !is_admin() ) ? $nav_list : $items;
	}
	
	function site_menus_css_class_nav( $classes, $item ) {
		
		global $post;
		
		$HomeUnknownChild = array('menu-item');
		$Current = array('current-menu-item');
		$parts = explode('/', $_SERVER['REQUEST_URI']);
		
		$cls = explode('.', $parts[1]);
		$brand = explode('.', $parts[3]);
		$child_cls = explode('.', $parts[3]);
		$hm_child_cls = explode('.', $parts[2]);
		
		$new_home_child = $cls[0].'-new-home-child-class';
		$main_parent	= $cls[0].'-parent-class'; //without brand
		//$brand_parent	= $brand[0].'-parent-class'; //with category/brand/subcatgeory
		$main_prnt_cld	= $child_cls[0].'-parent-child-class';
		$home_parent	= $cls[0].'-duplicate-home-parent-class';
		$hmChildCls		= $hm_child_cls[0].'-duplicate-home-child-class';		
		
		if( !$post->ID && count($parts) < 5 ) { // when url like category/brand/sub-category
			
			if( in_array( $main_parent, $classes) || in_array( $main_prnt_cld, $classes) ) {
			
				return array('current-menu-item');
			}
		}
	
		if( count($parts) > 3 ) {			
			
			if ( in_array( $parts[3].'-parent-child-class', $classes) ) {
			
				return array('current-menu-item');
			}
			else if(  in_array( $parts[1].'-parent-class', $classes) ) {
			
				return array('current-menu-parent');
			}
			else {
			
				return array('menu-item');
			}
		}
		else {
					
			if( in_array( $home_parent, $classes) || in_array( $hmChildCls, $classes) ) {
				
				//echo ' 2 ';
				return $HomeUnknownChild;
			}
		
			if(  $item->object_id == $post->ID || $item->object_id == $post->post_parent  ) {
			
				//echo ' 3 ';
				return $classes;
			}
		
			//echo $post->post_type;
			
			if( $item->post_name == 'home' && ( $post->post_type == 'post' || !$post->ID ) || in_array( $new_home_child, $classes)  )  {
						
				if( count($parts) < 3  ) {
				
					//echo ' 66 ';
					return $home = ( in_array( 'menu-item-home', $classes) ||  in_array( $new_home_child, $classes) ) ? array('current-menu-item') : $classes;
				}
				else {
					
					//echo ' 77 ';
					return $classes;
				}
			}
			/*else if( $item->post_name == 'home' && !in_array( 'current-menu-parent', $classes ) || in_array( $new_home_child, $classes)  ) {
				
				return $classes;
			}*/
			else {
			
				//echo ' 5 ';
				return $HomeUnknownChild;
			}
		}
		
		//return $classes;
	}
	
	add_filter( 'nav_menu_css_class', 'site_menus_css_class_nav', 10, 2 );
?>