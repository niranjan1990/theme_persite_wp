<?php
/**
	Plugin Name:	MSSQL CR Image
	Plugin URI:		http://www.invenda.com
	Description:	Fetching images from CR DB and displaying in Add New Dialog box in WP.
	Author:			ConsumerReview
	Version:		1.0
	Author URI:		http://www.invenda.com
*/
		
	//MSSQL Connction String
	if( is_admin() && get_option( 'mssqlPassword' ) ) {		
		
		$MSSQLCon = @mssql_connect( get_option( 'mssqlHost' ),  get_option( 'mssqlUser' ), get_option( 'mssqlPassword' ) );
		$CRDB = @mssql_select_db( get_option( 'mssqlDB' ), $MSSQLCon );
		
		if( !$MSSQLCon ) {
			
			?><div class='updated'><p><strong> <?php _e( "Could not Connect with Server [". get_option( 'mssqlHost' ) ."] " ); ?> </strong></p></div><?Php
			
		}
		else if ( !$CRDB ) {
			
			?><div class='updated'><p><strong> <?php _e( "Could not Connect with CR DB [". get_option( 'mssqlDB' ) ."]" ); ?> </strong></p></div><?Php
		}
	}
	
	
	function CuntQuery( $SQL_QRY ) {	
		
		
		if( $_GET['imagetype'] == 'searchmedia' && !$_POST['Keyword'] == NULL) {			
		
			$SQL 	= $SQL_QRY;
		}
		else {
			
			$WPPID = Get_WP_Product_ID() ?  Get_WP_Product_ID() : 0;
			$SQL 	= "Select Product_Image From Products Where ChannelID = 7 AND Product_Image != 'NoPhoto.jpg' AND ProductID = '$WPPID'";		
		}
		
		$result	= @mssql_query($SQL);
		return mssql_num_rows($result);
	}
	
	function Get_MSSQL_CR_IMAGES () {
		
		if( isset($_GET['wppage']) ) {
						
			$start	=	(($_GET['wppage']-1)*7)+1;
			$last	= 	$_GET['wppage']*7;
		}
		else {
		
			$start	=	1;
			$last	=	7;
		}	
		
		/*
		if( $_GET['imagetype'] == 'searchall' ) {		
			
			$SQL 	= "SELECT * FROM ( SELECT Product_Image, Product_Name row_number() OVER (ORDER BY ProductID) AS rownum FROM   Products Where Product_Image like '%.%' AND ChannelID = 7 AND Product_Image != 'NoPhoto.jpg' ) AS A WHERE A.rownum BETWEEN ($start) AND ($last)";
		}		
		else if( $_GET['imagetype'] == 'currpost' ) {
		
			$pid = get_option('product_id') ?  get_option('product_id') : 0;
			$SQL 	= "Select Product_Image, Product_Name From Products Where ChannelID = 7 AND Product_Image != 'NoPhoto.jpg' AND ProductID = ". $pid;
		}
		*/
		if( $_GET['imagetype'] == 'searchmedia' && !$_POST['Keyword'] == NULL ) {
		
			$SQL 	= "Select Product_Image, Product_Name From Products Where ChannelID = 7 AND Product_Image != 'NoPhoto.jpg' AND Product_Image like '%". trim($_POST['Keyword'])."%'";
		}
		else {
			
			$WPPID = Get_WP_Product_ID() ?  Get_WP_Product_ID() : 0;
			$SQL 	= "SELECT * FROM ( SELECT Product_Image, Product_Name, row_number() OVER (ORDER BY ProductID) AS rownum FROM   Products Where Product_Image like '%.%' AND ChannelID = 7 AND Product_Image != 'NoPhoto.jpg' AND ProductID = '$WPPID' ) AS A WHERE A.rownum BETWEEN ($start) AND ($last)";
		}
		
		return $SQL;	
	}
	
	function explodepostid( $postid ) {
		
		$postid = explode("_", $postid);
		return $postid[1];
	}
	
	function Get_WP_Product_ID() {
	
		global $wpdb;
		$post = $_GET['post_id'];
		return $wpdb->get_var( $wpdb->prepare( "Select meta_value From wp_postmeta Where post_id = '$post' And meta_key = 'product_id'" ));
	}
	
	function Get_Post_Title() {
			
		global $wpdb;
		$post = $_GET['post_id'];
		$var = $wpdb->get_var( $wpdb->prepare( "SELECT post_name FROM wp_posts Where ID ='$post'" ));		
		$title = str_replace("_", "/", $var );
		return $title.'.html';
	}
		
	// Build the gallery table based on file names
    function display_media_table() {	
?>		
		<link rel="stylesheet" href="<?php echo plugin_dir_url(__FILE__) . 'css/mssql.css'; ?>" type="text/css" media="screen" charset="utf-8" />
		<form action="<?php echo get_bloginfo('url'). "/wp-admin/media-upload.php?type=image&tab=crimg&post_id=". $_GET['post_id'] ."&imagetype=searchmedia"; ?>" id='toppannel' name='frmkeyword' method="post">
			<div id='ProductAction'>
				<label style='margin-left:5px;'><b>Product Path:</b></label>
				<input type='text' size='33' title='ex: /golf-clubs/[manufacturer]/[product].html' value='<?Php echo Get_Post_Title(); ?>' name='ProductPath' />
				<label style='margin-left:10px;'><b>Product ID:</b></label>
				<input type='text' size='9' title='ex: 323018' name='Keyword' /><input type='submit' class='button' name='BtnKeyword' value='Search Media' style='margin-left:15px;' /><br />
				<div style='margin:10px 5px 0 5px;'>Media available on <b><?Php echo $_SERVER['SERVER_NAME']; ?></b> product database:</div>
			</div>
		</form>
		
		<table class="widefat" id='display_media_table' style='margin:2px 0 0 20px;'>
            <thead><tr>
				<th width='20%'>Media</th><th width='60%'>Product Name</th><th width='20%'>Action</th></tr></thead>
<?php				
				
				$SQL	= Get_MSSQL_CR_IMAGES();
				$result	= @mssql_query($SQL) or die(  _e("<div class='updated'><p><strong> A sql error occured: " . mysql_error(). "</strong></p></div>" ) );
				$cr_img = "http://www.golfreview.com/Channels/GolfReview/images/products/";
				$plugins_url = plugins_url('admin.php', __FILE__);
				
				if( mssql_num_rows($result) > 0) {
					
					$i 	= rand(1,789990);
					
					while ($row = mssql_fetch_assoc($result)) {
					
						$nonce		= wp_create_nonce( "image_editor-$i" );
						$ajax_nonce = wp_create_nonce( "set_post_thumbnail-".$_GET['post_id'] );
						
						$html = "<tr id='media-head-$i'><td><img src='".$cr_img.$row["Product_Image"]."' width='30' height='30' title='".$row['Product_Image']."' /></td>";						
						$html .= "<td>".$row['Product_Name']."</td><td align='center'><a onclick=\"imageCREdit.insertCR( $i, '".$nonce."', '".$plugins_url."', '".$cr_img.$row["Product_Image"]."', '".$row["Product_Image"]."' )\" ";
						$html .= "class='button' href='#' id='img_view_btn-$i' style='line-height:30px;'>View</a><img src='" . esc_url( admin_url( 'images/wpspin_light.gif' ) ) . "' class='imgedit-wait-spin' alt='' /></td></tr>";						
						$html .= "<tr><td colspan='3' id='imgedit-response-$i' class='imgedit-response toggelClassName'></td></tr>";
						$html .= "<tr><td colspan='3' class='image-editor' id='image-editor-$i' style='border-bottom-color:#FFF;'></td></tr>";
						$html .= "<tr class='media-button-action-$i toggelClassName' id='media-button-action-$i'><td colspan='3' style='border-top-color:#FFF;'>";
						$html .= "<input type='submit' class='button' name='send[$i]' onclick=\"imageCREdit.insertIMG( '".$cr_img.$row["Product_Image"]."', '".$row["Product_Image"]."',175,175 )\"  value='" . esc_attr__( 'Insert into Post' ) . "' />";
						$html .= "<a class='wp-post-thumbnail' id='wp-post-thumbnail-" . $i . "' href='#' onclick='WPSetAsThumbnail(\"$i\", \"$ajax_nonce\"); return false;'>" . esc_html__( "Use as featured image" ) . "</a>";						
						$html .= "<a href='#' class='del-link' onclick=\"document.getElementById('del_attachment_$i').style.display='block'; return false;\">" . __( 'Delete' ) . "</a> ";
						$html .= "<div id='del_attachment_$i' class='del-attachment' style='display:none;'>" . sprintf( __( 'You are about to delete <strong>%s</strong>.' ), $row["Product_Image"] );
						$html .= " <a href='" . wp_nonce_url( "post.php?action=delete&amp;post=$i", 'delete-attachment_' . $i ) . "' id='del[$i]' class='button'>" . __( 'Continue' ) . "</a> ";
						$html .= " <a href='#' class='button' onclick=\"this.parentNode.style.display='none';return false;\">" . __( 'Cancel' ) . "</a></div>";
						$html .= "</td></tr>";
						_e($html);
						$i++;
					}
					
					include('paging.php');
					
					if( CuntQuery( $SQL ) >= 7) {
											
						$url = get_bloginfo('url'). "/wp-admin/media-upload.php";
						_e("<tr><td colspan='3'>". doPages(5, "$url", "type=image&tab=crimg&imagetype=searchall", CuntQuery( $SQL )) ."</td></tr>");
					}
				}
				else {
				
					_e("<tr><td colspan='3'><div class='updated'><p><strong>no media associated with this post.</strong></p></div></td></tr>");
				}
				
				@mssql_close($MSSQLCon);
			?>
            <tbody></tbody>            
        </table>
<?php
    }
	
	function crimage_media_menu_tab($tabs) {
		
		//crimg&imagetype=currpost
		$newtab = array('crimg' => __('Get Media', 'crimage'));
		return array_merge($tabs, $newtab);
	}
	
	function my_scripts_method() {
		
		wp_register_script('newscript', plugins_url('/js/script.js', __FILE__) );
		wp_enqueue_script( 'newscript' );
	}
	
	function media_crimage_menu_handle_process() {
	
		media_upload_header();		
		display_media_table();	
	}	
	
	function crimage_media_menu_handle() {
		
		return wp_iframe( 'media_crimage_menu_handle_process' );
	}	
	
	add_action('media_upload_crimg', 'crimage_media_menu_handle');
	add_filter('media_upload_tabs', 'crimage_media_menu_tab');
	
	
	function crimage_main_menu() {
		
		if(function_exists('add_menu_page')) {
		
			add_menu_page(__('MSSQL Setting', 'crimage'), __('MSSQL Setting', 'crimage'), 8, 'ci_mssql_con');
			add_submenu_page( 'ci_mssql_con', __('MSSQL Setting', 'cimssql'), __('MSSQL Con Setting', 'cimssql'), 8, 'ci_mssql_con', 'ci_mssql_setting');
		}
	}
	if( is_admin() ) {
		
		add_action('admin_menu', 'crimage_main_menu', 200);		
	}
	
	add_action( 'admin_init', 'my_scripts_method');
	
	function ci_mssql_setting() { ?>
		
		<link rel="stylesheet" href="<?php echo plugin_dir_url(__FILE__) . 'css/mssql.css'; ?>" type="text/css" media="screen" charset="utf-8" />
		<div class="wrap" id='pluginWrap'>
			<h2><?php _e( 'MSSQL Server Setting', 'mssql setting' ); ?></h2>		
			<form id="build_form" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>" method="post">			
				<div id='ipaddress'><label>&nbsp;&nbsp;<?php _e("MSSQL Host: " ); ?>&nbsp;&nbsp;</label><input name='mssqlHost' size='22' type='text' value="<?php echo get_option( 'mssqlHost' ); ?>" /><?php _e(" ex: 192.168.1.1/MSSQLSERVER" ); ?><label></div>			
				<div id='MssqlUser'><label>&nbsp;&nbsp;<?php _e("User Name: " ); ?>&nbsp;&nbsp;</label><input name='mssqlUser' size='22' type='text' value="<?php echo get_option( 'mssqlUser' ); ?>" /><?php _e(" ex: mssqluser" ); ?></div>			
				<div id='MssqlPassword'><label>&nbsp;&nbsp;<?php _e("Password: " ); ?>&nbsp;&nbsp;</label><input name='mssqlPassword' size='22' type='password' /><?php _e(" ex: password" ); ?></div>
				<div id='MssqlDB'><label>&nbsp;&nbsp;<?php _e("MSSQL DB Name: " ); ?>&nbsp;&nbsp;</label><input name='mssqlDB' size='22' type='text' value="<?php echo get_option( 'mssqlDB' ); ?>" /><?php _e(" ex: Venice" ); ?></div>
				<div id='MssqlButton'><input id='testCon' name='testCon' type='Submit' value="<?php _e("Test Connection" ); ?>" />   <input type="submit" name="MSSQLSubmit" value="<?php _e('Update Options') ?>" /></div>				
			</form>
		</div>
<?php } 

	if( isset($_POST['MSSQLSubmit'])) {	
		
		if( (!trim($_POST['mssqlHost']) || !trim($_POST['mssqlUser'])) || ( !trim($_POST['mssqlPassword']) || !trim($_POST['mssqlDB']))){
		
			?><div class='updated'><p><strong> <?php _e('MSSQl Connection values required.' ); ?> </strong></p></div><?Php		
		}
		else {
		
		$dbhost = trim($_POST['mssqlHost']);
		update_option('mssqlHost', $dbhost);
		
		$dbuser = trim($_POST['mssqlUser']);
        update_option('mssqlUser', $dbuser);
		
		$dbpwd = trim($_POST['mssqlPassword']);
        update_option('mssqlPassword', $dbpwd);
		
        $dbname = trim($_POST['mssqlDB']);
        update_option('mssqlDB', $dbname);
        
		?><div class='updated'><p><strong> <?php _e('Options saved.' ); ?> </strong></p></div><?Php
		}
	}
	
	if( isset($_POST['testCon'])) {	
	
		$dbconnect = @mssql_connect( trim($_POST['mssqlHost']), trim($_POST['mssqlUser']), trim($_POST['mssqlPassword']) );
        if ( $dbconnect ) {
		
			?><div class='updated'><p><strong> <?php _e('Connected with MSSQL' ); ?> </strong></p></div><?Php		
			
		} else {
		
			?><div class='updated'><p><strong> <?php _e('Connection Failed' ); ?> </strong></p></div><?Php
		}
	}	
?>
