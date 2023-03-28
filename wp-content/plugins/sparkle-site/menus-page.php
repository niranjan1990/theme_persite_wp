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
	
	include_once( ABSPATH.'wp-content/plugins/venice_class.php');
	require $_SERVER['DOCUMENT_ROOT'] . '/wp-config-extra.php';

	/* creating a class object $pages */
	$ClsPage = new ClassPages( get_option("dbconnection") );
	
	$PSiteName = get_option( 'PSiteName' );
	$SSiteName = get_option( 'SSiteName' );
	$TSiteName = get_option( 'TSiteName' );
	
	if( $_GET['action'] == 'allclear' || $_GET['action'] == 'GenerateMenus' ) {
		
		update_option( 'TemplateList',  $_GET['PMNODE_LVL'].":". $_GET['SMNODE_LVL'].":". $_GET['TMNODE_LVL'] );
	}
	
	if( get_option( 'PSiteName' ) && !get_option( 'SSiteName' ) && !get_option( 'TSiteName' ) ) {
		
		$PSite = explode(":", $PSiteName);
		$getallmaincategory = $ClsPage->GetAllMainCategory( $_GET['PChannelID'], $_GET['PMNODE_LVL'], $_GET['SChannelID'], $_GET['SMNODE_LVL'], $_GET['TChannelID'], $_GET['TMNODE_LVL'] );
		$subcategory = $ClsPage->Get_Sub_Pages( $_GET['PChannelID'], $_GET['PSNODE_LVL'], $_GET['SChannelID'], $_GET['SSNODE_LVL'], $_GET['TChannelID'], $_GET['TSNODE_LVL'] );
	}
	if( get_option( 'PSiteName' ) && get_option( 'SSiteName' ) && !get_option( 'TSiteName' ) ) {
		
		$PSite = explode(":", $PSiteName);
		$SSite = explode(":", $SSiteName);
		
		$getallmaincategory = $ClsPage->GetAllMainCategory( $_GET['PChannelID'], $_GET['PMNODE_LVL'], $_GET['SChannelID'], $_GET['SMNODE_LVL'], $_GET['TChannelID'], $_GET['TMNODE_LVL'] );
		$subcategory = $ClsPage->Get_Sub_Pages( $_GET['PChannelID'], $_GET['PSNODE_LVL'], $_GET['SChannelID'], $_GET['SSNODE_LVL'], $_GET['TChannelID'], $_GET['TSNODE_LVL'] );			
	}

	if( get_option( 'PSiteName' ) && get_option( 'SSiteName' ) && get_option( 'TSiteName' ) ) {
		
		$PSite = explode(":", $PSiteName);
		$SSite = explode(":", $SSiteName);
		$TSite = explode(":", $TSiteName);
		
		$getallmaincategory = $ClsPage->GetAllMainCategory( $_GET['PChannelID'], $_GET['PMNODE_LVL'], $_GET['SChannelID'], $_GET['SMNODE_LVL'], $_GET['TChannelID'], $_GET['TMNODE_LVL'] );
		$subcategory = $ClsPage->Get_Sub_Pages( $_GET['PChannelID'], $_GET['PSNODE_LVL'], $_GET['SChannelID'], $_GET['SSNODE_LVL'], $_GET['TChannelID'], $_GET['TSNODE_LVL'] );		
	}
	
	function CheckHomeExists( $menu_name ) {
	
		global $wpdb;
		$pname = $wpdb->get_var( "Select count(wp_posts.post_name) as pname From wp_posts Where post_type = 'nav_menu_item' And post_status = 'publish' And post_name = 'home'" );
		if( $pname == 0 ) {	return true; }
		else { return false;}
	}
	
	function CheckWPPageExists ( $menu_name ) {
	
		global $wpdb;		
		
		$menu_name = category_name_replace($menu_name);
		//$menu_name = str_replace(" ", "-", strtolower($menu_name) );
		$results = $wpdb->get_results( "Select ID, post_name, post_parent From wp_posts Where post_type = 'page' And post_name = '".$menu_name."'" );
		$id = ( $results[0]->ID ) ? $results[0] : 0;
		return $id;
	}
	
	function check_page_exsits($getallmaincategory) {
		
		if( count($getallmaincategory) > 0 ) {
		
			foreach( $getallmaincategory as $k => $page ) {
				
				$slug	= preg_replace('/ /', '-', strtolower($page->category_name) );
				$file	= get_template_directory() . '/'. "page-".$slug.".php";
				$test	+= ( file_exists($file) ) ? file_exists($file) : 0;
			}
		}
		
		$style = ( $test > 0) ? " " : "disabled style='color:#BBBBBB; border-color:#666666; '";
		return $style;
	}
	
	function check_menu_exists($getallmaincategory) {
		
		//$style = check_page_exsits($getallmaincategory);
		if ( is_nav_menu( 'menu_header' )) {
			
			$m .= "<input type='button' ".$style."  class='button' name='ReGenerateMenus' onClick='ReGenerateBtn()' value='Regenerate Menus' />&nbsp;";
			$d = "";
		}
		else {
			
			$m .= "<input type='submit' ".$style." class='button' name='GenerateMenus' onClick=\"GenerateMenus('')\" value='Generate Menus' />&nbsp;";
			$d = "disabled style='color:#BBBBBB; border-color:#666666; '";
		}
		
		$r .= "<tr><td colspan='4'>&nbsp;</td></tr>";
		$r .= "<tr><td align='center' colspan='2'>";
		$r .= $m;
		$r .= "<input type='button' class='button' ".$d."  onClick='COMEditMenus()' name='EditMenus' value='Edit Menus' />&nbsp;";
		$r .= "<input type='button' class='button' onClick='COMEditMenusProperties()' name='EditMenusProperties' value='Edit Menus with Properties' />&nbsp;";
		$r .= "</td></tr>";
		
		return $r;
	}
	
	function wp_get_nav_menu_parent_items_list( $a, $default = '' ) {
	
		$menu_exists = wp_get_nav_menu_object( 'menu_header' );
		$menuid_id = $menu_exists->term_id;
		$menu_items = wp_get_nav_menu_items( $menuid_id );
		
		if( count($menu_items) > 0 ) {
		
			foreach ($menu_items as $menu_item) {
				
				$parent_id = get_post_meta( $menu_item->ID, '_menu_item_menu_item_parent', true );
				if ( $menu_item->menu_item_parent == 0 && $parent_id == 0 ) {
					
					$selected = ( $default == $menu_item->ID ) ? " selected='selected'" : '' ;
					$action = (  $a == 'edit' ) ? $menu_item->ID . "|" . get_post_meta( $menu_item->ID, '_menu_item_object_id', true) : $menu_item->ID;
					$options .= "<option value='". $action ."' $selected > " . $menu_item->title . " </option>";
				}
			}
		}
		return $options;
	}
	
	function page_template_dropdown_box( $default = '' ) {
		
		echo "\n\t<option value=''>Select Template</option>";
		$templates = get_page_templates();
		ksort( $templates );
		foreach (array_keys( $templates ) as $template )
		
			: if ( $default == $templates[$template] )			
				$selected = " selected='selected'";				
			else			
				$selected = '';
				
		echo "\n\t<option value='".$templates[$template]."' $selected>$template</option>";
		endforeach;
		//echo "\n\t<option value='new'>Create New Page Template</option>";
	}
	
	function wp_get_nav_menu_parent_position() {
	
		$current_menu_id = $_POST['parent_menu_id'];
		$menu_exists = wp_get_nav_menu_object( 'menu_header' );
		$menuid_id = $menu_exists->term_id;
		$menu_items = wp_get_nav_menu_items( $menuid_id );
		$parent_id = get_post_meta( $current_menu_id->ID, '_menu_item_menu_item_parent', true );
		if ( $menu_item->menu_item_parent == 0 && $parent_id == 0 ) {
		
			echo $menu_item->ID;
		}		
	}
	
	
	function wp_get_nav_menu_parent_items_list_pos( $parent_pos_id ) {
	
		$Parents = array();
		$menu_exists = wp_get_nav_menu_object( 'menu_header' );
		$menuid_id = $menu_exists->term_id;
		$menu_items = wp_get_nav_menu_items( $menuid_id );
		
		if( count($menu_items) > 0 && $menuid_id != NULL ) {
			
			foreach ($menu_items as $menu_item) {
				
				$parent_id = get_post_meta( $menu_item->ID, '_menu_item_menu_item_parent', true );
				if ( $menu_item->menu_item_parent == 0 && $parent_id == 0 ) {
				
					$Parents[] = $menu_item->ID;
				}
			}
			
			$new_p_id = 0;
			$type = $_POST['option'];
			
			if( $type == 'BEFORE' OR $type == 'AFTER' ) {
				
				foreach( array_keys( $Parents ) as $index => $key ) {
		
					$index = ( $type == 'BEFORE' ) ? $index-1 : $index+1;
					if ( $parent_pos_id == $Parents[$key] && array_key_exists($index, $Parents) != false ) {
				
						$new_p_id = $Parents[$index];
					}
				}
			
				return $new_p_id = ($new_p_id == 0) ? $parent_pos_id: $new_p_id;
			}
			else {
			
				return $parent_pos_id;
			}			
		}
	}
	
	function getHome() {
	
		global $wpdb;		
		$homeid = get_option( 'homeid' );
		$home	= $wpdb->get_var( "Select wp_posts.post_title as pname From wp_posts Where post_type = 'nav_menu_item' And post_status = 'publish' And ID = '$homeid'" );
		return $home."|".$homeid ;
	}
	
	function InsertMenuHeader() {
	
		$menu_name = "menu_header";
		$theme = get_current_theme(); //first get the current theme
		$mods = get_option("mods_$theme"); //get theme's mods
			
		// Check if Top menu exists and make it if not
		if ( !is_nav_menu( 'menu_header' )) {
		
			$menu_id = wp_create_nav_menu( $menu_name );
		}
		else {
		
			$menu_obj = get_term_by( 'name', $menu_name, 'nav_menu' );
			$menu_id = $menu_obj->term_id;
		}
		
		$mods['nav_menu_locations']['primary'] = $menu_id; //update mods with menu id at theme location
		update_option("mods_$theme", $mods);
	}
	
	function wp_get_nav_menu_child_items_list_pos( $parent_id, $child_pos_id ) {
		
		$Childern = array();
		$menu_exists = wp_get_nav_menu_object( 'menu_header' );
		$menuid_id = $menu_exists->term_id;
		$menu_items = wp_get_nav_menu_items( $menuid_id );
		
		if( count($menu_items) > 0 ) {
			
			foreach ($menu_items as $menu_item ) {
				
				$p_id = get_post_meta( $menu_item->ID, '_menu_item_menu_item_parent', true );
				if ( $parent_id == $menu_item->menu_item_parent && $p_id != 0  ) {
				
					$Childern[] = $menu_item->ID;
				}
			}	
			
			$new_child_id = 0;
			$type =  $_POST['option'];
			
			if( $type == 'BEFORE' OR $type == 'AFTER' ) {
	
				foreach( array_keys( $Childern ) as $index => $key ) {
					
					$index = ( $type == 'BEFORE' ) ? $index-1 : $index+1;
					
					if ( $child_pos_id == $Childern[$key] && array_key_exists($index, $Childern) != false ) {

						$new_child_id = $Childern[$index];
					}
				}
				
				return $new_p_id = ($new_child_id == 0) ? $child_pos_id: $new_child_id;
			}
			else {
			
				return $child_pos_id;
			}
		}
	}
	
	function wp_get_parent_menu_order() {
	
		$menu_order = array();
		$menu_exists = wp_get_nav_menu_object( 'menu_header' );
		$menuid_id = $menu_exists->term_id;
		$menu_items = wp_get_nav_menu_items( $menuid_id );
		
		if( count($menu_items) > 0 ) {
		
			foreach ($menu_items as $menu_item) {
				
				$parent_id = get_post_meta( $menu_item->ID, '_menu_item_menu_item_parent', true );
				if ( $menu_item->menu_item_parent == 0 && $parent_id == 0 ) {
					 
					$menu_order[] = $menu_item->ID;
				}
			}
		}
		return $menu_order;
	}
	
	function wp_get_child_menu_order( $parent_id ) {
		
		$menu_order = array();
		$menu_exists = wp_get_nav_menu_object( 'menu_header' );
		$menuid_id = $menu_exists->term_id;
		$menu_items = wp_get_nav_menu_items( $menuid_id );
		
		if( count($menu_items) > 0 ) {
		
			foreach ($menu_items as $menu_item ) {
				
				$p_id = get_post_meta( $menu_item->ID, '_menu_item_menu_item_parent', true );
				if ( $parent_id == $menu_item->menu_item_parent && $p_id != 0  ) {
					
					$menu_order[] = $menu_item->ID;
				}
			}
		}
		return $menu_order;
	}
	
	function array_search_after( $pid, $m ) {
		
		$key = array_search( $pid, $m );
		return $key+1;
	}
	
	function GetParentURL( $post_title ) {
	
		$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
		$domainName = $_SERVER['HTTP_HOST'];
		$post_title = str_replace(" ","-", $post_title);
		return $protocol.$domainName . '/'. strtolower($post_title) . '.html';
	}
	
	function GetChildURL( $Prnt_post_title, $child_post_title, $homeid, $CID ) {
	
		$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
		$domainName = $_SERVER['HTTP_HOST'];
		
		$Prnt_post_title = str_replace(" ","-", $Prnt_post_title);
		$child_post_title = str_replace(" ","-", $child_post_title);
		$permalink = get_permalink( $CID, $leavename );
		
		if( $homeid == get_option("homeid") ) {
		
			//return $protocol.$domainName . '/'. strtolower($child_post_title) . '.html';
			return $permalink;
		}
		else {
		
			//return $protocol.$domainName . '/'. strtolower($Prnt_post_title) . '/' . strtolower($child_post_title) . '.html';
			return $permalink;
		}
	}
	
	function GetHostURL() {
	
		$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
		$domainName = $_SERVER['HTTP_HOST'];
		return $protocol.$domainName;
	}
	
	function CheckExistsChildMenu ( $ID ) {
			
		global $wpdb;
		$count = $wpdb->get_var( "Select count(*) From wp_posts Where ID = '".$ID."'" );
		$ResID = ( $count > 0 ) ? $ID : 0;
		return $ResID;
	}
	
	function category_name_replace($page) {
	
		$patterns = array('/review index/', '/bike shops/', '/other golf equipment/', '/golf bags/', '/golf balls/', '/golf shoes/' );
		$replacements = array('reviews', 'bikeshops', 'golf equipment', 'bags', 'balls', 'shoes' );
		
		$spacial_page = preg_replace($patterns, $replacements, strtolower($page) );
		return $slug = sanitize_title( preg_replace('/ /', '-', $spacial_page ) );
	}
	
	function title_name_replace($page) { // Added by Zaved as on 5 Dec 2016 only for golf equipment case.
	
		$patterns = array( 'bags', 'balls', 'shoes', 'bike shops' );
		$pre = "";
		$replacements = array( $pre.'bags', $pre.'balls', $pre.'shoes', $pre.'bikeshops' );
		return str_replace($patterns, $replacements, strtolower($page) );
	}
	
	function Create_New_Post( $page, $menu_item_object_id, $PageTemplate ) {
		
		$slug = category_name_replace($page);
		$post = array (	'post_title' => strtoupper($page), 'post_name'=> strtolower($slug), 'post_content' => '', 'post_type' => 'page', 'post_status' => 'publish', 'post_parent' => $menu_item_object_id, 'post_author' => 1 );
		$post_id = wp_insert_post( $post, $wp_error );
		add_post_meta( $post_id , "_wp_page_template", $PageTemplate, true );
		add_post_meta( $post_id , "_edit_last", 1, true );
		$postid = ($post_id ) ? $post_id : '';
		
		return $postid;
	}	
	
	function Create_New_Custom_Menu( $menu_header, $page, $exturl ) {
	
		InsertMenuHeader();
		$cls = category_name_replace($page);
		$cls = $cls.'-class';
		$menu_exists = wp_get_nav_menu_object( $menu_header );
		$menuid_id = $menu_exists->term_id;
		$menu = array( 'menu-item-type' => 'custom', 'menu-item-url' => strtolower($exturl) ,'menu-item-title' => strtoupper($page), 'menu-item-status' => 'publish', 'menu-item-classes' => strtolower($cls) );
		return wp_update_nav_menu_item( $menuid_id, 0, $menu );
	}
	
	function Create_New_Menu( $menu_header, $page, $post_id, $PID, $class ) {
	
		InsertMenuHeader();
		$cls = category_name_replace($page);
                $menutitle = str_replace("-", " ", $page);
		$cls = $cls.'-'.$class.'-class';
		$menu_exists = wp_get_nav_menu_object( $menu_header );
		$menuid_id = $menu_exists->term_id;		
		$menu = array( 'menu-item-object' => 'page', 'menu-item-type' => 'post_type', 'menu-item-classes' => strtolower($cls), 'menu-item-url' => home_url( strtolower($page) ) ,'menu-item-title' => strtoupper($menutitle), 'menu-item-status' => 'publish', 'menu-item-object-id' => $post_id, 'menu-item-parent-id' => $PID );
		return wp_update_nav_menu_item( $menuid_id, 0, $menu );
	}
	function Create_New_Child_Custom_Menu( $menu_header, $page, $post_id, $PID, $class, $exturl ) {
	
		InsertMenuHeader();
		$cls = category_name_replace($page);
		$cls = $cls.'-'.$class.'-class';
		$menu_exists = wp_get_nav_menu_object( $menu_header );
		$menuid_id = $menu_exists->term_id;		
		$menu = array( 'menu-item-object' => 'page', 'menu-item-type' => 'custom', 'menu-item-classes' => strtolower($cls), 'menu-item-url' => $exturl ,'menu-item-title' => strtoupper($page), 'menu-item-status' => 'publish', 'menu-item-object-id' => $post_id, 'menu-item-parent-id' => $PID );
		return wp_update_nav_menu_item( $menuid_id, 0, $menu );
	}
	function To_Get_Menu_Order( $MENUID, $PositID, $Parents, $Type, $MENUTYPE ) {
		
		global $wpdb;// define wordpress global varibale $wpdb.		
			
		$OrderReset = array();		
		$MenuPos = array_search( $MENUID, $Parents ); // Current Menu POS ID For Editor
		$PosiPos = array_search( $PositID, $Parents ); // Current Parent Menu POS ID From Drop list Box.
		
		if( $Type == 'BEFORE') {
		
			if(  $PosiPos == $MenuPos  ) { $OrderReset = $Parents;	}
			else if( $PosiPos == 0) { $OrderReset = $Parents; }
			else if ( $MenuPos == ($PosiPos-1)  ) { $OrderReset = $Parents; }
			else {
		
				unset( $Parents[$MenuPos] );
				$OrderReset = array_values($Parents);
				if( $PosiPos < $MenuPos )
					array_splice( $OrderReset, $PosiPos, 0, $MENUID );
				if( $PosiPos > $MenuPos )
					array_splice( $OrderReset, $PosiPos-1, 0, $MENUID );
			}
		}
		
		if( $Type == 'AFTER') {
	
			if(  $PosiPos == $MenuPos  ) { $OrderReset = $Parents;	}
			//else if( $PosiPos == (count($Parents)-1) ) { $OrderReset = $Parents; }
			else if ( $MenuPos == ($PosiPos+1)  ) { $OrderReset = $Parents; }
			else {
						
				unset( $Parents[$MenuPos] );
				$OrderReset = array_values($Parents);
				if( $PosiPos < $MenuPos )
					array_splice( $OrderReset, $PosiPos+1, 0, $MENUID );
				if( $PosiPos > $MenuPos )
					array_splice( $OrderReset, $PosiPos, 0, $MENUID );
			}		
		}
		
		foreach ( $OrderReset as $k => $ID ) {				
					
			$wpdb->query( "update wp_posts SET menu_order = $k Where ID = $ID ");
		}		
	}	
	
	if ( $_POST['SubmitHome'] ) {
		
		global $wpdb;
		if( $_POST['txthome'] == '' ) {
			
			echo '<div id="message" class="updated fade"><p>Home menu empty.</p></div>';
		}
		else {
			
			$wpdb->query("update wp_posts set post_title = '". $_POST['txthome'] ."' Where ID = ". $_POST['hdnhome'] );
			echo '<div id="message" class="updated fade"><p>Home menu updated.</p></div>';
		}
	}
	
	if ( $_POST['SubmitAddSearch'] ) {
	
		CheckGolfCourseSearch();
		echo '<div id="message" class="updated fade"><p>Search page created.</p></div>';
	}
	
	if( $_POST['BtnParentDone'] || $_POST['BtnParentAddAnother'] ) {
			
		$page = trim($_POST['txtmenubox']);
		
		if( $_POST['txtmenubox'] == '' ) {
	
			echo '<div id="message" class="updated fade"><p>Please enter the parent menu name.</p></div>';
		}
		else {
			
			$menu_header	= "menu_header";
			$PageTemplate	= ( $_POST['chkboxurl'] ) ? "" : $_POST['parent_drop_page_temp'];
			$exturl			= $_POST['txtexternalurl'];				
			
			if( $_POST['chkboxurl'] ) {
				
				$navid = Create_New_Custom_Menu( $menu_header, $page, $exturl );
				//echo '<div id="message" class="updated fade"><p>New custom menu has been added.</p></div>';
			}
			else {
					
				$postid = Create_New_Post( $page, 0, $PageTemplate );
				$navid	= Create_New_Menu( $menu_header, $page, $postid, 0, 'parent' );
			}
			
			if( isset($_POST['option']) ) {
				
				$Parents  = wp_get_parent_menu_order();
				To_Get_Menu_Order( $navid, $_POST['parent_menu_id'], $Parents, $_POST['option'], 'PARENT' );
			}
			
			echo '<div id="message" class="updated fade"><p>New parent menu has been added.</p></div>';
		}
		
		update_option( 'rewrite_rules', '' );
	
	} // first if
	else if( $_POST['BtnChildDone'] || $_POST['BtnChildAddAnother'] ) {
	
		if( $_POST['txtmenubox'] == '' ) {
		
			echo '<div id="message" class="updated fade"><p>Please enter the child menu name</p></div>';
		}
		else {
			
			$menu_header = "menu_header";
			$page = trim($_POST['txtmenubox']);

			$PID = explode('|', $_REQUEST['seleditparentmenu']);

				//$PageTemplate	= ( $_POST['chkboxurl'] ) ? "" : $_POST['parent_drop_page_temp'];
				$exturl			= $_POST['txtexternalurl'];				

				
			$menu_item_object_id = ( $PID[0] == get_option("homeid") ) ? 0 : get_post_meta( $PID[0], '_menu_item_object_id', true);
			
			if( $_POST['chkboxurl'] )
			{
				$PageTemplate = "";
			}
			else
			{
				$PageTemplate = ( $_POST['parent_drop_page_temp'] == '' ) ? "default" : $_POST['parent_drop_page_temp'];
			}
			
			$ID = CheckWPPageExists( $page );
			
			if( $ID->ID > 0 || $ID > 0 && $menu_item_object_id == 0 ) {
				
				if( $ID->ID > 0 && $ID->post_parent > 0 ) {
					
					
					$childnavid = Create_New_Menu( $menu_header, $page, $ID->ID, $PID[0], 'duplicate-home-child' );
					$FoundChildMenuWithParent = array( 'ID' => $childnavid, 'post_parent' => $ID->post_parent  );
					wp_update_post( $FoundChildMenuWithParent );
					echo '<div id="message" class="updated fade"><p style="color:green">Warning: This newly created child menu found and set to associated parent.</p></div>';
				}
				else {
					

					if( $ID->post_parent == 0 )
					{
						
						$childnavid = Create_New_Menu( $menu_header, $page, $ID->ID, $PID[0], 'duplicate-home-parent' );
						echo '<div id="message" class="updated fade"><p style="color:red">Warning: New home child menu has been added.But duplicate parent menu found.</p></div>';
					}
					else 
					{
					
						$postid		= Create_New_Post( $page, $menu_item_object_id, $PageTemplate );
						$childnavid = Create_New_Menu( $menu_header, $page, $postid, $PID[0], 'duplicate-home-child' );
						echo '<div id="message" class="updated fade"><p style="color:red">Warning: New home child menu has been added.But duplicate child menu found.</p></div>';
					}
				}
			}
			else 
			{
	
				if( $_POST['chkboxurl'] ) 
				{

				$childnavid = Create_New_Child_Custom_Menu( $menu_header, $page, $postid, $PID[0], 'parent-child', $exturl);
				echo '<div id="message" class="updated fade"><p>New Custom Child menu has been added.</p></div>';
					
				}
				else 
				{
					$postid		= Create_New_Post( $page, $menu_item_object_id, $PageTemplate );
					if( $ID->ID == 0 &&  $menu_item_object_id == 0) 
					{
						$childnavid = Create_New_Menu( $menu_header, $page, $postid, $PID[0], 'new-home-child' );
						echo '<div id="message" class="updated fade"><p style="color:red">Warning: This newly created child menu does not have any parent.</p></div>';
					}
					else 
					{
						$childnavid = Create_New_Menu( $menu_header, $page, $postid, $PID[0], 'parent-child' );
						echo '<div id="message" class="updated fade"><p>New child menu has been added.</p></div>';
					}
				}
			}
			
			if( isset($_REQUEST['option']) ) {
				
				$Child  = wp_get_child_menu_order( $PID[0] );
				$selchildmenu = explode('|', $_REQUEST['selchildmenu']);
				To_Get_Menu_Order( $childnavid, $selchildmenu[0], $Child, $_REQUEST['option'], 'CHILD' );
			
			} //childoption
		}
		
		update_option( 'rewrite_rules', '' );
	}
	else if( isset($_GET['cid']) ) {
		
		global $wpdb;
		$CID = get_post_meta( $_GET['cid'], "_menu_item_object_id", true  );
		$MID = get_post_meta( $_GET['mid'], "_menu_item_object_id", true  );
		$Prnt_post_title = $wpdb->get_var( "Select post_title From wp_posts Where ID = " . $MID );
		$child_post_title = $wpdb->get_var( "Select post_title From wp_posts Where ID = " . $CID );
		$childurl = GetChildURL( $Prnt_post_title, $child_post_title, $_GET['mid'], $CID );
		$child_meta_values = get_post_meta( $CID, "_wp_page_template", true  );



	
		$IDS = explode('|', $_GET['cid'] );			
		$PID = get_post_meta( $IDS[0], "_menu_item_object_id", true  );
		$PostTitle = $wpdb->get_row( "Select post_title From wp_posts Where ID = " . $PID );		
		$post_title = $PostTitle->post_title;
		$purl = GetParentURL($post_title);
		
		$parent_meta_values = get_post_meta( $PID, "_wp_page_template", true );
		$parent_external_url = get_post_meta( $PID, "_menu_item_url", true );
		
		if( GetHostURL() ==  $parent_external_url ) {
			
			$prnt_template_disabled	= "disabled='disabled'" ;
			$prnt_disabled	= "disabled='disabled'";
			$prnt_checked	= "";
		}
		else {
			
			$prnt_template_disabled	= ($parent_external_url) ? "disabled='disabled'" : "";
			$prnt_disabled	= ($parent_external_url) ? "" : "disabled='disabled'";
			$prnt_checked	= ($parent_external_url) ? "checked='checked'" : "";
		}
	}
	else if( isset($_GET['id']) ) { // Edit parent menu - Action
			
		global $wpdb;
		
		$IDS = explode('|', $_GET['id'] );			
		$PID = get_post_meta( $IDS[0], "_menu_item_object_id", true  );
		$PostTitle = $wpdb->get_row( "Select post_title From wp_posts Where ID = " . $PID );		
		$post_title = $PostTitle->post_title;
		$purl = GetParentURL($post_title);
		
		$parent_meta_values = get_post_meta( $PID, "_wp_page_template", true );
		$parent_external_url = get_post_meta( $PID, "_menu_item_url", true );
		
		if( GetHostURL() ==  $parent_external_url ) {
			
			$prnt_template_disabled	= "disabled='disabled'" ;
			$prnt_disabled	= "disabled='disabled'";
			$prnt_checked	= "";
		}
		else {
			
			$prnt_template_disabled	= ($parent_external_url) ? "disabled='disabled'" : "";
			$prnt_disabled	= ($parent_external_url) ? "" : "disabled='disabled'";
			$prnt_checked	= ($parent_external_url) ? "checked='checked'" : "";
		}
	}

	else if ($_POST['BtnParentEditDone']) { // EDIT Parent Menu Form Details HERE
	
		global $wpdb;
		$mname = trim($_REQUEST['txtmenubox']);
		$menuname = str_replace(" ", "-", strtolower($mname) );
		if( $_POST['chkboxurl'] ) {
		
			$txtexternalurl = trim($_REQUEST['txtexternalurl']);
			update_post_meta( $_POST['pid'] , "_menu_item_url", $txtexternalurl );// changing the extrenal url if checkbox is on.
		}
		else {
		
			$PageTemplate = ( $_POST['parent_drop_page_temp'] == '' ) ? "default" : $_POST['parent_drop_page_temp'];
			update_post_meta( $_POST['pid'] , "_wp_page_template", $PageTemplate );//changing the template file if file selected file are diffrent.
		}
		
		if( isset($_POST['option']) ) {
			
			$MenuID = explode('|', $_POST['menu_id'] );
			$EditParents  = wp_get_parent_menu_order();
			To_Get_Menu_Order( $MenuID[0], $_POST['selparentbox'], $EditParents, $_POST['option'], 'EDITPARENT' );
		}
		
		$wpdb->query( "update wp_posts set post_title = '". strtoupper($mname) ."', post_name = '". $menuname ."' Where ID = ". $_POST['pid'] ); //changing the POST TITLE name.
		update_option( 'rewrite_rules', '' );
		
		echo '<div id="message" class="updated fade"><p>Menu has been updated.</p></div>';
	}
	else if ( $_POST['BtnChildEditDone'] ) {	//Edit Child Menu Form
		
		global $wpdb;
		$mname = trim($_REQUEST['txtmenubox']);
		$url = parse_url($_REQUEST['childurl']);
		if( $_POST['chkboxurl'] ) 
		{
			$txtexternalurl = trim($_REQUEST['txtexternalurl']);
			echo "<script>alert('".$txtexternalurl.$_POST['ccid']."');</script>";
			update_post_meta( $_POST['ccid'] , "_menu_item_url", $txtexternalurl );// changing the extrenal url if checkbox is on.
		}
		else
		{
			$PageTemplate = ( $_POST['parent_drop_page_temp'] == '' ) ? "default" : $_POST['parent_drop_page_temp'];
			update_post_meta( $_POST['cid'] , "_wp_page_template", $PageTemplate );
			//update_post_meta( $_POST['ccid'] , "_menu_item_url", '' );// changing the extrenal url if checkbox is on.
		}
			if( $_REQUEST['mmid'] == get_option("homeid") ) 
			{								
				$menuname = str_replace(".html", "", strtolower( $url["path"] ) );
				$menuname = str_replace("/", "", $menuname );
				$menuname = ( preg_match("/\breview\b/i", $menuname ) ) ? "reviews" : $menuname; // spacial case for reviews.html
			}
			else {
					
				$menuname = explode( "/", strtolower( $url["path"] ) );
				$menuname = str_replace( ".html", "", $menuname[2] );
				$menuname = str_replace( "/", "", $menuname );
			}
			
		$wpdb->query( "update wp_posts set post_title = '". strtoupper($mname) ."', post_name = '". sanitize_title($menuname) ."' Where ID = ". $_POST['cid'] );
		
	
		if( isset($_REQUEST['option']) ) {
			
			$EditChild  = wp_get_child_menu_order( $_POST['mmid'] );			
			To_Get_Menu_Order( $_POST['ccid'], $_REQUEST['selchildmenu'], $EditChild, $_REQUEST['option'], 'EDITCHILD' );
			
		} //childoption		
		
		update_option( 'rewrite_rules', '' );		
		echo '<div id="message" class="updated fade"><p>Menu has been updated.</p></div>';
	}
	else if ( $_GET['delaction'] == 'deleteparentmenu' ) { // Working for parent menu and it all child including uder home child.
			
		$parentid = $_GET['postid'];
		$args = array( 'post_parent' => $parentid, 'post_type' => 'page');
		$posts = get_posts( $args );
		
		if (is_array($posts) && count($posts) > 0) {
			
			// Delete all the Children of the Parent Page/menu including home child.
			foreach($posts as $post){
			
				wp_delete_post($post->ID, true);
			}
		}
		// Delete the Parent Page
		wp_delete_post($parentid, true);
		update_option( 'rewrite_rules', '' );
		
		echo '<div id="message" class="updated fade"><p>The parent menu has been deleted with its all child menus, if there is child menus.</p></div>';
	}
	else if ( $_GET['delaction'] == 'deletechildmenu' ) { // Delete Child Menu/page along with parent menu and page.		
		
		if( $_GET['p_postid'] == $_GET['c_postid'] ) { // Consider For Home Child			
			
			$OnePost = get_post( $_GET['childpostid'] );
			if ( $OnePost->post_parent == 0  ) { // remove only child of home if exists another parents
				
				wp_delete_post( $_GET['childpostid'], true ); //child_post_id[0]
				echo '<div id="message" class="updated fade"><p>The home child menus has been deleted.</p></div>';
			}
			else {
			
				if( CheckExistsChildMenu( $_GET['childid'] ) != 0 ) { // For Non Home
					
					wp_delete_post( $_GET['childid'], true ); //child_post_id[1]
					echo '<div id="message" class="updated fade"><p style="color:red">Warning:The duplicate home child menus has been deleted.</p></div>';
				}
			}
		}
		else if( $_GET['p_postid'] != $_GET['c_postid'] ) { //Consider if any other parent (golf clubs) of Child.
		
			if( CheckExistsChildMenu( $_GET['childpostid'] ) != 0 ) {
				
				wp_delete_post( $_GET['childpostid'], true );// Remove Page and all nav id
			}
			
			echo '<div id="message" class="updated fade"><p>The child menus has been deleted.</p></div>';
		}
		
		update_option( 'rewrite_rules', '' );		
	}
	else if( $_GET['action'] == 'GenerateMenus' ) { // Generate Button -Newly created menus from venice database;
			
		InsertMenuHeader();
		sparkle_plugin_activate();
		
		if( count($getallmaincategory) > 0 ) {
			
			$PSite = explode(":", $PSiteName);
			
			foreach( $getallmaincategory as $k1 => $page ) {
				
				//$WPPage		= $page->category_name;
				$WPPage		= $page->url_safe_category_name;
				$NodeID		= $page->nodeid;
				$WPPage2	= preg_replace('/ /', '-', strtolower($WPPage));
				$SubTemplate = 'page-sub-category-subpage.php';
				
				if ( intval( $page->nodeid ) == $PSite[0] ) { $Template	= $PSite[2]; } //Added By Zaved as on 01 Dec 2016
				else if ( intval( $page->nodeid ) == $SSite[0] ) { $Template	= $SSite[2]; } //Added By Zaved as on 01 Dec 2016
				
				$postid = Create_New_Post( $WPPage, 0, $Template ); // Parent Menu Creator
				$mid	= Create_New_Menu( 'menu_header', $WPPage, $postid, 0, 'parent' ); // Parent Page Creator
				
				if( count($subcategory) > 0 ) {
				
					foreach( $subcategory as $k2 => $subpage ) {
						
						if( strpos($subpage->nodeid, $NodeID ) !== false ) {
								
							if( $ClsPage->Check_if_sub_page_has_products( $subpage->category_name, $PSite[0] ) > 0 ) {
							
								$subpostid = Create_New_Post( $subpage->url_safe_category_name, $postid, 'page-sub-category-subpage.php' ); // Parent Menu Creator
								$tname = $subpage->url_safe_category_name; // Added by Zaved as on 5 Dec 2016
								Create_New_Menu( 'menu_header', $tname, $subpostid, $mid, 'parent-child' ); // Parent Page Creator
							}
						}
					}
				}
			}
			echo '<div id="message" class="updated fade"><p>New menus added from venice db.</p></div>';
		}
	}
	else if( $_GET['action'] == "allclear" ) { //Generate/Regenerate Menus - first remove all menus and recreate all from the venice database;


		$pages = get_pages( array( 'post_type' => 'page', 'number' => 200) );
		if( count($pages) > 0 ) {
				
			foreach( $pages as $page ) {
				
				// Delete's each post.
				wp_delete_post( $page->ID, true);
				// Set to False if you want to send them to Trash.
			}
		}
		
		wp_delete_post( get_option( 'homeid'), true);
		
		InsertMenuHeader();
		sparkle_plugin_activate();
		
		if( count($getallmaincategory) > 0 ) {
			
			$PSite = explode(":", $PSiteName);
			
			foreach( $getallmaincategory as $k1 => $page ) {
					
				//$WPPage		= $page->category_name;
				$WPPage		= $page->url_safe_category_name;
				$NodeID		= $page->nodeid;
				$WPPage2	= preg_replace('/ /', '-', strtolower($WPPage));				
				$SubTemplate = 'page-sub-category-subpage.php';
				
				if ( intval( $page->nodeid ) == $PSite[0] ) { $Template	= $PSite[2]; } //Added By Zaved as on 01 Dec 2016
				else if ( intval( $page->nodeid ) == $SSite[0] ) { $Template	= $SSite[2]; } //Added By Zaved as on 01 Dec 2016
				
				$postid	= Create_New_Post( $WPPage, 0, $Template ); // create all parent menus only
				$mid 	= Create_New_Menu( 'menu_header', $WPPage, $postid, 0, 'parent' );				
				
				if( count($subcategory) > 0 ) {
				
					foreach( $subcategory as $k2 => $subpage ) {					
						
						if(strpos($subpage->nodeid, $NodeID ) !== false && strpos($subpage->nodeid, $NodeID ) == 0 ) { // create all child of each parent menus.
							if($DEEPCATEGORIES == '1')
							{
								$cat_pro_count = $ClsPage->Check_if_sub_page_has_products_outdoor( $subpage->url_safe_category_name, $PSite[0] );
							}
							else
							{
								$cat_pro_count = $ClsPage->Check_if_sub_page_has_products( $subpage->category_name, $PSite[0] );
							}
							if( $cat_pro_count > 0 ) {
							
								$subpostid = Create_New_Post( title_name_replace($subpage->url_safe_category_name), $postid, 'page-sub-category-subpage.php' );
								$tname = title_name_replace( $subpage->url_safe_category_name );// Added by Zaved as on 5 Dec 2016
								Create_New_Menu( 'menu_header', $tname, $subpostid, $mid, 'parent-child' );
							}
						}
					}
				}
			}
                        
			// Generate nginx config file.
                        (new NginConfFileGenerator())->doItNow();
			
                        echo '<div id="message" class="updated fade"><p>Menus has been regenerated from venice db.</p></div>';
		}
	}	

        
        
