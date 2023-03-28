<?Php
						
	$db = explode("|", get_option("dbconnection") );	
	if ( !CheckMysqlCon( $db[3], $db[0], $db[1] ) ) {
		
		echo '<div id="message" class="updated fade"><p>Please go to Connection Tab, and then try again ?</p></div>';
		exit;
	}
	
	if( !get_option( 'PSiteName' ) && !get_option( 'SSiteName' ) ) {
		
		echo '<div id="message" class="updated fade"><p>Please go to Connect Tab, and set atleast one site name ?</p></div>';
		exit;
	}
	
	function FullAds() {
	
		$serializedwp = array (
	
			2	=> array(),
			3	=> array(
				
				"title"	=> "",
				"text"	=> "<div class=\"sidebox\"><script type=\"text/javascript\">GA_googleFillSlot(\"GOLF_300X250_1\");</script></div>",
				"filter"=> (bool) 0
			),
			4	=> array(
			
				"title"	=> "",
				"text"	=> "<div class=\"sidebox\"><script type=\"text/javascript\">GA_googleFillSlot(\"GOLF_300X250_2\");</script></div>",
				"filter"=> (bool) 0
			),
			
			5	=> array(
			
				"title"	=> "",
				"text"	=> "<div class=\"editorial\"><script type=\"text/javascript\">GA_googleFillSlot(\"GOLF_193x90\");</script></div><div class=\"clear\"></div>",
				"filter"=> (bool) 0		
			),
			
			6	=> array(
			
				"title"	=> "",
				"text"	=> "<div class=\"leaderboard\"><script type=\"text/javascript\">GA_googleFillSlot(\"GOLF_728x90\");</script></div><div class=\"clear\"></div>",
				"filter"=> (bool) 0		
			),
			
			7	=> array(
			
				"title"	=> "",
				"text"	=> "<div id=\"footer-ad\"><script type=\"text/javascript\">GA_googleFillSlot(\"GOLF_728x90_2\");</script></div><div class=\"clear\"></div>",
				"filter"=> (bool) 0		
			),
			
			"_multiwidget" => (int) 1
		);	
	
		update_option( 'widget_text', $serializedwp );
		
		$wp_widgets	=	array (

			"wp_inactive_widgets"	=> array (	0=>"pages-2", 1=>"calendar-2", 2=>"links-2", 3=>"text-2", 4=>"rss-2", 5=>"tag_cloud-2", 6=>"nav_menu-2", 7=>"search-2"),		
			"sidebar-widget-area"			=> array( 0	=> "text-3"	),
			"secondary-sidebar-widget-area"	=> array( 0	=> "text-4" ),
			"small-promo-widget-area"		=> array( 0	=> "text-5" ),
			"top-leaderboard-widget-area"	=> array( 0	=> "text-6" ),
			"popular-widget-area"			=> array(),
			"bottom-leaderboard-widget-area"=> array( 0	=> "text-7" ),
			"footer-links-widget-area"		=> array(),
			"top-toc-links-widget-area"		=> array(),
			"Sponsor-links-widget-area"		=> array(),
			"bottom-vtsb-links-widget-area"	=> array(),
			"quick-nav-links-widget-area"	=> array(),
			"array_version"					=> (int) 3
			
		);
	
		update_option( 'sidebars_widgets', $wp_widgets);	
	}
	
	function AddPromotedPage() {
		
		global $wpdb;
		
		sparkle_plugin_activate();
		$post_name = "Callaway RAZR Hawk Fairway Woods"; //$_POST['promoadstitle'];
		$post_content = "Callaway RAZR Hawk Fairway Woods"; //$_POST['promoadscont'];
		$post_type = "post";
		$slug = sanitize_title( preg_replace('/ /', '-', $post_name ) );
		
		if ( Check_WP_Entry_Exists( $slug, $post_type ) ) {
						
			$post = array (	'post_title' => $post_name, 'post_name'=> strtolower($slug), 'post_content' => "$post_content", 'post_type' => 'post', 'post_status' => 'publish', 'post_parent' => $menu_item_object_id, 'post_author' => 1 );
			$post_id = wp_insert_post( $post, $wp_error );
			add_post_meta( $post_id , "_wp_page_template", '', true );
			add_post_meta( $post_id , "_edit_last", 1, true );
			$sql =	"Insert Into `golfreview_article_queue` Values ( '', 'homepage-featured', '".$post_id."',  '".time()."', 2, 9999),		( '', 'homepage-top-products1', '".$post_id."',  '".time()."', 2, 9999), ( '', 'homepage-top-products2', '".$post_id."',  '".time()."', 2, 9999)";
			$wpdb->query($sql);
			update_post_meta( $post_id, 'product_id', 456348 );
		}
	}
	
	if( isset( $_POST['SaveVbulletin'] ) ) {
		
		if( $_POST['Carousel'] == false && $_POST['FullAds'] == false ) {
		
			echo '<div id="message" class="updated fade"><p>Please select at least on checkbox.</p></div>';
		}/*
		else if ( $_POST['promoadstitle'] == "" ) {
		
			echo '<div id="message" class="updated fade"><p>Please the Promo Ads Title.</p></div>';
		}
		else if ( $_POST['promoadscont'] == "" ) {
		
			echo '<div id="message" class="updated fade"><p>Please the Promo Ads Content.</p></div>';
		}*/
		else if ( $_POST['Carousel'] == true && $_POST['FullAds'] == false ) {
			
			//Adding promoted & carousel here.
			AddPromotedPage();
			echo '<div id="message" class="updated fade"><p>Promoted Ads and the Carousel added.</p></div>';
		}
		else if ( $_POST['Carousel'] == false && $_POST['FullAds'] == true ) {
		
			//Adding full advertizment only here.
			FullAds();
			echo '<div id="message" class="updated fade"><p>Full Ads added.</p></div>';
		}
		else {
			
			//adding both promoted & carousel and full advertizment here.
			AddPromotedPage();
			FullAds();
			echo '<div id="message" class="updated fade"><p>Promoted Ads & Carousel and Full Advertisement added.</p></div>';
		}
	}
?>