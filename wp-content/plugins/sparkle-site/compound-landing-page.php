<?PHP

	$db = explode("|", get_option("dbconnection") );
	if ( !CheckMysqlCon( $db[3], $db[0], $db[1] ) ) {
		
		echo '<div id="message" class="updated fade"><p>Please go to Connect Tab, and then try again ?</p></div>';
		exit;
	}

	if( !get_option( 'PSiteName' ) && !get_option( 'SSiteName' ) ) {
		
		echo '<div id="message" class="updated fade"><p>Please go to Connect Tab, and set atleast one site name ?</p></div>';
		exit;
	}
	
	if( !get_option( 'TemplateList' ) ) {
		
		echo '<div id="message" class="updated fade"><p>Please go to Menus Tab, and Generate/Regenerate menus.</p></div>';
		exit;
	}
	
	include_once( ABSPATH.'wp-content/plugins/venice_class.php');
	
	/* creating a class object $pages */
	$ClsPage = new ClassPages( get_option("dbconnection") );
	$PSiteName = get_option( 'PSiteName' );
	$SSiteName = get_option( 'SSiteName' );
	
	$TemplateList = get_option( 'TemplateList' );
	$TemplateList = explode(":", $TemplateList);
	
	if( get_option( 'PSiteName' ) && !get_option( 'SSiteName' ) ) {
		
		$PSite = explode(":", $PSiteName);
		$getallmaincategory = $ClsPage->GetAllMain( $PSite[0], $TemplateList[0], 0, 0 );
	}
	if( get_option( 'PSiteName' ) && get_option( 'SSiteName' ) ) {
		
		$PSite = explode(":", $PSiteName);
		$SSite = explode(":", $SSiteName);
		$getallmaincategory = $ClsPage->GetAllMain( $PSite[0], $TemplateList[0], $SSite[0], $TemplateList[1] );
	}
	
	function Generate_HtaccessFile( $option ) {
		
		delete_option('rewrite_rules');
		$path = get_home_path();
		$path .= ".htaccess";
		
		$current .= "# BEGIN WordPress \n";
		$current .= "<IfModule mod_rewrite.c> \n";
		$current .= "RewriteEngine On \n";
		$current .= "RewriteBase / \n";
		$current .= "RewriteRule ^index\.php$ - [L] \n";
			
		if( $option == 'product' ) {
			
			$current .= "\n#golfreview brand page\n";
			$current .= "RewriteCond %{REQUEST_URI} !(golf-clubs|golf-deals|reviews|golf-equipment)\.html$\n";
			$current .= "RewriteRule ^([a-zA-Z0-9_-]+)\.html$ /wp-content/themes/". strtolower($option) ."/golfreview_brand_page.php [L]\n\n";
			$current .= "#golfreview brand/category page\n";
			$current .= "RewriteCond %{REQUEST_URI} !(golf-clubs|golf-deals|reviews|golf-equipment)/([a-zA-Z0-9_-]+)\.html$\n";
			$current .= "RewriteRule ^([a-zA-Z0-9_-]+)/([a-zA-Z0-9_-]+)\.html$ /wp-content/themes/". strtolower($option) ."/golfreview_brand_category_page.php [L]\n\n";
			$current .= "#golfreview product list rewrite\n";
			$current .= "#RewriteRule ^(golf-clubs|golf-equipment)/([a-zA-Z0-9_-]+)/([a-zA-Z0-9_-]+)\.html$ /wp-content/themes/". strtolower($option) ."/site_review_product_list.php [L]\n\n";
			$current .= "#golfreview product review\n";
			$current .= "RewriteRule ^(golf-clubs|golf-equipment)/([a-zA-Z0-9_-]+)/([a-z\-]+)/([a-zA-Z0-9_-]+)\-review\.html$ /wp-content/themes/". strtolower($option) ."/golfreview_product_review.php [L]\n\n";
			$current .= "#golfreview product page rewrite\n";
			$current .= "RewriteRule ^(golf-clubs|golf-equipment)/([a-zA-Z0-9_-]+)/([a-z\-]+)/([a-zA-Z0-9_-]+)\.html$ /wp-content/themes/". strtolower($option) ."/site_review_product_page.php [L]\n\n";			
		}		
		else if( $option == 'location' ) {
			
			$current .= "\n#golfreview brand page \n";
			$current .= "RewriteCond %{REQUEST_URI} !(golf-clubs|golf-courses|golf-deals|reviews|golf-equipment|golf-course-search)\.html$ \n";
			$current .= "RewriteRule ^([a-zA-Z0-9_-]+)\.html$ /wp-content/themes/". strtolower($option) ."/golfreview_brand_page.php [L] \n\n";
			$current .= "#golfreview brand/category page \n";
			$current .= "RewriteCond %{REQUEST_URI} !(golf-clubs|golf-courses|golf-deals|reviews|golf-equipment|golf-course-search)/([a-zA-Z0-9_-]+)\.html$ \n";
			$current .= "RewriteRule ^([a-zA-Z0-9_-]+)/([a-zA-Z0-9_-]+)\.html$ /wp-content/themes/". strtolower($option) ."/golfreview_brand_category_page.php [L] \n\n";
			$current .= "#golfreview course/state \n";
			$current .= "RewriteCond %{REQUEST_URI} /golf-courses/([A-Z]{1})([a-zA-Z\-]+)\.html$ [OR] \n";
			$current .= "RewriteCond %{REQUEST_URI} /golf-courses/(public-golf-courses|best-golf-courses|driving-ranges)/([A-Z]{1}[a-zA-Z\-]+)\.html$ \n";
			$current .= "RewriteRule ^(.*)$ /wp-content/themes/". strtolower($option) ."/golfreview_course_state.php [L] \n\n";
			$current .= "#golfreview course/state/city-zip \n";
			$current .= "RewriteCond %{REQUEST_URI} /golf-courses/([A-Z]{1}[a-zA-Z\-]+)/([a-zA-Z0-9_\-\.]+)\.html$ [OR] \n";
			$current .= "RewriteCond %{REQUEST_URI} /golf-courses/([0-9]+)\.html$ [OR] \n";
			$current .= "RewriteCond %{REQUEST_URI} /golf-courses/(public-golf-courses|best-golf-courses|driving-ranges)/([A-Z]{1}[a-zA-Z\-]+)/([a-zA-Z0-9_\.]+)\.html$ [OR] \n";
			$current .= "RewriteCond %{REQUEST_URI} /golf-courses/(public-golf-courses|best-golf-courses|driving-ranges)/([0-9]+)\.html$ \n";
			$current .= "RewriteRule ^(.*)$ /wp-content/themes/". strtolower($option) ."/site_review_location_listing.php [L] \n\n";
			$current .= "#golfcourse course review \n";
			$current .= "RewriteRule ^golf-courses/([A-Z]{1}[a-zA-Z\-]+)/([a-zA-Z0-9_\-\.]+)/([a-zA-Z0-9_\-]+)-review\.html$ /wp-content/themes/". strtolower($option) ."/golfreview_product_review.php [L] \n\n";
			$current .= "#golfcourse /golf_courses/state/city/course \n";
			$current .= "RewriteCond %{REQUEST_URI} /golf-courses/([A-Z]{1}[a-zA-Z\-]+)/([a-zA-Z0-9_\-\.]+)/([a-zA-Z0-9_\-]+)\.html$ \n";
			$current .= "RewriteRule ^(.*)$ /wp-content/themes/". strtolower($option) ."/golfreview_course_page.php [L] \n\n";
			$current .= "#golfreview product review \n";
			$current .= "RewriteRule ^(golf-clubs|golf-equipment)/([a-zA-Z0-9_-]+)/([a-z\-]+)/([a-zA-Z0-9_-]+)\-review\.html$ /wp-content/themes/". strtolower($option) ."/golfreview_product_review.php [L] \n\n";
			$current .= "#golfreview product page rewrite \n";
			$current .= "RewriteRule ^(golf-clubs|golf-equipment)/([a-zA-Z0-9_-]+)/([a-z\-]+)/([a-zA-Z0-9_-]+)\.html$ /wp-content/themes/". strtolower($option) ."/site_review_product_page.php [L] \n\n";
		}
		
		$current .= "RewriteCond %{REQUEST_FILENAME} !-f\n";
		$current .= "RewriteCond %{REQUEST_FILENAME} !-d\n";
		$current .= "RewriteRule . /index.php [L]\n";
		$current .= "</IfModule> \n# END WordPress\n";
		
		@file_put_contents( $path, $current);
	}
	
	function SelectCurrentTheme( $theme_name ) {
		
		$menu_name = "menu_header";
		if ( !is_nav_menu( 'menu_header' )) {
		
			$menu_id = wp_create_nav_menu( $menu_name );
		}
		else {
			
			$menu_obj = get_term_by( 'name', $menu_name, 'nav_menu' );
			$menu_id = $menu_obj->term_id;
		}
		
		$theme_data = get_theme_data( get_theme_root() . '/' . $theme_name . '/style.css' );
		update_option("current_theme", $theme_data['Title']);
		update_option("template", strtolower($theme_name) );
		update_option("stylesheet", strtolower($theme_name) );
		$theme	= get_current_theme(); //first get the current theme
		$mods	= get_option("mods_$theme"); //get theme's mods
		$mods['nav_menu_locations']['primary'] = $menu_id; //update mods with menu id at theme location
		update_option("mods_$theme", $mods);
		Generate_HtaccessFile( $theme_name );
	}
	
	function ReadTemplateDir( $dir, $Dest ) {
		
		// Open a directory, and read its contents
		if (is_dir($dir)) {
			chmod($dir, 0777);
			if ($dh = opendir($dir)) {
				while (($file = readdir($dh)) !== false) {
					if( is_file($dir.'/'.$file) ) {
						//echo $dir.'/'.$file . "<br/>";
						@copy( $dir.'/'.$file, $Dest.'/'.$file );
					}
				}
				closedir($dh);
			}
		}
	}
	
	function ThemeDirReadRemoveFiles( $dir ) {
	
		// Open a directory, and read its contents
		if (is_dir($dir)) {
			if ($dh = opendir($dir)) {
				while (($file = readdir($dh)) !== false) {
					if( is_file($dir.'/'.$file) ) {
						
						@unlink($dir.'/'.$file);
					}
				}
				closedir($dh);
			}
		}
	}

	function TransferTemplate($TransferTemplate) {
			
		$dir = plugin_dir_path( __FILE__ );
		$product = $dir."template/product";
		$location = $dir."template/location";
		$common = $dir."template/common";
		$TplDir = get_template_directory();		
		ThemeDirReadRemoveFiles($TplDir);
		
		if (!is_writable($TplDir)) {
			
			echo "<div id='message' class='updated fade'><p>There are no permission to copy files</p></div>";
		}
		elseif( $TransferTemplate == 'product' ) {
				
			ReadTemplateDir($product, $TplDir);
			ReadTemplateDir($common, $TplDir);
			echo "<div id='message' class='updated fade'><p>Files transfered.</p></div>";
		}
		elseif( $TransferTemplate == 'location' ) {
		
			ReadTemplateDir($location, $TplDir);
			ReadTemplateDir($common, $TplDir);
			echo "<div id='message' class='updated fade'><p>Files transfered.</p></div>";
		}
		elseif( $TransferTemplate == 'full' ) {
		
			ReadTemplateDir($product, $TplDir);
			ReadTemplateDir($location, $TplDir);
			ReadTemplateDir($common, $TplDir);
			echo "<div id='message' class='updated fade'><p>Files transfered.</p></div>";
		}
	}
	
	function Get_Templates() {
		
		$template = get_option('template');
		$tpl = array ( 'product', 'location' );
		$opt .= "<option value=''>Select Template File</option>";
		foreach( $tpl as $t ) {
			
			$selected = ($template == $t ) ? " selected='selected'" : '';
			$opt .= "<option value='$t'>".ucfirst($t)."</option>";
		}
		return $opt;
	}
	
	function ShowPages( $pages ) {
		
		$tr .= "<tr><td colspan='5'>&nbsp;</td></tr>";
		$tr .= "<tr><td><b>Pages</b></td><td><b>Article Queue Tag</b></td><td><b>Create Template File</b></td><td><b>Date</b></td><td><b>Action</b></td></tr>";
		$tr .= "<tr><td colspan='5'>&nbsp;</td></tr>";
			
		foreach( $pages as $k => $page ) {
			
			$slug	= preg_replace('/ /', '-', strtolower($page->category_name) );
			$tr .= "<form method='post' action='?page=site-review-sparkle-site.php&tab=templates'><input type='hidden' name='CATID' value='". $page->categoryid ."' />";
			$tr	.= "<tr><td width='20%'>". $page->category_name ."</td><td width='20%'>". $slug ."-featured</td><td width='20%'><select style='width:160px;' name='tplname_".$page->categoryid."'>" . Get_Templates() . "</select></td>". FileDetails( $page->category_name, $slug, $page->categoryid ) ."</tr>";
			$tr	.= "</form>";
		}
		
		return $tr;
	}
	
	/* Getting the result to have categories and adding to table rows and returning as well  */
	
	function FileDetails($filename, $slug, $cid ) {
	
		$file = get_template_directory() . '/'. "page-".$slug.".php";
		
		if (file_exists($file)) {
			
			$td .= "<td width='15%'>" . date ("M d Y", filemtime($file)) . "</td><td width='10%'><input type='submit' class='button' name='Initialize' value='Re-Initialize' /></td>";
		}
		else {
			
			$td .= "<td width='15%'>-</td><td width='10%'><input type='submit' class='button' name='Initialize' value='Initialize' /></td>";
		}
		return $td;
	}
	
	/*
		creation of golf courses text php file passing through four variable -
		$filename - when text file created it's need a name.
		$t_header - it is template header associated with wordpress page.
		$NodeID   - category have nodeid to filter categories and sub categories.
		$category_path - need a path from Venice database to set a location/page urls.
	*/
	
	function CreateGolfCoursesTextFile( $filename, $t_header, $NodeID, $category_path ) {
		
		$slug	= preg_replace('/ /', '-', strtolower($t_header));
		$file	= get_template_directory() . '/'. $filename;
		//$term_cat = get_categories_list( $subcategory, $NodeID );		
		$file	= get_template_directory() . '/page-location-landing-page.php';
		
		$current .= "<?Php /* Template Name: Location Landing Page */ ?> \n";
		$current .= "<?Php site_review_location_type();  /* get and return object array of all courses */ ?> \n";
		$current .= "<?Php \$parts = explode('/', \$_SERVER['REQUEST_URI']); ?>  \n";
		$current .= "<?Php \$cUrl = ( count(\$parts) == 2 ) ? \$CourseType['course_type'] : '/'. str_replace('.html','', \$parts[1]) .'/'. \$CourseType['course_type']; ?>  \n";
		$current .= "<?Php set_site_review_location(substr(\$cUrl, 1));  /* set the location of the variable \$cUrl */ ?> \n";
		$current .= "<?Php get_header(); ?> \n";
				
		$current .= "<div id=\"content-left\"> \n";
		$current .= "\t <div class=\"inner\"> \n";
		$current .= "\t <div class=\"main-content\"><!-- added new CSS class to make generic compound pages --> \n";
		$current .= "\t <div id=\"golf-courses\">\n";
		$current .= "\t <?php \$atype = get_site_review_category('featured'); ?>\n";
		$current .= "\t <?php site_review_get_articles( \$atype ); ?>\n";
		$current .= "\t <?php if( \$Article[\$atype]) { ?> \n";
		$current .= "\t \t<div id=\"article-page\" class=\"article-page clearfix\"> \n";
		$current .= "\t \t \t <div class=\"item-box\"> \n";
		$current .= "\t \t \t <<?php echo \$Article['tag'][\$atype]; ?> class=\"title lwr\"><?php echo \$Article['title'][\$atype]; ?></<?php echo \$Article['tag'][\$atype]; ?>> \n";
		$current .= "\t \t \t \t<?php foreach(\$Article[\$atype] as \$art) { ?> \n";
		$current .= "\t \t \t \t<div class=\"text\"><?php echo site_review_trim_content(\$art->content, 50); ?></div> \n";
		$current .= "\t \t \t \t<div class=\"text-right\"><a class=\"arrow-right\" href=\"<?php echo \$art->permalink; ?>\">read more</a> &nbsp;</div> \n";
		$current .= "\t \t \t \t<div class=\"product-share meta clearfix\"> \n";
		$current .= "\t \t \t \t \t<div class=\"sbg f\"><iframe src=\"http://www.facebook.com/plugins/like.php?<?php echo \$art->permalink; ?>&amp;send=false&amp;layout=button_count&amp;width=90&amp;show_faces=false&amp;action=like&amp;colorscheme=light&amp;font&amp;height=21\" scrolling=\"no\" frameborder=\"0\" style=\"border:none; overflow:hidden; width:90px; height:21px;\" allowTransparency=\"true\"></iframe></div> \n";
		$current .= "\t \t \t \t \t<div class=\"sbg\"><a href=\"http://twitter.com/share\" class=\"twitter-share-button\" data-url=\"<?php echo \$art->permalink; ?>\" data-count=\"horizontal\">Tweet</a><script type=\"text/javascript\" src=\"http://platform.twitter.com/widgets.js\"></script></div> \n";
		$current .= "\t \t \t \t \t<div class=\"sbg\"><div class=\"g-plusone\" data-size=\"medium\" href=\"<?php echo \$art->permalink; ?>\"></div></div> \n";
		$current .= "\t \t \t \t \t<div class=\"sbg cc l\"><a href=\"<?php echo \$art->permalink; ?>#comments\"><img style=\"margin-bottom:-4px;\" src=\"<?php bloginfo('template_url') ?>/images/comments-icon.png\" /> <?php comments_number( '0 Comments', '1 Comment', '% Comments' ); ?></a></div> \n";
		$current .= "\t \t \t \t</div> <?php } ?> </div> </div><?php } ?>  \n";
		$current .= "\t<h1><?php echo \$CourseType['course_type_name']; ?> <span class=\"lwr\">in the United States</span></h1>  \n";
		$current .= "\t<p class=\"subheadertext\">Select a state on the map or use our finder or our state listings below to see a list of <?php echo \$CourseType['course_type_name']; ?>.</p>  \n";
		$current .= "\t<p><img src=\"<?php bloginfo('template_url') ?>/images/states.png\" usemap=\"#states\" /></p>  \n";
		$current .= "\t\t\t<map id=\"states\" name=\"states\">  \n";
		$current .= "\t\t\t\t\t<area href=\"<?php echo \$cUrl; ?>/New-Mexico.html\" shape=\"poly\" alt=\"New Mexico\" coords=\"235,218,233,235,232,248,231,264,230,279,228,280,207,278,189,277,189,280,180,278,170,278,170,283,161,283,161,274,163,256,166,235,169,213,170,205,188,207,205,209,221,211,235,212,236,211\" title=\"New Mexico\" /> \n";
		$current .= "\t\t\t\t\t<area href=\"<?php echo \$cUrl; ?>/Arizona.html\" shape=\"poly\" alt=\"Arizona\" coords=\"98,255,101,254,101,252,102,250,99,249,100,245,102,242,104,238,106,236,109,234,106,231,106,228,105,226,105,222,106,220,107,208,109,208,112,209,112,210,115,204,116,199,117,198,142,202,170,206,167,232,163,259,161,281,150,281,145,281,137,279,128,273,112,265,98,258\" title=\"Arizona\" /> \n";
		$current .= "\t\t\t\t\t<area href=\"<?php echo \$cUrl; ?>/Utah.html\" shape=\"poly\" alt=\"Utah\" coords=\"122,165,125,149,129,129,158,134,156,147,176,151,170,205,140,201,116,197\" title=\"Utah\" /> \n";
		$current .= "\t\t\t\t\t<area href=\"<?php echo \$cUrl; ?>/Nevada.html\" shape=\"poly\" alt=\"Nevada\" coords=\"59,157,69,116,99,123,128,129,116,200,114,208,112,212,109,210,106,211,106,223,106,224,79,186\" title=\"Nevada\" /> \n";
		$current .= "\t\t\t\t\t<area href=\"<?php echo \$cUrl; ?>/Colorado.html\" shape=\"poly\" alt=\"Colorado\" coords=\"204,153,228,156,248,157,246,212,235,212,205,209,170,205,176,151\" title=\"Colorado\" /> \n";
		$current .= "\t\t\t\t\t<area href=\"<?php echo \$cUrl; ?>/Wyoming.html\" shape=\"poly\" alt=\"Wyoming\" coords=\"164,93,201,98,232,101,228,156,175,151,156,147\" title=\"Wyoming\" /> \n";
		$current .= "\t\t\t\t\t<area href=\"<?php echo \$cUrl; ?>/North-Dakota.html\" shape=\"poly\" alt=\"North Dakota\" coords=\"235,47,269,48,298,49,300,63,301,67,303,84,304,86,304,91,233,88\" title=\"North Dakota\" /> \n";
		$current .= "\t\t\t\t\t<area href=\"<?php echo \$cUrl; ?>/South-Dakota.html\" shape=\"poly\" alt=\"South Dakota\" coords=\"285,130,230,129,233,88,304,91,303,92,301,95,306,99,305,123,304,128,306,128,305,132,304,135,300,134,298,132,290,133,290,133\" title=\"South Dakota\" /> \n";
		$current .= "\t\t\t\t\t<area href=\"<?php echo \$cUrl; ?>/Nebraska.html\" shape=\"poly\" alt=\"Nebraska\" coords=\"317,172,247,170,248,157,228,155,231,129,285,131,291,134,292,132,298,133,300,134,304,135,306,138,307,143,310,147,310,151,312,153,312,161,314,164\" title=\"Nebraska\" /> \n";
		$current .= "\t\t\t\t\t<area href=\"<?php echo \$cUrl; ?>/Kansas.html\" shape=\"poly\" alt=\"Kansas\" coords=\"325,213,245,212,248,170,317,172,319,173,322,173,322,176,320,177,320,179,323,181,325,184\" title=\"Kansas\" /> \n";
		$current .= "\t\t\t\t\t<area href=\"<?php echo \$cUrl; ?>/Montana.html\" shape=\"poly\" alt=\"Montana\" coords=\"126,32,169,39,201,43,236,47,232,100,186,96,164,93,163,99,161,99,161,96,160,97,159,98,154,98,151,98,150,98,147,97,145,99,143,93,143,92,141,93,141,87,140,85,139,80,135,80,134,82,132,79,134,76,136,70,136,66,126,53,127,48,124,45\" title=\"Montana\" /> \n";
		$current .= "\t\t\t\t\t<area href=\"<?php echo \$cUrl; ?>/Idaho.html\" shape=\"poly\" alt=\"Idaho\" coords=\"111,69,113,73,114,75,109,84,105,88,104,93,106,94,104,99,99,123,129,129,158,134,163,99,161,99,161,97,159,98,150,98,147,97,145,99,144,93,141,92,141,88,139,81,135,80,135,81,132,80,134,75,137,65,134,64,126,53,127,48,124,45,127,32,118,30,112,61\" title=\"Idaho\" /> \n";
		$current .= "\t\t\t\t\t<area href=\"<?php echo \$cUrl; ?>/Oregon.html\" shape=\"poly\" alt=\"Oregon\" coords=\"29,106,69,117,98,122,106,93,104,92,104,88,110,83,114,75,110,70,103,68,93,66,84,66,76,66,70,65,68,64,58,64,55,61,56,55,51,52,46,51,43,66,37,78,33,88,28,94\" title=\"Oregon\" /> \n";
		$current .= "\t\t\t\t\t<area href=\"<?php echo \$cUrl; ?>/California.html\" shape=\"poly\" alt=\"California\" coords=\"71,251,101,253,101,250,99,249,100,245,103,240,108,234,106,231,106,228,105,222,59,155,69,117,28,106,27,114,26,119,21,125,21,130,25,135,24,139,22,140,22,148,28,159,28,162,30,165,32,161,35,162,39,164,40,164,40,166,37,166,33,164,32,165,34,172,32,172,30,167,29,174,30,177,34,179,34,182,31,185,31,188,39,205,41,207,39,215,47,218,53,222,54,226,60,227,61,231,63,231,70,240\" title=\"California\" /> \n";
		$current .= "\t\t\t\t\t<area href=\"<?php echo \$cUrl; ?>/Washington.html\" shape=\"poly\" alt=\"Washington\" coords=\"67,18,118,30,111,64,111,70,94,66,76,66,69,65,68,63,58,64,55,60,54,54,46,50,47,48,48,47,50,45,50,44,47,43,48,42,52,42,49,38,48,28,46,25,49,24,48,22,50,22,56,26,61,27,64,30,61,34,58,36,58,38,59,37,62,35,67,32,63,36,64,39,62,39,59,40,58,41,61,42,63,41,66,40,67,36,69,33,69,27,69,22\" title=\"Washington\" /> \n";
		$current .= "\t\t\t\t\t<area href=\"<?php echo \$cUrl; ?>/Oklahoma.html\" shape=\"poly\" alt=\"Oklahoma\" coords=\"328,259,322,256,321,256,319,256,315,255,310,257,307,258,303,256,299,256,299,258,293,255,290,257,286,253,282,253,275,251,273,248,270,249,267,246,266,219,235,218,235,211,325,213,326,224,328,231\" title=\"Oklahoma\" /> \n";
		$current .= "\t\t\t\t\t<area href=\"<?php echo \$cUrl; ?>/Texas.html\" shape=\"poly\" alt=\"Texas\" coords=\"227,321,216,314,209,307,209,302,207,296,201,292,190,280,189,277,229,280,235,218,266,219,267,245,269,248,274,248,275,251,280,252,284,253,287,253,291,257,292,255,297,257,300,256,305,256,308,259,311,257,318,256,321,256,329,259,330,260,334,261,335,282,337,284,338,289,340,293,340,301,338,303,339,307,337,310,336,313,333,314,328,315,327,312,324,313,324,316,324,320,322,321,321,324,313,327,312,328,309,327,306,328,303,326,303,329,306,331,303,331,301,330,302,333,298,334,297,336,298,338,294,338,294,340,296,341,295,345,291,344,290,345,292,348,293,348,293,350,292,350,295,358,296,365,294,366,291,362,285,363,279,360,275,358,271,349,270,341,266,340,260,332,258,323,248,311,233,311\" title=\"Texas\" /> \n";
		$current .= "\t\t\t\t\t<area href=\"<?php echo \$cUrl; ?>/Minnesota.html\" shape=\"poly\" alt=\"Minnesota\" coords=\"317,49,298,49,301,67,303,83,305,90,301,94,301,96,306,99,305,123,358,122,356,117,350,113,349,110,340,106,342,96,339,93,344,88,345,79,353,71,359,64,369,60,357,59,356,57,352,60,349,60,344,56,344,58,341,57,338,53,333,53,333,54,330,55,328,53,322,53,319,44,317,44\" title=\"Minnesota\" /> \n";
		$current .= "\t\t\t\t\t<area href=\"<?php echo \$cUrl; ?>/Iowa.html\" shape=\"poly\" alt=\"Iowa\" coords=\"364,134,366,139,369,140,369,145,368,148,362,150,361,152,362,156,361,160,359,161,357,165,353,162,314,163,311,152,310,150,309,145,306,141,306,138,304,134,305,128,305,123,357,123,359,130,361,132\" title=\"Iowa\" /> \n";
		$current .= "\t\t\t\t\t<area href=\"<?php echo \$cUrl; ?>/Missouri.html\" shape=\"poly\" alt=\"Missouri\" coords=\"374,217,375,221,373,224,380,225,381,219,382,217,385,215,385,209,382,208,381,204,380,200,370,193,373,188,372,185,366,184,366,180,358,171,357,165,352,162,314,163,317,172,322,173,322,175,320,180,325,184,325,213,326,220\" title=\"Missouri\" /> \n";
		$current .= "\t\t\t\t\t<area href=\"<?php echo \$cUrl; ?>/Arkansas.html\" shape=\"poly\" alt=\"Arkansas\" coords=\"367,266,367,262,366,257,367,256,368,249,372,246,373,241,377,237,377,231,380,226,380,225,374,224,376,220,374,217,326,220,328,231,328,258,330,260,334,260,334,268\" title=\"Arkansas\" /> \n";
		$current .= "\t\t\t\t\t<area href=\"<?php echo \$cUrl; ?>/Louisiana.html\" shape=\"poly\" alt=\"Louisiana\" coords=\"362,314,358,310,363,311,366,314,369,313,371,317,374,319,376,318,377,315,381,317,382,318,383,315,381,314,381,311,385,313,390,317,393,318,394,316,389,313,387,313,390,309,389,306,387,308,384,307,386,305,381,306,378,306,378,303,380,301,387,304,386,300,384,298,385,293,363,294,364,284,368,280,368,277,370,275,367,267,334,268,334,282,337,283,337,288,341,293,340,300,338,303,338,311,341,312,343,308,345,308,345,312,349,313,355,314\" title=\"Louisiana\" /> \n";
		$current .= "\t\t\t\t\t<area href=\"<?php echo \$cUrl; ?>/Tennessee.html\" shape=\"poly\" alt=\"Tennessee\" coords=\"398,213,447,209,466,206,466,210,464,212,460,214,459,216,455,216,454,219,449,223,444,224,443,227,440,229,440,232,398,237,376,238,377,231,380,226,381,219,384,216,398,216\" title=\"Tennessee\" /> \n";
		$current .= "\t\t\t\t\t<area href=\"<?php echo \$cUrl; ?>/Mississippi.html\" shape=\"poly\" alt=\"Mississippi\" coords=\"401,300,397,301,396,299,393,300,390,301,389,303,387,304,386,300,384,297,385,292,363,294,364,285,369,278,371,275,367,266,367,261,367,255,369,248,373,246,373,240,377,236,377,238,399,238,399,282\" title=\"Mississippi\" /> \n";
		$current .= "\t\t\t\t\t<area href=\"<?php echo \$cUrl; ?>/Alabama.html\" shape=\"poly\" alt=\"Alabama\" coords=\"410,291,439,287,438,277,437,278,437,271,439,270,438,267,426,234,399,237,399,285,401,300,404,299,405,294,407,295,407,299,409,299,409,301,410,301,411,299,413,298,412,294\" title=\"Alabama\" /> \n";
		$current .= "\t\t\t\t\t<area href=\"<?php echo \$cUrl; ?>/Georgia.html\" shape=\"poly\" alt=\"Georgia\" coords=\"454,230,452,234,458,237,467,247,471,249,477,255,482,263,483,265,486,266,485,268,483,268,482,274,482,278,481,280,481,285,476,285,475,290,473,291,472,288,442,290,439,287,438,277,437,278,437,272,440,270,425,234\" title=\"Georgia\" /> \n";
		$current .= "\t\t\t\t\t<area href=\"<?php echo \$cUrl; ?>/South-Carolina.html\" shape=\"poly\" alt=\"South Carolina\" coords=\"462,225,477,224,481,228,485,228,493,226,506,236,500,244,501,250,497,250,494,257,489,258,486,260,486,266,482,265,481,260,479,259,476,254,469,248,457,236,452,234,454,230\" title=\"South Carolina\" /> \n";
		$current .= "\t\t\t\t\t<area href=\"<?php echo \$cUrl; ?>/North-Carolina.html\" shape=\"poly\" alt=\"North Carolina\" coords=\"466,205,513,198,526,195,529,198,526,200,523,202,521,200,520,199,520,203,523,205,524,204,526,205,527,203,529,203,529,206,531,207,530,203,533,204,533,206,530,211,524,209,523,211,518,210,519,213,526,213,525,216,522,217,519,216,521,219,525,218,529,219,528,220,524,221,519,223,516,223,516,226,513,231,513,235,506,236,493,226,482,228,477,224,460,226,454,230,440,232,441,228,443,226,444,224,449,224,455,218,455,215,458,216,462,212,466,209\" title=\"North Carolina\" /> \n";
		$current .= "\t\t\t\t\t<area href=\"<?php echo \$cUrl; ?>/Kentucky.html\" shape=\"poly\" alt=\"Kentucky\" coords=\"392,208,392,204,396,202,397,197,401,195,404,195,406,197,409,194,411,194,413,191,418,193,419,189,421,187,422,182,428,181,428,176,433,176,436,179,448,181,449,178,453,184,454,186,461,194,454,200,453,203,447,208,397,214,397,217,385,215,385,209,387,206\" title=\"Kentucky\" /> \n";
		$current .= "\t\t\t\t\t<area href=\"<?php echo \$cUrl; ?>/Indiana.html\" shape=\"poly\" alt=\"Indiana\" coords=\"396,197,401,195,405,196,407,197,409,194,412,194,413,190,416,193,419,193,418,189,423,185,422,182,428,180,424,140,406,142,401,145,397,144,400,179,401,182,400,187,396,191\" title=\"Indiana\" /> \n";
		$current .= "\t\t\t\t\t<area href=\"<?php echo \$cUrl; ?>/Illinois.html\" shape=\"poly\" alt=\"Illinois\" coords=\"396,197,396,190,400,186,400,181,397,144,393,137,391,134,364,135,368,140,369,140,369,146,368,147,368,148,360,151,361,154,362,158,358,162,357,171,366,181,367,185,372,184,372,189,370,193,380,201,381,207,385,210,387,206,391,208,392,207,392,203,396,202\" title=\"Illinois\" /> \n";
		$current .= "\t\t\t\t\t<area href=\"<?php echo \$cUrl; ?>/Wisconsin.html\" shape=\"poly\" alt=\"Wisconsin\" coords=\"365,134,392,134,392,128,390,122,391,116,392,108,395,101,396,96,391,101,389,105,388,105,388,101,391,96,389,92,386,87,383,86,372,84,366,83,363,80,357,79,358,76,350,78,345,79,345,87,338,93,342,96,340,105,349,111,356,116,357,123,360,132\" title=\"Wisconsin\" /> \n";
		$current .= "\t\t\t\t\t<area href=\"<?php echo \$cUrl; ?>/Florida.html\" shape=\"poly\" alt=\"Florida\" coords=\"435,298,440,305,449,301,449,298,455,298,459,300,468,309,472,310,473,313,473,321,471,320,472,327,474,327,473,323,476,325,478,326,477,329,475,331,480,340,480,336,485,336,485,341,487,339,487,343,490,349,493,350,499,359,503,358,507,357,507,349,508,335,494,314,493,309,484,294,481,285,475,286,475,290,473,291,471,288,442,290,439,287,409,291,413,296,413,297,415,299,416,296,418,296,419,298,422,297,424,296,428,296,430,298,432,299\" title=\"Florida\" /> \n";
		$current .= "\t\t\t\t\t<area href=\"<?php echo \$cUrl; ?>/Ohio.html\" shape=\"poly\" alt=\"Ohio\" coords=\"460,134,466,131,470,151,468,162,465,167,462,167,462,169,461,169,460,170,460,174,457,173,456,181,454,182,452,183,449,178,447,181,440,180,435,179,433,176,428,177,424,140,440,139,442,141,442,141,444,141,450,141,452,139,455,139\" title=\"Ohio\" /> \n";
		$current .= "\t\t\t\t\t<area href=\"<?php echo \$cUrl; ?>/Pennsylvania.html\" shape=\"poly\" alt=\"Pennsylvania\" coords=\"466,132,470,154,472,161,520,151,522,148,524,149,526,147,527,145,529,143,523,137,523,133,525,126,522,125,518,120,474,129,473,125\" title=\"Pennsylvania\" /> \n";
		$current .= "\t\t\t\t\t<area href=\"<?php echo \$cUrl; ?>/New-York.html\" shape=\"poly\" alt=\"New York\" coords=\"498,107,493,107,493,108,491,108,491,107,482,108,478,110,478,112,481,113,481,116,473,126,474,128,518,121,523,126,536,129,536,131,537,130,537,128,538,125,536,114,535,103,533,94,531,94,529,82,526,75,513,78,505,88,506,89,502,93,505,93,505,96,503,96,503,98,505,99,505,101,502,102\" title=\"New York\" /> \n";
		$current .= "\t\t\t\t\t<area href=\"<?php echo \$cUrl; ?>/Vermont.html\" shape=\"poly\" alt=\"Vermont\" coords=\"543,71,527,75,532,94,534,94,536,104,542,103,543,103,542,94,541,94,542,88,543,88,543,79,545,77,545,75,544,74\" title=\"Vermont\" /> \n";
		$current .= "\t\t\t\t\t<area href=\"<?php echo \$cUrl; ?>/New-Hampshire.html\" shape=\"poly\" alt=\"New Hampshire\" coords=\"548,67,545,67,544,75,545,75,545,78,543,81,543,88,542,89,541,95,542,94,543,103,549,102,553,101,558,97,559,97,559,93,556,91\" title=\"New Hampshire\" /> \n";
		$current .= "\t\t\t\t\t<area href=\"<?php echo \$cUrl; ?>/New-York.html\" shape=\"poly\" alt=\"New York\" coords=\"536,135,538,135,549,128,553,126,552,125,546,128,540,130,538,131\" title=\"New York\" /> \n";
		$current .= "\t\t\t\t\t<area href=\"<?php echo \$cUrl; ?>/Connecticut.html\" shape=\"poly\" alt=\"Connecticut\" coords=\"552,111,554,119,549,121,549,123,543,124,537,129,538,125,537,119,536,114\" title=\"Connecticut\" /> \n";
		$current .= "\t\t\t\t\t<area href=\"<?php echo \$cUrl; ?>/Rhode-Island.html\" shape=\"poly\" alt=\"Rhode Island\" coords=\"554,119,558,118,557,111,553,110,552,112\" title=\"Rhode Island\" /> \n";
		$current .= "\t\t\t\t\t<area href=\"<?php echo \$cUrl; ?>/Massachusetts.html\" shape=\"poly\" alt=\"Massachusetts\" coords=\"561,115,564,111,566,116,566,113,571,111,571,109,569,110,565,111,562,105,559,105,559,103,560,100,557,98,553,101,536,104,536,114,556,110\" title=\"Massachusetts\" /> \n";
		$current .= "\t\t\t\t\t<area href=\"<?php echo \$cUrl; ?>/Maine.html\" shape=\"poly\" alt=\"Maine\" coords=\"565,78,570,77,571,69,572,69,573,71,574,71,574,68,576,71,578,70,577,68,579,68,581,66,583,65,584,63,587,63,587,61,585,61,585,57,583,57,582,57,580,52,577,52,570,34,565,31,563,34,559,35,558,33,556,33,555,39,553,42,552,56,551,60,551,64,547,66,556,91,559,93,560,89,562,81,564,81\" title=\"Maine\" /> \n";
		$current .= "\t\t\t\t\t<area href=\"<?php echo \$cUrl; ?>/New-Jersey.html\" shape=\"poly\" alt=\"New Jersey\" coords=\"531,159,533,159,533,155,536,149,536,146,537,138,533,139,533,134,535,133,535,129,525,126,523,132,523,137,529,142,524,149,524,154,530,156\" title=\"New Jersey\" /> \n";
		$current .= "\t\t\t\t\t<area href=\"<?php echo \$cUrl; ?>/West-Virginia.html\" shape=\"poly\" alt=\"West Virginia\" coords=\"468,196,472,196,476,192,479,191,479,186,484,176,488,176,489,171,494,166,494,162,497,161,500,163,501,161,500,158,497,157,494,158,493,161,490,159,485,165,483,165,483,159,472,161,469,153,468,162,465,167,462,167,462,168,460,170,460,173,460,174,457,173,456,178,456,181,452,183,465,197,467,197\" title=\"West Virginia\" /> \n";
		$current .= "\t\t\t\t\t<area href=\"<?php echo \$cUrl; ?>/Virginia.html\" shape=\"poly\" alt=\"Virginia\" coords=\"466,205,525,195,528,193,527,191,527,190,524,190,524,192,520,192,521,189,522,189,523,189,523,188,514,187,521,185,522,183,520,182,513,177,520,180,520,177,513,174,508,173,508,169,509,168,510,166,507,165,504,163,503,161,501,161,500,162,500,163,497,161,494,162,494,166,489,171,488,176,484,176,479,185,479,191,476,192,472,196,468,196,467,197,465,197,461,193,454,200,453,203,447,208,445,209,466,206\" title=\"Virginia\" /> \n";
		$current .= "\t\t\t\t\t<area href=\"<?php echo \$cUrl; ?>/Maryland.html\" shape=\"poly\" alt=\"Maryland\" coords=\"518,173,514,168,517,170,515,167,515,161,513,160,513,159,515,158,518,156,518,154,520,154,519,157,517,159,520,169,521,171,522,171,522,170,523,170,524,173,525,174,527,175,527,177,527,178,526,178,526,180,526,181,525,181,525,185,527,185,527,182,529,181,529,176,529,173,531,170,531,169,531,168,527,168,527,169,525,169,524,164,523,160,522,156,522,153,520,151,483,159,483,165,485,165,490,159,493,161,494,158,497,157,501,159,501,161,503,161,506,165,510,166,509,170,514,173\" title=\"Maryland\" /> \n";
		$current .= "\t\t\t\t\t<area href=\"<?php echo \$cUrl; ?>/Delaware.html\" shape=\"poly\" alt=\"Delaware\" coords=\"531,168,531,167,531,165,530,164,530,162,528,162,524,155,524,149,522,148,520,151,522,153,522,157,524,165,525,168,525,169,526,169,527,169,527,168\" title=\"Delaware\" /> \n";
		$current .= "\t\t\t\t\t<area href=\"<?php echo \$cUrl; ?>/Michigan.html\" shape=\"poly\" alt=\"Michigan\" coords=\"438,137,439,131,442,128,442,124,445,124,445,120,440,106,436,106,433,113,429,113,429,108,431,107,433,103,433,96,432,94,432,90,421,86,417,85,415,86,415,90,416,90,416,91,414,91,413,92,412,99,410,99,410,95,409,95,408,98,406,98,405,107,404,111,404,118,407,122,408,125,408,133,405,141,411,141,436,139,437,137,439,135,437,137\" title=\"Michigan\" /> \n";
		$current .= "\t\t\t\t\t<area href=\"<?php echo \$cUrl; ?>/Michigan.html\" shape=\"poly\" alt=\"Michigan\" coords=\"401,87,402,87,402,84,402,84,408,84,409,82,409,82,415,82,416,83,418,82,423,82,429,82,429,80,424,80,422,79,421,77,421,75,419,75,419,76,414,76,414,72,411,72,411,72,410,73,408,74,403,74,402,75,399,77,399,78,395,78,391,78,391,77,387,73,383,73,383,74,381,74,381,71,386,66,386,65,384,65,373,74,372,75,369,75,363,80,366,83,385,86,391,96,392,96,395,88,395,86,397,86,397,88,398,88,398,86,402,86,401,86,401,86\" title=\"Michigan\" /> \n";
		$current .= "\t\t\t\t\t<area href=\"<?php echo \$cUrl; ?>/Michigan.html\" shape=\"poly\" alt=\"Michigan\" coords=\"373,62,378,59,378,57,377,57,377,58,375,58,375,59,373,59\" title=\"Michigan\" /> \n";
		$current .= "\t\t\t\t\t<area href=\"<?php echo \$cUrl; ?>/Hawaii.html\" shape=\"poly\" alt=\"Hawaii\" coords=\"322,415,322,419,320,421,320,424,321,433,325,435,330,430,335,429,337,426,332,418,324,415\" title=\"Hawaii\" /> \n";
		$current .= "\t\t\t\t\t<area href=\"<?php echo \$cUrl; ?>/Hawaii.html\" shape=\"poly\" alt=\"Hawaii\" coords=\"309,404,313,408,313,409,315,409,317,408,319,406,314,403,311,404,310,402,309,402,309,405,309,405\" title=\"Hawaii\" /> \n";
		$current .= "\t\t\t\t\t<area href=\"<?php echo \$cUrl; ?>/Hawaii.html\" shape=\"circle\" alt=\"Hawaii\" coords=\"305,406,2\" title=\"Hawaii\" /> \n";
		$current .= "\t\t\t\t\t<area href=\"<?php echo \$cUrl; ?>/Hawaii.html\" shape=\"poly\" alt=\"Hawaii\" coords=\"300,399,304,399,304,400,308,400,306,402,305,401,300,401\" title=\"Hawaii\" /> \n";
		$current .= "\t\t\t\t\t<area href=\"<?php echo \$cUrl; ?>/Hawaii.html\" shape=\"poly\" alt=\"Hawaii\" coords=\"286,392,290,392,294,396,294,398,288,398,286,395\" title=\"Hawaii\" /> \n";
		$current .= "\t\t\t\t\t<area href=\"<?php echo \$cUrl; ?>/Hawaii.html\" shape=\"poly\" alt=\"Hawaii\" coords=\"262,386,267,389,269,388,270,384,269,383,265,383,263,385\" title=\"Hawaii\" /> \n";
		$current .= "\t\t\t\t\t<area href=\"<?php echo \$cUrl; ?>/HHawaii.html\" shape=\"poly\" alt=\"Hawaii\" coords=\"255,387,258,387,258,389,256,390\" title=\"Hawaii\" /> \n";
		$current .= "\t\t\t\t\t<area href=\"<?php echo \$cUrl; ?>/Alaska.html\" shape=\"poly\" alt=\"Alaska\" coords=\"112,335,118,334,122,325,127,323,131,322,137,318,136,317,138,317,139,319,145,319,147,321,178,322,197,388,202,389,203,387,214,394,217,387,228,391,240,402,249,404,252,408,251,411,244,410,245,414,243,415,235,408,232,408,230,408,224,403,220,399,218,397,213,396,205,393,204,391,200,393,197,393,196,392,191,392,189,393,186,395,177,389,173,389,170,390,171,396,171,398,167,397,163,403,161,405,159,404,162,401,159,400,161,393,165,391,165,388,167,386,164,387,162,389,157,393,154,398,153,403,150,405,150,407,154,408,153,410,151,412,150,415,144,419,142,423,133,428,133,431,128,432,126,435,124,434,121,434,119,437,113,441,108,442,109,438,112,439,118,436,122,432,126,433,128,428,134,425,137,420,137,418,139,413,141,407,137,410,135,408,133,408,133,412,126,409,123,410,121,398,123,393,119,395,118,402,115,402,110,397,110,395,115,395,114,393,112,393,111,388,110,390,108,391,109,386,114,377,119,378,121,375,126,374,126,364,123,364,121,366,108,366,108,362,110,359,106,359,106,357,111,354,116,351,116,355,124,356,128,354,128,351,123,351,123,348,118,347,117,344,111,338\" title=\"Alaska\" /> \n";
		$current .= "\t\t\t\t\t<area href=\"<?php echo \$cUrl; ?>/Alaska.html\" shape=\"poly\" alt=\"Alaska\" coords=\"89,373,94,373,96,375,97,376,99,375,99,374,97,373,95,371,89,370,88,371\" title=\"Alaska\" /> \n";
		$current .= "\t\t\t\t\t<area href=\"<?php echo \$cUrl; ?>/Alaska.html\" shape=\"poly\" alt=\"Alaska\" coords=\"101,400,103,401,107,401,108,399,106,397,105,397,103,398,102,398,101,399\" title=\"Alaska\" /> \n";
		$current .= "\t\t\t\t\t<area href=\"<?php echo \$cUrl; ?>/Alaska.html\" shape=\"rect\" alt=\"Alaska\" coords=\"62,451,65,453\" title=\"Alaska\" /> \n";
		$current .= "\t\t\t\t\t<area href=\"<?php echo \$cUrl; ?>/Alaska.html\" shape=\"rect\" alt=\"Alaska\" coords=\"52,452,53,454\" title=\"Alaska\" /> \n";
		$current .= "\t\t\t\t\t<area href=\"<?php echo \$cUrl; ?>/Alaska.html\" shape=\"poly\" alt=\"Alaska\" coords=\"89,450,92,447,94,447,94,448,91,450,91,451,89,451,89,449\" title=\"Alaska\" /> \n";
		$current .= "\t\t\t\t\t<area href=\"<?php echo \$cUrl; ?>/Alaska.html\" shape=\"poly\" alt=\"Alaska\" coords=\"150,418,154,417,158,416,159,417,158,419,156,420,155,421,153,422,151,423,149,420\" title=\"Alaska\" /> \n";
		$current .= "\t\t\t\t\t<area href=\"<?php echo \$cUrl; ?>/Alaska.html\" shape=\"poly\" alt=\"Alaska\" coords=\"156,412,159,411,159,412,158,413,157,413,157,415,155,415\" title=\"Alaska\" /> \n";
		$current .= "\t\t\t\t\t<area href=\"<?php echo \$cUrl; ?>/Washington.html\" shape=\"rect\" alt=\"Washington\" coords=\"61,21,64,24\" title=\"Washington\" /> \n";
		$current .= "\t\t\t\t\t<area href=\"<?php echo \$cUrl; ?>/California.html\" shape=\"rect\" alt=\"California\" coords=\"41,223,43,225\" title=\"California\" /> \n";
		$current .= "\t\t\t\t\t<area href=\"<?php echo \$cUrl; ?>/California.html\" shape=\"rect\" alt=\"California\" coords=\"45,224,47,225\" title=\"California\" /> \n";
		$current .= "\t\t\t\t\t<area href=\"<?php echo \$cUrl; ?>/California.html\" shape=\"rect\" alt=\"California\" coords=\"58,236,60,237\" title=\"California\" /> \n";
		$current .= "\t\t\t\t\t<area shape=\"default\" nohref=\"nohref\" alt=\"\" />\n\t</map> \n";
		$current .= "\t<p class=\"subheadertext\">Select a state below to see a list of <?php echo \$CourseType['course_type_name']; ?></p> \n";
		$current .= "\t<ul class=\"states\"><?php ";
		$current .= "\t\t\t\t\t for( \$i = 0; \$i < 51; \$i++ ) { ";				
		$current .= "\t\t\t\t\t\t\t\t \$l = (\$i == 50) ? ' class=\"lst\"' : ''; \n";
		$current .= "\t\t\t\t\t\t\t\t?><li<?php echo \$l; ?>><a href=\"<?php cr_state_url(cr_state(\$i,1), \$CourseType); ?>\"><?php echo cr_state(\$i,1); ?></a></li> \n";
		$current .= "<?php } ?> </ul> <ul class=\"states\"> \n";
		$current .= "\t\t\t<li><a href=\"<?php cr_state_url(cr_state(51,1),\$CourseType); ?>\"><?php echo cr_state(51,1); ?></a></li> \n";
		$current .= "\t\t\t<li class=\"lst\"><a href=\"<?php cr_state_url(cr_state(52,1),\$CourseType); ?>\"><?php echo cr_state(52,1); ?></a></li> </ul> \n \n";
		$current .= "\t<div class=\"course-search\"> \n";
		$current .= "\t\t<form method=\"get\" action=\"<?php bloginfo('url') ?>/site-location-search.html\"> \n";
		$current .= "\t\t\t<table cellspacing=\"0\"> \n";
		$current .= "\t\t\t\t<tr><td align='center'><div id='find_header_image'>FIND A <?php echo strtoupper(\$CourseType['singuler_type_name']); ?></div></td></tr> \n";
		$current .= "\t\t\t\t<tr><td class=\"txt\">Enter <?php echo ucwords(\$CourseType['singuler_type_name']); ?> Name &nbsp;<input type=\"text\" name=\"search\" /></td></tr> \n";
		$current .= "\t\t\t\t<tr><td>or</td></tr> \n";
		$current .= "\t\t\t\t<tr><td class=\"txt\">Enter Zip Code &nbsp;<input type=\"text\" name=\"zip\" style=\"width:100px;\" /></td></tr> \n";
		$current .= "\t\t\t\t<tr><td><input class=\"sub\" type=\"submit\" value=\"Find <?php echo ucwords(\$CourseType['singuler_type_name']); ?>\" /></td></tr> \n";
		$current .= "\t</table> \n </form>\n </div> \n";
			
		$current .= "\t<?php cr_site_deals_widget(); ?> \n";
		$current .= "\t <?php \$term_cat[] = \$CourseType['course_type_name']; ?> \n";
		$current .= "\t <?php site_review_recent_articles('Recent Articles & Reviews on '. ucwords(\$CourseType['singuler_type_name']), \$term_cat, 5); ?> \n \n </div>";
				
		$current .= "</div><!-- end main content --> \n";
		$current .= "</div><!-- end inner --> \n";
		$current .= "</div><!-- end content left --> \n";
		$current .= "<?php get_sidebar(); ?> \n";
		$current .= "<?php get_footer(); ?>";
		@file_put_contents($file, $current);
	}
	
	/*  
		creation of golf products text php file passing through four variable -
		$filename - when text file created it's need a name.
		$t_header - it is template header associated with wordpress page.
		$NodeID   - category have nodeid to filter categories and sub categories.
		$category_path - need a path from Venice database to set a location/page urls.	
	*/
	
	function CreateGolfProductsTextFile( $filename, $t_header, $NodeID, $category_path ) {
		
		$slug	= preg_replace('/ /', '-', strtolower($t_header));
		//$file	= get_template_directory() . '/'. $filename;
		//$term_cat = get_categories_list( $subcategory, $NodeID );
		$file	= get_template_directory() . '/page-product-landing-page.php';		
		
		$current .= "<?Php /* Template Name: Product Landing Page */ ?> \n";
		$current .= "<?Php \$parts1 = explode( '/', \$_SERVER['REQUEST_URI']); ?> \n";
		$current .= "<?Php \$parts2 = explode( '.html', \$parts1[1] ); ?> \n";
		$current .= "<?Php \$url = ucwords ( str_replace('-',' ', \$parts2[0] ) ); ?> \n\n";
		$current .= "<?Php site_review_category_list();  /* get and return object array of all categories */ ?> \n";
		$current .= "<?Php \$Category =  (object)array(); ?> \n";
		$current .= "<?Php \$Category->product_count	= 0; /* initializing value 0  to product_count */ ?> \n\n";
		$current .= "<?Php \$Category->category_name 	= \$url; // Golf Clubs ?>  \n";
		$current .= "<?Php \$Category->url_safe_category_name 	= \$parts2[0]; // golf-clubs ?>  \n";
		$current .= "<?Php set_site_review_location(\$Category->url_safe_category_name); ?>  \n";
		$current .= "<?Php get_header();  ?>  \n\n\n";	
				
		$current .= "<div id=\"content-left\"> \n";
		$current .= "\t <div class=\"inner\"> \n";
		$current .= "\t <div class=\"main-content VirtualClass\"> <!-- added new CSS class to make generic compound pages --> \n"; 
		$current .= "\t <h1><?Php echo \$Category->category_name; ?></h1> <!-- Page title will changed accordingly --> \n";
				
		$current .= "<?Php site_review_get_articles('". $slug ."-featured');  /* article queue tag is also changed accordingly */ ?> \n";
		$current .= "<?Php if( \$Article['". $slug ."-featured']) { ?> \n";
		$current .= "<div id=\"article-page\" class=\"article-page clearfix\"> \n";
		$current .= "<div class=\"item-box\"> \n";
		$current .= "<<?Php echo \$Article['tag']['". $slug ."-featured']; ?> class=\"title lwr\"><?php echo \$Article['title']['". $slug ."-featured']; ?></<?php echo \$Article['tag']['". $slug ."-featured']; ?>> \n";
		$current .= "<?Php foreach(\$Article['". $slug ."-featured'] as \$art) { ?> \n";
		$current .= "<div class=\"text\"><?php echo site_review_trim_content(\$art->content,50); ?></div> \n";
		$current .= "<div class=\"text-right\"><a class=\"arrow-right\" href=\"<?php echo \$art->permalink; ?>\">read more</a> &nbsp;</div> \n";
		$current .= "<div class=\"product-share meta clearfix\"> \n";
		$current .= "<div class=\"sbg f\"><iframe src=\"http://www.facebook.com/plugins/like.php?<?php echo \$art->permalink; ?>&amp;send=false&amp;layout=button_count&amp;width=90&amp;show_faces=false&amp;action=like&amp;colorscheme=light&amp;font&amp;height=21\" scrolling='no' frameborder='0' style='border:none; overflow:hidden; width:90px; height:21px;' allowTransparency='true'></iframe></div> \n";
		$current .= "<div class='sbg'><a href=\"http://twitter.com/share\" class=\"twitter-share-button\" data-url=\"<?php echo \$art->permalink; ?>\" data-count=\"horizontal\">Tweet</a><script type=\"text/javascript\" src=\"http://platform.twitter.com/widgets.js\"></script></div> \n";
		$current .= "<div class='sbg'><div class=\"g-plusone\" data-size=\"medium\" href=\"<?php echo \$art->permalink; ?>\"></div></div> \n";
		$current .= "<div class='sbg cc l'><a href=\"<?php echo \$art->permalink; ?>#comments\"><img style=\"margin-bottom:-4px;\" src=\"<?php bloginfo('template_url') ?>/images/comments-icon.png\" /> <?php comments_number( '0 Comments', '1 Comment', '% Comments' ); ?></a></div> \n";
		$current .= "</div><?php } ?></div></div><?php } ?> \n\n";		
		$current .= "<p class=\"subheadertext\">Select the type of <?Php echo strtolower(\$Category->category_name); ?> or product you are looking for</p> \n\n";		
		$current .= "<div class=\"VirtualClass\"> \n\n";
		$current .= "<table class=\"VirtualClass-list\" cellspacing='0'> \n";
		$current .= "<tr><td><?Php \n";
		$current .= " \$NODEID = Get_NodeID_By_Path( \$Category->url_safe_category_name );	//Added By Zaved as on 26/Nov/2016 \n\n";
		$current .= "for(\$i=0;\$i<count(\$Categories);\$i++) { /* NODEID is also database driven */ \n";
		$current .= "if(strpos(\$Categories[\$i]->nodeid, \$NODEID ) !== false && \$Categories[\$i]->node_level == 3 && number_format( \$Categories[\$i]->product_count) != 0) { ?> \n";
		$current .= "<a href=\"/<?php echo \$Categories[\$i]->category_path; ?>.html\"><?php cr_category_name(\$Categories[\$i]->category_name); ?> (<?php echo number_format(\$Categories[\$i]->product_count); ?>)</a> \n";		
		$current .= "<?Php \$CatName = strtolower( \$Categories[\$i]->category_name ); ?> \n";
		$current .= "<?Php \$CatName = str_replace('&', 'and', \$CatName ); ?> \n";
		$current .= "<?Php \$BrandList[] = str_replace(' ', '-', \$CatName ); ?> \n";
		$current .= "<?Php \$Category->product_count += \$Categories[\$i]->product_count; ?> \n";		
		$current .= "<?Php } } ?></td><td valign='top'></td></tr></table> \n\n";
		
		$current .= "<p class='subheadertext'>or select the brand you are interested in</p>	\n";
		$current .= "<div class=\"Virtual-Class-list\">Select the brand alphabetically \n";
		$current .= "<?Php site_review_brand_list(1, \$Category->url_safe_category_name, \$BrandList); /*** print out brand list alphabetically */ ?> \n";
		$current .= "</div></div><!-- end product landing page --> \n\n";
		$current .= "<?Php site_review_product_accessory_list( \$Categories, \$BrandList); ?>  \n ";
		
		$current .= "<?php if(\$Products) { ?> \n ";
		$current .= "<div id=\"products\" class=\"product-list\">";
		$current .= "<div class=\"product-list-nav clearfix\"><div><?Php \$GRclass->cr_category_pagination(\$Category, \$_GET['pg']); ?></div> \n";
		$current .= "Showing <?Php \$GRclass->cr_category_showing(\$Category, \$_GET['pg']); ?> of <?Php echo \$Category->product_count; ?> <?php echo \$Category->category_name; ?> \n";
		$current .= "</div><table cellspacing=\"0\"> \n";
		$current .= "<thead><tr><th>Product name</th><th>Brand</th><th align=\"center\" colspan=\"2\">Reviews</th></tr></thead>\n";
		$current .= "<?Php foreach(\$Products as \$Product) { ?> \n ";
		$current .= "<tr>";
		$current .= "<td class=\"pn\"><a href=\"<?Php cr_product_url(\$Product); ?>\"><?php echo \$Product->product_name; ?></a></td> \n";
		$current .= "<td class=\"brand\"><a href=\"<?Php cr_brand_url(\$Product); ?>\"><?php echo \$Product->manufacturer_name; ?></a></td> \n";
		$current .= "<td class=\"rate\"><?Php echo \$Product->average_rating; ?> of 5</td> \n ";
		$current .= "<td class=\"rev\"> \n";
		$current .= "<div><a href=\"<?Php cr_product_url(\$Product); ?>#reviews\"><?php echo \$Product->total_reviews; ?> Reviews</a></div> \n";
		$current .= "<img src=\"<?Php echo cr_rating_image(\$Product->average_rating); ?>\" /> \n";
		$current .= "</td></tr> <?Php } ?></table> \n";
		$current .= "<div class=\"product-list-nav clearfix\"><div><?php \$GRclass->cr_category_pagination(\$Category, \$_GET['pg']); ?></div> \n";
		$current .= "Showing <?php \$GRclass->cr_category_showing(\$Category, \$_GET['pg']); ?> of <?php echo \$Category->product_count; ?> <?php echo \$Category->category_name; ?>  \n";
		$current .= "</div></div><?Php } ?><!-- end product list --> \n";
		
		$current .= "<?php cr_site_deals_widget(1); ?> \n";
		$current .= "<?php site_review_recent_articles('Recent Articles & Reviews on '.\$Category->category_name, \$BrandList, 5); ?> \n\n";
		
		$current .= "</div><!-- end main content --> \n";
		$current .= "</div><!-- end inner --> \n";
		$current .= "</div><!-- end content left --> \n";
		$current .= "<?php get_sidebar(); ?> \n";
		$current .= "<?php get_footer(); ?>";
		@file_put_contents($file, $current);
	}
	
	if( isset($_POST['BtnTransferTemplate']) ) {
		
		$TransferTemplate = $_POST['TransferTemplate'];		
		if( $_POST['TransferTemplate'] == '' ) {
			
			echo "<div id='message' class='updated fade'><p>Please select the template to transfer files.</p></div>";
		}
		else {
		
			TransferTemplate($TransferTemplate);			
		}
	}
	
	//Trigger when it gets hit from button "Create Category Landing Pages";
		
	if( isset($_POST['createspages']) ) {
				
		$msg = "<div id='message' class='updated fade'><p>Please select the template for FILENAMES.</p></div>";
		
		if( count($getallmaincategory) > 0 ) {
			
			foreach( $getallmaincategory as $k => $page ) {
				
				$filename	= $page['category_name'];
				$NodeID		= $page['nodeid'];
				$filename2	= preg_replace('/ /', '-', strtolower($filename));
				$filename2	= 'page-'.$filename2.'.php';
				$AllFile	.= $filename2 .'  ' ;
				$AllFileName = $page['category_name'] . "  ";
				$r = 'tplname_'.$page['categoryid'];
				
				if( $_POST[$r] == '' ) {
			
					echo preg_replace('/FILENAMES/', $AllFileName, $msg );
				}
			}
			//echo "<div id='message' class='updated fade'><p>Files ( " .$AllFile.") has been re-initialized.</p></div>";
		}
	}
	else if( isset($_POST['Initialize']) ) {
		
		$CATID = $_POST['CATID'];
		$r = 'tplname_'.$CATID;
		$page = $ClsPage->GetPage( $CATID);		
					
		if( $_POST[$r] == '' ) {
			
			echo "<div id='message' class='updated fade'><p>Please select the template file for ".$page->category_name.".</p></div>";
		}
		else if ( $_POST[$r] == 'product' ) {
			
			$filename	= $page->category_name;
			$NodeID		= $page->nodeid;
			$slug	= preg_replace('/ /', '-', strtolower($filename));
			//$filename2	= 'page-'.$slug.'.php';
			$filename2	= 'page-product-landing-page.php';
			CreateGolfProductsTextFile( $filename2, $filename, $NodeID, $page->category_path );
			echo "<div id='message' class='updated fade'><p>File (".$filename2.") has been re-initialized.</p></div>";
		}
		else if ( $_POST[$r] == 'location' ) {
			
			$filename	= $page->category_name;
			$NodeID		= $page->nodeid;
			$slug	= preg_replace('/ /', '-', strtolower($filename));
			//$filename2	= 'page-'.$slug.'.php';
			$filename2	= 'page-location-landing-page.php';
			CreateGolfCoursesTextFile( $filename2, $filename, $NodeID, $page->category_path );
			echo "<div id='message' class='updated fade'><p>File (".$filename2.") has been re-initialized.</p></div>";
		}
	}
?>