/**
 * NGINX configuration file generator.
 */

class NginConfFileGenerator { 

    const PLACEHOLDER_LOCATION = "PLACEHOLDER_NAME__LOCATION";
    const PLACEHOLDER_SHOP = "PLACEHOLDER_SHOP";
    const PLACEHOLDER_CATEGORY = "PLACEHOLDER_NAME__CATEGORY";
    const NGINX_REGEX_EXTRA_ESCAPE_PREG_QUOTE = "/";

    function doItNow() {
        
        // only dinkan know whether it will break any functionality or not.
        to_get_mysql_sel_db();
        
        $nginxCategories = [];
        $q = "SELECT url_safe_category_name as category_name FROM categories c INNER JOIN channel_categories cc ON cc.categoryid = c.categoryid"
           . " WHERE cc.channelid = ".$_GET['PChannelID']." AND cc.node_level = " . $_GET['PMNODE_LVL'] . " ORDER BY nodeid";
	$res = mysql_query($q);
	while ($row = mysql_fetch_assoc($res)) {
            $nginxCategories[] = sanitize_title($row['category_name']);
            // TODO following clean up needed?! 
            //echo $Category_Name = ( $Category_Name != "" || $Category_Name != NULL ) ? substr($Category_Name, 0, -2) : "Nothing";
	}
        $nginxSpecialCategories = [];
        $q = "SELECT url_safe_category_name as category_name FROM categories c INNER JOIN channel_categories cc ON cc.categoryid = c.categoryid"
           . " WHERE cc.channelid = ".$_GET['SChannelID']." AND cc.node_level = " . $_GET['SMNODE_LVL'] . " ORDER BY nodeid";
	$res2 = mysql_query($q);
        while ($row = mysql_fetch_assoc($res2)) {
            $nginxSpecialCategories[] =  sanitize_title($row['category_name']);
	}
	$nginxSpecialshopCategories = [];
        $q = "SELECT url_safe_category_name as category_name FROM categories c INNER JOIN channel_categories cc ON cc.categoryid = c.categoryid"
           . " WHERE cc.channelid = ".$_GET['TChannelID']." AND cc.node_level = " . $_GET['TMNODE_LVL'] . " ORDER BY nodeid";
	$res2 = mysql_query($q);
        while ($row = mysql_fetch_assoc($res2)) {
            $nginxSpecialshopCategories[] =  sanitize_title($row['category_name']);
	}
        $this->process($nginxSpecialCategories,$nginxSpecialshopCategories, $nginxCategories);
    }
    

    /**
     * The $data array contains a set of search and replace data.
     * Search and replace it in $string and return the processed string.
     */
    function replace($string, $data) {

        $ret = $string;
        foreach($data as $searchKey=>$replaceValue) {
            $ret = str_replace($searchKey, $replaceValue, $ret);
        }
        return $ret;
    }


    /**
     * Escape each item in the $data array for nginx regex
     * and combine them as regex OR!
     */
    function orData($data) {

        $escData =[];
        foreach($data as $d) {
            $escData[] = preg_quote($d, self::NGINX_REGEX_EXTRA_ESCAPE_PREG_QUOTE);
        }
        return join("|", $escData);
    }


    /**
     * Generate nginx config from the data provided.
     */
    function process($specialCategory,$specialshopCategory, $category) {
        global $SITE_CONFIG;

        // prepare the config file data.
		
        $templateRaw = file_get_contents($SITE_CONFIG['nginx_config']['template_file']);
        $data = [
            self::PLACEHOLDER_LOCATION => $this->orData($specialCategory), 
            self::PLACEHOLDER_SHOP => $this->orData($specialshopCategory), 
            self::PLACEHOLDER_CATEGORY => $this->orData($category)
        ];
        $template = $this->replace($templateRaw, $data);

        // write the config to file.
        file_put_contents($SITE_CONFIG['nginx_config']['output_file'], $template);
        //file_put_contents($SITE_CONFIG['nginx_config']['deploy_latest'], $template);
    }
}   
