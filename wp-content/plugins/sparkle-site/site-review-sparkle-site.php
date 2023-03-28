<?php
/*
	Plugin Name:	Sparkle Site
	Plugin URI:		http://www.invenda.com
	Description:	It's create template, Sparkle Menus & File System.
	Author:			ConsumerReview
	Version:		1.0
	Author URI:		http://www.invenda.com
	*/
require 'aws.phar';
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;
use Aws\Common\Credentials\Credentials;


	$con1=mysql_connect(DB_HOST,DB_USER,DB_PASSWORD);
	mysql_select_db(DB_NAME);

	//echo DB_HOST.DB_USER.DB_PASSWORD.DB_NAME;
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }
  else
  {
	  //echo "connected";
  }
	function Check_WP_Entry_Exists( $menu_name, $post_type ) {

		global $wpdb;
		$pname = $wpdb->get_var( "Select count(*) as pname From wp_posts Where post_type = '".$post_type."' And post_status = 'publish' And post_name = '".$menu_name."'");
		if( $pname == 0 ) {	return true; }
		else { return false;}
	}

	function HtmlAddMenuType( $default='' ) {

		$addmenu = array('AFTER' => 'After', 'BEFORE' => 'Before' );
		$sel .= "<select id='childoption' name='option'>";

			foreach( $addmenu as $k => $v ) {

				$selected = ( $default == $k ) ? " selected='selected'" : '';
				$sel .= "<option $selected value='".$k."'>".$v."</option>";
			}

		return $sel .= "</select>";
	}

	function CheckGolfCourseSearch() {

		$post_name = 'site location search';
		$post_type = "page";
		$slug = sanitize_title( preg_replace('/ /', '-', $post_name ) );

		if ( Check_WP_Entry_Exists( $slug, $post_type ) ) {

			$post = array (	'post_title' => strtoupper($post_name), 'post_name'=> strtolower($slug), 'post_content' => '', 'post_type' => 'page', 'post_status' => 'publish', 'post_parent' => $menu_item_object_id, 'post_author' => 1 );
			$post_id = wp_insert_post( $post, $wp_error );
			add_post_meta( $post_id , "_wp_page_template", 'site_location_search.php', true );
			add_post_meta( $post_id , "_edit_last", 1, true );
		}
	}

	function InsertMenuHeaderActivate() {

		// Check if Top menu exists and make it if not
		$menu_name = "menu_header";
		// Check if Top menu exists and make it if not

		if ( !is_nav_menu( 'menu_header' )) {

			$menu_id = wp_create_nav_menu( $menu_name );
			$mods['nav_menu_locations']['primary'] = $menu_id; //update mods with menu id at theme location
			update_option("theme_mods_Site Review Theme", $mods);
		}
		else {

			$menu_obj = get_term_by( 'name', $menu_name, 'nav_menu' );
			$menu_id = $menu_obj->term_id;
			$theme = get_current_theme(); //first get the current theme
			$mods = get_option("theme_mods_$theme"); //get theme's mods
			$mods['nav_menu_locations']['primary'] = $menu_id; //update mods with menu id at theme location
			update_option("theme_mods_$theme", $mods);
		}
	}

	function AlterWP_Post() {

		global $wpdb;
		$esatags	= "CREATE TABLE `esatags` ( `tag_id` int(11) NOT NULL AUTO_INCREMENT, `tag_name` varchar(200) NOT NULL, `product_id` int(20) DEFAULT NULL,  PRIMARY KEY (`tag_id`)) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;";
		$wp_posts	= "Alter table wp_posts add column product_id bigint(20)";
		$wp_delete	= "delete from wp_posts where post_name = 'about'";

		$wpdb->query($wp_delete);
		$wpdb->query($wp_posts);
		$wpdb->query($esatags);
	}

	function Get_Data_Info() {

		$ChannelID = trim($_REQUEST['ChannelID']);
		$NodeLevel = trim($_REQUEST['NodeLevel']);

		to_get_mysql_sel_db();
		$q = "SELECT category_name FROM categories c INNER JOIN channel_categories cc ON cc.categoryid = c.categoryid WHERE cc.channelid = ".$ChannelID." AND cc.node_level = ".$NodeLevel." ORDER BY nodeid";
		$res = mysql_query($q);
		while ($row = mysql_fetch_assoc($res)) {

			$Category_Name .= $row['category_name'] . ", ";
		}

		echo $Category_Name = ( $Category_Name != "" || $Category_Name != NULL ) ? substr($Category_Name, 0, -2) : "Nothing";
	}

	function GetChannelName() {

		$ChannelID = trim($_REQUEST['ChannelID']);
		$NodeLevel = trim($_REQUEST['NodeLevel']);
		$res = mysql_query("SELECT category_name FROM categories c INNER JOIN channel_categories cc ON cc.categoryid = c.categoryid WHERE cc.channelid = ".$ChannelID." AND cc.node_level = ".$NodeLevel." ORDER BY nodeid");
		while ($row = mysql_fetch_assoc($res)) {

			$Category_Name .= $row['category_name'] . ", ";
		}

		echo $Category_Name = ( $Category_Name != "" || $Category_Name != NULL ) ? substr($Category_Name, 0, -2) : "Nothing";
	}

	function remove_chkbx_secondary_site() {

		delete_option( 'SSiteName' );
	}
	function remove_chkbx_tertiary_site() {

		delete_option( 'TSiteName' );
	}

	function PSaveChannelCategory() {

		$ChannelID	= trim($_REQUEST['ChannelID']);
		$PSiteName	= trim($_REQUEST['PSiteName']);
		$Template	= trim($_REQUEST['Template']);
		$PSite		= trim($_REQUEST['PSite']);

		update_option( 'PSiteName', $ChannelID.':'.$PSiteName.':'. $Template );
		update_option( 'PrimaryReviewDetails',  $PSite );
	}

	function SSaveChannelCategory() {
		$PChannelID	= trim($_REQUEST['PChannelID']);
		$PSiteName	= trim($_REQUEST['PSiteName']);
		$SChannelID	= trim($_REQUEST['SChannelID']);
		$SSiteName	= trim($_REQUEST['SSiteName']);
		$PSite		= trim($_REQUEST['PSite']);
		$SSite		= trim($_REQUEST['SSite']);

		$PmryTemplate	= trim($_REQUEST['PmryTemplate']); //Added Zaved as on December 01/Dec/2016
		$SnryTemplate	= trim($_REQUEST['SnryTemplate']); //Added Zaved as on December 01/Dec/2016

		update_option( 'PSiteName', $PChannelID.':'.$PSiteName.':'.$PmryTemplate );
		update_option( 'SSiteName', $SChannelID.':'.$SSiteName.':'.$SnryTemplate );

		update_option( 'PrimaryReviewDetails',  $PSite );
		update_option( 'SecondryReviewDetails',  $SSite );
	}

	function TSaveChannelCategory() {
		$PChannelID	= trim($_REQUEST['PChannelID']);
		$PSiteName	= trim($_REQUEST['PSiteName']);

		$SChannelID	= trim($_REQUEST['SChannelID']);
		$SSiteName	= trim($_REQUEST['SSiteName']);

		$TChannelID	= trim($_REQUEST['TChannelID']);
		$TSiteName	= trim($_REQUEST['TSiteName']);

		$PSite		= trim($_REQUEST['PSite']);
		$SSite		= trim($_REQUEST['SSite']);
		$TSite		= trim($_REQUEST['TSite']);

		$PmryTemplate	= trim($_REQUEST['PmryTemplate']); //Added Zaved as on December 01/Dec/2016
		$SnryTemplate	= trim($_REQUEST['SnryTemplate']); //Added Zaved as on December 01/Dec/2016
		$TtryTemplate	= trim($_REQUEST['TtryTemplate']); //Added Zaved as on December 01/Dec/2016

		update_option( 'PSiteName', $PChannelID.':'.$PSiteName.':'.$PmryTemplate );
		update_option( 'SSiteName', $SChannelID.':'.$SSiteName.':'.$SnryTemplate );
		update_option( 'TSiteName', $TChannelID.':'.$TSiteName.':'.$TtryTemplate );

		update_option( 'PrimaryReviewDetails',  $PSite );
		update_option( 'SecondryReviewDetails',  $SSite );
		update_option( 'TertiaryReviewDetails',  $TSite );
	}

	function CheckMysqlCon( $server, $username, $password ) {

		$link = @mysql_connect( $server, $username, $password );
		return $link;
	}

	function ShowDatabases( $default='' ) {

		$res = mysql_query("SHOW DATABASES");
		$db .= "<option value=''>Select Databases</option>";
		while ($row = mysql_fetch_assoc($res)) {

			$selected = ( $default == $row['Database'] ) ? " selected='selected'" : '';
			$db .= "<option ".$selected." value='" . $row['Database'] ."'>" . $row['Database'] ."</option>";
		}
		return $db;
	}

	function GetMysqlDatabases( $server, $username, $password ) {

		if ( CheckMysqlCon( $server, $username, $password ) ) {

			return "<tr><td>Select Database -</td><td><select style='width:175px;' name='databases'>". ShowDatabases( $_POST['databases'] ) ."</select></td><td>&nbsp;</td></tr>";
		}
	}

	function GetSaveBtn( $server, $username, $password ) {

		if ( CheckMysqlCon( $server, $username, $password ) ) {

			return "&nbsp;<input class='button' name='SaveCon' value='Save Host' type='submit' />";
		}
	}

	function ShowChannelIDS( $c ) {

		$res = mysql_query("Select distinct(channelid) as channelid From channel_categories");
		$db .= "<option value=''>Select Channel ID</option>";

		while ($row = mysql_fetch_assoc($res)) {

			$selected = ( $c == $row['channelid'] ) ? " selected='selected'" : '';
			$db .= "<option $selected value='" . $row['channelid'] ."'>" . $row['channelid'] ."</option>";
		}
		return $db;
	}

	function to_get_mysql_sel_db() {

		if( get_option("dbconnection") ) {

			$db = explode("|", get_option("dbconnection") );
			$link = CheckMysqlCon( $db[3],  $db[0], $db[1] );
			mysql_select_db( $db[2], $link );
		}
	}

	function ShowNodeLevel( $n ) {

		to_get_mysql_sel_db();
		$res = mysql_query("Select distinct(node_level) as node_level From channel_categories");
		$db .= "<option value=''>Select Node Level</option>";

		while ($row = mysql_fetch_assoc($res)) {

			$selected = ( $n == $row['node_level'] ) ? " selected='selected'" : '';
			$db .= "<option $selected value='" . $row['node_level'] ."'>" . $row['node_level'] ."</option>";
		}
		return $db;
	}

	function GetChannelID() {

		$ChannelID = trim($_REQUEST['ChannelID']);
		$Level = trim($_REQUEST['Level']);

		if( get_option( 'SChannelCategory') == $ChannelID && $Level == 'primary' ) {

			return false;
		}
		else if( get_option( 'PChannelCategory') == $ChannelID && $Level == 'secondary' ) {

			return false;
		}
		else if( get_option( 'TChannelCategory') == $ChannelID && $Level == 'tertiary' ) {

			return false;
		}
		else {

			return true;
		}
	}

	function Load_ChannelID( $server, $username, $password, $c ) {

		if ( CheckMysqlCon( $server, $username, $password ) ) {

			return ShowChannelIDS( $c );
		}
		else {

			return "<option value=''>Select Channel ID</option>";
		}
	}

	function Load_NodeLevel( $server, $username, $password, $n ) {

		if ( CheckMysqlCon( $server, $username, $password ) ) {

			return ShowNodeLevel( $n );
		}
		else {

			return "<option value=''>Select Node Level</option>";
		}
	}

	function ShowSubHTML( $PMNODE_LVL, $PSNODE_LVL, $TSNODE_LVL, $SMNODE_LVL, $SSNODE_LVL, $TSNODE_LVL ) {

		$PSiteName = get_option( 'PSiteName' );
		$SSiteName = get_option( 'SSiteName' );
		$TSiteName = get_option( 'TSiteName' );

		$PSiteName = explode(":", $PSiteName );
		$SSiteName = explode(":", $SSiteName );
		$TSiteName = explode(":", $TSiteName );

		if( get_option( 'PSiteName' ) && !get_option( 'SSiteName' ) && !get_option( 'TSiteName' ) ) {

			$tr .= "<tr><td colspan='4'><b>". $PSiteName[1] ."</b> -</td></tr>";
			$tr .= "<tr><td align='center' colspan='4'><input id='GetMainPsiteInfo' type='hidden' value='". $PSiteName[0] ."' /><b>Get Main Category</b>&nbsp;&nbsp;<select id='PMainShowNodeLevel' onChange='PmryChangeInputs(this);' style='width:180px;'>". ShowNodeLevel($PMNODE_LVL) ."</select>&nbsp;&nbsp;<input type='button' name='PMVIEW' class='button' id='PMVIEW' onClick='Get_Main_Psite_Info()' value='VIEW' /></td></tr>";
			$tr .= "<tr><td align='center' colspan='4'><input id='GetSubPsiteInfo' type='hidden' value='". $PSiteName[0] ."' /><b>Get Sub Category</b>&nbsp;&nbsp;&nbsp;<select id='PSubShowNodeLevel' disabled style='width:180px;'>". ShowNodeLevel($PSNODE_LVL) ."</select>&nbsp;&nbsp;<input type='button' name='PVIEW' class='button' id='PSVIEW' onClick='Get_Sub_Psite_Info()' value='VIEW' /></td></tr>";
		}

		if( get_option( 'PSiteName' ) && get_option( 'SSiteName' ) && !get_option( 'TSiteName' )) {

			$tr .= "<tr><td colspan='4'><b>". $PSiteName[1] ."</b> -</td></tr>";
			$tr .= "<tr><td align='center' colspan='4'><input id='GetMainPsiteInfo' type='hidden' value='". $PSiteName[0] ."' /><b>Get Main Category</b>&nbsp;&nbsp;<select id='PMainShowNodeLevel' onChange='PmryChangeInputs(this);' style='width:180px;'>". ShowNodeLevel($PMNODE_LVL) ."</select>&nbsp;&nbsp;<input type='button' name='PMVIEW' class='button' id='PMVIEW' onClick='Get_Main_Psite_Info()' value='VIEW' /></td></tr>";
			$tr .= "<tr><td align='center' colspan='4'><input id='GetSubPsiteInfo' type='hidden' value='". $PSiteName[0] ."' /><b>Get Sub Category</b>&nbsp;&nbsp;&nbsp;<select id='PSubShowNodeLevel' disabled style='width:180px;'>". ShowNodeLevel($PSNODE_LVL) ."</select>&nbsp;&nbsp;<input type='button' name='PSVIEW' class='button' id='PSVIEW' onClick='Get_Sub_Psite_Info()' value='VIEW' /></td></tr>";

			$tr .= "<tr><td colspan='4'>&nbsp;</td></tr>";
			$tr .= "<tr><td colspan='4'><b>". $SSiteName[1] ."</b> -</td></tr>";
			$tr .= "<tr><td align='center' colspan='4'><input id='GetMainSsiteInfo' type='hidden' value='". $SSiteName[0] ."' /><b>Get Main Category</b>&nbsp;&nbsp;<select id='SMainShowNodeLevel' onChange='SndyChangeInputs(this);' style='width:180px;'>". ShowNodeLevel($SMNODE_LVL) ."</select>&nbsp;&nbsp;<input type='button' name='SMVIEW' class='button' id='SMVIEW' onClick='Get_Main_Ssite_Info()' value='VIEW' /></td></tr>";
			$tr .= "<tr><td align='center' colspan='4'><input id='GetSubSsiteInfo' type='hidden' value='". $SSiteName[0] ."' /><b>Get Sub Category</b>&nbsp;&nbsp;&nbsp;<select id='SSubShowNodeLevel' disabled style='width:180px;'>". ShowNodeLevel($SSNODE_LVL) ."</select>&nbsp;&nbsp;<input type='button' name='SSVIEW' class='button' id='SSVIEW' onClick='Get_Sub_Ssite_Info()' value='VIEW' /></td></tr>";
			$tr .= "<tr><td colspan='4'>&nbsp;</td></tr>";
		}
		if( get_option( 'PSiteName' ) && get_option( 'SSiteName' ) && get_option( 'TSiteName' )) {

			$tr .= "<tr><td colspan='4'><b>". $PSiteName[1] ."</b> -</td></tr>";
			$tr .= "<tr><td align='center' colspan='4'><input id='GetMainPsiteInfo' type='hidden' value='". $PSiteName[0] ."' /><b>Get Main Category</b>&nbsp;&nbsp;<select id='PMainShowNodeLevel' onChange='PmryChangeInputs(this);' style='width:180px;'>". ShowNodeLevel($PMNODE_LVL) ."</select>&nbsp;&nbsp;<input type='button' name='PMVIEW' class='button' id='PMVIEW' onClick='Get_Main_Psite_Info()' value='VIEW' /></td></tr>";
			$tr .= "<tr><td align='center' colspan='4'><input id='GetSubPsiteInfo' type='hidden' value='". $PSiteName[0] ."' /><b>Get Sub Category</b>&nbsp;&nbsp;&nbsp;<select id='PSubShowNodeLevel' disabled style='width:180px;'>". ShowNodeLevel($PSNODE_LVL) ."</select>&nbsp;&nbsp;<input type='button' name='PSVIEW' class='button' id='PSVIEW' onClick='Get_Sub_Psite_Info()' value='VIEW' /></td></tr>";


			$tr .= "<tr><td colspan='4'>&nbsp;</td></tr>";
			$tr .= "<tr><td colspan='4'><b>". $SSiteName[1] ."</b> -</td></tr>";
			$tr .= "<tr><td align='center' colspan='4'><input id='GetMainSsiteInfo' type='hidden' value='". $SSiteName[0] ."' /><b>Get Main Category</b>&nbsp;&nbsp;<select id='SMainShowNodeLevel' onChange='SndyChangeInputs(this);' style='width:180px;'>". ShowNodeLevel($SMNODE_LVL) ."</select>&nbsp;&nbsp;<input type='button' name='SMVIEW' class='button' id='SMVIEW' onClick='Get_Main_Ssite_Info()' value='VIEW' /></td></tr>";
			$tr .= "<tr><td align='center' colspan='4'><input id='GetSubSsiteInfo' type='hidden' value='". $SSiteName[0] ."' /><b>Get Sub Category</b>&nbsp;&nbsp;&nbsp;<select id='SSubShowNodeLevel' disabled style='width:180px;'>". ShowNodeLevel($SSNODE_LVL) ."</select>&nbsp;&nbsp;<input type='button' name='SSVIEW' class='button' id='SSVIEW' onClick='Get_Sub_Ssite_Info()' value='VIEW' /></td></tr>";
			$tr .= "<tr><td colspan='4'>&nbsp;</td></tr>";


			$tr .= "<tr><td colspan='4'>&nbsp;</td></tr>";
			$tr .= "<tr><td colspan='4'><b>". $TSiteName[1] ."</b> -</td></tr>";
			$tr .= "<tr><td align='center' colspan='4'><input id='GetMainTsiteInfo' type='hidden' value='". $TSiteName[0] ."' /><b>Get Main Category</b>&nbsp;&nbsp;<select id='TMainShowNodeLevel' onChange='TtryChangeInputs(this);' style='width:180px;'>". ShowNodeLevel($TMNODE_LVL) ."</select>&nbsp;&nbsp;<input type='button' name='TMVIEW' class='button' id='TMVIEW' onClick='Get_Main_Tsite_Info()' value='VIEW' /></td></tr>";
			$tr .= "<tr><td align='center' colspan='4'><input id='GetSubTsiteInfo' type='hidden' value='". $TSiteName[0] ."' /><b>Get Sub Category</b>&nbsp;&nbsp;&nbsp;<select id='TSubShowNodeLevel' disabled style='width:180px;'>". ShowNodeLevel($TSNODE_LVL) ."</select>&nbsp;&nbsp;<input type='button' name='TSVIEW' class='button' id='TSVIEW' onClick='Get_Sub_Tsite_Info()' value='VIEW' /></td></tr>";
			$tr .= "<tr><td colspan='4'>&nbsp;</td></tr>";


		}

		return $tr;
	}


	function ShowMainHTML( $PMNODE_LVL, $SMNODE_LVL, $TMNODE_LVL ) {

		$PSiteName = get_option( 'PSiteName' );
		$SSiteName = get_option( 'SSiteName' );
		$TSiteName = get_option( 'TSiteName' );

		$PSiteName = explode(":", $PSiteName );
		$SSiteName = explode(":", $SSiteName );
		$TSiteName = explode(":", $TSiteName );

		if( get_option( 'PSiteName' ) && !get_option( 'SSiteName' ) && !get_option( 'TSiteName' ) ) {

			$tr .= "<tr><td colspan='4'><b>". $PSiteName[1] ."</b> -</td></tr>";
			$tr .= "<tr><td align='center' colspan='4'><input id='GetMainPsiteInfo' type='hidden' value='". $PSiteName[0] ."' /><b>Get Main Category</b>&nbsp;&nbsp;<select id='PMainShowNodeLevel' style='width:135px;'>". ShowNodeLevel($PMNODE_LVL) ."</select>&nbsp;&nbsp;<input type='button' name='PMVIEW' class='button' id='PMVIEW' onClick='Get_Main_Psite_Info()' value='VIEW' /></td></tr>";
			$tr .= "<tr><td colspan='4'>&nbsp;</td></tr>";
		}

		if( get_option( 'PSiteName' ) && get_option( 'SSiteName' ) && !get_option( 'TSiteName' ) ) {


			$tr .= "<tr><td colspan='4'><b>". $PSiteName[1] ."</b> -</td></tr>";
			$tr .= "<tr><td align='center' colspan='4'><input id='GetMainPsiteInfo' type='hidden' value='". $PSiteName[0] ."' /><b>Get Main Category</b>&nbsp;&nbsp;<select id='PMainShowNodeLevel' style='width:135px;'>". ShowNodeLevel($PMNODE_LVL) ."</select>&nbsp;&nbsp;<input type='button' name='PMVIEW' class='button' id='PMVIEW' onClick='Get_Main_Psite_Info()' value='VIEW' /></td></tr>";
			$tr .= "<tr><td colspan='4'>&nbsp;</td></tr>";
			$tr .= "<tr><td colspan='4'><b>". $SSiteName[1] ."</b> -</td></tr>";
			$tr .= "<tr><td align='center' colspan='4'><input id='GetMainSsiteInfo' type='hidden' value='". $SSiteName[0] ."' /><b>Get Main Category</b>&nbsp;&nbsp;<select id='SMainShowNodeLevel' style='width:135px;'>". ShowNodeLevel($SMNODE_LVL) ."</select>&nbsp;&nbsp;<input type='button' name='SMVIEW' class='button' id='SMVIEW' onClick='Get_Main_Ssite_Info()' value='VIEW' /></td></tr>";
			$tr .= "<tr><td colspan='4'>&nbsp;</td></tr>";
		}

		if( get_option( 'PSiteName' ) && get_option( 'SSiteName' ) && get_option( 'TSiteName' ) ) {


			$tr .= "<tr><td colspan='4'><b>". $PSiteName[1] ."</b> -</td></tr>";
			$tr .= "<tr><td align='center' colspan='4'><input id='GetMainPsiteInfo' type='hidden' value='". $PSiteName[0] ."' /><b>Get Main Category</b>&nbsp;&nbsp;<select id='PMainShowNodeLevel' style='width:135px;'>". ShowNodeLevel($PMNODE_LVL) ."</select>&nbsp;&nbsp;<input type='button' name='PMVIEW' class='button' id='PMVIEW' onClick='Get_Main_Psite_Info()' value='VIEW' /></td></tr>";
			$tr .= "<tr><td colspan='4'>&nbsp;</td></tr>";

			$tr .= "<tr><td colspan='4'><b>". $SSiteName[1] ."</b> -</td></tr>";
			$tr .= "<tr><td align='center' colspan='4'><input id='GetMainSsiteInfo' type='hidden' value='". $SSiteName[0] ."' /><b>Get Main Category</b>&nbsp;&nbsp;<select id='SMainShowNodeLevel' style='width:135px;'>". ShowNodeLevel($SMNODE_LVL) ."</select>&nbsp;&nbsp;<input type='button' name='SMVIEW' class='button' id='SMVIEW' onClick='Get_Main_Ssite_Info()' value='VIEW' /></td></tr>";
			$tr .= "<tr><td colspan='4'>&nbsp;</td></tr>";

			$tr .= "<tr><td colspan='4'><b>". $TSiteName[1] ."</b> -</td></tr>";
			$tr .= "<tr><td align='center' colspan='4'><input id='GetMainTsiteInfo' type='hidden' value='". $TSiteName[0] ."' /><b>Get Main Category</b>&nbsp;&nbsp;<select id='TMainShowNodeLevel' style='width:135px;'>". ShowNodeLevel($TMNODE_LVL) ."</select>&nbsp;&nbsp;<input type='button' name='TMVIEW' class='button' id='TMVIEW' onClick='Get_Main_Tsite_Info()' value='VIEW' /></td></tr>";
			$tr .= "<tr><td colspan='4'>&nbsp;</td></tr>";
		}

		if( get_option( 'PSiteName' ) && get_option( 'SSiteName' ) && get_option( 'TSiteName' ) ) {

			$tr .= "<tr><td colspan='4'><b>". $PSiteName[1] ."</b> -</td></tr>";
			$tr .= "<tr><td align='center' colspan='4'><input id='GetMainPsiteInfo' type='hidden' value='". $PSiteName[0] ."' /><b>Get Main Category</b>&nbsp;&nbsp;<select id='PMainShowNodeLevel' style='width:135px;'>". ShowNodeLevel($PMNODE_LVL) ."</select>&nbsp;&nbsp;<input type='button' name='PMVIEW' class='button' id='PMVIEW' onClick='Get_Main_Psite_Info()' value='VIEW' /></td></tr>";
			$tr .= "<tr><td colspan='4'>&nbsp;</td></tr>";

			$tr .= "<tr><td colspan='4'><b>". $SSiteName[1] ."</b> -</td></tr>";
			$tr .= "<tr><td align='center' colspan='4'><input id='GetMainSsiteInfo' type='hidden' value='". $SSiteName[0] ."' /><b>Get Main Category</b>&nbsp;&nbsp;<select id='SMainShowNodeLevel' style='width:135px;'>". ShowNodeLevel($SMNODE_LVL) ."</select>&nbsp;&nbsp;<input type='button' name='SMVIEW' class='button' id='SMVIEW' onClick='Get_Main_Ssite_Info()' value='VIEW' /></td></tr>";
			$tr .= "<tr><td colspan='4'>&nbsp;</td></tr>";

			$tr .= "<tr><td colspan='4'><b>". $TSiteName[1] ."</b> -</td></tr>";
			$tr .= "<tr><td align='center' colspan='4'><input id='GetMainTsiteInfo' type='hidden' value='". $TSiteName[0] ."' /><b>Get Main Category</b>&nbsp;&nbsp;<select id='TMainShowNodeLevel' style='width:135px;'>". ShowNodeLevel($TMNODE_LVL) ."</select>&nbsp;&nbsp;<input type='button' name='TMVIEW' class='button' id='TMVIEW' onClick='Get_Main_Tsite_Info()' value='VIEW' /></td></tr>";
			$tr .= "<tr><td colspan='4'>&nbsp;</td></tr>";
		}

		return $tr;
	}

	function sparkle_plugin_activate() {

		AlterWP_Post();
		InsertMenuHeaderActivate();

		if ( is_nav_menu( 'menu_header' )) {

			$menu_name = "home";
			$post_type = 'nav_menu_item';
			$menu_exists = wp_get_nav_menu_object( 'menu_header' );
			$menu_id = $menu_exists->term_id;
			$menu = array( 'menu-item-type' => 'custom', 'menu-item-url' => get_home_url('/'), 'menu-item-classes' => 'ishome', 'menu-item-title' => ucfirst ($menu_name), 'menu-item-status' => 'publish' );
			if ( Check_WP_Entry_Exists( $menu_name, $post_type ) ) {

				$nid = wp_update_nav_menu_item( $menu_id, 0, $menu );
				update_option( 'homeid', $nid  );
			}
		}

		update_option( 'permalink_structure', '/reviews/%postname%.html' ); // they have changed the structure.

	}

	function modal_window_scripts() {

		wp_register_script('ajaxfile', plugins_url('/js/ajaxfile.js', __FILE__) );
		wp_enqueue_script( 'ajaxfile', array('jquery') );
	}

	function wp_get_nav_menu_child_items_list( $ID, $a, $default='' ) {

		$parent_id = explode("|", $ID);
		$menu_exists = wp_get_nav_menu_object( 'menu_header' );
		$menuid_id = $menu_exists->term_id;
		$menu_items = wp_get_nav_menu_items( $menuid_id );

		if( count($menu_items) > 1 ) {

			foreach ($menu_items as $menu_item ) {

				$p_id = get_post_meta( $menu_item->ID, '_menu_item_menu_item_parent', true );
				if ( $parent_id[0] == $menu_item->menu_item_parent && $p_id != 0  ) {	// $parent_id[0] is the mav id;

					$selected = ( $default == $menu_item->ID ) ? " selected='selected'" : '' ;
					$action = (  $a == 'edit' ) ? $menu_item->ID . "|" . get_post_meta( $menu_item->ID, '_menu_item_object_id', true) : $menu_item->ID;
					$options .= "<option value='" . $action . "' $selected > " . $menu_item->title ." </option>";
				}
			}
		}

		return $options;
	}

	function golfreview_sparkle_site() {

		//add_submenu_page( 'options-general.php', 'Sparkle Site', 'Sparkle Site', 'manage_options', 'sparkle-site', 'golfreview_sparkle_site_callback' );
		add_options_page( __('Sparkle Site', 'sparkle'), __('Sparkle Site', 'sparkle'), 'manage_options', basename(__FILE__), 'golfreview_sparkle_site_callback');
	}

	function golfreview_sparkle_site_callback() { ?>

		<?php
		include(__DIR__ .'/../../../wp-config-extra.php');
		$pg = ( $_GET['tab'] == '' ) ? $_GET['tab'] . 'menus' : $_GET['tab']; ?>

		<link rel="stylesheet" href="<?php echo plugin_dir_url(__FILE__) . 'stylesite.css'; ?>" type="text/css" media="screen" charset="utf-8" />
		<!--<link type="text/css" rel="stylesheet" href="<?php echo get_template_directory_uri() . "/style-gradient.css"; ?>" />-->
		<link type="text/css" rel="stylesheet" href="<?php echo $CDN_DOMAIN."/".$S3_FOLDER."/style-gradient.css"; ?>" />
		<style>input[name='EditMenus']:hover { border-color:#666666; }</style>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.js"></script>
		<link href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/themes/ui-lightness/jquery-ui.css" rel="stylesheet" type="text/css"/>
		<script type="text/javascript" src="<?php echo plugin_dir_url(__FILE__) . 'colorpicker-master/jquery.colorpicker.js' ?>"></script>
		<link rel="stylesheet" href="<?php echo plugin_dir_url(__FILE__) . 'colorpicker-master/jquery.colorpicker.css'; ?>" type="text/css" />
		<style>.ui-dialog{ font-family:'Segoe UI', Verdana, Arial, Helvetica, sans-serif; font-size:85.5%; } body {background:none;}</style>
		<script>

			function _disable_secondary_site() {

				if( document.getElementById('id_disable_secondary_site').checked ) {

					$("#s_site_name").attr('disabled', 'disabled');
					$("#SChannelID").attr('disabled', 'disabled');
					$("#btn_secondary_save").attr('disabled', 'disabled');
					$.ajax({ url: ajaxurl, data: { 'action' : 'remove_chkbx_secondary_site' } });

				} else {

					$('#s_site_name').removeAttr('disabled');
					$('#SChannelID').removeAttr('disabled');
					$('#btn_secondary_save').removeAttr('disabled');
				}
			}

			function _disable_tertiary_site() {

				if( document.getElementById('id_disable_tertiary_site').checked ) {

					$("#t_site_name").attr('disabled', 'disabled');
					$("#TChannelID").attr('disabled', 'disabled');
					$("#btn_tertiary_save").attr('disabled', 'disabled');
					$.ajax({ url: ajaxurl, data: { 'action' : 'remove_chkbx_tertiary_site' } });

				} else {

					$('#t_site_name').removeAttr('disabled');
					$('#TChannelID').removeAttr('disabled');
					$('#btn_tertiary_save').removeAttr('disabled');
				}
			}

			function PmryChangeInputs( t ) {

				if( t.value == "" ) {

					$("#PSubShowNodeLevel").attr('disabled', 'disabled');
				}
				else {

					$('#PSubShowNodeLevel').removeAttr('disabled');
				}
			}

			function SndyChangeInputs( t ) {

				if( t.value == "" ) {

					$("#SSubShowNodeLevel").attr('disabled', 'disabled');
				}
				else {

					$('#SSubShowNodeLevel').removeAttr('disabled');
				}
			}

			function TtryChangeInputs( t ) {

				if( t.value == "" ) {

					$("#TSubShowNodeLevel").attr('disabled', 'disabled');
				}
				else {

					$('#TSubShowNodeLevel').removeAttr('disabled');
				}
			}

			function GetData( CID, NodeLevel ) {

				$.ajax({ url: ajaxurl, data: { 'action' : 'Get_Data_Info' , 'ChannelID':CID, 'NodeLevel':NodeLevel }, success:function(data) {
					data = data.replace('0','');
					var str = "Information - \n\n"+data+"\n\nIs this you wanted to get ? Click to Ok";
					alert( str );
				}});
			}

			function Get_Main_Psite_Info() {

				var PMainShowNodeLevel	= $("#PMainShowNodeLevel").val();
				var ChannelID			= $("#GetMainPsiteInfo").val();

				if( PMainShowNodeLevel == '' ) {

					alert("Please select the node level.");
				}
				else {

					GetData( ChannelID, PMainShowNodeLevel );
				}
			}

			function Get_Sub_Psite_Info() {

				var PSubShowNodeLevel	= $("#PSubShowNodeLevel").val();
				var ChannelID			= $("#GetSubPsiteInfo").val();

				if( PSubShowNodeLevel == '' ) {

					alert("Please select the node level.");
				}
				else {

					GetData( ChannelID, PSubShowNodeLevel );
				}
			}

			function Get_Main_Ssite_Info() {

				var SMainShowNodeLevel	= $("#SMainShowNodeLevel").val();
				var ChannelID			= $("#GetMainSsiteInfo").val();

				if( SMainShowNodeLevel == '' ) {

					alert("Please select the node level.");
				}
				else {

					GetData( ChannelID, SMainShowNodeLevel );
				}
			}

			function Get_Sub_Ssite_Info() {

				var SSubShowNodeLevel	= $("#SSubShowNodeLevel").val();
				var ChannelID			= $("#GetSubSsiteInfo").val();

				if( SSubShowNodeLevel == '' ) {

					alert("Please select the node level.");
				}
				else {

					GetData( ChannelID, SSubShowNodeLevel );
				}
			}

			function Get_Main_Tsite_Info() {

				var TMainShowNodeLevel	= $("#TMainShowNodeLevel").val();
				var ChannelID			= $("#GetMainTsiteInfo").val();

				if( TMainShowNodeLevel == '' ) {

					alert("Please select the node level.");
				}
				else {

					GetData( ChannelID, TMainShowNodeLevel );
				}
			}





			function Get_Sub_Tsite_Info() {

				var TSubShowNodeLevel	= $("#TSubShowNodeLevel").val();
				var ChannelID			= $("#GetSubTsiteInfo").val();

				if( TSubShowNodeLevel == '' ) {

					alert("Please select the node level.");
				}
				else {

					GetData( ChannelID, TSubShowNodeLevel );
				}
			}

			/*-----------------------------------------------------*/

			function PSaveChannelCategory( ChannelID, PSiteName, template, psite ) {

				$.ajax({ url: ajaxurl, data: { 'action' : 'PSaveChannelCategory' , 'ChannelID':ChannelID, 'PSiteName':PSiteName, 'Template':template, 'PSite': psite } });
				alert("Setting Saved.");
			}

			function SSaveChannelCategory( PChannelID, PSiteName, SChannelID, SSiteName, nm_primary_template, nm_secondary_template, psite, ssite ) {

				$.ajax({ url: ajaxurl, data: { 'action' : 'SSaveChannelCategory' , 'PChannelID':PChannelID, 'PSiteName':PSiteName, 'SChannelID':SChannelID, 'SSiteName':SSiteName, 'PmryTemplate': nm_primary_template, 'SnryTemplate': nm_secondary_template, 'PSite': psite, 'SSite': ssite } });
				alert("Setting Saved.");
			}

			function TSaveChannelCategory( PChannelID, PSiteName, SChannelID, SSiteName, TChannelID, TSiteName, nm_primary_template, nm_secondary_template, nm_tertiary_template, psite, ssite, tsite ) {

				$.ajax({ url: ajaxurl, data: { 'action' : 'TSaveChannelCategory' , 'PChannelID':PChannelID, 'PSiteName':PSiteName, 'SChannelID':SChannelID, 'SSiteName':SSiteName, 'TChannelID':TChannelID, 'TSiteName':TSiteName, 'PmryTemplate': nm_primary_template, 'SnryTemplate': nm_secondary_template, 'TtryTemplate': nm_tertiary_template, 'PSite': psite, 'SSite': ssite, 'TSite': tsite } });

				alert("Setting Saved.");
			}

			function SShowChannelName() {

				var SChannelID	= $("#SChannelID").val();
				var PSiteName	= $("#p_site_name").val();
				var PChannelID	= $("#PChannelID").val();
				var SSiteName	= $("#s_site_name").val();

				var p_title_2	= $("#p_title_2").val();
				var p_reviewed	= $("#p_reviewed").val();
				var p_similar	= $("#p_similar").val();
				var p_title_3	= $("#p_title_3").val();

				var s_title_2	= $("#s_title_2").val();
				var s_reviewed	= $("#s_reviewed").val();
				var s_similar	= $("#s_similar").val();
				var s_title_3	= $("#s_title_3").val();

				var nm_primary_template		= $("input[name='nm_primary_radio']:checked").val();
				var nm_secondary_template	= $("input[name='nm_secondary_radio']:checked").val();

				if( SChannelID == '' ) {

					alert("Please select the secondary channel id.");
				}
				else if ( p_title_2 == '' ) {

					alert("please enter the primary review page - 2. title.");
				}
				else if ( p_reviewed == '' ) {

					alert("please enter the primary review page - reviewed.");
				}
				else if ( p_similar == '' ) {

					alert("please enter the primary review page - similar.");
				}
				else if ( p_title_3 == '' ) {

					alert("please enter the primary review page - 3. title.");
				}
				else if ( s_title_2 == '' ) {

					alert("please enter the secondary review page - 2. title.");
				}
				else if ( s_reviewed == '' ) {

					alert("please enter the secondary review page - reviewed.");
				}
				else if ( s_similar == '' ) {

					alert("please enter the secondary review page - similar.");
				}
				else if ( s_title_3 == '' ) {

					alert("please enter the secondary review page - 3. title.");
				}
				else if ( SSiteName == '' ) {

					alert("Please enter the secondary site name.");
				}
				else if ( !CheckChannelID('secondary', SChannelID ) ) {

					alert("Primary & secondary channel id should not match.");
				}
				else if( PChannelID == '' ) {

					alert("Primary channel id should not blank.");
				}
				else if( PSiteName == '' ) {

					alert("Please enter the primary site name.");
				}
				else if (  nm_primary_template == nm_secondary_template ) {

					alert("Primary & secondary template should not match.");
				}
				else {

					var psite = PChannelID+'$'+p_title_2+'$'+p_reviewed+'$'+p_similar+'$'+p_title_3;
					var ssite = SChannelID+'$'+s_title_2+'$'+s_reviewed+'$'+s_similar+'$'+s_title_3;

					SSaveChannelCategory( PChannelID, PSiteName, SChannelID, SSiteName, nm_primary_template, nm_secondary_template, psite, ssite );
				}
			}

			//For tertiary

			function TShowChannelName() {

				var TChannelID	= $("#TChannelID").val();
				var TSiteName	= $("#t_site_name").val();

				var SChannelID	= $("#SChannelID").val();
				var PSiteName	= $("#p_site_name").val();

				var PChannelID	= $("#PChannelID").val();
				var SSiteName	= $("#s_site_name").val();

				var p_title_2	= $("#p_title_2").val();
				var p_reviewed	= $("#p_reviewed").val();
				var p_similar	= $("#p_similar").val();
				var p_title_3	= $("#p_title_3").val();


				var s_title_2	= $("#s_title_2").val();
				var s_reviewed	= $("#s_reviewed").val();
				var s_similar	= $("#s_similar").val();
				var s_title_3	= $("#s_title_3").val();


				var t_title_2	= $("#t_title_2").val();
				var t_reviewed	= $("#t_reviewed").val();
				var t_similar	= $("#t_similar").val();
				var t_title_3	= $("#t_title_3").val();

				var nm_primary_template		= $("input[name='nm_primary_radio']:checked").val();
				var nm_secondary_template	= $("input[name='nm_secondary_radio']:checked").val();
				var nm_tertiary_template	= $("input[name='nm_tertiary_radio']:checked").val();

				if( SChannelID == '' ) {

					alert("Please select the secondary channel id.");
				}
				if( TChannelID == '' ) {

					alert("Please select the tertiary channel id.");
				}
				else if ( p_title_2 == '' ) {

					alert("please enter the primary review page - 2. title.");
				}
				else if ( p_reviewed == '' ) {

					alert("please enter the primary review page - reviewed.");
				}
				else if ( p_similar == '' ) {

					alert("please enter the primary review page - similar.");
				}
				else if ( p_title_3 == '' ) {

					alert("please enter the primary review page - 3. title.");
				}
				else if ( s_title_2 == '' ) {

					alert("please enter the secondary review page - 2. title.");
				}
				else if ( s_reviewed == '' ) {

					alert("please enter the secondary review page - reviewed.");
				}
				else if ( s_similar == '' ) {

					alert("please enter the secondary review page - similar.");
				}
				else if ( s_title_3 == '' ) {

					alert("please enter the secondary review page - 3. title.");
				}
				else if ( SSiteName == '' ) {

					alert("Please enter the secondary site name.");
				}
				else if ( t_title_2 == '' ) {

					alert("please enter the tertiary review page - 2. title.");
				}
				else if ( t_reviewed == '' ) {

					alert("please enter the tertiary review page - reviewed.");
				}
				else if ( t_similar == '' ) {

					alert("please enter the tertiary review page - similar.");
				}
				else if ( t_title_3 == '' ) {

					alert("please enter the tertiary review page - 3. title.");
				}
				else if ( TSiteName == '' ) {

					alert("Please enter the tertiary site name.");
				}
				else if ( !CheckChannelID('secondary', SChannelID ) ) {

					alert("Primary & secondary channel id should not match.");
				}
				else if ( !CheckChannelID('tertiary', TChannelID ) ) {

					alert("Primary & tertiary channel id should not match.");
				}
				else if( PChannelID == '' ) {

					alert("Primary channel id should not blank.");
				}
				else if( PSiteName == '' ) {

					alert("Please enter the primary site name.");
				}
				else if (  nm_primary_template == nm_secondary_template ) {

					alert("Primary & secondary template should not match.");
				}
				else if (  nm_primary_template == nm_tertiary_template ) {

					alert("Primary & tertiary template should not match.");
				}
				else {

					var psite = PChannelID+'$'+p_title_2+'$'+p_reviewed+'$'+p_similar+'$'+p_title_3;
					var ssite = SChannelID+'$'+s_title_2+'$'+s_reviewed+'$'+s_similar+'$'+s_title_3;
					var tsite = TChannelID+'$'+t_title_2+'$'+t_reviewed+'$'+t_similar+'$'+t_title_3;

					TSaveChannelCategory( PChannelID, PSiteName, SChannelID, SSiteName, TChannelID, TSiteName, nm_primary_template, nm_secondary_template, nm_tertiary_template, psite, ssite, tsite );
				}
			}


			function CheckChannelID( Level, ChannelID ) {
				/*
				$.ajax({ url: ajaxurl, data: { 'action' : 'GetChannelID' , 'ChannelID':ChannelID, 'Level':Level },
				success:function(data) {
					return data;
				}});*/

				var PChannelID = $("#PChannelID").val();
				var SChannelID = $("#SChannelID").val();
				var TChannelID = $("#TChannelID").val();

				if( PChannelID == SChannelID ) {

					return false;
				}
				else {

					return true;
				}
				if( PChannelID == TChannelID ) {

					return false;
				}
				else {

					return true;
				}
				if( SChannelID == TChannelID ) {

					return false;
				}
				else {

					return true;
				}
			}

			function PShowChannelName() {

				var ChannelID	= $("#PChannelID").val();
				var PSiteName	= $("#p_site_name").val();

				var p_title_2	= $("#p_title_2").val();
				var p_reviewed	= $("#p_reviewed").val();
				var p_similar	= $("#p_similar").val();
				var p_title_3	= $("#p_title_3").val();

				var nm_primary_template		= $("input[name='nm_primary_radio']:checked").val();
				var nm_secondary_template	= $("input[name='nm_secondary_radio']:checked").val();
				var nm_tertiary_template	= $("input[name='nm_tertiary_radio']:checked").val();

				if( ChannelID == '' ) {

					alert("please select the primary channel Id.");
				}
				else if ( p_title_2 == '' ) {

					alert("please enter the primary review page - 2. title.");
				}
				else if ( p_reviewed == '' ) {

					alert("please enter the primary review page - reviewed.");
				}
				else if ( p_similar == '' ) {

					alert("please enter the primary review page - similar.");
				}
				else if ( p_title_3 == '' ) {

					alert("please enter the primary review page - 3. title.");
				}
				else if ( PSiteName == '' ) {

					alert("please enter the primary site name.");
				}
				else if ( !CheckChannelID('primary', ChannelID ) ) {

					alert("primary & secondary channel id should not match.");
				}
				else if (  nm_primary_template == nm_secondary_template ) {

					alert("primary & secondary template should not match.");
				}
				else if (  nm_primary_template == nm_tertiary_template ) {

					alert("primary & tertiary template should not match.");
				}
				else {

					var ssite = ChannelID+'$'+p_title_2+'$'+p_reviewed+'$'+p_similar+'$'+p_title_3;
					PSaveChannelCategory( ChannelID, PSiteName, nm_primary_template, ssite );
				}
			}

			function View_Template_List() {

				var url = window.location.protocol + '//' + window.location.hostname + window.location.pathname
				+ '?page=site-review-sparkle-site.php&tab=templates&action=ViewTemplateList';

				GenerateMenus(urls);
			}

			function View_Promo_List() {

				var url = window.location.protocol + '//' + window.location.hostname + window.location.pathname
				+ '?page=site-review-sparkle-site.php&tab=promo-queue&action=GeneratePromoQueue';
				GenerateMenus(url);
			}

			function COMEditMenus() {

				var path = '';
				path = window.location.protocol + '//'+ window.location.hostname + window.location.pathname;
				var res = path.replace("options-general.php", "nav-menus.php");
				location.href = res;
			}

			function COMEditMenusProperties() {

				var path = '';
				path = window.location.protocol + '//' + window.location.hostname + window.location.pathname + "?page=site-review-sparkle-site.php&tab=menus&action=editmenusproperties";
				location.href = path;
			}

			function GenerateMenus( urls ) {

				var PMNODE_LVL	= $("#PMainShowNodeLevel").val();
				var PSNODE_LVL	= $("#PSubShowNodeLevel").val();
				var PChannelID	= $("#GetMainPsiteInfo").val();

				var SMNODE_LVL	= $("#SMainShowNodeLevel").val();
				var SSNODE_LVL	= $("#SSubShowNodeLevel").val();
				var SChannelID	= $("#GetMainSsiteInfo").val();

				var TMNODE_LVL	= $("#TMainShowNodeLevel").val();
				var TSNODE_LVL	= $("#TSubShowNodeLevel").val();
				var TChannelID	= $("#GetMainTsiteInfo").val();

				if( PMNODE_LVL == "" ) {

					alert("Primary - Please select the main node level.");
				}/*
				else if ( PSNODE_LVL == "" ) {

					alert("Primary - Please select the sub node level.");
				}*/
				else if ( SMNODE_LVL == "" ) {

					alert("Secondary - Please select the main node level.");
				}/*
				else if ( SSNODE_LVL == "" ) {

					alert("Secondary - Please select the sub node level.");
				}*/
				else if ( TMNODE_LVL == "" ) {

					alert("Tertiary - Please select the main node level.");
				}/*
				else if ( SSNODE_LVL == "" ) {

					alert("Secondary - Please select the sub node level.");
				}*/
				else {

					var path = window.location.protocol + '//' + window.location.hostname
					+ window.location.pathname + '?page=site-review-sparkle-site.php';
					var qry = '';

					urls = ( urls != '' ) ? urls : path+"&tab=menus&action=GenerateMenus";
					qry += (PMNODE_LVL) ? '&PMNODE_LVL='+PMNODE_LVL : '';
					qry += (PSNODE_LVL) ? '&PSNODE_LVL='+PSNODE_LVL : '&PSNODE_LVL='+0;
					qry += (SMNODE_LVL) ? '&SMNODE_LVL='+SMNODE_LVL : '';
					qry += (SSNODE_LVL) ? '&SSNODE_LVL='+SSNODE_LVL : '&SSNODE_LVL='+0;
					qry += (TMNODE_LVL) ? '&TMNODE_LVL='+TMNODE_LVL : '';
					qry += (TSNODE_LVL) ? '&TSNODE_LVL='+TSNODE_LVL : '&TSNODE_LVL='+0;
					qry += (PChannelID) ? '&PChannelID='+PChannelID : '';
					qry += (SChannelID) ? '&SChannelID='+SChannelID : '';
					qry += (TChannelID) ? '&TChannelID='+TChannelID : '';
					qry = urls + qry;
					location.href = qry;
				}
			}

			function ReGenerateBtn() {

				var PMNODE_LVL	= $("#PMainShowNodeLevel").val();
				var PSNODE_LVL	= $("#PSubShowNodeLevel").val();
				var PChannelID	= $("#GetMainPsiteInfo").val();

				var SMNODE_LVL	= $("#SMainShowNodeLevel").val();
				var SSNODE_LVL	= $("#SSubShowNodeLevel").val();
				var SChannelID	= $("#GetMainSsiteInfo").val();

				var TMNODE_LVL	= $("#TMainShowNodeLevel").val();
				var TSNODE_LVL	= $("#TSubShowNodeLevel").val();
				var TChannelID	= $("#GetMainTsiteInfo").val();

				if( PMNODE_LVL == "" ) {

					alert("Primary - Please select the main node level.");
				}/*
				else if ( PSNODE_LVL == "" ) {

					alert("Primary - Please select the sub node level.");
				}*/
				else if ( SMNODE_LVL == "" ) {

					alert("Secondary - Please select the main node level.");
				}/*
				else if ( SSNODE_LVL == "" ) {

					alert("Secondary - Please select the sub node level.");
				}*/
				else if ( TMNODE_LVL == "" ) {

					alert("Tertiary - Please select the main node level.");
				}/*
				else if ( SSNODE_LVL == "" ) {

					alert("Secondary - Please select the sub node level.");
				}*/
				else {

					var path = window.location.protocol + '//' + window.location.hostname
					+ window.location.pathname + '?page=site-review-sparkle-site.php';
					var qry = '';

					qry += (PMNODE_LVL) ? '&PMNODE_LVL='+PMNODE_LVL : '';
					qry += (PSNODE_LVL) ? '&PSNODE_LVL='+PSNODE_LVL : '&PSNODE_LVL='+0;
					qry += (SMNODE_LVL) ? '&SMNODE_LVL='+SMNODE_LVL : '';
					qry += (SSNODE_LVL) ? '&SSNODE_LVL='+SSNODE_LVL : '&SSNODE_LVL='+0;
					qry += (TMNODE_LVL) ? '&TMNODE_LVL='+TMNODE_LVL : '';
					qry += (TSNODE_LVL) ? '&TSNODE_LVL='+TSNODE_LVL : '&TSNODE_LVL='+0;
					qry += (PChannelID) ? '&PChannelID='+PChannelID : '';
					qry += (SChannelID) ? '&SChannelID='+SChannelID : '';
					qry += (TChannelID) ? '&TChannelID='+TChannelID : '';
					var r = confirm("Information - \n\nThis will remove php category file and also remove pages and menus,\nAre your sure ?");
					if (r == true) {

						location.href = path+"&tab=menus&action=allclear"+qry;
					}
				}
			}
		</script>
		<div id="cp-dialog-modal" title="Basic modal dialog title Box">
			<span class="cp-onclick" style="display: inline-block; vertical-align: top;"></span>
		</div>
		<div id="boxes">
			<div id="Menu_Gradient_Dialog" class="Menu_Gradient_Window this_window">
				<div id='HeaderBox'>Menu Gradient <img title="close" class="close" src='<?Php echo plugins_url( 'images/close.png', __FILE__ ); ?>' /></div>
				<form id="frmDialogBoxGradient" method="post">
					<table width='100%' border='1' id='tblDialogBoxGradient'></table>
				</form>
			</div>
			<div id="mask"></div>
		</div>
		<div class="wrap">
		<div class='nav-wrap-tab'>
			<h2 class='tab'>
				<a href="?page=site-review-sparkle-site.php&tab=mysqlcon" class=" nav-tab <?php echo ($pg == 'mysqlcon' ) ? 'nav-tab-active':''; ?>">Connection</a>
				<a href="?page=site-review-sparkle-site.php&tab=menus" class=" nav-tab <?php echo ($pg == 'menus' ) ? 'nav-tab-active':''; ?>">Menus</a>
				<a href="?page=site-review-sparkle-site.php&tab=templates" class=" nav-tab <?php echo ($pg == 'templates' ) ? 'nav-tab-active':''; ?>">Templates</a>
				<a href="?page=site-review-sparkle-site.php&tab=colors" class=" nav-tab <?php echo ($pg == 'colors' ) ? 'nav-tab-active':''; ?>">Colors</a>
				<a href="?page=site-review-sparkle-site.php&tab=promo-ads" class=" nav-tab <?php echo ($pg == 'promo-ads' ) ? 'nav-tab-active':''; ?>">Promo Ads</a>
				<a href="?page=site-review-sparkle-site.php&tab=email" class=" nav-tab <?php echo ($pg == 'email' ) ? 'nav-tab-active':''; ?>">Email</a>
				<a href="?page=site-review-sparkle-site.php&tab=cookie" class=" nav-tab <?php echo ($pg == 'cookie' ) ? 'nav-tab-active':''; ?>">Cookie</a>
				<a href="?page=site-review-sparkle-site.php&tab=vbulletin" class=" nav-tab <?php echo ($pg == 'vbulletin' ) ? 'nav-tab-active':''; ?>">Vbulletin</a>
				<a href="?page=site-review-sparkle-site.php&tab=profile#one" class=" nav-tab <?php echo ($pg == 'profile' ) ? 'nav-tab-active':''; ?>">Profile</a>
				<a href="?page=site-review-sparkle-site.php&tab=seo" class=" nav-tab <?php echo ($pg == 'seo' ) ? 'nav-tab-active':''; ?>">SEO</a>
			</h2>
		</div>
<?Php if(  $pg == 'mysqlcon'  ) {

		if(isset($_POST['connect'] ) || $_POST['SaveCon'] ) {

			if( $_POST['hostuname'] == '' ) {

				echo '<div id="message" class="updated fade"><p>Please enter user name.</p></div>';
			}
			else if( $_POST['hostname'] == '' ) {

				echo '<div id="message" class="updated fade"><p>Please enter host name.</p></div>';
			}
			else if ( CheckMysqlCon( $_POST['hostname'], $_POST['hostuname'], $_POST['hostpwd']  ) == false ) {

				echo '<div id="message" class="updated fade"><p>Could not connect to hostname.</p></div>';
			}
			else {

				if( $_POST['databases'] == '' && $_POST['SaveCon'] ) {

					echo '<div id="message" class="updated fade"><p>Please select database name.</p></div>';
				}
				else if( $_POST['SaveCon'] ) {

					update_option( "dbconnection", $_POST['hostuname']."|". $_POST['hostpwd'] ."|".$_POST['databases']."|". $_POST['hostname'] );
					echo '<div id="message" class="updated fade"><p>Connection Saved.</p></div>';
					$link = CheckMysqlCon( $_POST['hostname'], $_POST['hostuname'], $_POST['hostpwd']  );
					mysql_select_db( $_POST['databases'], $link);
				}
				else {

					echo '<div id="message" class="updated fade"><p>Connected.</p></div>';
				}
			}
		}
		else {

			if( get_option("dbconnection") ) {

				$db = explode("|", get_option("dbconnection") );
				$_POST['hostuname']	= $db[0];
				$_POST['hostpwd']	= $db[1];
				$_POST['databases']	= $db[2];
				$_POST['hostname']	= $db[3];
				$PC = explode(":", get_option("PSiteName") );
				$SC = explode(":", get_option("SSiteName") );
				$TC = explode(":", get_option("TSiteName") );
				to_get_mysql_sel_db();
				$PRD	= explode("$", get_option( 'PrimaryReviewDetails' ) );
				$SRD	= explode("$", get_option( 'SecondryReviewDetails' ) );
				$TRD	= explode("$", get_option( 'TertiaryReviewDetails' ) );
			}
		}
	?>
	<h3>Connection with Venice Database -</h3>
		<form name='frmvenivedatabase' method='post' action='?page=site-review-sparkle-site.php&tab=mysqlcon'>
		<table style='margin-left:50px;' name='connection' width='80%'>
			<tr><td bgcolor='#eceaea' colspan='3'><p><b>&nbsp;Connect with host for following -</b></p></td></tr>
			<tr><td colspan='3'>&nbsp;</td></tr>
			<tr><td>Database Hostname -</td><td><input name='hostname' value='<?Php echo $_POST['hostname']; ?>' type='text' size='25' /></td><td>Ex: localhost;</td></tr>
			<tr><td>Database Username -</td><td><input name='hostuname' value='<?Php echo $_POST['hostuname']; ?>' type='text' size='25' /></td><td>Ex: root;</td></tr>
			<tr><td>Database Password -</td><td><input name='hostpwd' value='<?Php echo $_POST['hostpwd']; ?>' type='text' size='25' /></td><td>Ex: root;</td></tr>
			<?php echo GetMysqlDatabases( $_POST['hostname'], $_POST['hostuname'], $_POST['hostpwd'] ); ?>
			<tr><td colspan='3'>&nbsp;</td></tr>
			<tr><td>&nbsp;</td><td colspan='2'><input name='connect' class='button' type='submit' value='Test Connect' /> <?php echo GetSaveBtn( $_POST['hostname'], $_POST['hostuname'], $_POST['hostpwd'] ); ?></td></tr>
			<tr><td colspan='3'>&nbsp;</td></tr>
			<tr><td bgcolor='#eceaea' colspan='3'><p><b>&nbsp;Create the Web Site For Primary Channel ID -</b></p></td></tr>
			<tr><td colspan='3'>&nbsp;</td></tr>
			<tr><td>Select Channel ID -</td><td><select id='PChannelID' style='width:100px;'><?php echo Load_ChannelID( $_POST['hostname'], $_POST['hostuname'], $_POST['hostpwd'], $PC[0] ); ?></select></td><td>&nbsp;</td></tr>
			<tr><td>Enter Site Name -</td><td><input maxlength='200' type='text' id='p_site_name' name='p_site_name' value='<?Php echo $PC[1];?>' size='25' /></td><td>&nbsp;</td></tr>
			<tr><td>Primary Site Template -</td><td><p><input type='radio' id='id_primary_product' checked="checked" name='nm_primary_radio' value='page-product-landing-page.php' />Product &nbsp;&nbsp;&nbsp; <input type='radio' id='id_primary_location' name='nm_primary_radio' value='page-location-landing-page.php' />Location </p>&nbsp;</td></tr></td></tr>
			<tr><td bgcolor='#eceaea' colspan='3'><p style='margin-bottom:10px;'><b>&nbsp;Primary Review Page Setting</b></p></td></tr>
			<tr><td colspan='3'>&nbsp;</td></tr>
			<tr><td>Enter Title 2 -</td><td><input maxlength='200' type='text' id='p_title_2' name='p_title_2' value='<?Php echo $PRD[1];?>' size='25' /></td><td>&nbsp;eq: 2. Product Review:</td></tr>
			<!--<tr><td>Enter Reviewed -</td><td><input maxlength='200' type='text' id='p_reviewed' name='p_reviewed' value='<?Php echo $PRD[2];?>' size='25' /></td><td>&nbsp;eq: Model Reviewed:</td></tr>
			<tr><td>Enter Similar -</td><td><input maxlength='200' type='text' id='p_similar' name='p_similar' value='<?Php echo $PRD[3];?>' size='25' /></td><td>&nbsp;eq: Similar Products Used:</td></tr>
			<tr><td>Enter Title 3 -</td><td><input maxlength='200' type='text' id='p_title_3' name='p_title_3' value='<?Php echo $PRD[4];?>' size='25' /></td><td>&nbsp;eq: 3. Product Rating</td></tr>-->
			<tr><td colspan='3'>&nbsp;</td></tr>
			<tr><td>&nbsp;</td><td colspan='2'><input name='Save' onClick='PShowChannelName()' class='button' type='button' value='Save Primary Setting' /></td></tr>
			<tr><td colspan='3'>&nbsp;</td></tr>
			<tr>
				<td bgcolor='#eceaea' colspan='3'>
					<p>
						<b>&nbsp;Create the Web Site For Secondary Channel ID -</b>
						&nbsp;&nbsp;&nbsp;
						<input type='checkbox' id='id_disable_secondary_site' onClick='_disable_secondary_site()' name='disable_secondary_site' size='25' />
						Disable Secondary Site
					</p>
				</td>
			</tr>
			<tr><td colspan='3'>&nbsp;</td></tr>
			<tr><td>Select Channel ID -</td><td><select id='SChannelID' style='width:100px;'><?php echo Load_ChannelID( $_POST['hostname'], $_POST['hostuname'], $_POST['hostpwd'], $SC[0] ); ?></select></td><td>&nbsp;</td></tr>
			<tr><td>Enter Site Name -</td><td><input maxlength='200' type='text' id='s_site_name' name='s_site_name' value='<?Php echo $SC[1];?>' size='25' /></td><td>&nbsp;</td></tr>
			<tr><td style='margin-top:150px;'>Secondary Site Template -</td><td><p><input type='radio' id='id_secondary_product' name='nm_secondary_radio' value='page-product-landing-page.php' />Product &nbsp;&nbsp;&nbsp; <input type='radio' checked="checked" id='id_primary_location' name='nm_secondary_radio' value='page-location-landing-page.php' />Location </p>&nbsp;</td></tr></td></tr>
			<tr><td bgcolor='#eceaea' colspan='3'><p style='margin-bottom:10px;'><b>&nbsp;Secondary Review Page Setting</b></p></td></tr>
			<tr><td colspan='3'>&nbsp;</td></tr>
			<tr><td>Enter Title 2 -</td><td><input maxlength='200' type='text' id='s_title_2' name='s_title_2' value='<?Php echo $SRD[1];?>' size='25' /></td><td>&nbsp;eq: 2. Course Review</td></tr>
			<tr><td>Enter Location Word -</td><td><input maxlength='200' type='text' id='s_reviewed' name='s_reviewed' value='<?Php echo $SRD[2];?>' size='25' /></td><td>&nbsp;eq: Golf Course</td></tr>
			<!--<tr><td>Enter Similar -</td><td><input maxlength='200' type='text' id='s_similar' name='s_similar' value='<?Php echo $SRD[3];?>' size='25' /></td><td>&nbsp;eq: Similar Courses Played:</td></tr>
			<tr><td>Enter Title 3 -</td><td><input maxlength='200' type='text' id='s_title_3' name='s_title_3' value='<?Php echo $SRD[4];?>' size='25' /></td><td>&nbsp;eq: 3. Course Rating</td></tr>-->
			<tr><td colspan='3'>&nbsp;</td></tr>
			<tr><td>&nbsp;</td><td colspan='2'><input name='Save' id='btn_secondary_save' onClick='SShowChannelName()' class='button' type='button' value='Save Secondary Setting' /></td></tr>
			<tr><td colspan='3'>&nbsp;</td></tr>

			<!-- For tertiary -->

			<tr><td colspan='3'>&nbsp;</td></tr>
			<tr>
				<td bgcolor='#eceaea' colspan='3'>
					<p>
						<b>&nbsp;Create the Web Site For Tertiary Channel ID -</b>
						&nbsp;&nbsp;&nbsp;
						<input type='checkbox' id='id_disable_tertiary_site' onClick='_disable_tertiary_site()' name='disable_tertiary_site' size='25' />
						Disable Tertiary Site
					</p>
				</td>
			</tr>
			<tr>
				<td colspan='3'>&nbsp;</td>
			</tr>
			<tr>
				<td>Select Channel ID -</td>
				<td>
					<select id='TChannelID' style='width:100px;'>
						<?php echo Load_ChannelID( $_POST['hostname'], $_POST['hostuname'], $_POST['hostpwd'], $TC[0] ); ?>
					</select>
				</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td>Enter Site Name -</td>
				<td><input maxlength='200' type='text' id='t_site_name' name='t_site_name' value='<?Php echo $TC[1];?>' size='25' /></td>
				<td>&nbsp;</td>
			</tr>

			<tr>
				<td style='margin-top:150px;'>Tertiary Site Template -</td>
				<td>
					<p>
						<input type='radio' id='id_tertiary_product' name='nm_tertiary_radio' value='page-product-landing-page.php' />
						Product &nbsp;&nbsp;&nbsp;
						<input type='radio' checked="checked" id='id_primary_location' name='nm_tertiary_radio' value='page-location-landing-page.php' />
						Location
					</p>&nbsp;
				</td>
			</tr>
			</td>
			</tr>
			<tr><td bgcolor='#eceaea' colspan='3'><p style='margin-bottom:10px;'><b>&nbsp;Tertiary Review Page Setting</b></p></td></tr>
			<tr><td colspan='3'>&nbsp;</td>
			</tr>

			<tr>
				<td>Enter Title 3 -</td>
				<td><input maxlength='200' type='text' id='t_title_2' name='t_title_2' value='<?Php echo $TRD[1];?>' size='25' /></td>
				<td>&nbsp;eq: 2. Course Review</td>
			</tr>
			<tr><td colspan='3'>&nbsp;</td></tr>
			<tr><td>&nbsp;</td><td colspan='2'><input name='Save' id='btn_tertiary_save' onClick='TShowChannelName()' class='button' type='button' value='Save Tertiary Setting' /></td></tr>
			<tr><td colspan='3'>&nbsp;</td></tr>
		</table></form>
<?Php } ?>
<?Php if(  $pg == 'menus'  ) { include_once('menus-page.php'); ?>
<?Php if( $_GET['action'] == 'addparentmenu' ) { ?>
<?Php if( $_POST['BtnParentAddAnother'] && $_POST['txtmenubox'] ) { $default = wp_get_nav_menu_parent_items_list_pos( $_POST['parent_menu_id'] );	} ?>

		<h3>Sparkle Site - Parent Menu Creator</h3>
		<form method="post" name="PerantMenuCreator" action="?page=site-review-sparkle-site.php&action=addparentmenu">
			<table width='100%' name='parentmenu'>
				<tr><td colspan='2'><table width='100%' height='70' id='idcurrentmenu' name='currentmenu'>
				<tr><td colspan='2'><div id="header"><div class="inner"><nav id="site-navigation" class="main-navigation" role="navigation">
				<div id="navigation"><?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_class' => 'nav-menu' ) ); ?></div>
				<!--<div class='search_box'><form method="get" action="/"><input type="text" name="s" /></form></div>--></nav>
				</div></div></td></tr><tr><td>&nbsp;</td><td>&nbsp;</td></tr></table></td></tr><tr><td colspan='2'>
				<table width='100%' cellspacing='10' id='idcurrentmenu' name='currentmenu'>
				<tr><td width='20%'><b>Add Menu</b></td><td width='30%'><?Php echo HtmlAddMenuType( $_POST['option'] ); ?></td><td><b>Parent Menu </b><select id='parent_menu_id' name='parent_menu_id'><?Php echo wp_get_nav_menu_parent_items_list('', $default ); ?></select></td></tr>
				<tr><td width='20%'><b>Add Name</b></td><td width='30%'><input type='text' onBlur="AjaxFile.DisplayPrntMenuInfo()" name='txtmenubox' id='txtmenubox' size='33' /></td><td>&nbsp;</td></tr>
				<tr><td width='20%'><b>Template</b></td><td width='30%'><select OnChange='AjaxFile.GetPageTemplate()' id='parent_drop_page_temp' name='parent_drop_page_temp'><?php page_template_dropdown_box();?></select></td><td>&nbsp;</td></tr>
				<tr><td width='20%'><b>Template Php File</b></td><td width='30%'><input type='text' disabled='disabled' id='txtTemplateFile' name='txtTemplateFile' size='33' /></td><td>&nbsp;</td></tr>
				<tr><td width='20%'><b>Wordpress Page Name</b></td><td width='30%'><input type='text' disabled='disabled' id='txtWPPageName' name='txtWPPageName' size='33' /></td><td>&nbsp;</td></tr>
				<tr><td width='20%'><b>URL</b></td><td width='30%'><input type='text' id='txtpageurl' name='txtpageurl' disabled='disabled' size='33' /></td><td>&nbsp;</td></tr>
				<tr>

				<td width='20%'>&nbsp;</td>

				<td width='30%'><input type='checkbox' id='chkboxurl' onClick='AjaxFile.ExternalCheckBox();' name='chkboxurl' size='33' />&nbsp; Go to external URL</td><td>&nbsp;</td>
				</tr>

				<tr>
					<td width='20%'><b>External URL</b></td><td width='30%'><input type='text' id='txtexternalurl' name='txtexternalurl' disabled='disabled' size='33' /></td><td>&nbsp;</td>
				</tr>

				<tr><td width='20%'>&nbsp;</td><td width='30%'><input value='Add Menu' class='button' type='submit' name='BtnParentAddAnother' size='33' />&nbsp;&nbsp;<input value='Exit' class='button' onClick='AjaxFile.FormCancelParent();' type='button' name='BtnCancel' size='33' /></td><td>&nbsp;</td></tr>
				</table></td></tr>
			</table>
		</form>

<?Php } else if( $_GET['action'] == 'addchildmenu' ) {
	if( $_POST['BtnChildAddAnother'] && $_POST['txtmenubox'] ) {

		$pmenuid = explode('|', $_POST['seleditparentmenu']);
		$selchildmenu = explode('|', $_POST['selchildmenu']);
		$nid = wp_get_nav_menu_child_items_list_pos( $pmenuid[0], $selchildmenu[0] );
	}

	else { ?> <script>jQuery(document).ready(function() { AjaxFile.ShowChildMenus( '', 'edit', '' );	});</script> <?php }?>
	<form method="post" name="ChildMenuCreator" action="?page=site-review-sparkle-site.php&action=addchildmenu">
		<h3>Sparkle Site - Child Menu Creator</h3>
			<table width='100%' name='parentmenu'>
				<tr><td align='center'><b>Parent Menu </b></td><td><select  OnChange='AjaxFile.ShowChildMenus(this.value,"edit", "" ); AjaxFile.DisplayChildMenuInfo()' name='seleditparentmenu' id='seleditparentmenu'><?Php echo wp_get_nav_menu_parent_items_list('edit', $pmenuid[0] ); ?></select></td></tr>
				<tr><td colspan='2'><table width='100%' height='70' id='idcurrentmenu' name='currentmenu'>
				<tr><td colspan='2'><div id="header"><div class="inner"><nav id="site-navigation" class="main-navigation" role="navigation">
				<div id="navigation"><?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_class' => 'nav-menu' ) ); ?></div>
				<!--<div class='search_box'><form method="get" action="/"><input type="text" name="s" /></form></div>--></nav>
				</div></div></td></tr><tr><td>&nbsp;</td><td>&nbsp;</td></tr></table></td></tr><tr><td colspan='2'>
				<table width='100%' cellspacing='10' id='idcurrentmenu' name='currentmenu'>
					<tr><td width='20%'><b>Add Menu</b></td><td width='30%'><?Php echo HtmlAddMenuType( $_POST['option'] ); ?></td><td><b>Child Menu </b><select name='selchildmenu' id='selchildmenu'><?Php echo wp_get_nav_menu_child_items_list( $_POST['seleditparentmenu'], 'edit', $nid ); ?></select></td></tr>
					<tr><td width='20%'><b>Add Name</b></td><td width='30%'><input type='text' onBlur="AjaxFile.DisplayChildMenuInfo()" id='txtmenubox' name='txtmenubox' size='33' /></td><td>&nbsp;</td></tr>
					<tr><td width='20%'><b>Template</b></td><td width='30%'><select onBlur="AjaxFile.DisplayChildMenuInfo()" OnChange='AjaxFile.GetPageTemplate()' id='parent_drop_page_temp' name='parent_drop_page_temp'><?php page_template_dropdown_box();?></select></td><td>&nbsp;</td></tr>
					<tr><td width='20%'><b>Template Php File</b></td><td width='30%'><input type='text' disabled='disabled' id='txtTemplateFile' name='txtTemplateFile' size='33' /></td><td>&nbsp;</td></tr>
					<tr><td width='20%'><b>Wordpress Page Name</b></td><td width='30%'><input type='text' disabled='disabled' id='txtWPPageName' name='txtWPPageName' size='33' /></td><td>&nbsp;</td></tr>
					<tr><td width='20%'><b>URL</b></td><td width='30%'><input type='text' disabled='disabled' id='txtpageurl' name='txtpageurl' size='33' /></td><td>&nbsp;</td></tr>
					<td width='20%'>&nbsp;</td>

				<td width='30%'><input type='checkbox' id='chkboxurl' onClick='AjaxFile.ExternalCheckBox();' name='chkboxurl' size='33' />&nbsp; Go to external URL</td><td>&nbsp;</td>
				</tr>

				<tr>
					<td width='20%'><b>External URL</b></td><td width='30%'><input type='text' id='txtexternalurl' name='txtexternalurl' disabled='disabled' size='33' /></td><td>&nbsp;</td>
				</tr>


					<tr><td width='20%'><input type='hidden' id='home_id' name='home_id' value='<?php echo get_option("homeid"); ?>' />&nbsp;</td><td width='30%'><input value='Add Menu' class='button' onClick='AjaxFile.AddAnotherChildReset()' type='submit' name='BtnChildAddAnother' size='33' />&nbsp;&nbsp;<input class='button' value='Exit' onClick='AjaxFile.FormCancelChild()' type='button' name='BtnCancel' size='33' /></td><td>&nbsp;</td></tr>
				</table>
				</td></tr>
			</table>
		</form>
<?Php } else if( $_GET['action'] == 'editparentmenu' ) {
		$mid = explode('|', $_GET['id'] );
		$pos = wp_get_nav_menu_parent_items_list_pos( $mid[0] ); ?>

		<form method="post" name="PerantMenuEditor" action="?page=site-review-sparkle-site.php&tab=menus&action=editmenusproperties&a=edit">
		<h3>Sparkle Site - Parent Menu Editor</h3>
			<table width='100%' name='parentmenu'>
				<tr>
					<td colspan='2'>
					</td>
				</tr>
				<tr>
					<td colspan='2'>
						<table width='100%' cellspacing='10' id='idcurrentmenu' name='currentmenu'>
							<tr>
								<td width='20%'><b>Add Menu</b></td>
								<td width='30%'>
									<?Php echo HtmlAddMenuType( $_POST['option'] ); ?>
								</td>
								<td><b>Parent Menu </b>
									<select name='selparentbox' id='selparentbox'>
										<?Php echo wp_get_nav_menu_parent_items_list('', $pos ); ?>
									</select>
								</td>
							</tr>
							<tr>
								<td width='20%'><b>Add Name</b></td>
								<td width='30%'>
									<input type='text' onBlur="AjaxFile.DisplayPrntMenuInfo()" id='txtmenubox' value='<?Php echo $post_title; ?>' name='txtmenubox' size='33' />
								</td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td width='20%'><b>Template</b></td>
								<td width='30%'>
									<select OnChange='AjaxFile.GetPageTemplate()' id='parent_drop_page_temp' <?php echo $prnt_template_disabled; ?> name='parent_drop_page_temp'>
										<?php page_template_dropdown_box($parent_meta_values);?>
									</select>
								</td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td width='20%'><b>Template Php File</b></td>
								<td width='30%'>
									<input type='text' disabled='disabled' value='<?php echo $parent_meta_values; ?>' id='txtTemplateFile' name='txtTemplateFile' size='33' />
								</td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td width='20%'><b>Wordpress Page Name</b></td>
								<td width='30%'>
									<input type='text' disabled='disabled' id='txtWPPageName' value='<?Php echo $post_title; ?>' name='txtWPPageName' size='33' />
								</td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td width='20%'><b>URL</b></td>
								<td width='30%'>
									<input type='text' disabled='disabled' id='txtpageurl' value='<?Php echo $purl ; ?>' name='txtpageurl' size='33' />
								</td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td width='20%'>&nbsp;</td>
								<td width='30%'>
									<input type='checkbox' id='chkboxurl' onClick='AjaxFile.ExternalCheckBox();' <?Php echo $prnt_checked; ?> name='chkboxurl' size='33' />&nbsp; Go to external URL</td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td width='20%'><b>External URL</b></td>
								<td width='30%'>
									<input type='text' id='txtexternalurl' name='txtexternalurl' value='<?Php echo $parent_external_url; ?>' <?php echo $prnt_disabled; ?> size='33' /></td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td width='20%'>
									<input value='<?php echo $parent_meta_values; ?>' type='hidden' id='hdnTemplateFile' name='hdnTemplateFile' />
									<input value='<?php echo $_GET[' id ']; ?>' type='hidden' name='menu_id' />&nbsp;
									<input value='<?php echo $PID; ?>' type='hidden' name='pid' />&nbsp;</td>
								<td width='30%'>
									<input value='Done' class='button' type='submit' name='BtnParentEditDone' size='33' />&nbsp;&nbsp;
									<input value='Cancel' class='button' onClick='AjaxFile.FormCancelChild()' type='button' name='BtnCancel' size='33' />
								</td>
								<td>&nbsp;</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</form>

<?Php
}
else if( $_GET['action'] == 'editchildmenu' )
{
?>

		<form method="post" name="ChildMenuEditor" action="?page=site-review-sparkle-site.php&tab=menus&action=editmenusproperties">
		<h3>Sparkle Site - Child Menu Editor</h3>
		<table width='100%' name='parentmenu'>
			<tr><td colspan='2'><table width='100%' height='70' id='idcurrentmenu' name='currentmenu'>
			<tr>
				<td colspan='2'>
					<div id="header">
						<div class="inner">
							<nav id="site-navigation" class="main-navigation" role="navigation">
								<div id="navigation">
									<?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_class' => 'nav-menu' ) ); ?>
								</div>
							</nav>
						</div>
					</div>
				</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
		</table>



		</td>
	</tr>
	<tr>
		<td colspan='2'>
			<table width='100%' cellspacing='10' id='idcurrentmenu' name='currentmenu'>
				<tr>
					<td width='20%'>
						<b>Add Menu</b>
					</td>
					<td width='30%'><?Php echo HtmlAddMenuType( $_POST['option'] ); ?></td>
					<td><b>Child Menu </b></td>
					<td>
						<select name='selchildmenu' id='selchildmenu'><?php echo wp_get_nav_menu_child_items_list(  $_GET['mid']. '|'. $_GET['cid'], '', $_GET['cid'] ); ?></select>
					</td>
				</tr>
				<tr>
					<td width='20%'>
						<b>Add Name</b>
					</td>
					<td width='30%'>
						<input type='text' onBlur="AjaxFile.DisplayChildMenuInfo()" id='txtmenubox' name='txtmenubox' value='<?Php echo $child_post_title; ?>' size='33' />
					</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td width='20%'><b>Template</b></td>
					<td width='30%'>
						<select OnChange='AjaxFile.GetPageTemplate()' id='parent_drop_page_temp' name='parent_drop_page_temp'><?php page_template_dropdown_box($child_meta_values);?></select>
					</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td width='20%'>
						<b>Template Php File</b>
					</td>
					<td width='30%'>
						<input type='text' disabled='disabled' value='<?php echo $child_meta_values; ?>' id='txtTemplateFile' name='txtTemplateFile' size='33' />
					</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td width='20%'>
						<b>Wordpress Page Name</b>
					</td>
					<td width='30%'>
						<input type='text' disabled='disabled' value='<?php echo $child_post_title; ?>' id='txtWPPageName' name='txtWPPageName' size='33' />
					</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td width='20%'>
						<b>URL</b>
					</td>
					<td width='30%'>
						<input type='text' disabled='disabled' id='txtpageurl' value='<?php echo $childurl; ?>'  name='txtpageurl' size='33' />
					</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td width='20%'>&nbsp;</td>
					<td width='30%'>
						<input type='checkbox' id='chkboxurl' onClick='AjaxFile.ExternalCheckBox();' <?Php echo $prnt_checked; ?> name='chkboxurl' size='33' />&nbsp; Go to external URL</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td width='20%'><b>External URL</b></td>
						<td width='30%'>
							<input type='text' id='txtexternalurl' name='txtexternalurl' value='<?Php echo $parent_external_url; ?>' <?php echo $prnt_disabled; ?> size='33' /></td>
						<td>&nbsp;</td>
				</tr>
				<tr>
					<td width='20%'>
						<input type='hidden' id='childurl' name='childurl' value='<?php echo $childurl; ?>' />
						<input type='hidden' id='home_id' name='home_id' value='<?php echo get_option("homeid"); ?>' />
						<input value='<?php echo $child_meta_values; ?>' type='hidden' id='hdnTemplateChildFile' name='hdnTemplateChildFile' />
						<input value='<?php echo $CID; ?>' type='hidden' name='cid' />
						<input value='<?php echo $_GET['cid']; ?>' type='hidden' name='ccid' />&nbsp;
						<input value='<?php echo $_GET['mid']; ?>' type='hidden' name='mmid' />&nbsp;
					</td>
					<td width='30%'>
						<input value='Done' class='button' type='submit' name='BtnChildEditDone' size='33' />&nbsp;&nbsp;
						<input value='Cancel' onClick='AjaxFile.FormCancelChild()' class='button' type='button' name='BtnCancel' size='33' />
					</td>
					<td>&nbsp;</td>
				</tr>
			</table>
			</td>
		</tr>
	</table>
</form>
<?Php
}
else if( $_GET['action'] == 'editmenusproperties' )
{

		sparkle_plugin_activate();
		$ParentMenuId = ( $_GET['pid'] ) ? explode('|', $_GET['pid'] ) : 0; // Delete
		$default = wp_get_nav_menu_parent_items_list_pos( $ParentMenuId[0] ); // On delete action
		$home = explode("|", getHome() );
		if( $_GET['pid'] == '') { ?>
			<script>jQuery(document).ready(function() { AjaxFile.ShowChildMenus( '', 'edit', '' );	});</script>
		<?Php } ?>
		<form method="post" name="ChildMenuEditor" action="?page=site-review-sparkle-site.php&tab=menus&action=editmenusproperties">

			<h3>Sparkle Site - Custom Menus, Pages and Templates</h3>
			<table width='100%' name='tblsparklesite'>
				<tr><td width='50%'>
					<table width='100%' class='menudesc'>
						<tr><th class='tdclr' height='40px' align="center" colspan='2'>Plugin Description</th></tr>
						<tr><td height='120px' colspan='2'><div id='text'>This plugin creates the Menu hierarchy (Parent & Child) for the Sparkle Site Platform. It creates menus, pages associated to menus in WordPress and attaches templates to those pages.</div></td></tr>
					</table>
				</td><td align='right'>
					<table width='90%'>
						<tr><td width='50%' ><input disabled='disabled' checked='checked' type="checkbox" name='home' />&nbsp;<b>Create Home Menu</b><br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(mandatory)</td><td><input type='hidden' name='hdnhome' id="hdnhome" value='<?Php echo $home[1]; ?>' /><input type='text' name='txthome' id="txthome" value='<?Php echo $home[0]; ?>' /><input type='submit' name='SubmitHome' class='button' id="SubmitHome" value='Save Home Menu Text' /></td></tr>
						<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
						<tr><td width='50%' ><input disabled='disabled' checked='checked' type="checkbox" name='home' />&nbsp;<b>Create Search Page</b><br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(mandatory)</td><td><input type='submit' class='button' name='SubmitAddSearch'  id="SubmitAddSearch" value='Add' /></td></tr>
						<!-- <tr><td width='50%' ><input checked='checked' type="checkbox" name='home' />&nbsp;<b>Create Search Page<b><br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(mandatory)</td><td><input type='button' name='btnsearch' id="btnsearch" value='Search' /></td></tr> -->
						<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
						<tr><td colspan='2'><a href='?page=site-review-sparkle-site.php&tab=menus&action=addparentmenu'>Add Parent Menu</a></td></tr>
						<tr><td colspan='2'><a href='?page=site-review-sparkle-site.php&tab=menus&action=addchildmenu'>Add Child Menu</a></td></tr>
					</table>
				</td></tr>
				<!--<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
				<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
				<tr><td width='50%'><b>Current Menu</b></td><td>View Child Menu of <select  name='viewofchildmenu' id='viewofchildmenu'><?Php echo wp_get_nav_menu_parent_items_list( 'edit', $default ); ?></select></td></tr>-->
				<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
				<tr><td colspan='2'>
				<!--
				<div id="header"><div class="inner">
				<nav id="site-navigation" class="main-navigation" role="navigation">
				<div id="navigation"><?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_class' => 'nav-menu' ) ); ?></div>
				</nav>
				</div></div>

				-->
				</td></tr><tr><td>&nbsp;</td><td>&nbsp;</td></tr>
				<tr><td colspan='2'><table width='100%'>
					<tr><td width='15%'><b>Parent Menu</b></td><td width='30%'><select id='seleditparentmenu' OnChange='AjaxFile.ShowChildMenus("","edit","")' name='seleditparentmenu'><?php echo wp_get_nav_menu_parent_items_list( 'edit', $default ); ?></select></td><td><a onClick="AjaxFile.EditParentMenu();" href='#'>Edit</a>&nbsp;&nbsp;&nbsp;<a onClick="AjaxFile.DeleteParentMenu(this.value);" href='#'>Delete</a></td></tr>
					<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
					<tr><td><b>Child Menu</b></td><td><select  id='selchildmenu' name='selchildmenu'><?Php echo wp_get_nav_menu_child_items_list( $default, 'edit', '' ); ?></select></td><td><a onClick="AjaxFile.EditChildMenu();" href='#'>Edit</a>&nbsp;&nbsp;&nbsp;<a onClick="AjaxFile.DeleteChildMenu(this.value);" href='#'>Delete</a></td></tr>
					</table>
				</td></tr>
				<tr><td align='center' colspan='2'>&nbsp;</td></tr>
				<tr><td align='center' colspan='2'><input type='submit' class='button' name='Refresh'  id="Refresh" value='Refresh' /></td></tr>
			</table>
		</form>
<?Php } else { ?>

		<h3>Golfreview Custom Menus -</h3>
		<p><b>ReGenerate Menus</b> - After confirmation, which Drops the Existing menus at once and ReGenerate menus from venice db.</p>
		<p><b>Generate Menus</b> - If there is no menu header then it's Generate menus from venice db.</p>
		<p><b>Edit Menus</b> - Which redirect user to Wp admin's Menus under appearance.</p>
		<p><b>Edit Menus with Properties</b> - Which Shows the Sparkle Site Menu's interface.</p>

		<table name='cat' border='1' width='90%'>
			<tr><td colspan='4'>&nbsp;</td></tr>
			<?Php echo ShowSubHTML( $_GET['PMNODE_LVL'], $_GET['PSNODE_LVL'], $_GET['SMNODE_LVL'], $_GET['SSNODE_LVL'] ); ?>
			<?php echo check_menu_exists($getallmaincategory); ?>
			<tr><td colspan='4'>&nbsp;</td></tr>
		</table>

<?PHP } } else if( $pg == 'templates' ) { include_once('compound-landing-page.php'); ?>



	<form method="post" action="?page=site-review-sparkle-site.php&tab=templates">

		<h3>Template Settings</h3>
		<table name='cat' width='90%'>
			<!--
			<tr><td align='center' colspan='5'>
				<form method="post" action="?page=site-review-sparkle-site.php&tab=templates"><br/>
				Build Template Directory - <select name='TransferTemplate'><option value=''>Transfer Template</option>
				<option value='product'>Product Template</option><option value='location'>Location Template</option>
				<option value='full'>Product+Location Template</option></select>&nbsp;
				<input type='submit' class='button' name='BtnTransferTemplate' value='Move' /></form></td></tr>
			<tr><td colspan='5'>&nbsp;</td></tr>
			<?Php echo ShowPages($getallmaincategory); ?>
			-->



























			<tr>

				<td align="center" colspan="4">
					<br><br>
					<h4>This will import sql files except Search Table</h4>
					<form action="" method="post">
						<input type="submit" class="button" name="sql"  value="Import All SQL Files">
				<?php
					if(isset($_POST['sql']))
					{
						$con1=mysql_connect(DB_HOST,DB_USER,DB_PASSWORD);
	mysql_select_db(DB_NAME);

						// for profile


						$query1 = mysql_query("CREATE TABLE IF NOT EXISTS `profile_answers` (`id` int(255) NOT NULL,  `questionid` int(255) NOT NULL,  `answer` varchar(255) NOT NULL)");

						$query2 = mysql_query("CREATE TABLE IF NOT EXISTS `profile_questions` (`id` int(255) NOT NULL,  `question` varchar(255) NOT NULL,  `qid` varchar(255) NOT NULL,  `page` varchar(255) NOT NULL)");
						$query3 = mysql_query("CREATE TABLE IF NOT EXISTS `user_profile` (`id` int(11) NOT NULL,  `user_id` int(11) NOT NULL,  `user_name` varchar(125) NOT NULL,  `country` varchar(250) NOT NULL,  `zip` varchar(11) NOT NULL,  `gender` varchar(250) NOT NULL,  `time_zone` varchar(250) NOT NULL, `daylight_time` tinyint(1) NOT NULL)");
						$query4 = mysql_query("CREATE TABLE IF NOT EXISTS `user_profile_answers` (`id` int(11) NOT NULL,  `userid` int(11) NOT NULL,  `questionid` int(11) NOT NULL,  `answerid` int(11) DEFAULT NULL,  `productid` int(11) DEFAULT NULL,  `answer` longtext,  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP)");
						$query5 = mysql_query("ALTER TABLE `profile_answers` ADD PRIMARY KEY (`id`)");
						$query6 = mysql_query("ALTER TABLE `profile_questions` ADD PRIMARY KEY (`id`)");
						$query7 = mysql_query("ALTER TABLE `user_profile` ADD PRIMARY KEY (`id`)");
						$query8 = mysql_query("ALTER TABLE `user_profile_answers` ADD PRIMARY KEY (`id`)");


						$query9 = mysql_query("ALTER TABLE `profile_answers` MODIFY `id` int(255) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=11;");
						$query10 = mysql_query("ALTER TABLE `profile_questions` MODIFY `id` int(255) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=24;");
						$query11 = mysql_query("ALTER TABLE `user_profile` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=12;");
						$query12 = mysql_query("ALTER TABLE `user_profile_answers` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=77;");

  $query13 = mysql_query("CREATE TABLE IF NOT EXISTS `wp_vbulltein_user_activation` ( `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY, `userid` int(11) NOT NULL, `security_code` varchar(11) NOT NULL,`email` varchar(255) NOT NULL, `newsletter` INT NOT NULL DEFAULT '0', `hotdealemail` INT NOT NULL DEFAULT '0')");

          $query14 = mysql_query("ALTER TABLE wp_vbulltein_user_activation ADD COLUMN newsletter INT NOT NULL DEFAULT '0'");

         $query15 = mysql_query("ALTER TABLE wp_vbulltein_user_activation ADD COLUMN hotdealemail INT NOT NULL DEFAULT '0'");
         $query16 = mysql_query("ALTER TABLE wp_vbulltein_user_activation ADD COLUMN date_created DATETIME NULL");
         $query17 = mysql_query("ALTER TABLE wp_vbulltein_user_activation ADD COLUMN date_activated DATETIME NULL");

 // 'Hello World!' post
	wp_delete_post( 1, true );

    // 'Sample page' page
    wp_delete_post( 2, true );
						if($query1 && $query2 && $query3 && $query4 && $query5 && $query6 && $query7 && $query8 && $query9 && $query10 && $query11 && $query12 && $query13 && $query14 && $query15 && $query16 && $query17)
						{
							echo "<br>Wordpress Databse Modified for Profile Successfully!...";
						}

include('../wp-reviewconfig.php');
						// for reviews modification
						$con2 = mysqli_connect(DB_RHOST,DB_RUSER,DB_RPASSWORD,DB_RNAME);

						// Check connection
						if (mysqli_connect_errno())
						{
							echo "Failed to connect to MySQL: " . mysqli_connect_error();
						}
						else
						{
							$query13 = mysqli_query($con2,"ALTER TABLE reviews ADD `pros` varchar(2500) DEFAULT NULL, ADD `cons` varchar(2500) DEFAULT NULL, ADD `year_purchased` varchar(25) DEFAULT NULL, ADD `price` varchar(25) DEFAULT NULL, ADD `product_type` varchar(25) DEFAULT NULL COMMENT 'New or Old';");
							if($query13)
							{
								echo "<br>Review table Modified for Pro's and Con's Successfully!...";

							}

						}







					}

				?>

					</form>
					<br><br>
				</td>

			</tr>















			<tr>

				<td align="center" colspan="4">
					<br><br>
					<h4>This will generate all required files from Sparkle Site Plugin in root</h4>
					*Note: Do this only if you copy plugins and themes from bitbucket else not required
					<br><br>
					<form action="" method="post">
						<input type="submit" class="button" name="search" <?php if(file_exists('../ajaxform/ajax.php')){ echo "disabled title='Files already generated...' ";}else{}?> value="Generate All Autosearch Files">
				<?php
					if(isset($_POST['search']))
					{
						if(file_exists('../ajaxform/ajax.php'))
						{
							echo "File already there";
						}
						else
						{
							$zip = new ZipArchive;
							// for folders
							$res = $zip->open('../wp-content/plugins/sparkle-site/search_files/search.zip');
							if ($res === TRUE) {
							  $zip->extractTo('../');
							  $zip->close();
							  echo "<br>All Search Related Folders Created Successfully!...";
							} else {
							  echo '<br>Unable to handle the Zip file!...';
							}


							// for files
							$res1 = $zip->open('../wp-content/plugins/sparkle-site/search_files/assets.zip');
							if ($res1 === TRUE) {
							  $zip->extractTo('../');
							  $zip->close();
							  echo "<br>All Search Related Files Created Successfully!...";
							} else {
							  echo '<br>Unable to handle the Zip file!...';
							}

						}



						//echo DB_HOST;
					}

				?>

					</form>
					<br><br>
				</td>

			</tr>























			<tr>
				<td align="center" colspan="4">
					<br><br>
					<h4>This will create required pages and link to Template</h4>
					<br>
					<form action="" method="post">
						<input type="submit" class="button" name="custompage"  value="Generate All Custom Pages">
					</form>
					<br><br>
					<?php
					if(function_exists('original_guid'))
					{
					}
					else
					{
					function original_guid() {

						$protocol 	= ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
						$domainName = $_SERVER['HTTP_HOST'];

						return $protocol.$domainName;
					}

					}

					if(function_exists('original_create_page_template'))
					{
					}
					else
					{
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


					}



					if(isset($_POST['custompage']))
					{
						//echo DB_HOST;
						original_create_page_template('955371161', 'User Login', 'user-login', 'userlogin.php', 0, 0 );
						original_create_page_template('955371160', 'User Activation', 'user-activation', 'useractivation.php', 0, 0 );
						original_create_page_template('955371159', 'User Profile', 'user-profile', 'userprofile.php', 0, 0 );
						original_create_page_template('955371158', 'User Profile 1', 'user-profile-1', 'userprofile1.php', 0, 0 );
						original_create_page_template('955371157', 'User Profile 2', 'user-profile-2', 'userprofile2.php', 0, 0 );
						original_create_page_template('955371156', 'User Profile 3', 'user-profile-3', 'userprofile3.php', 0, 0 );
						original_create_page_template('955371155', 'User Registration', 'user-registration', 'userregistration.php', 0, 0 );
						original_create_page_template('955371154', 'User Request Password', 'user-requestpassword', 'userrequestpassword.php', 0, 0 );
						original_create_page_template('955371153', 'User Reset Password', 'user-resetpassword', 'userresetpassword.php', 0, 0 );
						original_create_page_template('955371152', 'Search', 'search', 'search.php', 0, 0 );
						original_create_page_template('955371151', 'CommerceRedirect', 'commerceredirect', 'commerce-redirect-template.php', 0, 0 );
						original_create_page_template('955371150', 'Brands', 'brands', 'site-brand-page.php', 0, 0 );
						original_create_page_template('955371149', 'Partners', 'partners', 'partners.php', 0, 0 );
						original_create_page_template('955371148', 'Latest Reviews', 'latestreviews', 'site_top_reviews_page.php', 0, 0 );
						original_create_page_template('955371147', 'Ajax Hotdeals', 'ajax-hotdeals', 'page-ajax-hotdeals.php', 0, 0 );
						original_create_page_template('955371146', 'User Profile 4', 'user-profile-4', 'userprofile4.php', 0, 0 );
						original_create_page_template('955371145', 'Ajax Products', 'ajax-products', 'page-ajax-products.php', 0, 0 );
						original_create_page_template('955371144', 'terms', 'terms-of-use', 'terms.php', 0, 0 );
                        original_create_page_template('955371143', 'privacy', 'privacy-policy', 'privacy.php', 0, 0 );
                        original_create_page_template('955371142', 'advertise with us', 'advertise-with-us', 'advertise.php', 0, 0 );
                        original_create_page_template('955371141', 'advertise success', 'advertise-success', 'advertise_redirect.php', 0, 0 );
                        original_create_page_template('955371140', 'logout', 'logout', 'user-logout.php', 0, 0 );
                        original_create_page_template('955371139', 'latestproducts', 'latest-products', 'site_latest_product_page.php', 0, 0 );
                        original_create_page_template('955371138', 'Latest Reviews', 'latest-reviews', 'site_latest_reviews_page.php', 0, 0 );
                        original_create_page_template('955371137', 'most popular products', 'most-popular-products', 'site_top_reviews_page.php', 0, 0 );
                        original_create_page_template('955371136', 'Ajax Medallian', 'ajax-medallian', 'page-ajax-medallian.php', 0, 0 );
			original_create_page_template('955371135', 'forum rules', 'forum-rules', 'forum-rules.php', 0, 0 );

						echo "All Pages Created Successfully!...";
					}
					?>
				</td>
			</tr>




























			<tr>

				<td align="center" colspan="4">
					<br><br>
					<h2>Settings for Extra Pages</h2>
					<br><br>



						<?php
						if(isset($_POST['temppara']))
						{
							update_option( 'topreviews', $_POST['topreviews'] );
							update_option( 'topreviewscount', $_POST['topreviewscount'] );
							update_option( 'latestproducts', $_POST['latestproducts'] );
							update_option( 'latestproductscount', $_POST['latestproductscount'] );
							update_option( 'latestreviews', $_POST['latestreviews'] );
							update_option( 'latestreviewscount', $_POST['latestreviewscount'] );
							update_option( 'defaultsort', $_POST['defaultsort'] );
							update_option( 'latestproduct', $_POST['latestproduct'] );
							update_option( 'archivedproducts', $_POST['archivedproducts'] );
						}
						?>




						<form action="" method="post">
						<h3>Most Popular Products</h3><br>
						<fieldset>
						<p>Heading Title:
						<input type="text" value="<?php echo get_option('topreviews'); ?>" name="topreviews">
						 </p>
						<br>

						<p>No. of Products in Page Listing:
						<input type="text" value="<?php echo get_option('topreviewscount'); ?>" name="topreviewscount">
						 </p>
						<br>
						</fieldset>



						<h3>Latest Products</h3><br>
						<fieldset>
						<p>Heading Title:
						<input type="text" value="<?php echo get_option('latestproducts'); ?>" name="latestproducts">
						 </p>
						<br>

						<p>No. of Products in Page Listing:
						<input type="text" value="<?php echo get_option('latestproductscount'); ?>" name="latestproductscount">
						 </p>
						<br>
						</fieldset>



						<h3>Latest Reviews</h3><br>
						<fieldset>
						<p>Heading Title:
						<input type="text" value="<?php echo get_option('latestreviews'); ?>" name="latestreviews">
						 </p>
						<br>

						<p>No. of Products in Page Listing:
						<input type="text" value="<?php echo get_option('latestreviewscount'); ?>" name="latestreviewscount">
						 </p>
						<br>
						</fieldset>



						<h3>Product List Sorting</h3><br>
						<fieldset>
						<p>Sorting Default Option:
						<?php  $defaultsort = get_option("defaultsort"); ?>
							<select style="    border: 1px solid #ccc;    padding: 2px;" name="defaultsort">
								<option value="reviews" <?php if($defaultsort == "reviews"){ echo "selected";}?>>Latest Reviews</option>
								<option value="latest" <?php if($defaultsort == "latest"){ echo "selected";}?>>Latest Products</option>
								<option value="score" <?php if($defaultsort == "score"){ echo "selected";}?>>Best Reviews</option>
								<option value="maxreviews" <?php if($defaultsort == "maxreviews"){ echo "selected";}?>>Most Reviews</option>
								<option value="views" <?php if($defaultsort == "views"){ echo "selected";}?>>Most Popular</option>
								<option value="asc" <?php if($defaultsort == "asc"){ echo "selected";}?>>Alphabetical</option>
							</select>
						 </p>
						<br>
						</fieldset>



						<fieldset>
						<p>Flag for Latest Products:
						<?php  $latestproduct = get_option("latestproduct"); ?>
							<select style="    border: 1px solid #ccc;    padding: 2px;" name="latestproduct">
								<option value="enable" <?php if($latestproduct == "enable"){ echo "selected";}?>>Enable Latest Products</option>
								<option value="disable" <?php if($latestproduct == "disable"){ echo "selected";}?>>Disable Latest Products</option>
							</select>
						 </p>
								<p style="    font-style: italic;
    color: cornflowerblue;"><?php if($latestproduct == "disable"){ echo "If 'Latest Products' option disabled and it was selected Default in the above 'Default Sorting' drop-down,<br> It will take 'Latest Reviews' as Default Sorting Option";}?></p>
						<br>
						<br>
						</fieldset>




						<fieldset>
						<p>Flag for Archived Products:
						<?php  $archivedproducts = get_option("archivedproducts"); ?>
							<select style="    border: 1px solid #ccc;    padding: 2px;" name="archivedproducts">
								<option value="1" <?php if($archivedproducts == "1"){ echo "selected";}?>>Enable Archived Products</option>
								<option value="0" <?php if($archivedproducts == "0"){ echo "selected";}?>>Disable Archived Products</option>
							</select>
						 </p>
						<br>
						<br>
						</fieldset>





						<input type="submit" class="button" name="temppara" value="Add Template Parameters">


					</form>
					<br><br>
				</td>

			</tr>




















		</table>



	</form>




<?PHP } else if( $pg == 'promo-queue' ) {  include_once('article-promo-queues.php'); ?>

	<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
		<h3>Article Promo Queues -</h3>
		<table name='cat' id='promo-queue' width='100%'>
			<tr><td colspan='5'>&nbsp;</td></tr>
			<?Php echo ShowSubHTML( $_GET['PMNODE_LVL'], $_GET['PSNODE_LVL'], $_GET['SMNODE_LVL'], $_GET['SSNODE_LVL'], $_GET['TMNODE_LVL'], $_GET['TSNODE_LVL'] ); ?>
			<tr><td colspan='5'>&nbsp;</td></tr>
			<tr><td colspan='5' align='center'><input onClick='View_Promo_List()' type='button' name='ViewPromoList' class='button' value='View Promo List' /></td></tr>
			<?Php
				if( $_GET['action'] == 'GeneratePromoQueue' ) {

					echo get_top_products_promo_queues_list($getallpages);
					echo get_featured_article_promo_queues_list($getallpages);
				}
			?>
		</table>
	</form>

<?PHP } else if( $pg == 'colors' ) {

	$db = explode("|", get_option("dbconnection") );
	if ( !CheckMysqlCon( $db[3], $db[0], $db[1] ) ) {

		echo '<div id="message" class="updated fade"><p>Please go to Connection Tab, and then try again ?</p></div>';
		exit;
	}

	if( !get_option( 'PSiteName' ) && !get_option( 'SSiteName' ) ) {

		echo '<div id="message" class="updated fade"><p>Please go to Connect Tab, and set atleast one site name ?</p></div>';
		exit;
	}
?>
		<h3>Theme Colors and Gradient -</h3>
		<table name='cat' id='promo-queue' width='100%'>
			<tr>
				<td colspan='4'>
					<!--<div id="header">
						<div class="inner">
							<a style="float:left; margin-left:30px;" href="<?php bloginfo('url') ?>"><img src="<?php bloginfo('template_url') ?>/images/logo.png" /></a>
							<nav id="site-navigation" style="margin-left:30px;" class="main-navigation" role="navigation">
								<div id="navigation">
									<?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_class' => 'nav-menu' ) ); ?>
								</div>
							</nav>

						</div>
					</div>-->
					</td>
			</tr>



			<tr>
				<td colspan='4' style="text-align: center;">
				<br><br>



					<form name="dlogo" method="post" action=""  enctype="multipart/form-data">
						<input type="file" name="fileToUpload" id="fileToUpload" >
						<input type="submit" name="logo" class="button" value="Change Logo">
						*Note: Logo Dimension should be (113px X 55px).png
					</form>

				<?php
				if(isset($_POST['logo']))
				{
					$target_dir = "../wp-content/themes/site/images/";
					$photo = $target_dir . basename($_FILES["fileToUpload"]["name"]);
					$photoType = pathinfo($photo,PATHINFO_EXTENSION);
					$photoname = "logo.".$photoType;

					if(move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_dir . $photoname)) {
						echo "File uploaded successfully!";
					} else{
						echo "Sorry, file not uploaded, please try again!";
					}
				}
				?>

				<h4>Available Desktop Logo</h4>
				<?php
				if(file_exists("../wp-content/themes/site/images/logo.png"))
				{
						echo "<img style='max-width:150px' src='../wp-content/themes/site/images/logo.png'>";
						echo "<pre style='border: 2px dashed #03A9F4; background: #fff;'>https://".$_SERVER[HTTP_HOST]."/wp-content/themes/site/images/logo.png</pre>";
				}
				else
				{
						echo "<pre style='border: 2px dashed #03A9F4; background: #fff;'>No Logo Available... Upload Some</pre>";
				}
				?>


				</td>
			</tr>
			<tr>
				<td colspan='4' style="text-align: center;">
				<br><br>




					<form name="mdlogo" method="post" action=""  enctype="multipart/form-data">
						<input type="file" name="fileToUpload" id="fileToUpload" >
						<input type="submit" name="mlogo" class="button" value="Change Mobile Logo">
						*Note: Logo Filetype should be .png
					</form>



				<?php
				if(isset($_POST['mlogo']))
				{
					$target_dir = "../wp-content/themes/site/images/";
					$photo = $target_dir . basename($_FILES["fileToUpload"]["name"]);
					$photoType = pathinfo($photo,PATHINFO_EXTENSION);
					$photoname = "mlogo.".$photoType;

					if(move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_dir . $photoname)) {
						echo "File uploaded successfully!<br>";
						//echo "<img src='../wp-content/themes/site/images/mlogo.".$photoType."'>";
						//echo "<pre style='border: 2px dashed #03A9F4; background: #fff;'>http://".$_SERVER[HTTP_HOST]."/wp-content/themes/site/images/mlogo.".$photoType."</pre>";
					} else{
						echo "Sorry, file not uploaded, please try again!";
					}
				}
				?>

				<h4>Available Mobile Logo</h4>
				<?php
				if(file_exists("../wp-content/themes/site/images/mlogo.png"))
				{
						echo "<img style='max-width:150px' src='../wp-content/themes/site/images/mlogo.png'>";
						echo "<pre style='border: 2px dashed #03A9F4; background: #fff;'>https://".$_SERVER[HTTP_HOST]."/wp-content/themes/site/images/mlogo.png</pre>";
				}
				else
				{
						echo "<pre style='border: 2px dashed #03A9F4; background: #fff;'>No Logo Available... Upload Some</pre>";
				}
				?>


				</td>
			</tr>







		<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">

			<tr>
				<td colspan='4'>&nbsp;</td>
			</tr>
			<tr>
				<td colspan='4' style="background-color:#FFF; height:30px ">&nbsp;&nbsp;<b>Main Element Items - Headers & Menus</b></td>
			</tr>
			<tr>
				<td colspan='4'>&nbsp;</td>
			</tr>






			<!-- For main menu gradient -->

			<tr>
				<td align='center' width='50%' valign='top' colspan='2'><input type='button' href="#Menu_Gradient_Dialog" name="modal" name='MainMenuGradient' OnClick='AjaxFile.MainMenuGradient();' class='button' value='Main Menu Gradient' /></td>

				<td width='50%' colspan='2'>
					<nav id="site-navigation" class="main-navigation nav-site sample-site-navigation" role="navigation">
						<div id="navigation">
							<div class='reviewAppOO7-menu-menu_header-container'>
								<ul id='menu-menu_header' class='nav-menu samle_main_menu'>
									<li class='inactive'><a href="#">Home</a></li>
									<li class='current-menu-item active'><a href="#">Golf Clubs</a></li>
									<li class='inactive'><a href="#">Golf Deals</a></li>
								</ul>
							</div>
						</div>
					</nav>
				</td>
			</tr>

			<!-- For main menu gradient -->



			<tr>
				<td colspan='4'>&nbsp;</td>
			</tr>
			<tr>
				<td align='center' width='50%' colspan='2'>&nbsp;</td>
				<td width='50%' colspan='2'>
					<input type='button' name='MainActiveMenuColor' class='button' OnClick="AjaxFile.MainActiveMenuTextColor();" value='Main Active Menu Text Color' />&nbsp;&nbsp;
					<input type='button' OnClick='AjaxFile.MainInactiveMenuColor();' name='MainInactiveMenuColor' class='button' value='Main Inactive Menu Text Color' />
				</td>
			</tr>
			<tr>
				<td colspan='4'>&nbsp;</td>
			</tr>




			<!-- For sub menu gradient -->

			<tr>
				<td align='center' width='50%' colspan='2'>
					<input type='button' href="#Menu_Gradient_Dialog" name="modal" OnClick='AjaxFile.SubMenuGradient();' class='button' value='Sub Menu Gradient' />
				</td>
				<td width='50%' colspan='2'>
					<div class='header_sub_menu_sample'>
						<div class="inner">
							<ul class='sub-menu-sample'>
								<li class="inactive" id="menu-item-544"><a href="#">DRIVERS</a></li>
								<li class="active"><a href="#">HYBRIDS</a></li>
								<li class="inactive"><a href="#">PUTTERS</a></li>
								<li class="inactive"><a href="#">WEDGES</a></li>
							</ul>
				</td>
			</tr>

			<!-- For sub menu gradient -->



			<tr>
				<td colspan='4'>&nbsp;</td>
			</tr>
			<tr>
				<td  align='center' width='50%' colspan='2'>

					<input name='Enable3D' id='enable3d' onClick='AjaxFile.Enable3D();' type='checkbox' />&nbsp; Sub Active 3D Enable

				</td>
				<td width='50%' colspan='2'>
					<input type='button' OnClick='AjaxFile.SubActiveMenuColor();' name='SubActiveMenuColor' class='button' value='Sub Active Menu Text Color' />&nbsp;&nbsp;
					<input type='button' OnClick='AjaxFile.SubInactiveMenuColor();' name='SubInactiveMenuColor' class='button' value='Sub Inactive Menu Text Color' />
				</td>
			</tr>
			<tr>
				<td align='center' width='50%' colspan='2'>&nbsp;</td>
				<td width='50%' colspan='2'>
					<input type='button' OnClick='AjaxFile.SubActiveMenuBckrund();' name='SubActiveMenuBckrund' class='button' value='Sub Active Menu Background' />
					<input type='button' OnClick='AjaxFile.SubActive3DTop();' checked id='3dtop' name='SubActive3DTop' class='button' value='Sub Active 3D Top' />
				</td>
			</tr>
			<tr>
				<td align='center' width='50%' colspan='2'>&nbsp;</td>
				<td width='50%' colspan='2'>
					<input type='button' OnClick='AjaxFile.SubActive3DBottom();'  id='3dbottom' name='SubActive3DBottom' class='button' value='Sub Active 3D Bottom' />
					<br>
					<br>
					<div style="height:26px; width:150px;    background: #e9e9e9 url('/wp-content/themes/site/images/top-bg.png') top left repeat-x;">
						<p  class="TopLoginRegister"><strong style="margin: 12px 13px 13px 22px;">Login / Register</strong></p>
					</div>
					<br>
					<input type='button' OnClick='AjaxFile.TopLoginRegister();'  id='TopLoginRegister' name='TopLoginRegister' class='button' value='Top Login/Register' />
					<br><br><br>
					<div style="width:100%;    background-color: #474739; text-align:center;">
					<br>
						<p class="FooterColor"><strong style="margin: 12px 13px 13px 22px;">(C) Copyright 1996-2017. All Rights Reserved.</strong></p>
						<br>
					</div>
					<br>
					<input type='button' OnClick='AjaxFile.FooterColor();'  id='FooterColor' name='FooterColor' class='button' value='Footer Text Color' />
				</td>
			</tr>
			<tr>
				<td colspan='4'>&nbsp;</td>
			</tr>
			<tr>
				<td align='center' colspan='4'>
					<input name='fontfamily' checked='checked' id='fontfamily' onClick='AjaxFile.GetFontFamilyToSecondElem();' type='checkbox' />&nbsp; Secondary Element Section </td>
			</tr>
			<tr>
				<td colspan='4'>&nbsp;</td>
			</tr>
			<tr>
				<td colspan='4' style="background-color:#FFF; height:30px ">&nbsp;&nbsp;<b>Secondary Element Items - Main Menu Gradient</b></td>
			</tr>
			<tr>
				<td colspan='4'>&nbsp;</td>
			</tr>
			<tr>
				<td align='center' width='50%' valign='top' colspan='2'>
					<input type='button' href="#Menu_Gradient_Dialog" name="modal" OnClick='AjaxFile.ArticleSectionHeaderGradient();' class='button thishastodisable' value='Article Section Header Gradient' />
				</td>
				<td>
					<div class="text-header text-header-sample">
						<h3>Recent Articles &amp; Reviews</h3></div>
				</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td colspan='4'>&nbsp;</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>
					<input type='button' name='ArticleHeaderTextColor' class='button thishastodisable' OnClick="AjaxFile.ArticleHeaderTextColor();" value='Article Second Header Text Color' />
				</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td colspan='4'>&nbsp;</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>
					<div class="title headertxtcolor"><a href="#">Callaway RAZR Hawk Fairway Woods</a></div>
				</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td colspan='4'>&nbsp;</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>
					<input type='button' name='ArticleTitleTextColor' class='button thishastodisable' OnClick="AjaxFile.ArticleTitleTextColor();" value='Article Title Text Color' />
				</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td colspan='4'>&nbsp;</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>
					<div id="footer-separator" class='bottomdivider'></div>
				</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td colspan='4'>&nbsp;</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>
					<input type='button' name='ArticleHeaderBottomDividerColor' class='button thishastodisable' OnClick="AjaxFile.ArticleHeaderBottomDividerColor();" value='Article Header Bottom Divider Color' />
				</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td colspan='4'>&nbsp;</td>
			</tr>
			<!-- For Circle Arrow Image -->
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td><a href="javascript:ss.hotlink()" class="arrow-right">read more</a></td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td colspan='4'>&nbsp;</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>
					<input type='button' name='CircleArrowImage' class='button thishastodisable' OnClick="AjaxFile.CircleArrowImage();" value='Circle Arrow Image' />
				</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td colspan='4'>&nbsp;</td>
			</tr>
			<tr>
				<td colspan='4' style="background-color:#FFF; height:30px ">&nbsp;&nbsp;<b>Secondary Element Items - Sub Menu Gradient</b></td>
			</tr>
			<tr>
				<td colspan='4'>&nbsp;</td>
			</tr>
			<!-- For Home Page Featured Golf Deals Background -->
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td class='featured-golf-deals-sample'>Home Page >> Featured Golf Deals Background</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td colspan='4'>&nbsp;</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>
					<input type='button' name='FeaturedGolfDealsBackgroundColor' class='button thishastodisable' OnClick="AjaxFile.FeaturedGolfDealsBackgroundColor();" value='Featured Golf Deals Background Color' />
				</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td colspan='4'>&nbsp;</td>
			</tr>
			<!-- For Home Page Best Golf Clubs Background -->
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td class='best-golf-clubs-sample'>Home Page >> Best Golf Clubs Gradient</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td colspan='4'>&nbsp;</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>
					<input type='button' href="#Menu_Gradient_Dialog" name="modal" class='button thishastodisable' OnClick="AjaxFile.BestGolfClubsGradient();" value='Best Golf Clubs Gradient' />
				</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td colspan='4'>&nbsp;</td>
			</tr>
			<tr>
				<td colspan='4' style="background-color:#FFF; height:30px ">&nbsp;&nbsp;<b>Secondary Element Items - Carousel Controls</b></td>
			</tr>
			<tr>
				<td colspan='4'>&nbsp;</td>
			</tr>
			<tr>
				<td colspan='4'>&nbsp;</td>
			</tr>
			<tr>
				<td align='center' colspan='4'>
					<input type='button' class='button' onClick='AjaxFile.CreateCSS()' name="createcss" value='Apply' />

				</td>
			</tr>
			<tr>
				<td colspan='4'>&nbsp;</td>
			</tr>
		</table>
	</form>






<center>
	<form method="post" action="options-general.php?page=site-review-sparkle-site.php&tab=colors">
		<input type='submit' class='button' name="restoredb" value='Restore from DB' />
	</form>
</center>

<?php

if(isset($_POST['BtnS3Save']))
{
	update_option("CDN_DOMAIN",$_POST['CDN_DOMAIN']);
	update_option("S3_BUCKET",$_POST['S3_BUCKET']);
	update_option("S3_FOLDER",$_POST['S3_FOLDER']);
	update_option("AWS_KEY",$_POST['AWS_KEY']);
	update_option("AWS_SECRET",$_POST['AWS_SECRET']);
	update_option("AWS_REGION",$_POST['AWS_REGION']);
	echo "<script>alert('S3 Settings Updated!');</script>";
}


if(isset($_POST['BtnImageS3Save']))
{
	update_option("IMAGE_CDN_DOMAIN",$_POST['IMAGE_CDN_DOMAIN']);
	update_option("IMAGE_S3_BUCKET",$_POST['IMAGE_S3_BUCKET']);
	update_option("IMAGE_S3_FOLDER",$_POST['IMAGE_S3_FOLDER']);
	update_option("IMAGE_AWS_KEY",$_POST['IMAGE_AWS_KEY']);
	update_option("IMAGE_AWS_SECRET",$_POST['IMAGE_AWS_SECRET']);
	update_option("IMAGE_AWS_REGION",$_POST['IMAGE_AWS_REGION']);
	echo "<script>alert('Product Image S3 Settings Updated!');</script>";
}
?>
	<form method="post" action="options-general.php?page=site-review-sparkle-site.php&tab=colors">
		<h3>Amazon S3 Settings :-</h3>
			<table style='margin-left:50px;' name='p_ads' id='promo-queue' width='70%'>
				<tr>
					<td width='70%'>&nbsp;</td>
					<td width='30%'>&nbsp;</td>
				</tr>
				<tr>
					<td>
						Enter CDN Domain &nbsp;&nbsp;&nbsp;<input type="text" name="CDN_DOMAIN"  value="<?php echo get_option('CDN_DOMAIN'); ?>" placeholder="Domain Name here"> <br>
					</td>
					<td>https://s3.amazon.com</td>
				</tr>
				<tr>
					<td>
						Enter S3 Bucket &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="S3_BUCKET"  value="<?php echo get_option('S3_BUCKET'); ?>" placeholder="S3 Bucket Name here"> <br>
					</td>
					<td>invenda.mtbr.com</td>
				</tr>
				<tr>
					<td>
						Enter S3 Folder &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="S3_FOLDER"  value="<?php echo get_option('S3_FOLDER'); ?>" placeholder="S3 Folder Name here"> <br>
					</td>
					<td>golfreview (should NOT end with slash '/')</td>
				</tr>
				<tr>
					<td>
						Enter AWS KEY &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="AWS_KEY"  value="<?php echo get_option('AWS_KEY'); ?>" placeholder="AWS Key here"> <br>
					</td>
					<td>Leave blank to use EC2 instance profile creds</td>
				</tr>
				<tr>
					<td>
						Enter AWS SECRET &nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="AWS_SECRET"  value="<?php echo get_option('AWS_SECRET'); ?>" placeholder="AWS Secret here"> <br>
					</td>
					<td>Leave blank to use EC2 instance profile creds</td>
				</tr>
				<tr>
					<td>
						Enter AWS REGION &nbsp;&nbsp;&nbsp;<input type="text" name="AWS_REGION"  value="<?php echo get_option('AWS_REGION'); ?>" placeholder="AWS Region here"> <br>
					</td>
					<td>us-west-2 (CR default)</td>
				</tr>
				<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
				<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
				<tr><td>&nbsp;<input type='submit' class='button' name="BtnS3Save" value='Update Settings'  /></td><td></td></tr>
			</table>
	</form>


	<form method="post" action="options-general.php?page=site-review-sparkle-site.php&tab=colors">
		<h3>Amazon S3 Settings(Product Image) :-</h3>
			<table style='margin-left:50px;' name='p_ads' id='promo-queue' width='70%'>
				<tr>
					<td width='70%'>&nbsp;</td>
					<td width='30%'>&nbsp;</td>
				</tr>
				<tr>
					<td>
						Enter Image CDN Domain &nbsp;&nbsp;&nbsp;<input type="text" name="IMAGE_CDN_DOMAIN"  value="<?php echo get_option('IMAGE_CDN_DOMAIN'); ?>" placeholder="Domain Name here"> <br>
					</td>
					<td>https://s3.amazon.com</td>
				</tr>
				<tr>
					<td>
						Enter S3 Bucket &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="IMAGE_S3_BUCKET"  value="<?php echo get_option('IMAGE_S3_BUCKET'); ?>" placeholder="S3 Bucket Name here"> <br>
					</td>
					<td>invenda.mtbr.com</td>
				</tr>
				<tr>
					<td>
						Enter S3 Folder &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="IMAGE_S3_FOLDER"  value="<?php echo get_option('IMAGE_S3_FOLDER'); ?>" placeholder="S3 Folder Name here"> <br>
					</td>
					<td>golfreview (should NOT end with slash '/')</td>
				</tr>
				<tr>
					<td>
						Enter AWS KEY &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="IMAGE_AWS_KEY"  value="<?php echo get_option('IMAGE_AWS_KEY'); ?>" placeholder="AWS Key here"> <br>
					</td>
					<td>Leave blank to use EC2 instance profile creds</td>
				</tr>
				<tr>
					<td>
						Enter AWS SECRET &nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="IMAGE_AWS_SECRET"  value="<?php echo get_option('IMAGE_AWS_SECRET'); ?>" placeholder="AWS Secret here"> <br>
					</td>
					<td>Leave blank to use EC2 instance profile creds</td>
				</tr>
				<tr>
					<td>
						Enter AWS REGION &nbsp;&nbsp;&nbsp;<input type="text" name="IMAGE_AWS_REGION"  value="<?php echo get_option('IMAGE_AWS_REGION'); ?>" placeholder="AWS Region here"> <br>
					</td>
					<td>us-west-2 (CR default)</td>
				</tr>
				<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
				<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
				<tr><td>&nbsp;<input type='submit' class='button' name="BtnImageS3Save" value='Update Settings'  /></td><td></td></tr>
			</table>
	</form>

<?php
if(isset($_POST['generateAssetsVersion']))
{
	//Cache Version
	update_option("cache_version","?version=1.0.".rand(10,99));
	echo "<script>alert('CSS and JS cache cleared');</script>";

}
?>
	<form method="post" action="options-general.php?page=site-review-sparkle-site.php&tab=colors">
		<h3>Refresh CSS and JS cache</h3>
			<table style='margin-left:50px;' name='p_ads' id='promo-queue' width='70%'>
				<tr><td width='70%'>&nbsp;</td><td width='30%'>&nbsp;</td></tr>
				<tr>
					<td>Click below to Refresh CSS and JS cache</td>
				</tr>
				<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
				<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
				<tr><td>&nbsp;<input type='submit' class='button' name="generateAssetsVersion" value='Update Version ID'  /></td><td></td></tr>
			</table>
	</form>

<?php


if(isset($_POST['restoredb']))
{
	$header_full = get_option("menu_gradient_full");
	$style_full = get_option("style_gradient_full");
	echo "<script>alert('Restored from Database".$header_full."');</script>";

	$headerWidgetCssFile = get_template_directory() . "/header-widget.css";
	file_put_contents($headerWidgetCssFile, $header_full);

	$file = get_template_directory() . "/style-gradient.css";
	file_put_contents( $file, $style_full);


	update_option("cache_version","?version=1.0.".rand(10,99));


	// Upload files to S3
	uploadFileToS3('style-gradient.css', __DIR__ . '/../../themes/site/style-gradient.css', 'text/css');
	uploadFileToS3('header-widget.css', __DIR__ . '/../../themes/site/header-widget.css', 'text/css');
		uploadFileToS3('topnav-bg.png', __DIR__ . '/../../themes/site/images/topnav-bg.png', 'image/png');
		uploadFileToS3('search-bg.png', __DIR__ . '/../../themes/site/images/search-bg.png', 'image/png');
		uploadFileToS3('topnav-cur.png', __DIR__ . '/../../themes/site/images/topnav-cur.png', 'image/png');
		uploadFileToS3('top-bg.png', __DIR__ . '/../../themes/site/images/top-bg.png', 'image/png');
		uploadFileToS3('top-bg-line.png', __DIR__ . '/../../themes/site/images/top-bg-line.png', 'image/png');

}
?>

<?PHP } else if( $pg == 'promo-ads' ) { include_once('promo-ads.php'); ?>

	<!--<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
		<h3>Promoted Content -</h3>
			<table style='margin-left:50px;' name='p_ads' id='promo-queue' width='70%'>
				<tr><td width='10%'>&nbsp;</td><td width='75%'>&nbsp;</td></tr>
				<tr><td>Promo Ads Title :</td><td><input name='promoadstitle' type='box' size='45' value='Callaway RAZR Hawk Fairway Woods' /></td></tr>
				<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
				<tr><td>Promo Ads Content :</td><td><textarea name='promoadscont' type='box' rows='3' cols='36'>Callaway RAZR Hawk Fairway Woods</textarea></td></tr>
				<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
				<tr><td><input name='Carousel' type='checkbox' checked='checked' value='Carousel' /></td><td>Select Promoted Ads & Carousel.</td></tr>
				<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
				<tr><td><input name='FullAds' type='checkbox' checked='checked' value='FullAds' /></td><td>Select Promoted Ads & Carousel and Full Advertisement.</td></tr>
				<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
				<tr><td>&nbsp;</td><td><input type='submit' class='button' name="BtnAdsSave" value='Save'  /></td></tr>
			</table>
	</form>-->






<?php
if(isset($_POST['BtnBotSave']))
{
	$option = "bot_black_list";
	$value = $_POST['botslist'];
	update_option( $option, $value);

}
if(isset($_POST['BtnAnalyticsSave']))
{
	$option = "google_analytics";
	$value = $_POST['analytics'];
	update_option( $option, $value);

}
if(isset($_POST['BtnAdvertiseSave']))
{
	$option = "advertiseformid";
	$value = $_POST['cf7'];
	$option1 = "advertiseformidIE";
	$value1 = $_POST['cf7ie'];
	update_option( $option, $value);
	update_option( $option1, $value1);

}
if(isset($_POST['BtnNoPhoto']))
{
	$option = "nophotoformid";
	$value = $_POST['nophoto'];
	update_option( $option, $value);

}

$ga = get_option("google_analytics");

?>

	<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
		<h3>Commerce Redirect Bots Blacklist List -</h3>
			<table style='margin-left:50px;' name='p_ads' id='promo-queue' width='70%'>
				<tr><td width='70%'>&nbsp;</td><td width='30%'>&nbsp;</td></tr>
				<tr>
					<td>
						<textarea rows="8" placeholder="GoogleBot,YahooBot etc..." required style="width: 100%!important;" type="text" name="botslist"><?php echo get_option('bot_black_list');?></textarea>
					</td>
					<td>Enter Bot's List as comma seperated value <br> Ex. GoogleBot,YahooBot</td>
				</tr>
				<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
				<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
				<tr><td>&nbsp;<input type='submit' class='button' name="BtnBotSave" value='Update Blacklist'  /></td><td></td></tr>
			</table>
	</form>
	<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
		<h3>Google Analytics -</h3>
			<table style='margin-left:50px;' name='p_ads' id='promo-queue' width='70%'>
				<tr><td width='70%'>&nbsp;</td><td width='30%'>&nbsp;</td></tr>
				<tr>
					<td>
						<input type="checkbox" name="analytics" <?php if($ga == "enable"){echo "checked";}else{ echo "";} ?> value="enable"> Google Analytics <br>
					</td>
					<td>Check to Enable Google Analytics</td>
				</tr>
				<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
				<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
				<tr><td>&nbsp;<input type='submit' class='button' name="BtnAnalyticsSave" value='Update Analytics'  /></td><td></td></tr>
			</table>
	</form>
	<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
		<h3>Advertise With Us -</h3>
			<table style='margin-left:50px;' name='p_ads' id='promo-queue' width='70%'>
				<tr><td width='70%'>&nbsp;</td><td width='30%'>&nbsp;</td></tr>
				<tr>
					<td>
						<input type="text" name="cf7" value="<?php echo get_option("advertiseformid");?>"> Advertise Form Id [With Captcha]<br>
						<input type="text" name="cf7ie" value="<?php echo get_option("advertiseformidIE");?>"> Advertise Form Id For IE8/9[Without Captcha]<br>
					</td>
					<td>Check to Enable Google Analytics</td>
				</tr>
				<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
				<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
				<tr><td>&nbsp;<input type='submit' class='button' name="BtnAdvertiseSave" value='Update Form ID'  /></td><td></td></tr>
			</table>
	</form>
	<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
		<h3>No Photo Images Flag -</h3>
			<table style='margin-left:50px;' name='p_ads' id='promo-queue' width='70%'>
				<tr><td width='70%'>&nbsp;</td><td width='30%'>&nbsp;</td></tr>
				<tr>
					<td>
						<input type="checkbox" name="nophoto" <?php $flag=get_option("nophotoformid");?> value="1" <?php if($flag == "1"){echo "checked";}else{ echo "";} ?>> Sort products with pictures<br>
					</td>
					<td></td>
				</tr>
				<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
				<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
				<tr><td>&nbsp;<input type='submit' class='button' name="BtnNoPhoto" value='Update NoPhotoForm ID'  /></td><td></td></tr>
			</table>
	</form>







<?Php } ?>









<!-- for email tab -->

<?php
if( $pg == 'email' )
{

	//For Email
	$result5=mysql_query("SELECT * FROM wp_options where option_name = 'aws_fromemail'");
	$f1=mysql_fetch_array($result5);

	$result55=mysql_query("SELECT * FROM wp_options where option_name = 'aws_fname'");
	$f2=mysql_fetch_array($result55);

	$result555=mysql_query("SELECT * FROM wp_options where option_name = 'aws_mailregion'");
	$f3=mysql_fetch_array($result555);

	//For Email Template
	$result5555=mysql_query("SELECT * FROM wp_options where option_name = 'aws_vm_subject'");
	$vm1=mysql_fetch_array($result5555);

	$result55555=mysql_query("SELECT * FROM wp_options where option_name = 'aws_vm_content'");
	$vm2=mysql_fetch_array($result55555);

	$result555555=mysql_query("SELECT * FROM wp_options where option_name = 'aws_fp_subject'");
	$fp1=mysql_fetch_array($result555555);

	$result5555555=mysql_query("SELECT * FROM wp_options where option_name = 'aws_fp_content'");
	$fp2=mysql_fetch_array($result5555555);

	if(isset($_POST['SaveTemplate']))
	{
		if(mysql_num_rows($result5555)>0)
		{
			$sql13="UPDATE `wp_options` SET `option_value`='".$_POST['subject']."' WHERE `option_name`='aws_vm_subject'";

			$result3=mysql_query($sql13);

			$result5=mysql_query("SELECT * FROM wp_options where option_name = 'aws_vm_subject'");
			$vm1=mysql_fetch_array($result5);
			 echo "<script>alert('AWS Verification Subject Updated to ".$_POST['subject']."');</script>";

		}
		else
		{
			$sql14="insert into wp_options(option_name,option_value,autoload) values('aws_vm_subject','".$_POST['subject']."','yes')";
			$result1=mysql_query($sql14);

			$result5=mysql_query("SELECT * FROM wp_options where option_name = 'aws_vm_subject'");
			$vm1=mysql_fetch_array($result5);
			 echo "<script>alert('AWS Verification Subject Updated to ".$_POST['subject']."');</script>";
		}
		if(mysql_num_rows($result555555)>0)
		{
			$sql13="UPDATE `wp_options` SET `option_value`='".$_POST['fpsubject']."' WHERE `option_name`='aws_fp_subject'";

			$result3=mysql_query($sql13);

			$result5=mysql_query("SELECT * FROM wp_options where option_name = 'aws_fp_subject'");
			$fp1=mysql_fetch_array($result5);
			 echo "<script>alert('AWS Forgot Password Subject Updated to ".$_POST['fpsubject']."');</script>";

		}
		else
		{
			$sql14="insert into wp_options(option_name,option_value,autoload) values('aws_fp_subject','".$_POST['fpsubject']."','yes')";
			$result1=mysql_query($sql14);

			$result5=mysql_query("SELECT * FROM wp_options where option_name = 'aws_fp_subject'");
			$fp1=mysql_fetch_array($result5);
			 echo "<script>alert('AWS Forgot Password Subject Updated to ".$_POST['fpsubject']."');</script>";
		}

		if(mysql_num_rows($result55555)>0)
		{
			$sql13="UPDATE `wp_options` SET `option_value`='".$_POST['content']."' WHERE `option_name`='aws_vm_content'";

			$result3=mysql_query($sql13);

			$result5=mysql_query("SELECT * FROM wp_options where option_name = 'aws_vm_content'");
			$vm2=mysql_fetch_array($result5);
			 echo "<script>alert('AWS Verification Mail Content Updated to ".$_POST['content']."');</script>";

		}
		else
		{
			$sql14="insert into wp_options(option_name,option_value,autoload) values('aws_vm_content','".$_POST['content']."','yes')";
			$result1=mysql_query($sql14);

			$result5=mysql_query("SELECT * FROM wp_options where option_name = 'aws_vm_content'");
			$vm2=mysql_fetch_array($result5);
			 echo "<script>alert('AWS Verification Mail Content Updated to ".$_POST['content']."');</script>";
		}
		if(mysql_num_rows($result5555555)>0)
		{
			$sql13="UPDATE `wp_options` SET `option_value`='".$_POST['fpcontent']."' WHERE `option_name`='aws_fp_content'";

			$result3=mysql_query($sql13);


			$result5=mysql_query("SELECT * FROM wp_options where option_name = 'aws_fp_content'");
			$fp2=mysql_fetch_array($result5);
			 echo "<script>alert('AWS Forgot Password Mail Content Updated to ".$_POST['fpcontent']."');</script>";

		}
		else
		{
			$sql14="insert into wp_options(option_name,option_value,autoload) values('aws_fp_content','".$_POST['fpcontent']."','yes')";
			$result1=mysql_query($sql14);

			$result5=mysql_query("SELECT * FROM wp_options where option_name = 'aws_fp_content'");
			$fp2=mysql_fetch_array($result5);
			 echo "<script>alert('AWS Forgot Password Mail Content Updated to ".$_POST['fpcontent']."');</script>";
		}

	}



	if(isset($_POST['SaveAwsEmail']))
	{


		if(mysql_num_rows($result5)>0)
		{
			$sql13="UPDATE `wp_options` SET `option_value`='".$_POST['email']."' WHERE `option_name`='aws_fromemail'";

			$result3=mysql_query($sql13);

			$result5=mysql_query("SELECT * FROM wp_options where option_name = 'aws_fromemail'");
			$f1=mysql_fetch_array($result5);


		}
		else
		{
			$sql14="insert into wp_options(option_name,option_value,autoload) values('aws_fromemail','".$_POST['email']."','yes')";
			$result1=mysql_query($sql14);

			$result5=mysql_query("SELECT * FROM wp_options where option_name = 'aws_fromemail'");

			$f1=mysql_fetch_array($result5);


		}

		if(mysql_num_rows($result55)>0)
		{
			$sql13="UPDATE `wp_options` SET `option_value`='".$_POST['fname']."' WHERE `option_name`='aws_fname'";

			$result3=mysql_query($sql13);

			$result5=mysql_query("SELECT * FROM wp_options where option_name = 'aws_fname'");
			$f2=mysql_fetch_array($result5);


		}
		else
		{
			$sql14="insert into wp_options(option_name,option_value,autoload) values('aws_fname','".$_POST['fname']."','yes')";
			$result1=mysql_query($sql14);

			$result5=mysql_query("SELECT * FROM wp_options where option_name = 'aws_fname");
			$f2=mysql_fetch_array($result5);

		}

		if(mysql_num_rows($result555)>0)
		{
			$sql13="UPDATE `wp_options` SET `option_value`='".$_POST['mailregion']."' WHERE `option_name`='aws_mailregion'";

			$result3=mysql_query($sql13);

			$result5=mysql_query("SELECT * FROM wp_options where option_name = 'aws_mailregion'");
			$f3=mysql_fetch_array($result5);


		}
		else
		{
			$sql14="insert into wp_options(option_name,option_value,autoload) values('aws_mailregion','".$_POST['mailregion']."','yes')";
			$result1=mysql_query($sql14);

			$result5=mysql_query("SELECT * FROM wp_options where option_name = 'aws_mailregion");
			$f3=mysql_fetch_array($result5);

		}

	 echo "<script>alert('AWS From EMail Settings Updated');</script>";

	}

?>

	<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
		<br>
		<h3>AWS EMail Settings</h3>	<br>



		<table style="margin-left:50px;" name="connection" width="80%">
			<tbody>
				<tr>
						<td bgcolor="#eceaea" colspan="3">
							<p><b>&nbsp;Change the AWS From EMAIL</b></p>
						</td>
					</tr>
				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
				<tr>
					<td>From Email</td>
					<td><input name="email" required value="<?php echo $f1["option_value"]; ?>" placeholder="abc@gmail.com" type="text"></td>
					<td>Ex: abc@xyz.qwe</td>
				</tr>
				<tr>
					<td>Friendly Name</td>
					<td><input name="fname" required value="<?php echo $f2["option_value"]; ?>" placeholder="Admin" type="text"></td>
					<td>Ex: Admin</td>
				</tr>
				<tr>
					<td>Mail Region</td>
					<td><input name="mailregion" required value="<?php echo $f3["option_value"]; ?>" placeholder="us-west-2" type="text"></td>
					<td>Ex: us-west-2</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td colspan="2">
						<br><br>&nbsp;
						<input class="button" name="SaveAwsEmail" value="Save AWS" type="submit">
					</td>
				</tr>
			</tbody>
		</table>
	</form>




	<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
		<br>



		<table style="margin-left:50px;" name="connection" width="80%">
			<tbody>
				<tr>
						<td bgcolor="#eceaea" colspan="3">
							<p><b>&nbsp;Verification EMail Template</b></p>
						</td>
					</tr>
				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
				<tr>
					<td>Mail Subject:</td>
					<td>
						<textarea name="subject" rows="2" placeholder="Confirmation Code" required style="width: 500px;"><?php echo $vm1["option_value"]; ?></textarea>

					</td>
					<td>Ex: Confirmation Code</td>
				</tr>
				<tr>
					<td>Mail Content:</td>
					<td>
						<textarea name="content" rows="5" placeholder="Hi, Your Activation Code is $pin" required style="width: 500px;"><?php echo $vm2["option_value"]; ?></textarea>

					</td>
					<td>Ex: <strong>$pin</strong> variable to display Verification pin. </td>
				</tr>

				<tr>
						<td bgcolor="#eceaea" colspan="3">
							<p><b>&nbsp;Forgot Password EMail Template</b></p>
						</td>
					</tr>
				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
				<tr>
					<td>Mail Subject:</td>
					<td>
						<textarea name="fpsubject" rows="2" placeholder="Forgot Password" required style="width: 500px;"><?php echo $fp1["option_value"]; ?></textarea>

					</td>
					<td>Ex: Confirmation Code</td>
				</tr>
				<tr>
					<td>Mail Content:</td>
					<td>
						<textarea name="fpcontent" rows="5" placeholder="Hi, Click here to reset your password $reset_link" required style="width: 500px;"><?php echo $fp2["option_value"]; ?></textarea>

					</td>
					<td>Ex: Hi, Click here to reset your password <strong>$reset_link</strong>. </td>
				</tr>

				<tr>
					<td>&nbsp;</td>
					<td colspan="2">
						<br><br>&nbsp;
						<input class="button" name="SaveTemplate" value="Save Email Template" type="submit">
					</td>
				</tr>
			</tbody>
		</table>
	</form>











<?php
$result6=mysql_query("SELECT * FROM wp_options where option_name = 'search_var'");
	$row5=mysql_fetch_array($result6);

	if(isset($_POST['SaveSearch']))
	{
		if(mysql_num_rows($result6)>0)
		{
			$sql3="UPDATE `wp_options` SET `option_value`='".$_POST['searchkey']."' WHERE `option_name`='search_var'";

			$result3=mysql_query($sql3);

			$result6=mysql_query("SELECT * FROM wp_options where option_name = 'search_var'");
			$row5=mysql_fetch_array($result6);

			 echo "<script>alert('Search Variable Updated to ".$_POST['searchkey']."');</script>";

		}
		else
		{
			$sql1="insert into wp_options(option_name,option_value,autoload) values('search_var','".$_POST['searchkey']."','yes')";
			$result1=mysql_query($sql1);

			$result6=mysql_query("SELECT * FROM wp_options where option_name = 'search_var'");
			$row5=mysql_fetch_array($result6);
			 echo "<script>alert('Search Variable Updated to ".$_POST['searchkey']."');</script>";
		}



	}
?>
	<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
		<br>
		<h3>Search Variable Configuration</h3>	<br>



		<table style="margin-left:50px;" name="connection" width="80%">
			<tbody>
				<tr>
						<td bgcolor="#eceaea" colspan="3">
							<p><b>&nbsp;Change the Search Variable</b></p>
						</td>
					</tr>
				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
				<tr>
					<td>Search Variable</td>
					<td><input name="searchkey" required value="<?php echo $row5["option_value"]; ?>" placeholder="011680803714718892657:axq-b3jvyqc" type="text"></td>
					<td>Ex: 011680803714718892657:axq-b3jvyqc</td>
				</tr>
				<tr>
					<td>&nbsp;</td>

					<td colspan="2"><br><br><br><br><br>&nbsp;<input class="button" name="SaveSearch" value="Save Search" type="submit"></td>
				</tr>
			</tbody>
		</table>
	</form>

<?php

		$result55=mysql_query("SELECT * FROM wp_options where option_name = 'social_links'");
		$row55=mysql_fetch_array($result55);

		$result555=mysql_query("SELECT * FROM wp_options where option_name = 'social_check'");
		$row555=mysql_fetch_array($result555);


// For social links
	if(isset($_POST['SaveSocialLinks']))
	{

			$fb = $ig = $tr = $yt = $em = $gl = 0;
			if(isset($_POST['fb']))
			{
				$fb = 1;
			}
			if(isset($_POST['ig']))
			{
				$ig = 1;
			}
			if(isset($_POST['tr']))
			{
				$tr = 1;
			}
			if(isset($_POST['yt']))
			{
				$yt = 1;
			}
			if(isset($_POST['em']))
			{
				$em = 1;
			}
			if(isset($_POST['gl']))
			{
				$gl = 1;
			}


		//For checkbox
		if(mysql_num_rows($result555)>0)
		{
			$sql3="UPDATE `wp_options` SET `option_value`='".$fb."|".$ig."|".$tr."|".$yt."|".$em."|".$gl."' WHERE `option_name`='social_check'";

			$result3=mysql_query($sql3);

			$result555=mysql_query("SELECT * FROM wp_options where option_name = 'social_check'");
			$row555=mysql_fetch_array($result555);


		}
		else
		{
			$sql1="insert into wp_options(option_name,option_value,autoload) values('social_check','".$fb."|".$ig."|".$tr."|".$yt."|".$em."|".$gl."','yes')";
			$result1=mysql_query($sql1);
			$result555=mysql_query("SELECT * FROM wp_options where option_name = 'social_check'");
			$row555=mysql_fetch_array($result555);


		}





		if(mysql_num_rows($result55)>0)
		{
			// Associative array

			//printf ("%s (%s)\n",$row5["option_name"],$row5["option_value"]);

			$sql3="UPDATE `wp_options` SET `option_value`='".$_POST['facebook']."|".$_POST['insta']."|".$_POST['twitter']."|".$_POST['youtube']."|".$_POST['email']."|".$_POST['google']."' WHERE `option_name`='social_links'";


			$result3=mysql_query($sql3);

			$result55=mysql_query("SELECT * FROM wp_options where option_name = 'social_links'");
			$row55=mysql_fetch_array($result55);


			echo "<script>alert('Socail Links Updated to ".$_POST['facebook']."|".$_POST['insta']."|".$_POST['twitter']."|".$_POST['youtube']."|".$_POST['email']."|".$_POST['google']."');</script>";


			//header('location '.$_SERVER['REQUEST_URI']);
			//echo $row5["option_value"];
		}
		else
		{

			$sql1="insert into wp_options(option_name,option_value,autoload) values('social_links','".$_POST['facebook']."|".$_POST['insta']."|".$_POST['twitter']."|".$_POST['youtube']."|".$_POST['email']."|".$_POST['google']."','yes')";

			$result1=mysql_query($sql1);
			$result55=mysql_query("SELECT * FROM wp_options where option_name = 'social_links'");
			$row55=mysql_fetch_array($result55);


			echo "<script>alert('Social Links Updated to ".$_POST['facebook']."|".$_POST['insta']."|".$_POST['twitter']."|".$_POST['youtube']."|".$_POST['email']."|".$_POST['google']."');</script>";

		}



	}
?>





<!-- For Social links -->
	<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
		<br>
		<h3>Social Links Configuration</h3>	<br>



		<table style="margin-left:50px;" name="connection" width="80%">
			<tbody>
				<tr>
						<td bgcolor="#eceaea" colspan="3">
							<p><b>&nbsp;Change the Social Variable</b></p>
						</td>
					</tr>
				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
				<tr>


				<?php
					$s_c = explode('|',$row555['option_value']);
				?>
					<td><input type="checkbox" name="fb" <?php  if($s_c[0]==1){echo "checked=true";}else{}?> value="1">Facebook Domain Link</td>
					<td><input name="facebook" value="<?php $fb = explode('|',$row55["option_value"]); echo $fb[0];?>" placeholder="Enter Facebook Page Link" type="text"></td>
					<td>Ex: http://stage-www.mtbr.com</td>

				</tr>
				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
				<tr>

					<td><input type="checkbox" name="ig" <?php  if($s_c[1]==1){echo "checked=true";}else{}?> value="1">Instagram Link</td>
					<td><input name="insta" value="<?php $gp = explode('|',$row55["option_value"]); echo $gp[1]; ?>" placeholder="Enter Instagram Page Link" type="text"></td>
					<td>Ex: http://instagram.com</td>

				</tr>
				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
				<tr>

					<td><input type="checkbox" name="tr" <?php  if($s_c[2]==1){echo "checked=true";}else{}?> value="1">Twitter Link</td>
					<td><input name="twitter" value="<?php $gp = explode('|',$row55["option_value"]); echo $gp[2]; ?>" placeholder="Enter Twitter Page Link" type="text"></td>
					<td>Ex: http://twitter.com/mtbr</td>

				</tr>
				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
				<tr>

					<td><input type="checkbox" name="yt" <?php  if($s_c[3]==1){echo "checked=true";}else{}?> value="1">Youtube Link</td>
					<td><input name="youtube" value="<?php $gp = explode('|',$row55["option_value"]); echo $gp[3]; ?>" placeholder="Enter youtube Page Link" type="text"></td>
					<td>Ex: http://www.youtube.com/mtbr</td>

				</tr>
				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
				<tr>

					<td><input type="checkbox" name="em" <?php  if($s_c[4]==1){echo "checked=true";}else{}?> value="1">Email Link</td>
					<td><input name="email" value="<?php $gp = explode('|',$row55["option_value"]); echo $gp[4]; ?>" placeholder="Enter Email Link" type="text"></td>
					<td>Ex: info@mtbr.com</td>

				</tr>
				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
				<tr>

					<td><input type="checkbox" name="gl" <?php  if($s_c[5]==1){echo "checked=true";}else{}?> value="1">Google Link</td>
					<td><input name="google" value="<?php $gp = explode('|',$row55["option_value"]); echo $gp[5]; ?>" placeholder="Enter google Plus Page Link" type="text"></td>
					<td>Ex: http://plus.google.com/+mtbr</td>

				</tr>
				<tr>
					<td>&nbsp;</td>

					<td colspan="2"><br><br><br><br><br>&nbsp;<input class="button" name="SaveSocialLinks" value="Save Social Links" type="submit"></td>
				</tr>
			</tbody>
		</table>
	</form>
<!-- For Social links -->

<?php

		$result55=mysql_query("SELECT * FROM wp_options where option_name = 'footer_social_links'");
		$row55=mysql_fetch_array($result55);


// For social links
	if(isset($_POST['SaveFooterSocialLinks']))
	{
		if(mysql_num_rows($result55)>0)
		{
			// Associative array

			//printf ("%s (%s)\n",$row5["option_name"],$row5["option_value"]);

			$sql3="UPDATE `wp_options` SET `option_value`='".$_POST['footer_facebook']."|".$_POST['footer_twitter']."|".$_POST['footer_youtube']."' WHERE `option_name`='footer_social_links'";


			$result3=mysql_query($sql3);

			$result55=mysql_query("SELECT * FROM wp_options where option_name = 'footer_social_links'");
			$row55=mysql_fetch_array($result55);


			echo "<script>alert('Footer Social Links Updated to ".$_POST['footer_facebook']."|".$_POST['footer_twitter']."|".$_POST['footer_youtube']."');</script>";

		}
		else
		{

			$sql1="insert into wp_options(option_name,option_value,autoload) values('footer_social_links','".$_POST['footer_facebook']."|".$_POST['footer_twitter']."|".$_POST['footer_youtube']."','yes')";

			$result1=mysql_query($sql1);
			$result55=mysql_query("SELECT * FROM wp_options where option_name = 'footer_social_links'");
			$row55=mysql_fetch_array($result55);


			echo "<script>alert('Footer Social Links Updated to ".$result55.$_POST['footer_facebook']."|".$_POST['footer_twitter']."|".$_POST['footer_youtube']."');</script>";

		}



	}
?>


<!-- For FOOTER Social links -->
	<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
		<br>
		<h3>Footer Social Links Configuration</h3>	<br>



		<table style="margin-left:50px;" name="connection" width="80%">
			<tbody>
				<tr>
						<td bgcolor="#eceaea" colspan="3">
							<p><b>&nbsp;Change the Social Variable</b></p>
						</td>
				<?php $fb = explode('|',$row55["option_value"]);?>
					</tr>
				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
				<tr>
					<td>Facebook Link</td>
					<td><input name="footer_facebook" value="<?php echo $fb[0];?>" placeholder="Enter Facebook Page Link" type="text"></td>
					<td>Ex: http://stage-www.mtbr.com</td>

				</tr>
				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
				<tr>

					<td>Twitter Link</td>
					<td><input name="footer_twitter" value="<?php echo $fb[1]; ?>" placeholder="Enter Twitter Page Link" type="text"></td>
					<td>Ex: http://twitter.com/mtbr</td>

				</tr>
				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
				<tr>

					<td>Youtube Link</td>
					<td><input name="footer_youtube" value="<?php echo $fb[2]; ?>" placeholder="Enter youtube Page Link" type="text"></td>
					<td>Ex: http://www.youtube.com/mtbr</td>

				</tr>
				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
				<tr>
					<td>&nbsp;</td>

		<td colspan="2"><br><br><br><br><br>&nbsp;<input class="button" name="SaveFooterSocialLinks" value="Save Footer Social Links" type="submit"></td>
				</tr>
			</tbody>
		</table>
	</form>
<!-- For Social links -->

<?php
}
?>

<!-- email Tab ends -->
















<!-- for cookie tab -->

<?php
if( $pg == 'cookie' )
{
	//include_once('cookie.php');
	$result5=mysql_query("SELECT * FROM wp_options where option_name = 'cookie_domain'");
	$row5=mysql_fetch_array($result5);
	if(isset($_POST['SaveCookie']))
	{


		if(mysql_num_rows($result5)>0)
		{
			$sql3="UPDATE `wp_options` SET `option_value`='".$_POST['hostname']."' WHERE `option_name`='cookie_domain'";

			$result3=mysql_query($sql3);

			$result5=mysql_query("SELECT * FROM wp_options where option_name = 'cookie_domain'");
			$row5=mysql_fetch_array($result5);

			echo "<script>alert('Cookie Domain Updated to ".$_POST['hostname']."');</script>";

		}
		else
		{
			$sql1="insert into wp_options(option_name,option_value,autoload) values('cookie_domain','".$_POST['hostname']."','yes')";
			$result1=mysql_query($sql1);
			$result5=mysql_query("SELECT * FROM wp_options where option_name = 'cookie_domain'");
			$row5=mysql_fetch_array($result5);

			echo "<script>alert('Cookie Domain Updated to ".$_POST['hostname']."');</script>";
		}



	}
?>

	<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
	<br>
		<h3>Cookie Domain Settings</h3>	<br>



		<table style="margin-left:50px;" name="connection" width="80%">
			<tbody>
				<tr>
						<td bgcolor="#eceaea" colspan="3">
							<p><b>&nbsp;Change the Cookie domain details -</b></p>
						</td>
					</tr>
				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
				<tr>
					<td>Cookie Hostname -</td>
					<td><input name="hostname" required value="<?php echo $row5["option_value"]; ?>" placeholder=".owshi.com" type="text"></td>
					<td>Ex: .owshi.com;</td>
				</tr>
				<tr>
					<td>&nbsp;</td>

					<td colspan="2"><br><br><br><br><br>&nbsp;<input class="button" name="SaveCookie" value="Save Cookie Host" type="submit"></td>
				</tr>
			</tbody>
		</table>





	</form>

<?php
}
?>

<!-- cookie Tab ends -->















<!-- for vbulletin tab -->

<?php
if( $pg == 'vbulletin' )
{

if(isset($_POST['regflag']))
{
	update_option('registration_flag',$_POST['regflag']);
}

	//include_once('vbulletin.php');

//include_once('cookie.php');
	$result5=mysql_query("SELECT * FROM wp_options where option_name = 'vbulletin_domain'");
	$row5=mysql_fetch_array($result5);
	if(isset($_POST['SaveVbulletin']))
	{


		if(mysql_num_rows($result5)>0)
		{
			$sql3="UPDATE `wp_options` SET `option_value`='".$_POST['hostname']."' WHERE `option_name`='vbulletin_domain'";

			$result3=mysql_query($sql3);

			$result5=mysql_query("SELECT * FROM wp_options where option_name = 'vbulletin_domain'");
			$row5=mysql_fetch_array($result5);

			echo "<script>alert('VBulletin Domain Updated to ".$_POST['hostname']."');</script>";

		}
		else
		{
			$sql1="insert into wp_options(option_name,option_value,autoload) values('vbulletin_domain','".$_POST['hostname']."','yes')";
			$result1=mysql_query($sql1);
			$result5=mysql_query("SELECT * FROM wp_options where option_name = 'vbulletin_domain'");
			$row5=mysql_fetch_array($result5);

			echo "<script>alert(''VBulletin Domain Updated to ".$_POST['hostname']."');</script>";
		}



	}

?>

	<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
	<br>
		<h3>VBulletin Settings</h3>
		<table style="margin-left:50px;" name="connection" width="80%">
			<tbody>
				<tr>
						<td bgcolor="#eceaea" colspan="3">
							<p><b>&nbsp;Change the VBulletin domain details -</b></p>
						</td>
					</tr>
				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
				<tr>
					<td>VBulletin Hostname -</td>
					<td><input name="hostname" required value="<?php echo $row5["option_value"]; ?>" placeholder="vbulletin.owshi.com" type="text" size="25"></td>
					<td>Ex: vbulletin.owshi.com;</td>
				</tr>
				<tr>
					<td>&nbsp;</td>

					<td colspan="2"><br><br><br><br><br>&nbsp;<input class="button" name="SaveVbulletin" value="Save VBulletin Host" type="submit"></td>
				</tr>
			</tbody>
		</table>

	</form>
	<form >
	<br>
		<h3>Redirect Settings</h3>
		<table style="margin-left:50px;" name="connection" width="80%">
			<tbody>
				<tr>
						<td bgcolor="#eceaea" colspan="3">
							<p><b>&nbsp;Registration Redirect Flag -</b></p>
						</td>
					</tr>
				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
				<tr>
					<td>Flag (Enable / Disable) -</td>
					<td><input name="favorite" type="checkbox" <?php $flagnew = get_option('registration_flag'); if($flagnew=='1'){ echo "checked=checked"; }else{echo 'Unchecked';} ?> >Enable / Disable</td>
					<td>Ex: Enable - Redirect to Profile, Disable - Redirect back to previous page;</td>
				</tr>

			</tbody>
		</table>
	<script type="text/javascript">
		 $('input[name=favorite]').live("click",function(){
			if($(this).attr('checked')) {
				alert('Enabled');
				var favorite = 1;
			} else {
				alert('Disabled');
				var favorite = 0;
			}

			$.ajax({
				type:'POST',
				url:window.location.href,
				data:'regflag='+favorite
			});

		 });
	</script>
	</form>

<?php
}
?>

<!-- vbulletin Tab ends -->









<!-- for forum tab -->

<?php
if( $pg == 'profile' )
{
	$q1=mysql_query("SELECT * FROM profile_questions where qid='q1'");
	$q2=mysql_query("SELECT * FROM profile_questions where qid='q2'");
	$q3=mysql_query("SELECT * FROM profile_questions where qid='q3'");
	$q4=mysql_query("SELECT * FROM profile_questions where qid='q4'");
	$q5=mysql_query("SELECT * FROM profile_questions where qid='q5'");
	$q6=mysql_query("SELECT * FROM profile_questions where qid='q6'");
	$q7=mysql_query("SELECT * FROM profile_questions where qid='q7'");
	$q8=mysql_query("SELECT * FROM profile_questions where qid='q8'");
	$q9=mysql_query("SELECT * FROM profile_questions where qid='q9'");
	$q10=mysql_query("SELECT * FROM profile_questions where qid='q10'");
	$q11=mysql_query("SELECT * FROM profile_questions where qid='q11'");
	$q12=mysql_query("SELECT * FROM profile_questions where qid='q12'");
	$q13=mysql_query("SELECT * FROM profile_questions where qid='q13'");
	$q14=mysql_query("SELECT * FROM profile_questions where qid='q14'");
	$q15=mysql_query("SELECT * FROM profile_questions where qid='q15'");
	$q16=mysql_query("SELECT * FROM profile_questions where qid='q16'");
	$q17=mysql_query("SELECT * FROM profile_questions where qid='q17'");
	$q18=mysql_query("SELECT * FROM profile_questions where qid='q18'");
	$a1=mysql_query("SELECT * FROM profile_questions where qid='a1'");
	$a2=mysql_query("SELECT * FROM profile_questions where qid='a2'");
	$a3=mysql_query("SELECT * FROM profile_questions where qid='a3'");
	$a4=mysql_query("SELECT * FROM profile_questions where qid='a4'");
	$a5=mysql_query("SELECT * FROM profile_questions where qid='a5'");






// For Profile Answers
	if(isset($_POST['ans']))
	{

		//For question q1
		if(mysql_num_rows($a1)>0)
		{
			if($_POST['a1']!='')
			{
				$updateq1="UPDATE profile_questions SET question='".$_POST['a1']."' where qid='a1'";
				$resultq1=mysql_query($updateq1);
			}
		}
		else
		{
			if($_POST['a1']!='')
			{
				$insertq1="insert into profile_questions(question,qid,page) values('".$_POST['a1']."','a1','page1')";
				$resultq1=mysql_query($insertq1);
			}
		}

		//For question q2
		if(mysql_num_rows($a2)>0)
		{
			if($_POST['a2']!='')
			{
			$updateq2="UPDATE profile_questions SET question='".$_POST['a2']."' where qid='a2'";
			$resultq2=mysql_query($updateq2);
			}
		}
		else
		{
			if($_POST['a2']!='')
			{
			$insertq2="insert into profile_questions(question,qid,page) values('".$_POST['a2']."','a2','page1')";
			$resultq2=mysql_query($insertq2);
			}
		}


		//For question q3
		if(mysql_num_rows($a3)>0)
		{
			if($_POST['a3']!='')
			{
			$updateq3="UPDATE profile_questions SET question='".$_POST['a3']."' where qid='a3'";
			$resultq3=mysql_query($updateq3);
			}
		}
		else
		{
			if($_POST['a3']!='')
			{

			$insertq3="insert into profile_questions(question,qid,page) values('".$_POST['a3']."','a3','page1')";
			$resultq3=mysql_query($insertq3);
			}
		}

		//For question q4
		if(mysql_num_rows($a4)>0)
		{
			if($_POST['a4']!='')
			{
			$updateq3="UPDATE profile_questions SET question='".$_POST['a4']."' where qid='a4'";
			$resultq3=mysql_query($updateq3);
			}
		}
		else
		{
			if($_POST['a4']!='')
			{
			$insertq3="insert into profile_questions(question,qid,page) values('".$_POST['a4']."','a4','page1')";
			$resultq3=mysql_query($insertq3);
			}
		}

		//For question q5
		if(mysql_num_rows($a5)>0)
		{
			if($_POST['a5']!='')
			{
			$updateq3="UPDATE profile_questions SET question='".$_POST['a5']."' where qid='a5'";
			$resultq3=mysql_query($updateq3);
			}
		}
		else
		{
			if($_POST['a5']!='')
			{
			$insertq3="insert into profile_questions(question,qid,page) values('".$_POST['a5']."','a5','page1')";
			$resultq3=mysql_query($insertq3);
			}
		}
		//For question q6
		if(mysql_num_rows($a6)>0)
		{
			if($_POST['a6']!='')
			{
			$updateq6="UPDATE profile_questions SET question='".$_POST['a6']."' where qid='a6'";
			$resultq6=mysql_query($updateq6);
			}
		}
		else
		{
			if($_POST['a6']!='')
			{
			$insertq6="insert into profile_questions(question,qid,page) values('".$_POST['a6']."','a6','page1')";
			$resultq6=mysql_query($insertq6);
			}
		}

		//For question q7
		if(mysql_num_rows($a7)>0)
		{
			if($_POST['a7']!='')
			{
			$updateq7="UPDATE profile_questions SET question='".$_POST['a7']."' where qid='a7'";
			$resultq7=mysql_query($updateq7);
			}
		}
		else
		{
			if($_POST['a7']!='')
			{
			$insertq7="insert into profile_questions(question,qid,page) values('".$_POST['a7']."','a7','page1')";
			$resultq7=mysql_query($insertq7);
			}
		}


		//For question q8
		if(mysql_num_rows($a8)>0)
		{
			if($_POST['a8']!='')
			{
			$updateq8="UPDATE profile_questions SET question='".$_POST['a8']."' where qid='a8'";
			$resultq8=mysql_query($updateq8);
			}
		}
		else
		{
			if($_POST['a8']!='')
			{
			$insertq8="insert into profile_questions(question,qid,page) values('".$_POST['a8']."','a8','page1')";
			$resultq8=mysql_query($insertq8);
			}
		}

		//For question q9
		if(mysql_num_rows($a9)>0)
		{
			if($_POST['a9']!='')
			{
			$updateq9="UPDATE profile_questions SET question='".$_POST['a9']."' where qid='a9'";
			$resultq9=mysql_query($updateq9);
			}
		}
		else
		{
			if($_POST['a9']!='')
			{
			$insertq9="insert into profile_questions(question,qid,page) values('".$_POST['a9']."','a9','page1')";
			$resultq9=mysql_query($insertq9);
			}
		}

		//For question q10
		if(mysql_num_rows($a10)>0)
		{
			if($_POST['a10']!='')
			{
			$updateq10="UPDATE profile_questions SET question='".$_POST['a10']."' where qid='a10'";
			$resultq10=mysql_query($updateq10);
			}
		}
		else
		{
			if($_POST['a10']!='')
			{
			$insertq10="insert into profile_questions(question,qid,page) values('".$_POST['a10']."','a10','page1')";
			$resultq10=mysql_query($insertq10);
			}
		}


	}

// For Profile Page 1
	if(isset($_POST['page1']))
	{
		$Maintitle1=$_POST['MainTitleProfile1'];
		update_option("MainTitleProfile1", $Maintitle1);
		$SubTitle1=$_POST['SubTitleProfile1'];
		update_option("SubTitleProfile1", $SubTitle1);


		//For question q1
		if(mysql_num_rows($q1)>0)
		{
			$updateq1="UPDATE profile_questions SET question='".$_POST['q1']."' where qid='q1'";
			$resultq1=mysql_query($updateq1);
		}
		else
		{
			$insertq1="insert into profile_questions(question,qid,page) values('".$_POST['q1']."','q1','page1')";
			$resultq1=mysql_query($insertq1);
		}

		//For question q2
		if(mysql_num_rows($q2)>0)
		{
			$updateq2="UPDATE profile_questions SET question='".$_POST['q2']."' where qid='q2'";
			$resultq2=mysql_query($updateq2);
		}
		else
		{
			$insertq2="insert into profile_questions(question,qid,page) values('".$_POST['q2']."','q2','page1')";
			$resultq2=mysql_query($insertq2);
		}


		//For question q3
		if(mysql_num_rows($q3)>0)
		{
			$updateq3="UPDATE profile_questions SET question='".$_POST['q3']."' where qid='q3'";
			$resultq3=mysql_query($updateq3);
		}
		else
		{
			$insertq3="insert into profile_questions(question,qid,page) values('".$_POST['q3']."','q3','page1')";
			$resultq3=mysql_query($insertq3);
		}


	}




// For Profile Page 2
	if(isset($_POST['page2']))
	{

		$Maintitle2=$_POST['MainTitleProfile2'];
		update_option("MainTitleProfile2", $Maintitle2);
		$SubTitle2=$_POST['SubTitleProfile2'];
		update_option("SubTitleProfile2", $SubTitle2);

		//For question q4
		if(mysql_num_rows($q4)>0)
		{
			$updateq4="UPDATE profile_questions SET question='".$_POST['q4']."' where qid='q4'";
			$resultq4=mysql_query($updateq4);
		}
		else
		{
			$insertq4="insert into profile_questions(question,qid,page) values('".$_POST['q4']."','q4','page2')";
			$resultq4=mysql_query($insertq4);
		}

		//For question q5
		if(mysql_num_rows($q5)>0)
		{
			$updateq5="UPDATE profile_questions SET question='".$_POST['q5']."' where qid='q5'";
			$resultq5=mysql_query($updateq5);
		}
		else
		{
			$insertq5="insert into profile_questions(question,qid,page) values('".$_POST['q5']."','q5','page2')";
			$resultq5=mysql_query($insertq5);
		}


		//For question q6
		if(mysql_num_rows($q6)>0)
		{
			$updateq6="UPDATE profile_questions SET question='".$_POST['q6']."' where qid='q6'";
			$resultq6=mysql_query($updateq6);
		}
		else
		{
			$insertq6="insert into profile_questions(question,qid,page) values('".$_POST['q6']."','q6','page2')";
			$resultq6=mysql_query($insertq6);
		}

	}



// For Profile Page 3
	if(isset($_POST['page3']))
	{

		$Maintitle3=$_POST['MainTitleProfile3'];
		update_option("MainTitleProfile3", $Maintitle3);
		$SubTitle3=$_POST['SubTitleProfile3'];
		update_option("SubTitleProfile3", $SubTitle3);

		//For question q7
		if(mysql_num_rows($q7)>0)
		{
			$updateq7="UPDATE profile_questions SET question='".$_POST['q7']."' where qid='q7'";
			$resultq7=mysql_query($updateq7);
		}
		else
		{
			$insertq7="insert into profile_questions(question,qid,page) values('".$_POST['q7']."','q7','page3')";
			$resultq7=mysql_query($insertq7);
		}

		//For question q8
		if(mysql_num_rows($q8)>0)
		{
			$updateq8="UPDATE profile_questions SET question='".$_POST['q8']."' where qid='q8'";
			$resultq8=mysql_query($updateq8);
		}
		else
		{
			$insertq8="insert into profile_questions(question,qid,page) values('".$_POST['q8']."','q8','page3')";
			$resultq8=mysql_query($insertq8);
		}


		//For question q9
		if(mysql_num_rows($q9)>0)
		{
			$updateq9="UPDATE profile_questions SET question='".$_POST['q9']."' where qid='q9'";
			$resultq9=mysql_query($updateq9);
		}
		else
		{
			$insertq9="insert into profile_questions(question,qid,page) values('".$_POST['q9']."','q9','page3')";
			$resultq9=mysql_query($insertq9);
		}

		//For question q10
		if(mysql_num_rows($q10)>0)
		{
			$updateq10="UPDATE profile_questions SET question='".$_POST['q10']."' where qid='q10'";
			$resultq10=mysql_query($updateq10);
		}
		else
		{
			$insertq10="insert into profile_questions(question,qid,page) values('".$_POST['q10']."','q10','page3')";
			$resultq10=mysql_query($insertq10);
		}

		//For question q11
		if(mysql_num_rows($q11)>0)
		{
			$updateq11="UPDATE profile_questions SET question='".$_POST['q11']."' where qid='q11'";
			$resultq11=mysql_query($updateq11);
		}
		else
		{
			$insertq11="insert into profile_questions(question,qid,page) values('".$_POST['q11']."','q11','page3')";
			$resultq11=mysql_query($insertq11);
		}

		//For question q12
		if(mysql_num_rows($q12)>0)
		{
			$updateq12="UPDATE profile_questions SET question='".$_POST['q12']."' where qid='q12'";
			$resultq12=mysql_query($updateq12);
		}
		else
		{
			$insertq12="insert into profile_questions(question,qid,page) values('".$_POST['q12']."','q12','page3')";
			$resultq12=mysql_query($insertq12);
		}

	}



// For Profile Page 4
	if(isset($_POST['page4']))
	{

		$Maintitle4=$_POST['MainTitleProfile4'];
		update_option("MainTitleProfile4", $Maintitle4);
		$SubTitle4=$_POST['SubTitleProfile4'];
		update_option("SubTitleProfile4", $SubTitle4);

		//For question q13
		if(mysql_num_rows($q13)>0)
		{
			$updateq13="UPDATE profile_questions SET question='".$_POST['q13']."' where qid='q13'";
			$resultq13=mysql_query($updateq13);
		}
		else
		{
			$insertq13="insert into profile_questions(question,qid,page) values('".$_POST['q13']."','q13','page4')";
			$resultq13=mysql_query($insertq13);
		}

		//For question q14
		if(mysql_num_rows($q14)>0)
		{
			$updateq14="UPDATE profile_questions SET question='".$_POST['q14']."' where qid='q14'";
			$resultq14=mysql_query($updateq14);
		}
		else
		{
			$insertq14="insert into profile_questions(question,qid,page) values('".$_POST['q14']."','q14','page4')";
			$resultq14=mysql_query($insertq14);
		}


		//For question q15
		if(mysql_num_rows($q15)>0)
		{
			$updateq15="UPDATE profile_questions SET question='".$_POST['q15']."' where qid='q15'";
			$resultq15=mysql_query($updateq15);
		}
		else
		{
			$insertq15="insert into profile_questions(question,qid,page) values('".$_POST['q15']."','q15','page4')";
			$resultq15=mysql_query($insertq15);
		}

		//For question q16
		if(mysql_num_rows($q16)>0)
		{
			$updateq16="UPDATE profile_questions SET question='".$_POST['q16']."' where qid='q16'";
			$resultq16=mysql_query($updateq16);
		}
		else
		{
			$insertq16="insert into profile_questions(question,qid,page) values('".$_POST['q16']."','q16','page4')";
			$resultq16=mysql_query($insertq16);
		}

		//For question q17
		if(mysql_num_rows($q17)>0)
		{
			$updateq17="UPDATE profile_questions SET question='".$_POST['q17']."' where qid='q17'";
			$resultq17=mysql_query($updateq17);
		}
		else
		{
			$insertq17="insert into profile_questions(question,qid,page) values('".$_POST['q17']."','q17','page4')";
			$resultq17=mysql_query($insertq17);
		}

		//For question q18
		if(mysql_num_rows($q18)>0)
		{
			$updateq18="UPDATE profile_questions SET question='".$_POST['q18']."' where qid='q18'";
			$resultq18=mysql_query($updateq18);
		}
		else
		{
			$insertq18="insert into profile_questions(question,qid,page) values('".$_POST['q18']."','q18','page4')";
			$resultq18=mysql_query($insertq18);

		}
	}





	$q1=mysql_query("SELECT * FROM profile_questions where qid='q1'");
	$q2=mysql_query("SELECT * FROM profile_questions where qid='q2'");
	$q3=mysql_query("SELECT * FROM profile_questions where qid='q3'");
	$q4=mysql_query("SELECT * FROM profile_questions where qid='q4'");
	$q5=mysql_query("SELECT * FROM profile_questions where qid='q5'");
	$q6=mysql_query("SELECT * FROM profile_questions where qid='q6'");
	$q7=mysql_query("SELECT * FROM profile_questions where qid='q7'");
	$q8=mysql_query("SELECT * FROM profile_questions where qid='q8'");
	$q9=mysql_query("SELECT * FROM profile_questions where qid='q9'");
	$q10=mysql_query("SELECT * FROM profile_questions where qid='q10'");
	$q11=mysql_query("SELECT * FROM profile_questions where qid='q11'");
	$q12=mysql_query("SELECT * FROM profile_questions where qid='q12'");
	$q13=mysql_query("SELECT * FROM profile_questions where qid='q13'");
	$q14=mysql_query("SELECT * FROM profile_questions where qid='q14'");
	$q15=mysql_query("SELECT * FROM profile_questions where qid='q15'");
	$q16=mysql_query("SELECT * FROM profile_questions where qid='q16'");
	$q17=mysql_query("SELECT * FROM profile_questions where qid='q17'");
	$q18=mysql_query("SELECT * FROM profile_questions where qid='q18'");
	$a1=mysql_query("SELECT * FROM profile_questions where qid='a1'");
	$a2=mysql_query("SELECT * FROM profile_questions where qid='a2'");
	$a3=mysql_query("SELECT * FROM profile_questions where qid='a3'");
	$a4=mysql_query("SELECT * FROM profile_questions where qid='a4'");
	$a5=mysql_query("SELECT * FROM profile_questions where qid='a5'");
	$a6=mysql_query("SELECT * FROM profile_questions where qid='a6'");
	$a7=mysql_query("SELECT * FROM profile_questions where qid='a7'");
	$a8=mysql_query("SELECT * FROM profile_questions where qid='a8'");
	$a9=mysql_query("SELECT * FROM profile_questions where qid='a9'");
	$a10=mysql_query("SELECT * FROM profile_questions where qid='a10'");


	if(isset($_POST['page1']) || isset($_POST['page2']) || isset($_POST['page3']) || isset($_POST['page4']) || isset($_POST['ans']))
	{
		//echo "<script>alert('value posted');</script>";
		//header('location: www.google.com');
		$rowq1=mysql_fetch_array($q1);
		$rowq2=mysql_fetch_array($q2);
		$rowq3=mysql_fetch_array($q3);
		$rowq4=mysql_fetch_array($q4);
		$rowq5=mysql_fetch_array($q5);
		$rowq6=mysql_fetch_array($q6);
		$rowq7=mysql_fetch_array($q7);
		$rowq8=mysql_fetch_array($q8);
		$rowq9=mysql_fetch_array($q9);
		$rowq10=mysql_fetch_array($q10);
		$rowq11=mysql_fetch_array($q11);
		$rowq12=mysql_fetch_array($q12);
		$rowq13=mysql_fetch_array($q13);
		$rowq14=mysql_fetch_array($q14);
		$rowq15=mysql_fetch_array($q15);
		$rowq16=mysql_fetch_array($q16);
		$rowq17=mysql_fetch_array($q17);
		$rowq18=mysql_fetch_array($q18);
		$rowa1=mysql_fetch_array($a1);
		$rowa2=mysql_fetch_array($a2);
		$rowa3=mysql_fetch_array($a3);
		$rowa4=mysql_fetch_array($a4);
		$rowa5=mysql_fetch_array($a5);
		$rowa6=mysql_fetch_array($a6);
		$rowa7=mysql_fetch_array($a7);
		$rowa8=mysql_fetch_array($a8);
		$rowa9=mysql_fetch_array($a9);
		$rowa10=mysql_fetch_array($a10);
	}
	else
	{
		//echo "<script>alert('without post');</script>";
		$rowq1=mysql_fetch_array($q1);
		$rowq2=mysql_fetch_array($q2);
		$rowq3=mysql_fetch_array($q3);
		$rowq4=mysql_fetch_array($q4);
		$rowq5=mysql_fetch_array($q5);
		$rowq6=mysql_fetch_array($q6);
		$rowq7=mysql_fetch_array($q7);
		$rowq8=mysql_fetch_array($q8);
		$rowq9=mysql_fetch_array($q9);
		$rowq10=mysql_fetch_array($q10);
		$rowq11=mysql_fetch_array($q11);
		$rowq12=mysql_fetch_array($q12);
		$rowq13=mysql_fetch_array($q13);
		$rowq14=mysql_fetch_array($q14);
		$rowq15=mysql_fetch_array($q15);
		$rowq16=mysql_fetch_array($q16);
		$rowq17=mysql_fetch_array($q17);
		$rowq18=mysql_fetch_array($q18);
		$rowa1=mysql_fetch_array($a1);
		$rowa2=mysql_fetch_array($a2);
		$rowa3=mysql_fetch_array($a3);
		$rowa4=mysql_fetch_array($a4);
		$rowa5=mysql_fetch_array($a5);
		$rowa6=mysql_fetch_array($a6);
		$rowa7=mysql_fetch_array($a7);
		$rowa8=mysql_fetch_array($a8);
		$rowa9=mysql_fetch_array($a9);
		$rowa10=mysql_fetch_array($a10);
	}

?>


<style>
input[type=text], select {
    color:green!important;
}
::-webkit-input-placeholder { /* Chrome */
  color: red;
}
:-ms-input-placeholder { /* IE 10+ */
  color: red;
}
::-moz-placeholder { /* Firefox 19+ */
  color: red;
  opacity: 1;
}
:-moz-placeholder { /* Firefox 4 - 18 */
  color: red;
  opacity: 1;
}
</style>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<div class="container" style="width:auto">
  <center><h2>Profile Questions Settings</h2></center>
  <p>To make the tabs toggleable, add the data-toggle="tab" attribute to each link. Then add a .tab-pane class with a unique ID for every tab and wrap them inside a div element with class .tab-content.</p>

  <ul class="nav nav-tabs">
    <li class="active"><a data-toggle="tab" href="#home">Profile Page 1</a></li>
    <li><a data-toggle="tab" href="#menu1">Profile Page 2</a></li>
    <li><a data-toggle="tab" href="#menu2">Profile Page 3</a></li>
    <li><a data-toggle="tab" href="#menu3">Profile Page 4</a></li>
<?php
if(mysql_num_rows($q4)>0)
{
?>
    <li><a data-toggle="tab" href="#menu4"><?php echo $rowq4['question'];?> Answers</a></li>
<?php
}
?>
  </ul>

  <div class="tab-content">
    <div id="home" class="tab-pane fade in active">
      <h3>Profile Page 1</h3>
      <p>

	<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
	<br>
		<table style="margin-left:50px;" name="connection" width="80%">
			<tbody>

				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>

				<tr>
					<td>Main title</td>
					<td><input name="MainTitleProfile1" required value="<?php echo get_option("MainTitleProfile1"); ?>" placeholder="Welcome..." type="text" size="50"></td>
					<td></td>
				</tr>
				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
<tr>
					<td>Sub Title</td>
					<td><input name="SubTitleProfile1" required value="<?php echo get_option("SubTitleProfile1"); ?>" placeholder="Please tell something.." type="text" size="50"></td>
					<td></td>
				</tr>
				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
				<tr>
					<td>Question 1 -</td>
					<td><input name="q1" required value="<?php echo $rowq1['question']; ?>" placeholder="Name" type="text" size="50"></td>
					<td>Ex: Name?;</td>
				</tr>
				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
				<tr>
					<td>Question 2 -</td>
					<td><input name="q2" required value="<?php echo $rowq2['question']; ?>" placeholder="Zipcode" type="text" size="50"></td>
					<td>Ex: Zipcode (or) Enter your Zip Code;</td>
				</tr>
				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
				<tr>
					<td>Question 3 (Placeholder) -</td>
					<td><input name="q3" value="<?php echo $rowq3['question']; ?>" placeholder="Select Timezone" type="text" size="50"></td>
					<td>Ex: Choose Timezone;</td>
				</tr>

				<tr>
					<td>&nbsp;</td>

					<td colspan="2"><br><br>&nbsp;<input class="button" name="page1" value="Save Questions" type="submit"></td>
				</tr>
			</tbody>
		</table>

	</form>

	  </p>
    </div>




    <div id="menu1" class="tab-pane fade">
      <h3>Profile Page 2</h3>
      <p>
	<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
	<br>
		<table style="margin-left:50px;" name="connection" width="80%">
			<tbody>

				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
				<tr>
					<td>Main title</td>
					<td><input name="MainTitleProfile2" required value="<?php echo get_option("MainTitleProfile2"); ?>" placeholder="Welcome..." type="text" size="50"></td>
					<td></td>
				</tr>
				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
<tr>
					<td>Sub Title</td>
					<td><input name="SubTitleProfile2" required value="<?php echo get_option("SubTitleProfile2"); ?>" placeholder="Please tell something.." type="text" size="50"></td>
					<td></td>
				</tr>
				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
				<tr>
					<td>Question 4 ( Dropdown )-</td>
					<td><input name="q4" required value="<?php echo $rowq4['question']; ?>" placeholder="What is your handicap?" type="text" size="50"></td>
					<td>Ex: What is your Handicap?;</td>
				</tr>
				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
				<tr>
					<td>Question 5 -</td>
					<td><input name="q5" required value="<?php echo $rowq5['question']; ?>" placeholder="Home Golf Course?" type="text" size="50"></td>
					<td>Ex: Home Golf Course?;</td>
				</tr>
				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
				<tr>
					<td>Question 6 (Placeholder) -</td>
					<td><input name="q6" value="<?php echo $rowq6['question']; ?>" placeholder="Favorite Golf Course?" type="text" size="50"></td>
					<td>Ex: Favorite Golf Course?;</td>
				</tr>

				<tr>
					<td>&nbsp;</td>

					<td colspan="2"><br><br>&nbsp;<input class="button" name="page2" value="Save Questions" type="submit"></td>
				</tr>
			</tbody>
		</table>

	</form>

	  </p>
    </div>



    <div id="menu2" class="tab-pane fade">
      <h3>Profile Page 3</h3>
      <p>
	<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
	<br>
		<table style="margin-left:50px;" name="connection" width="80%">
			<tbody>

				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
				<tr>
					<td>Main title</td>
					<td><input name="MainTitleProfile3" required value="<?php echo get_option("MainTitleProfile3"); ?>" placeholder="Welcome..." type="text" size="50"></td>
					<td></td>
				</tr>
				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
<tr>
					<td>Sub Title</td>
					<td><input name="SubTitleProfile3" required value="<?php echo get_option("SubTitleProfile3"); ?>" placeholder="Please tell something.." type="text" size="50"></td>
					<td></td>
				</tr>
				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
				<tr>
					<td>Question 7 -</td>
					<td><input name="q7" required value="<?php echo $rowq7['question']; ?>" placeholder="Current Driver/Wood/Iron" type="text" size="50"></td>
					<td>Ex: Current Driver/Wood/Iron?;</td>
				</tr>
				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
				<tr>
					<td>Question 8 -</td>
					<td><input name="q8" required value="<?php echo $rowq8['question']; ?>" placeholder="Approx Price Paid ($xxx)" type="text" size="50"></td>
					<td>Ex: Approx Price Paid ($xxx)?;</td>
				</tr>
				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
				<tr>
					<td>Question 9 -</td>
					<td><input name="q9" value="<?php echo $rowq9['question']; ?>" placeholder="Year Purchased" type="text" size="50"></td>
					<td>Ex: Year Purchased?;</td>
				</tr>
				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
				<tr>
					<td>Question 10 -</td>
					<td><input name="q10" required value="<?php echo $rowq10['question']; ?>" placeholder="Current Driver/Wood/Iron" type="text" size="50"></td>
					<td>Ex: Current Driver/Wood/Iron?;</td>
				</tr>
				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
				<tr>
					<td>Question 11 -</td>
					<td><input name="q11" required value="<?php echo $rowq11['question']; ?>" placeholder="Approx Price Paid ($xxx)" type="text" size="50"></td>
					<td>Ex: Approx Price Paid ($xxx)?;</td>
				</tr>
				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
				<tr>
					<td>Question 12 -</td>
					<td><input name="q12" value="<?php echo $rowq12['question']; ?>" placeholder="Year Purchased" type="text" size="50"></td>
					<td>Ex: Year Purchased?;</td>
				</tr>

				<tr>
					<td>&nbsp;</td>

					<td colspan="2"><br><br>&nbsp;<input class="button" name="page3" value="Save Questions" type="submit"></td>
				</tr>
			</tbody>
		</table>

	</form>


	  </p>
    </div>




  <div id="menu3" class="tab-pane fade">
      <h3>Profile Page 4</h3>
      <p>
	<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
	<br>
		<table style="margin-left:50px;" name="connection" width="80%">
			<tbody>

				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
				<tr>
					<td>Main title</td>
					<td><input name="MainTitleProfile4" required value="<?php echo get_option("MainTitleProfile4"); ?>" placeholder="Welcome..." type="text" size="50"></td>
					<td></td>
				</tr>
				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
<tr>
					<td>Sub Title</td>
					<td><input name="SubTitleProfile4" required value="<?php echo get_option("SubTitleProfile4"); ?>" placeholder="Please tell something.." type="text" size="50"></td>
					<td></td>
				</tr>
				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
				<tr>
					<td>Question 13 -</td>
					<td><input name="q13" required value="<?php echo $rowq13['question']; ?>" placeholder="Current Driver/Wood/Iron" type="text" size="50"></td>
					<td>Ex: Current Driver/Wood/Iron?;</td>
				</tr>
				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
				<tr>
					<td>Question 14 -</td>
					<td><input name="q14" required value="<?php echo $rowq14['question']; ?>" placeholder="Approx Price Paid ($xxx)" type="text" size="50"></td>
					<td>Ex: Approx Price Paid ($xxx)?;</td>
				</tr>
				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
				<tr>
					<td>Question 15 -</td>
					<td><input name="q15" value="<?php echo $rowq15['question']; ?>" placeholder="Year Purchased" type="text" size="50"></td>
					<td>Ex: Year Purchased?;</td>
				</tr>
				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
				<tr>
					<td>Question 16 -</td>
					<td><input name="q16" required value="<?php echo $rowq16['question']; ?>" placeholder="Current Driver/Wood/Iron" type="text" size="50"></td>
					<td>Ex: Current Driver/Wood/Iron?;</td>
				</tr>
				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
				<tr>
					<td>Question 17 -</td>
					<td><input name="q17" required value="<?php echo $rowq17['question']; ?>" placeholder="Approx Price Paid ($xxx)" type="text" size="50"></td>
					<td>Ex: Approx Price Paid ($xxx)?;</td>
				</tr>
				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
				<tr>
					<td>Question 18 -</td>
					<td><input name="q18" value="<?php echo $rowq18['question']; ?>" placeholder="Year Purchased" type="text" size="50"></td>
					<td>Ex: Year Purchased?;</td>
				</tr>

				<tr>
					<td>&nbsp;</td>

					<td colspan="2"><br><br>&nbsp;<input class="button" name="page4" value="Save Questions" type="submit"></td>
				</tr>
			</tbody>
		</table>

	</form>

	  </p>
    </div>


  <div id="menu4" class="tab-pane fade">
      <h3><?php echo $rowq4['question']; ?>'s Dropdown Options</h3>
	  <p>* Minimum One Drop Down required</p>
      <p>
	<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
	<br>
		<table style="margin-left:50px;" name="connection" width="80%">
			<tbody>

				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
				<tr>
					<td>Dropdown Option 1 -</td>
					<td><input name="a1" required value="<?php echo $rowa1['question']; ?>" placeholder="Current Driver/Wood/Iron" type="text" size="50"></td>
					<td>Ex: Scratch Golfer;</td>
				</tr>
				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
				<tr>
					<td>Dropdown Option 2 -</td>
					<td><input name="a2" value="<?php echo $rowa2['question']; ?>" placeholder="Approx Price Paid ($xxx)" type="text" size="50"></td>
					<td>Ex: Shoots in the 70s;</td>
				</tr>
				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
				<tr>
					<td>Dropdown Option 3 -</td>
					<td><input name="a3" value="<?php echo $rowa3['question']; ?>" placeholder="Year Purchased" type="text" size="50"></td>
					<td>Ex: Shoots in the 80s;</td>
				</tr>
				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
				<tr>
					<td>Dropdown Option 4 -</td>
					<td><input name="a4" value="<?php echo $rowa4['question']; ?>" placeholder="Current Driver/Wood/Iron" type="text" size="50"></td>
					<td>Ex: Shoots in the 90s;</td>
				</tr>
				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
				<tr>
					<td>Dropdown Option 5 -</td>
					<td><input name="a5" value="<?php echo $rowa5['question']; ?>" placeholder="Approx Price Paid ($xxx)" type="text" size="50"></td>
					<td>Ex: Shoots in the 100s;</td>
				</tr>

				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
				<tr>
					<td>Dropdown Option 6 -</td>
					<td><input name="a6" value="<?php echo $rowa6['question']; ?>" placeholder="Current Driver/Wood/Iron" type="text" size="50"></td>
					<td>Ex: Scratch Golfer;</td>
				</tr>
				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
				<tr>
					<td>Dropdown Option 7 -</td>
					<td><input name="a7" value="<?php echo $rowa7['question']; ?>" placeholder="Approx Price Paid ($xxx)" type="text" size="50"></td>
					<td>Ex: Shoots in the 70s;</td>
				</tr>
				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
				<tr>
					<td>Dropdown Option 8 -</td>
					<td><input name="a8" value="<?php echo $rowa8['question']; ?>" placeholder="Year Purchased" type="text" size="50"></td>
					<td>Ex: Shoots in the 80s;</td>
				</tr>
				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
				<tr>
					<td>Dropdown Option 9 -</td>
					<td><input name="a9" value="<?php echo $rowa9['question']; ?>" placeholder="Current Driver/Wood/Iron" type="text" size="50"></td>
					<td>Ex: Shoots in the 90s;</td>
				</tr>
				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
				<tr>
					<td>Dropdown Option 10 -</td>
					<td><input name="a10" value="<?php echo $rowa10['question']; ?>" placeholder="Approx Price Paid ($xxx)" type="text" size="50"></td>
					<td>Ex: Shoots in the 100s;</td>
				</tr>

				<tr>
					<td>&nbsp;</td>

					<td colspan="2"><br><br>&nbsp;<input class="button" name="ans" value="Save Answers" type="submit"></td>
				</tr>
			</tbody>
		</table>

	</form>

	  </p>
    </div>



 </div>
</div>

</body>
</html>


<?php
}
?>

















<!-- for seo tab -->

<?php
if( $pg == 'seo' )
{

	//home
	if(isset($_POST['saveseohome']))
	{
			update_option("seohometitle", $_POST['seohometitle']);
			update_option("seohomedesc",$_POST['seohomedesc']);
			update_option("seohomekey",$_POST['seohomekey']);

			$seohometitle=stripcslashes(get_option("seohometitle"));
			$seohomedesc=stripcslashes(get_option("seohomedesc"));
			$seohomekey=stripcslashes(get_option("seohomekey"));

			echo "<script>alert('SEO details for home page updated successfully');</script>";
	}
	else
	{
			$seohometitle=stripcslashes(get_option("seohometitle"));
			$seohomedesc=stripcslashes(get_option("seohomedesc"));
			$seohomekey=stripcslashes(get_option("seohomekey"));
	}
	//category
	if(isset($_POST['saveseocategory']))
	{
			update_option("seocategorytitle", $_POST['seocategorytitle']);
			update_option("seocategorydesc",$_POST['seocategorydesc']);
			update_option("seocategorykey",$_POST['seocategorykey']);

			$seocategorytitle=stripcslashes(get_option("seocategorytitle"));
			$seocategorydesc=stripcslashes(get_option("seocategorydesc"));
			$seocategorykey=stripcslashes(get_option("seocategorykey"));

			echo "<script>alert('SEO details for category page updated successfully');</script>";
	}
	else
	{
			$seocategorytitle=stripcslashes(get_option("seocategorytitle"));
			$seocategorydesc=stripcslashes(get_option("seocategorydesc"));
			$seocategorykey=stripcslashes(get_option("seocategorykey"));
	}

	//category brand
	if(isset($_POST['saveseocategorybrand']))
	{
			update_option("seocategorybrandtitle", $_POST['seocategorybrandtitle']);
			update_option("seocategorybranddesc",$_POST['seocategorybranddesc']);
			update_option("seocategorybrandkey",$_POST['seocategorybrandkey']);

			$seocategorybrandtitle=stripcslashes(get_option("seocategorybrandtitle"));
			$seocategorybranddesc=stripcslashes(get_option("seocategorybranddesc"));
			$seocategorybrandkey=stripcslashes(get_option("seocategorybrandkey"));

			echo "<script>alert('SEO details for Category brand page updated successfully');</script>";
	}
	else
	{
			$seocategorybrandtitle=stripcslashes(get_option("seocategorybrandtitle"));
			$seocategorybranddesc=stripcslashes(get_option("seocategorybranddesc"));
			$seocategorybrandkey=stripcslashes(get_option("seocategorybrandkey"));
	}

	//category sub category
	if(isset($_POST['saveseocategorysubcategory']))
	{
			update_option("seocategorysubcategorytitle", $_POST['seocategorysubcategorytitle']);
			update_option("seocategorysubcategorydesc",$_POST['seocategorysubcategorydesc']);
			update_option("seocategorysubcategorykey",$_POST['seocategorysubcategorykey']);

			$seocategorysubcategorytitle=stripcslashes(get_option("seocategorysubcategorytitle"));
			$seocategorysubcategorydesc=stripcslashes(get_option("seocategorysubcategorydesc"));
			$seocategorysubcategorykey=stripcslashes(get_option("seocategorysubcategorykey"));

			echo "<script>alert('SEO details for Category Sub Category page updated successfully');</script>";
	}
	else
	{
			$seocategorysubcategorytitle=stripcslashes(get_option("seocategorysubcategorytitle"));
			$seocategorysubcategorydesc=stripcslashes(get_option("seocategorysubcategorydesc"));
			$seocategorysubcategorykey=stripcslashes(get_option("seocategorysubcategorykey"));
	}

	//category brand sub category
	if(isset($_POST['saveseocategorybrandsubcategory']))
	{
			update_option("seocategorybrandsubcategorytitle", $_POST['seocategorybrandsubcategorytitle']);
			update_option("seocategorybrandsubcategorydesc",$_POST['seocategorybrandsubcategorydesc']);
			update_option("seocategorybrandsubcategorykey",$_POST['seocategorybrandsubcategorykey']);

			$seocategorybrandsubcategorytitle=stripcslashes(get_option("seocategorybrandsubcategorytitle"));
			$seocategorybrandsubcategorydesc=stripcslashes(get_option("seocategorybrandsubcategorydesc"));
			$seocategorybrandsubcategorykey=stripcslashes(get_option("seocategorybrandsubcategorykey"));

			echo "<script>alert('SEO details for Category Brand Sub Category page updated successfully');</script>";
	}
	else
	{
			$seocategorybrandsubcategorytitle=stripcslashes(get_option("seocategorybrandsubcategorytitle"));
			$seocategorybrandsubcategorydesc=stripcslashes(get_option("seocategorybrandsubcategorydesc"));
			$seocategorybrandsubcategorykey=stripcslashes(get_option("seocategorybrandsubcategorykey"));
	}

	//product
	if(isset($_POST['saveseoproduct']))
	{
			update_option("seoproducttitle", $_POST['seoproducttitle']);
			update_option("seoproductdesc",$_POST['seoproductdesc']);
			update_option("seoproductkey",$_POST['seoproductkey']);

			$seoproducttitle=stripcslashes(get_option("seoproducttitle"));
			$seoproductdesc=stripcslashes(get_option("seoproductdesc"));
			$seoproductkey=stripcslashes(get_option("seoproductkey"));

			echo "<script>alert('SEO details for product page updated successfully');</script>";
	}
	else
	{
			$seoproducttitle=stripcslashes(get_option("seoproducttitle"));
			$seoproductdesc=stripcslashes(get_option("seoproductdesc"));
			$seoproductkey=stripcslashes(get_option("seoproductkey"));
	}

	//brands
	if(isset($_POST['saveseobrands']))
	{
			update_option("seobrandstitle", $_POST['seobrandstitle']);
			update_option("seobrandsdesc",$_POST['seobrandsdesc']);
			update_option("seobrandskey",$_POST['seobrandskey']);

			$seobrandstitle=stripcslashes(get_option("seobrandstitle"));
			$seobrandsdesc=stripcslashes(get_option("seobrandsdesc"));
			$seobrandskey=stripcslashes(get_option("seobrandskey"));

			echo "<script>alert('SEO details for brands page updated successfully');</script>";
	}
	else
	{
			$seobrandstitle=stripcslashes(get_option("seobrandstitle"));
			$seobrandsdesc=stripcslashes(get_option("seobrandsdesc"));
			$seobrandskey=stripcslashes(get_option("seobrandskey"));
	}

	//brands manufacturer
	if(isset($_POST['saveseobrandsmanufacturer']))
	{
			update_option("seobrandsmanufacturertitle", $_POST['seobrandsmanufacturertitle']);
			update_option("seobrandsmanufacturerdesc",$_POST['seobrandsmanufacturerdesc']);
			update_option("seobrandsmanufacturerkey",$_POST['seobrandsmanufacturerkey']);

			$seobrandsmanufacturertitle=stripcslashes(get_option("seobrandsmanufacturertitle"));
			$seobrandsmanufacturerdesc=stripcslashes(get_option("seobrandsmanufacturerdesc"));
			$seobrandsmanufacturerkey=stripcslashes(get_option("seobrandsmanufacturerkey"));

			echo "<script>alert('SEO details for Brands Manufacturer page updated successfully');</script>";
	}
	else
	{
			$seobrandsmanufacturertitle=stripcslashes(get_option("seobrandsmanufacturertitle"));
			$seobrandsmanufacturerdesc=stripcslashes(get_option("seobrandsmanufacturerdesc"));
			$seobrandsmanufacturerkey=stripcslashes(get_option("seobrandsmanufacturerkey"));
	}

	//location home
	if(isset($_POST['saveseolocationhome']))
	{
			update_option("seolocationhometitle", $_POST['seolocationhometitle']);
			update_option("seolocationhomedesc",$_POST['seolocationhomedesc']);
			update_option("seolocationhomekey",$_POST['seolocationhomekey']);

			$seolocationhometitle=stripcslashes(get_option("seolocationhometitle"));
			$seolocationhomedesc=stripcslashes(get_option("seolocationhomedesc"));
			$seolocationhomekey=stripcslashes(get_option("seolocationhomekey"));

			echo "<script>alert('SEO details for Location Landing page updated successfully');</script>";
	}
	else
	{
			$seolocationhometitle=stripcslashes(get_option("seolocationhometitle"));
			$seolocationhomedesc=stripcslashes(get_option("seolocationhomedesc"));
			$seolocationhomekey=stripcslashes(get_option("seolocationhomekey"));
	}

	//Location State
	if(isset($_POST['saveseolocationstate']))
	{
			update_option("seolocationstatetitle", $_POST['seolocationstatetitle']);
			update_option("seolocationstatedesc",$_POST['seolocationstatedesc']);
			update_option("seolocationstatekey",$_POST['seolocationstatekey']);

			$seolocationstatetitle=stripcslashes(get_option("seolocationstatetitle"));
			$seolocationstatedesc=stripcslashes(get_option("seolocationstatedesc"));
			$seolocationstatekey=stripcslashes(get_option("seolocationstatekey"));

			echo "<script>alert('SEO details for Location State page updated successfully');</script>";
	}
	else
	{
			$seolocationstatetitle=stripcslashes(get_option("seolocationstatetitle"));
			$seolocationstatedesc=stripcslashes(get_option("seolocationstatedesc"));
			$seolocationstatekey=stripcslashes(get_option("seolocationstatekey"));
	}

	//locationcity
	if(isset($_POST['saveseolocationcity']))
	{
			update_option("seolocationcitytitle", $_POST['seolocationcitytitle']);
			update_option("seolocationcitydesc",$_POST['seolocationcitydesc']);
			update_option("seolocationcitykey",$_POST['seolocationcitykey']);

			$seolocationcitytitle=stripcslashes(get_option("seolocationcitytitle"));
			$seolocationcitydesc=stripcslashes(get_option("seolocationcitydesc"));
			$seolocationcitykey=stripcslashes(get_option("seolocationcitykey"));

			echo "<script>alert('SEO details for Location City page updated successfully');</script>";
	}
	else
	{
			$seolocationcitytitle=stripcslashes(get_option("seolocationcitytitle"));
			$seolocationcitydesc=stripcslashes(get_option("seolocationcitydesc"));
			$seolocationcitykey=stripcslashes(get_option("seolocationcitykey"));
	}

	//location
	if(isset($_POST['saveseolocation']))
	{
			update_option("seolocationtitle", $_POST['seolocationtitle']);
			update_option("seolocationdesc",$_POST['seolocationdesc']);
			update_option("seolocationkey",$_POST['seolocationkey']);

			$seolocationtitle=stripcslashes(get_option("seolocationtitle"));
			$seolocationdesc=stripcslashes(get_option("seolocationdesc"));
			$seolocationkey=stripcslashes(get_option("seolocationkey"));

			echo "<script>alert('SEO details for location page updated successfully');</script>";
	}
	else
	{
			$seolocationtitle=stripcslashes(get_option("seolocationtitle"));
			$seolocationdesc=stripcslashes(get_option("seolocationdesc"));
			$seolocationkey=stripcslashes(get_option("seolocationkey"));
	}

	//hotdeals
	if(isset($_POST['saveseohotdeals']))
	{
			update_option("seohotdealstitle", $_POST['seohotdealstitle']);
			update_option("seohotdealsdesc",$_POST['seohotdealsdesc']);
			update_option("seohotdealskey",$_POST['seohotdealskey']);

			$seohotdealstitle=stripcslashes(get_option("seohotdealstitle"));
			$seohotdealsdesc=stripcslashes(get_option("seohotdealsdesc"));
			$seohotdealskey=stripcslashes(get_option("seohotdealskey"));

			echo "<script>alert('SEO details for hotdeals page updated successfully');</script>";
	}
	else
	{
			$seohotdealstitle=stripcslashes(get_option("seohotdealstitle"));
			$seohotdealsdesc=stripcslashes(get_option("seohotdealsdesc"));
			$seohotdealskey=stripcslashes(get_option("seohotdealskey"));
	}

	//hotdealsindividual
	if(isset($_POST['saveseohotdealsindividual']))
	{
			update_option("seohotdealsindividualtitle", $_POST['seohotdealsindividualtitle']);
			update_option("seohotdealsindividualdesc",$_POST['seohotdealsindividualdesc']);
			update_option("seohotdealsindividualkey",$_POST['seohotdealsindividualkey']);

			$seohotdealsindividualtitle=stripcslashes(get_option("seohotdealsindividualtitle"));
			$seohotdealsindividualdesc=stripcslashes(get_option("seohotdealsindividualdesc"));
			$seohotdealsindividualkey=stripcslashes(get_option("seohotdealsindividualkey"));

			echo "<script>alert('SEO details for Hot Deals Individual page updated successfully');</script>";
	}
	else
	{
			$seohotdealsindividualtitle=stripcslashes(get_option("seohotdealsindividualtitle"));
			$seohotdealsindividualdesc=stripcslashes(get_option("seohotdealsindividualdesc"));
			$seohotdealsindividualkey=stripcslashes(get_option("seohotdealsindividualkey"));
	}


	//hotdealspartners
	if(isset($_POST['saveseohotdealspartners']))
	{
			update_option("seohotdealspartnerstitle", $_POST['seohotdealspartnerstitle']);
			update_option("seohotdealspartnersdesc",$_POST['seohotdealspartnersdesc']);
			update_option("seohotdealspartnerskey",$_POST['seohotdealspartnerskey']);

			$seohotdealspartnerstitle=stripcslashes(get_option("seohotdealspartnerstitle"));
			$seohotdealspartnersdesc=stripcslashes(get_option("seohotdealspartnersdesc"));
			$seohotdealspartnerskey=stripcslashes(get_option("seohotdealspartnerskey"));

			echo "<script>alert('SEO details for Hot Deals  Partners page updated successfully');</script>";
	}
	else
	{
			$seohotdealspartnerstitle=stripcslashes(get_option("seohotdealspartnerstitle"));
			$seohotdealspartnersdesc=stripcslashes(get_option("seohotdealspartnersdesc"));
			$seohotdealspartnerskey=stripcslashes(get_option("seohotdealspartnerskey"));
	}

	//4th level category
	if(isset($_POST['saveseocategorylevelfour']))
	{
			update_option("seocategorytitlelevelfour", $_POST['seocategorytitlelevelfour']);
			update_option("seocategorydesclevelfour",$_POST['seocategorydesclevelfour']);
			update_option("seocategorykeylevelfour",$_POST['seocategorykeylevelfour']);

			$seocategorytitlelevelfour=stripcslashes(get_option("seocategorytitlelevelfour"));
			$seocategorydesclevelfour=stripcslashes(get_option("seocategorydesclevelfour"));
			$seocategorykeylevelfour=stripcslashes(get_option("seocategorykeylevelfour"));

			echo "<script>alert('SEO details for 4th Level category page updated successfully');</script>";
	}
	else
	{
			$seocategorytitlelevelfour=stripcslashes(get_option("seocategorytitlelevelfour"));
			$seocategorydesclevelfour=stripcslashes(get_option("seocategorydesclevelfour"));
			$seocategorykeylevelfour=stripcslashes(get_option("seocategorykeylevelfour"));
	}

	//3rd level category
	if(isset($_POST['saveseocategorylevelthird']))
	{
			update_option("seocategorytitlelevelthird", $_POST['seocategorytitlelevelthird']);
			update_option("seocategorydesclevelthird",$_POST['seocategorydesclevelthird']);
			update_option("seocategorykeylevelthird",$_POST['seocategorykeylevelthird']);

			$seocategorytitlelevelthird=stripcslashes(get_option("seocategorytitlelevelthird"));
			$seocategorydesclevelthird=stripcslashes(get_option("seocategorydesclevelthird"));
			$seocategorykeylevelthird=stripcslashes(get_option("seocategorykeylevelthird"));

			echo "<script>alert('SEO details for 3rd Level category page updated successfully');</script>";
	}
	else
	{
			$seocategorytitlelevelthird=stripcslashes(get_option("seocategorytitlelevelthird"));
			$seocategorydesclevelthird=stripcslashes(get_option("seocategorydesclevelthird"));
			$seocategorykeylevelthird=stripcslashes(get_option("seocategorykeylevelthird"));
	}


	//4th level brands
	if(isset($_POST['saveseobrandslevelfour']))
	{
			update_option("seobrandstitlelevelfour", $_POST['seobrandstitlelevelfour']);
			update_option("seobrandsdesclevelfour",$_POST['seobrandsdesclevelfour']);
			update_option("seobrandskeylevelfour",$_POST['seobrandskeylevelfour']);

			$seobrandstitlelevelfour=stripcslashes(get_option("seobrandstitlelevelfour"));
			$seobrandsdesclevelfour=stripcslashes(get_option("seobrandsdesclevelfour"));
			$seobrandskeylevelfour=stripcslashes(get_option("seobrandskeylevelfour"));

			echo "<script>alert('SEO details for 4th level brands page updated successfully');</script>";
	}
	else
	{
			$seobrandstitlelevelfour=stripcslashes(get_option("seobrandstitlelevelfour"));
			$seobrandsdesclevelfour=stripcslashes(get_option("seobrandsdesclevelfour"));
			$seobrandskeylevelfour=stripcslashes(get_option("seobrandskeylevelfour"));
	}

	//3rd level brands
	if(isset($_POST['saveseobrandslevelthird']))
	{
			update_option("seobrandstitlelevelthird", $_POST['seobrandstitlelevelthird']);
			update_option("seobrandsdesclevelthird",$_POST['seobrandsdesclevelthird']);
			update_option("seobrandskeylevelthird",$_POST['seobrandskeylevelthird']);

			$seobrandstitlelevelthird=stripcslashes(get_option("seobrandstitlelevelthird"));
			$seobrandsdesclevelthird=stripcslashes(get_option("seobrandsdesclevelthird"));
			$seobrandskeylevelthird=stripcslashes(get_option("seobrandskeylevelthird"));

			echo "<script>alert('SEO details for 3rd level brands page updated successfully');</script>";
	}
	else
	{
			$seobrandstitlelevelthird=stripcslashes(get_option("seobrandstitlelevelthird"));
			$seobrandsdesclevelthird=stripcslashes(get_option("seobrandsdesclevelthird"));
			$seobrandskeylevelthird=stripcslashes(get_option("seobrandskeylevelthird"));
	}

?>

	<form method="post" name="seohome" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
	<br>
		<h3>SEO Settings</h3>
		<table style="margin-left:50px;" name="connection" width="80%">
			<tbody>
				<tr>
						<td bgcolor="#eceaea" colspan="2">
							<p><b>&nbsp;Change the Home page SEO details -</b></p>
						<td bgcolor="#eceaea">Shortcode [example]</td>
						</td>
					</tr>
				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
				<tr>
					<td>Title -</td>
					<td><input name="seohometitle" required value="<?php echo $seohometitle; ?>" placeholder="SEO Meta Title comes here" type="text" size="100"></td>
					<td>[site-name]</td>
				</tr>
				<tr>
					<td>Description -</td>
					<td><input name="seohomedesc" required value="<?php echo $seohomedesc; ?>" placeholder="SEO Meta Description comes here" type="text" size="100"></td>
					<td>[site-name]</td>
				</tr>
				<tr>
					<td>Keywords -</td>
					<td><input name="seohomekey" required value="<?php echo $seohomekey; ?>" placeholder="SEO Meta Keywords comes here" type="text" size="100"></td>
					<td>[site-name]</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>&nbsp;<input class="button" name="saveseohome" value="Save Home SEO" type="submit"></td>
					<td>&nbsp;</td>
				</tr>
			</tbody>
		</table>

	</form>


	<!-- category -->
	<form method="post" name="seocategory" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
	<br>
		<table style="margin-left:50px;" name="connection" width="80%">
			<tbody>
				<tr>
						<td bgcolor="#eceaea" colspan="2">
							<p><b>&nbsp;Change the Category page SEO details -</b></p>
						</td>
						<td bgcolor="#eceaea">Shortcode [example]</td>
					</tr>
				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
				<tr>
					<td>Title -</td>
					<td><input name="seocategorytitle" required value="<?php echo $seocategorytitle; ?>" placeholder="SEO Meta Title comes here" type="text" size="100"></td>
					<td>[site-name], [category_name]</td>
				</tr>
				<tr>
					<td>Description -</td>
					<td><input name="seocategorydesc" required value="<?php echo $seocategorydesc; ?>" placeholder="SEO Meta Description comes here" type="text" size="100"></td>
					<td>[site-name], [category_name]</td>
				</tr>
				<tr>
					<td>Keywords -</td>
					<td><input name="seocategorykey" required value="<?php echo $seocategorykey; ?>" placeholder="SEO Meta Keywords comes here" type="text" size="100"></td>
					<td>[site-name], [category_name]</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>&nbsp;<input class="button" name="saveseocategory" value="Save Category SEO" type="submit"></td>
					<td>&nbsp;</td>
				</tr>
			</tbody>
		</table>

	</form>

	<!-- Category Brand Page -->
	<form method="post" name="seocategorybrand" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
	<br>
		<table style="margin-left:50px;" name="connection" width="80%">
			<tbody>
				<tr>
						<td bgcolor="#eceaea" colspan="2">
							<p><b>&nbsp;Change the Category Brand page SEO details -</b></p>
						</td>
						<td bgcolor="#eceaea">Shortcode [example]</td>
					</tr>
				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
				<tr>
					<td>Title -</td>
					<td><input name="seocategorybrandtitle" required value="<?php echo $seocategorybrandtitle; ?>" placeholder="SEO Meta Title comes here" type="text" size="100"></td>
					<td>[site-name], [category_name], [brand_name]</td>
				</tr>
				<tr>
					<td>Description -</td>
					<td><input name="seocategorybranddesc" required value="<?php echo $seocategorybranddesc; ?>" placeholder="SEO Meta Description comes here" type="text" size="100"></td>
					<td>[site-name], [category_name], [brand_name]</td>
				</tr>
				<tr>
					<td>Keywords -</td>
					<td><input name="seocategorybrandkey" required value="<?php echo $seocategorybrandkey; ?>" placeholder="SEO Meta Keywords comes here" type="text" size="100"></td>
					<td>[site-name], [category_name], [brand_name]</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>&nbsp;<input class="button" name="saveseocategorybrand" value="Save Category Brand SEO" type="submit"></td>
					<td>&nbsp;</td>
				</tr>
			</tbody>
		</table>

	</form>

	<!-- Category Sub Category -->
	<form method="post" name="seocategorysubcategoey" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
	<br>
		<table style="margin-left:50px;" name="connection" width="80%">
			<tbody>
				<tr>
						<td bgcolor="#eceaea" colspan="2">
							<p><b>&nbsp;Change the Category Sub Category page SEO details -</b></p>
						</td>
						<td bgcolor="#eceaea">Shortcode [example]</td>
					</tr>
				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
				<tr>
					<td>Title -</td>
					<td><input name="seocategorysubcategorytitle" required value="<?php echo $seocategorysubcategorytitle; ?>" placeholder="SEO Meta Title comes here" type="text" size="100"></td>
					<td>[site-name], [category_name], [subcategory_name]</td>
				</tr>
				<tr>
					<td>Description -</td>
					<td><input name="seocategorysubcategorydesc" required value="<?php echo $seocategorysubcategorydesc; ?>" placeholder="SEO Meta Description comes here" type="text" size="100"></td>
					<td>[site-name], [category_name], [subcategory_name]</td>
				</tr>
				<tr>
					<td>Keywords -</td>
					<td><input name="seocategorysubcategorykey" required value="<?php echo $seocategorysubcategorykey; ?>" placeholder="SEO Meta Keywords comes here" type="text" size="100"></td>
					<td>[site-name], [category_name], [subcategory_name]</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>&nbsp;<input class="button" name="saveseocategorysubcategory" value="Save Category Sub Category SEO" type="submit"></td>
					<td>&nbsp;</td>
				</tr>
			</tbody>
		</table>

	</form>

	<!-- category beand sub category -->
	<form method="post" name="seocategorybrandsubcategory" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
	<br>
		<table style="margin-left:50px;" name="connection" width="80%">
			<tbody>
				<tr>
						<td bgcolor="#eceaea" colspan="2">
							<p><b>&nbsp;Change the Category Brand Sub Category page SEO details -</b></p>
						</td>
						<td bgcolor="#eceaea">Shortcode [example]</td>
					</tr>
				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
				<tr>
					<td>Title -</td>
					<td><input name="seocategorybrandsubcategorytitle" required value="<?php echo $seocategorybrandsubcategorytitle; ?>" placeholder="SEO Meta Title comes here" type="text" size="100"></td>
					<td>[site-name], [category_name], [brand_name], [subcategory_name]</td>
				</tr>
				<tr>
					<td>Description -</td>
					<td><input name="seocategorybrandsubcategorydesc" required value="<?php echo $seocategorybrandsubcategorydesc; ?>" placeholder="SEO Meta Description comes here" type="text" size="100"></td>
					<td>[site-name], [category_name], [brand_name], [subcategory_name]</td>
				</tr>
				<tr>
					<td>Keywords -</td>
					<td><input name="seocategorybrandsubcategorykey" required value="<?php echo $seocategorybrandsubcategorykey; ?>" placeholder="SEO Meta Keywords comes here" type="text" size="100"></td>
					<td>[site-name], [category_name], [brand_name], [subcategory_name]</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>&nbsp;<input class="button" name="saveseocategorybrandsubcategory" value="Save Category SEO" type="submit"></td>
					<td>&nbsp;</td>
				</tr>
			</tbody>
		</table>

	</form>

	<!-- Product -->
	<form method="post" name="seoproduct" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
	<br>
		<table style="margin-left:50px;" name="connection" width="80%">
			<tbody>
				<tr>
						<td bgcolor="#eceaea" colspan="2">
							<p><b>&nbsp;Change the product page SEO details -</b></p>
						</td>
						<td bgcolor="#eceaea">Shortcode [example]</td>
					</tr>
				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
				<tr>
					<td>Title -</td>
					<td><input name="seoproducttitle" required value="<?php echo $seoproducttitle; ?>" placeholder="SEO Meta Title comes here" type="text" size="100"></td>
					<td>[site-name], [category_name], [brand_name], [product_name], [subcategory_name], [review_score], [review_count]</td>
				</tr>
				<tr>
					<td>Description -</td>
					<td><input name="seoproductdesc" required value="<?php echo $seoproductdesc; ?>" placeholder="SEO Meta Description comes here" type="text" size="100"></td>
					<td>[site-name], [category_name], [brand_name], [product_name], [subcategory_name], [review_score], [review_count]</td>
				</tr>
				<tr>
					<td>Keywords -</td>
					<td><input name="seoproductkey" required value="<?php echo $seoproductkey; ?>" placeholder="SEO Meta Keywords comes here" type="text" size="100"></td>
					<td>[site-name], [category_name], [brand_name], [product_name], [subcategory_name]</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>&nbsp;<input class="button" name="saveseoproduct" value="Save Product SEO" type="submit"></td>
					<td>&nbsp;</td>
				</tr>
			</tbody>
		</table>

	</form>

	<!-- brands -->
	<form method="post" name="seobrands" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
	<br>
		<table style="margin-left:50px;" name="connection" width="80%">
			<tbody>
				<tr>
						<td bgcolor="#eceaea" colspan="2">
							<p><b>&nbsp;Change the brands page SEO details -</b></p>
						</td>
						<td bgcolor="#eceaea">Shortcode [example]</td>
					</tr>
				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
				<tr>
					<td>Title -</td>
					<td><input name="seobrandstitle" required value="<?php echo $seobrandstitle; ?>" placeholder="SEO Meta Title comes here" type="text" size="100"></td>
					<td>[site-name], [category_name]</td>
				</tr>
				<tr>
					<td>Description -</td>
					<td><input name="seobrandsdesc" required value="<?php echo $seobrandsdesc; ?>" placeholder="SEO Meta Description comes here" type="text" size="100"></td>
					<td>[site-name], [category_name]</td>
				</tr>
				<tr>
					<td>Keywords -</td>
					<td><input name="seobrandskey" required value="<?php echo $seobrandskey; ?>" placeholder="SEO Meta Keywords comes here" type="text" size="100"></td>
					<td>[site-name], [category_name]</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>&nbsp;<input class="button" name="saveseobrands" value="Save brands SEO" type="submit"></td>
					<td>&nbsp;</td>
				</tr>
			</tbody>
		</table>

	</form>

	<!-- brands manufacturer -->
	<form method="post" name="seobrandsmanufacturer" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
	<br>
		<table style="margin-left:50px;" name="connection" width="80%">
			<tbody>
				<tr>
						<td bgcolor="#eceaea" colspan="2">
							<p><b>&nbsp;Change the Brands manufacturer page SEO details -</b></p>
						</td>
						<td bgcolor="#eceaea">Shortcode [example]</td>
					</tr>
				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
				<tr>
					<td>Title -</td>
					<td><input name="seobrandsmanufacturertitle" required value="<?php echo $seobrandsmanufacturertitle; ?>" placeholder="SEO Meta Title comes here" type="text" size="100"></td>
					<td>[site-name], [category_name]</td>
				</tr>
				<tr>
					<td>Description -</td>
					<td><input name="seobrandsmanufacturerdesc" required value="<?php echo $seobrandsmanufacturerdesc; ?>" placeholder="SEO Meta Description comes here" type="text" size="100"></td>
					<td>[site-name], [category_name]</td>
				</tr>
				<tr>
					<td>Keywords -</td>
					<td><input name="seobrandsmanufacturerkey" required value="<?php echo $seobrandsmanufacturerkey; ?>" placeholder="SEO Meta Keywords comes here" type="text" size="100"></td>
					<td>[site-name], [category_name]</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>&nbsp;<input class="button" name="saveseobrandsmanufacturer" value="Save Brands Manufacturer SEO" type="submit"></td>
					<td>&nbsp;</td>
				</tr>
			</tbody>
		</table>

	</form>

	<!-- location home trails.html -->
	<form method="post" name="seolocationhome" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
	<br>
		<table style="margin-left:50px;" name="connection" width="80%">
			<tbody>
				<tr>
						<td bgcolor="#eceaea" colspan="2">
							<p><b>&nbsp;Change the Location Home page SEO details -</b></p>
						</td>
						<td bgcolor="#eceaea">Shortcode [example]</td>
					</tr>
				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
				<tr>
					<td>Title -</td>
					<td><input name="seolocationhometitle" required value="<?php echo $seolocationhometitle; ?>" placeholder="SEO Meta Title comes here" type="text" size="100"></td>
					<td>[site-name]</td>
				</tr>
				<tr>
					<td>Description -</td>
					<td><input name="seolocationhomedesc" required value="<?php echo $seolocationhomedesc; ?>" placeholder="SEO Meta Description comes here" type="text" size="100"></td>
					<td>[site-name]</td>
				</tr>
				<tr>
					<td>Keywords -</td>
					<td><input name="seolocationhomekey" required value="<?php echo $seolocationhomekey; ?>" placeholder="SEO Meta Keywords comes here" type="text" size="100"></td>
					<td>[site-name]</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>&nbsp;<input class="button" name="saveseolocationhome" value="Save Location Home SEO" type="submit"></td>
					<td>&nbsp;</td>
				</tr>
			</tbody>
		</table>

	</form>

	<!-- Location State -->
	<form method="post" name="seolocationstate" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
	<br>
		<table style="margin-left:50px;" name="connection" width="80%">
			<tbody>
				<tr>
						<td bgcolor="#eceaea" colspan="2">
							<p><b>&nbsp;Change the Location State page SEO details -</b></p>
						</td>
						<td bgcolor="#eceaea">Shortcode [example]</td>
					</tr>
				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
				<tr>
					<td>Title -</td>
					<td><input name="seolocationstatetitle" required value="<?php echo $seolocationstatetitle; ?>" placeholder="SEO Meta Title comes here" type="text" size="100"></td>
					<td>[site-name], [state_name]</td>
				</tr>
				<tr>
					<td>Description -</td>
					<td><input name="seolocationstatedesc" required value="<?php echo $seolocationstatedesc; ?>" placeholder="SEO Meta Description comes here" type="text" size="100"></td>
					<td>[site-name], [state_name]</td>
				</tr>
				<tr>
					<td>Keywords -</td>
					<td><input name="seolocationstatekey" required value="<?php echo $seolocationstatekey; ?>" placeholder="SEO Meta Keywords comes here" type="text" size="100"></td>
					<td>[site-name], [state_name]</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>&nbsp;<input class="button" name="saveseolocationstate" value="Save Location State SEO" type="submit"></td>
					<td>&nbsp;</td>
				</tr>
			</tbody>
		</table>

	</form>

	<!-- Location City -->
	<form method="post" name="seolocationcity" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
	<br>
		<table style="margin-left:50px;" name="connection" width="80%">
			<tbody>
				<tr>
						<td bgcolor="#eceaea" colspan="2">
							<p><b>&nbsp;Change the Location City page SEO details -</b></p>
						</td>
						<td bgcolor="#eceaea">Shortcode [example]</td>
					</tr>
				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
				<tr>
					<td>Title -</td>
					<td><input name="seolocationcitytitle" required value="<?php echo $seolocationcitytitle; ?>" placeholder="SEO Meta Title comes here" type="text" size="100"></td>
					<td>[site-name], [state_name], [city_name]</td>
				</tr>
				<tr>
					<td>Description -</td>
					<td><input name="seolocationcitydesc" required value="<?php echo $seolocationcitydesc; ?>" placeholder="SEO Meta Description comes here" type="text" size="100"></td>
					<td>[site-name], [state_name], [city_name]</td>
				</tr>
				<tr>
					<td>Keywords -</td>
					<td><input name="seolocationcitykey" required value="<?php echo $seolocationcitykey; ?>" placeholder="SEO Meta Keywords comes here" type="text" size="100"></td>
					<td>[site-name], [state_name], [city_name]</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>&nbsp;<input class="button" name="saveseolocationcity" value="Save Location City SEO" type="submit"></td>
					<td>&nbsp;</td>
				</tr>
			</tbody>
		</table>

	</form>

	<!-- Location -->
	<form method="post" name="seolocation" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
	<br>
		<table style="margin-left:50px;" name="connection" width="80%">
			<tbody>
				<tr>
						<td bgcolor="#eceaea" colspan="2">
							<p><b>&nbsp;Change the location page SEO details -</b></p>
						</td>
						<td bgcolor="#eceaea">Shortcode [example]</td>
					</tr>
				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
				<tr>
					<td>Title -</td>
					<td><input name="seolocationtitle" required value="<?php echo $seolocationtitle; ?>" placeholder="SEO Meta Title comes here" type="text" size="100"></td>
					<td>[site-name],[city_name],[product_name],[state_name],[review_score],[review_count]</td>
				</tr>
				<tr>
					<td>Description -</td>
					<td><input name="seolocationdesc" required value="<?php echo $seolocationdesc; ?>" placeholder="SEO Meta Description comes here" type="text" size="100"></td>
					<td>[site-name],[city_name],[product_name],[state_name],[review_score],[review_count]</td>
				</tr>
				<tr>
					<td>Keywords -</td>
					<td><input name="seolocationkey" required value="<?php echo $seolocationkey; ?>" placeholder="SEO Meta Keywords comes here" type="text" size="100"></td>
					<td>[site-name],[city_name],[product_name],[state_name],[review_score],[review_count]</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>&nbsp;<input class="button" name="saveseolocation" value="Save location SEO" type="submit"></td>
					<td>&nbsp;</td>
				</tr>
			</tbody>
		</table>

	</form>

	<!-- Hotdeals -->
	<form method="post" name="seohotdeals" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
	<br>
		<table style="margin-left:50px;" name="connection" width="80%">
			<tbody>
				<tr>
						<td bgcolor="#eceaea" colspan="2">
							<p><b>&nbsp;Change the hotdeals page SEO details -</b></p>
						</td>
						<td bgcolor="#eceaea">Shortcode [example]</td>
					</tr>
				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
				<tr>
					<td>Title -</td>
					<td><input name="seohotdealstitle" required value="<?php echo $seohotdealstitle; ?>" placeholder="SEO Meta Title comes here" type="text" size="100"></td>
					<td>[site-name]</td>
				</tr>
				<tr>
					<td>Description -</td>
					<td><input name="seohotdealsdesc" required value="<?php echo $seohotdealsdesc; ?>" placeholder="SEO Meta Description comes here" type="text" size="100"></td>
					<td>[site-name]</td>
				</tr>
				<tr>
					<td>Keywords -</td>
					<td><input name="seohotdealskey" required value="<?php echo $seohotdealskey; ?>" placeholder="SEO Meta Keywords comes here" type="text" size="100"></td>
					<td>[site-name]</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>&nbsp;<input class="button" name="saveseohotdeals" value="Save hotdeals SEO" type="submit"></td>
					<td>&nbsp;</td>
				</tr>
			</tbody>
		</table>

	</form>

	<!-- hotdeals individual -->
	<form method="post" name="seohotdealsindividual" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
	<br>
		<table style="margin-left:50px;" name="connection" width="80%">
			<tbody>
				<tr>
						<td bgcolor="#eceaea" colspan="2">
							<p><b>&nbsp;Change the Hot Deals Individual page SEO details -</b></p>
						</td>
						<td bgcolor="#eceaea">Shortcode [example]</td>
					</tr>
				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
				<tr>
					<td>Title -</td>
					<td><input name="seohotdealsindividualtitle" required value="<?php echo $seohotdealsindividualtitle; ?>" placeholder="SEO Meta Title comes here" type="text" size="100"></td>
					<td>[site-name], [store_name]</td>
				</tr>
				<tr>
					<td>Description -</td>
					<td><input name="seohotdealsindividualdesc" required value="<?php echo $seohotdealsindividualdesc; ?>" placeholder="SEO Meta Description comes here" type="text" size="100"></td>
					<td>[site-name], [store_name]</td>
				</tr>
				<tr>
					<td>Keywords -</td>
					<td><input name="seohotdealsindividualkey" required value="<?php echo $seohotdealsindividualkey; ?>" placeholder="SEO Meta Keywords comes here" type="text" size="100"></td>
					<td>[site-name], [store_name]</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>&nbsp;<input class="button" name="saveseohotdealsindividual" value="Save Hot Deals Individual SEO" type="submit"></td>
					<td>&nbsp;</td>
				</tr>
			</tbody>
		</table>

	</form>

	<!-- Hotdeals Partners -->
	<form method="post" name="seohotdealspartners" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
	<br>
		<table style="margin-left:50px;" name="connection" width="80%">
			<tbody>
				<tr>
						<td bgcolor="#eceaea" colspan="2">
							<p><b>&nbsp;Change the Hot Deals Partners page SEO details -</b></p>
						</td>
						<td bgcolor="#eceaea">Shortcode [example]</td>
					</tr>
				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
				<tr>
					<td>Title -</td>
					<td><input name="seohotdealspartnerstitle" required value="<?php echo $seohotdealspartnerstitle; ?>" placeholder="SEO Meta Title comes here" type="text" size="100"></td>
					<td>[site-name]</td>
				</tr>
				<tr>
					<td>Description -</td>
					<td><input name="seohotdealspartnersdesc" required value="<?php echo $seohotdealspartnersdesc; ?>" placeholder="SEO Meta Description comes here" type="text" size="100"></td>
					<td>[site-name]</td>
				</tr>
				<tr>
					<td>Keywords -</td>
					<td><input name="seohotdealspartnerskey" required value="<?php echo $seohotdealspartnerskey; ?>" placeholder="SEO Meta Keywords comes here" type="text" size="100"></td>
					<td>[site-name]</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>&nbsp;<input class="button" name="saveseohotdealspartners" value="Save Hot Deals Partners SEO" type="submit"></td>
					<td>&nbsp;</td>
				</tr>
			</tbody>
		</table>

	</form>


<!-- 4th level category -->
	<form method="post" name="seocategorylevelfour" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
	<br>
		<table style="margin-left:50px;" name="connection" width="80%">
			<tbody>
				<tr>
						<td bgcolor="#eceaea" colspan="2">
							<p><b>&nbsp;Change the 4th level Category page SEO details -</b></p>
						</td>
						<td bgcolor="#eceaea">Shortcode [example]</td>
					</tr>
				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
				<tr>
					<td>Title -</td>
					<td><input name="seocategorytitlelevelfour" required value="<?php echo $seocategorytitlelevelfour; ?>" placeholder="SEO Meta Title comes here" type="text" size="100"></td>
					<td>[site-name], [category_name]</td>
				</tr>
				<tr>
					<td>Description -</td>
					<td><input name="seocategorydesclevelfour" required value="<?php echo $seocategorydesclevelfour; ?>" placeholder="SEO Meta Description comes here" type="text" size="100"></td>
					<td>[site-name], [category_name]</td>
				</tr>
				<tr>
					<td>Keywords -</td>
					<td><input name="seocategorykeylevelfour" required value="<?php echo $seocategorykeylevelfour; ?>" placeholder="SEO Meta Keywords comes here" type="text" size="100"></td>
					<td>[site-name], [category_name]</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>&nbsp;<input class="button" name="saveseocategorylevelfour" value="Save 4th level Category SEO" type="submit"></td>
					<td>&nbsp;</td>
				</tr>
			</tbody>
		</table>

	</form>


<!-- 3rd level category -->
	<form method="post" name="seocategorylevelthird" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
	<br>
		<table style="margin-left:50px;" name="connection" width="80%">
			<tbody>
				<tr>
						<td bgcolor="#eceaea" colspan="2">
							<p><b>&nbsp;Change the 3rd level Category page SEO details -</b></p>
						</td>
						<td bgcolor="#eceaea">Shortcode [example]</td>
					</tr>
				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
				<tr>
					<td>Title -</td>
					<td><input name="seocategorytitlelevelthird" required value="<?php echo $seocategorytitlelevelthird; ?>" placeholder="SEO Meta Title comes here" type="text" size="100"></td>
					<td>[site-name], [category_name]</td>
				</tr>
				<tr>
					<td>Description -</td>
					<td><input name="seocategorydesclevelthird" required value="<?php echo $seocategorydesclevelthird; ?>" placeholder="SEO Meta Description comes here" type="text" size="100"></td>
					<td>[site-name], [category_name]</td>
				</tr>
				<tr>
					<td>Keywords -</td>
					<td><input name="seocategorykeylevelthird" required value="<?php echo $seocategorykeylevelthird; ?>" placeholder="SEO Meta Keywords comes here" type="text" size="100"></td>
					<td>[site-name], [category_name]</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>&nbsp;<input class="button" name="saveseocategorylevelthird" value="Save 3rd level Category SEO" type="submit"></td>
					<td>&nbsp;</td>
				</tr>
			</tbody>
		</table>

	</form>


	<!-- 4th level brands -->
	<form method="post" name="seobrandslevelfour" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
	<br>
		<table style="margin-left:50px;" name="connection" width="80%">
			<tbody>
				<tr>
						<td bgcolor="#eceaea" colspan="2">
							<p><b>&nbsp;Change the 4th level brands page SEO details -</b></p>
						</td>
						<td bgcolor="#eceaea">Shortcode [example]</td>
					</tr>
				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
				<tr>
					<td>Title -</td>
					<td><input name="seobrandstitlelevelfour" required value="<?php echo $seobrandstitlelevelfour; ?>" placeholder="SEO Meta Title comes here" type="text" size="100"></td>
					<td>[site-name], [category_name]</td>
				</tr>
				<tr>
					<td>Description -</td>
					<td><input name="seobrandsdesclevelfour" required value="<?php echo $seobrandsdesclevelfour; ?>" placeholder="SEO Meta Description comes here" type="text" size="100"></td>
					<td>[site-name], [category_name]</td>
				</tr>
				<tr>
					<td>Keywords -</td>
					<td><input name="seobrandskeylevelfour" required value="<?php echo $seobrandskeylevelfour; ?>" placeholder="SEO Meta Keywords comes here" type="text" size="100"></td>
					<td>[site-name], [category_name]</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>&nbsp;<input class="button" name="saveseobrandslevelfour" value="Save 4th level brands SEO" type="submit"></td>
					<td>&nbsp;</td>
				</tr>
			</tbody>
		</table>

	</form>



<!-- 3rd level brands -->
	<form method="post" name="seobrandslevelthird" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
	<br>
		<table style="margin-left:50px;" name="connection" width="80%">
			<tbody>
				<tr>
						<td bgcolor="#eceaea" colspan="2">
							<p><b>&nbsp;Change the 3rd level brands page SEO details -</b></p>
						</td>
						<td bgcolor="#eceaea">Shortcode [example]</td>
					</tr>
				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
				<tr>
					<td>Title -</td>
					<td><input name="seobrandstitlelevelthird" required value="<?php echo $seobrandstitlelevelthird; ?>" placeholder="SEO Meta Title comes here" type="text" size="100"></td>
					<td>[site-name], [category_name]</td>
				</tr>
				<tr>
					<td>Description -</td>
					<td><input name="seobrandsdesclevelthird" required value="<?php echo $seobrandsdesclevelthird; ?>" placeholder="SEO Meta Description comes here" type="text" size="100"></td>
					<td>[site-name], [category_name]</td>
				</tr>
				<tr>
					<td>Keywords -</td>
					<td><input name="seobrandskeylevelthird" required value="<?php echo $seobrandskeylevelthird; ?>" placeholder="SEO Meta Keywords comes here" type="text" size="100"></td>
					<td>[site-name], [category_name]</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>&nbsp;<input class="button" name="saveseobrandslevelthird" value="Save 3rd level brands SEO" type="submit"></td>
					<td>&nbsp;</td>
				</tr>
			</tbody>
		</table>

	</form>

<?php
}
?>

<!-- seo Tab ends -->
















		</div> <!-- wrap -->
<?Php }

	add_action( 'wp_ajax_AddAnotherParentReset', 'AddAnotherParentReset' );
	add_action( 'wp_ajax_ShowChildMenu', 'ShowChildMenu' );
	add_action( 'wp_ajax_CreateCSS', 'CreateCSS' );
	add_action( 'wp_ajax_ReLoadNav', 'ReLoadNav' );
	add_action( 'wp_ajax_GetChannelName', 'GetChannelName' );
	add_action( 'wp_ajax_GetChannelID', 'GetChannelID' );
	add_action( 'wp_ajax_Get_Data_Info', 'Get_Data_Info' );
	add_action( 'wp_ajax_PSaveChannelCategory', 'PSaveChannelCategory' );
	add_action( 'wp_ajax_SSaveChannelCategory', 'SSaveChannelCategory' );
	add_action( 'wp_ajax_TSaveChannelCategory', 'TSaveChannelCategory' );
	add_action( 'wp_ajax_remove_chkbx_secondary_site', 'remove_chkbx_secondary_site' );
	add_action( 'wp_ajax_remove_chkbx_tertiary_site', 'remove_chkbx_tertiary_site' );


	function CreateCSS() {

		$sitenavigation = str_replace('\\', '', $_REQUEST['MainMenuGradient'] );
		$header			= str_replace('\\', '', $_REQUEST['SubMenuGradient'] );
		$ArticleHeaderGradient	= str_replace('\\', '', $_REQUEST['ArticleHeaderGradient'] );
		$BestGolfClubsGradient	= str_replace('\\', '', $_REQUEST['BestGolfClubsGradient'] );
		$MainInactiveMenuColor = ( $_REQUEST['MainInactiveMenuColor'] != NULL ) ? $_REQUEST['MainInactiveMenuColor'] : '#fff';
		$SubActive3DTop = ( $_REQUEST['SubActive3DTop'] != NULL ) ? $_REQUEST['SubActive3DTop'] : 'none!important';
		$SubActive3DBottom = ( $_REQUEST['SubActive3DBottom'] != NULL ) ? $_REQUEST['SubActive3DBottom'] : 'none!important';
		$Brand_Background_Color = $_REQUEST['FeaturedGolfDealsBackgroundColor'];

                /* The following code is duplicated to create css for header_widget.*/
                /* Remember Rule of three: "The code can be copied once, but that when the same code is used three times,
                 *  it should be extracted into a new procedure."
                 */
                $headerWidgetCss = '/* Dynamic Content follows */';
                $headerHtmlId = '#reviewAppOO7';



//-----------------------------------------------------------------------------------------------------------------------//

		//For MainInactiveMenuColor
		if(get_option("MainInactiveMenuColor") == "")
		{
			$maininactivecss = "";
		}
		else
		{
			$maininactivecss = get_option("MainInactiveMenuColor");
		}
		if(get_option("MainInactiveMenuColorSG") == "")
		{
			$maininactivecssSG = "";
		}
		else
		{
			$maininactivecssSG = get_option("MainInactiveMenuColorSG");
		}
		$updateCss .= "\n /* Main Menu Inactive Menu Color CSS */ \n";
		$updateCss1 = ( $_REQUEST['MainInactiveMenuColor'] != NULL ) ? "ul.nav-menu li a { /*background-image:none;*/ color:". $MainInactiveMenuColor ."; }\n .golf-club-list-nav ul li a { display: inline-block;padding: 6px 5px 8px 5px;text-decoration: none;font-size: 17px; color:". $MainInactiveMenuColor ."; }\n" : $maininactivecssSG;
		$updateCss	.= $updateCss1;
		$headerWidgetCss .= "\n /* Main Menu Inactive Menu Color CSS */ \n";

		$headerWidgetCss1 = ( $_REQUEST['MainInactiveMenuColor'] != NULL ) ? "{$headerHtmlId} ul.reviewAppOO7-nav-menu li a { /*background-image:none;*/ color:". $MainInactiveMenuColor ."; }\n {$headerHtmlId} .reviewAppOO7-nav-menu li a { color:". $MainInactiveMenuColor ."; }\n" : $maininactivecss;
		$headerWidgetCss .= $headerWidgetCss1;

		update_option("MainInActiveMenuColor", $headerWidgetCss1);
		update_option("MainInactiveMenuColorSG", $updateCss1);
		update_option("BrandMainInactiveColor",$_REQUEST['MainInactiveMenuColor']);



//-----------------------------------------------------------------------------------------------------------------------//

		//For MainActiveMenuColor

		if(get_option("MainActiveMenuColor") == "")
		{
			$mainactivecss = "{$headerHtmlId} ul li.current-menu-parent a, {$headerHtmlId} ul li.current-menu-item a, {$headerHtmlId} ul.reviewAppOO7-nav-menu a:hover { color:#fff; }\n";
		}
		else
		{
			$mainactivecss = get_option("MainActiveMenuColor");
		}



		if(get_option("MainActiveMenuColorSG") == "")
		{
			$mainactivecssSG = "ul li.current-menu-parent a, ul li.current-menu-item a, ul.nav-menu a:hover { color:#fff; }\n";
		}
		else
		{
			$mainactivecssSG = get_option("MainActiveMenuColorSG");
		}
				$updateCss .= "\n /* Main Menu Active Menu Color CSS */ \n";
		$updateCss2 = ( $_REQUEST['MainActiveMenuColor'] != NULL ) ? "ul li.current-menu-parent a, ul li.current-menu-item a, ul.nav-menu a:hover {  color:". $_REQUEST['MainActiveMenuColor']. "; }\n .golf-club-list-nav ul li a:hover, .golf-club-list-nav ul li.sel a{ background: ". $_REQUEST['MainActiveMenuColor'] .";}\n .golf-club-list-nav ul li a {color: ". $_REQUEST['MainActiveMenuColor'] ."};\n" : $mainactivecssSG;
		$updateCss	.= $updateCss2;

		$headerWidgetCss .= "\n /* Main Menu Active Menu Color CSS */ \n";

       $headerWidgetCss2 = ( $_REQUEST['MainActiveMenuColor'] != NULL ) ? "{$headerHtmlId} ul li.current-menu-parent a, {$headerHtmlId} ul li.current-menu-item a, {$headerHtmlId} ul.reviewAppOO7-nav-menu a:hover {  color:". $_REQUEST['MainActiveMenuColor']. "; }\n .golf-club-list-nav ul li a:hover, .golf-club-list-nav ul li.sel a{ background: ". $_REQUEST['MainActiveMenuColor'] .";}\n .golf-club-list-nav ul li a {color: ". $_REQUEST['MainActiveMenuColor'] ."};\n" : $mainactivecss;

		$headerWidgetCss .= $headerWidgetCss2;

		update_option("MainActiveMenuColor", $headerWidgetCss2);
		update_option("MainActiveMenuColorSG", $updateCss2);
		update_option("BrandMainColor",$_REQUEST['MainActiveMenuColor']);

//-----------------------------------------------------------------------------------------------------------------------//

		//For SubActiveMenuColor

		if(get_option("SubActiveMenuColor") == "")
		{
			$subactivecss = "{$headerHtmlId} ul.sub-menu li a:hover, {$headerHtmlId} ul.sub-menu li.current-menu-item  a, {$headerHtmlId} ul.sub-menu-sample li.active a { color:#fff; }\n";
		}
		else
		{
			$subactivecss = get_option("SubActiveMenuColor");
		}

		if(get_option("SubActiveMenuColorSG") == "")
		{
			$subactivecssSG = "ul.sub-menu li a:hover, ul.sub-menu li.current-menu-item  a, ul.sub-menu-sample li.active a { color:#fff; }\n";
		}
		else
		{
			$subactivecssSG = get_option("SubActiveMenuColorSG");
		}

		$updateCss .= "\n /* Sub Active Menu Color CSS */ \n";

        $updateCss3 = ( $_REQUEST['SubActiveMenuColor'] != NULL ) ? "ul.sub-menu li a:hover, ul.sub-menu li.current-menu-item  a, ul.sub-menu-sample li.active a {color:". $_REQUEST['SubActiveMenuColor']. "; } \n" : $subactivecssSG;
		$updateCss .= $updateCss3;





		$headerWidgetCss .= "\n /* Sub Menu Active Menu Color CSS */ \n";

		$headerWidgetCss3 = ( $_REQUEST['SubActiveMenuColor'] != NULL ) ? "{$headerHtmlId} ul.sub-menu li a:hover, {$headerHtmlId} ul.sub-menu li.current-menu-item  a, {$headerHtmlId} ul.sub-menu-sample li.active a { color:". $_REQUEST['SubActiveMenuColor']. "; }\n" : $subactivecss;

		$headerWidgetCss .= $headerWidgetCss3;

		update_option("SubActiveMenuColor", $headerWidgetCss3);
		update_option("SubActiveMenuColorSG", $updatecss3);
		update_option("BrandSelectColor",$_REQUEST['SubActiveMenuColor']);

//-----------------------------------------------------------------------------------------------------------------------//

		//For SubActive3DTop
	if($_REQUEST['Enable3D'] == "checked")
	{
		if(get_option("SubActive3DTop") == "")
		{
			$subactive3dtopcss = "{$headerHtmlId} ul.sub-menu li a:hover, {$headerHtmlId} ul.sub-menu li.current-menu-item  a, {$headerHtmlId} ul.sub-menu-sample li.active a { border-top:none!important; }\n";
		}
		else
		{
			$subactive3dtopcss = "";
		}

		if(get_option("SubActive3DTopSG") == "")
		{
			$subactive3dtopcssSG = "ul.sub-menu li a:hover, ul.sub-menu li.current-menu-item  a, ul.sub-menu-sample li.active a { border-top:none!important; }\n";
		}
		else
		{
			$subactive3dtopcssSG = "";
		}

		$updateCss .= "\n /* Sub Active 3D Top Color CSS */ \n";

        $updateCss3333 = ( $_REQUEST['SubActive3DTop'] != NULL ) ? "@media only screen and (max-width: 600px) {ul.sub-menu li a:hover, ul.sub-menu li.current-menu-item  a, ul.sub-menu-sample li.active a {border-top:none;border-bottom:none;}}@media only screen and (min-width: 700px) {
		ul.sub-menu li a:hover, ul.sub-menu li.current-menu-item  a, ul.sub-menu-sample li.active a {border-top:". $_REQUEST['SubActive3DTop']. "; }} \n" : $subactivetopcssSG;
		$updateCss .= $updateCss3333;





		$headerWidgetCss .= "\n /* Sub Menu Active 3DTop Color CSS */ \n";

		$headerWidgetCss3333 = ( $_REQUEST['SubActive3DTop'] != NULL ) ? "@media only screen and (max-width: 600px) {{$headerHtmlId} ul.sub-menu li a:hover, {$headerHtmlId} ul.sub-menu li.current-menu-item  a, {$headerHtmlId} ul.sub-menu-sample li.active a {border-top:none;border-bottom:none;}}@media only screen and (min-width: 700px) {{$headerHtmlId} ul.sub-menu li a:hover, {$headerHtmlId} ul.sub-menu li.current-menu-item  a, {$headerHtmlId} ul.sub-menu-sample li.active a { border-top:". $_REQUEST['SubActive3DTop']. "; }}\n" : $subactive3dtopcss;
		$headerWidgetCss .= $headerWidgetCss3333;

		update_option("SubActive3DTop", $headerWidgetCss3333);
		update_option("SubActive3DTopSG", $updatecss3333);
	}
	else
	{
		$updateCss .= "\n /* Sub Active 3D Top Color CSS */ \n";

        $updateCss3333 = "ul.sub-menu li a:hover, ul.sub-menu li.current-menu-item  a, ul.sub-menu-sample li.active a {border-top: none; } \n";
		$updateCss .= $updateCss3333;



		$headerWidgetCss .= "\n /* Sub Menu Active 3DTop Color CSS */ \n";

		$headerWidgetCss3333 = "{$headerHtmlId} ul.sub-menu li a:hover, {$headerHtmlId} ul.sub-menu li.current-menu-item  a, {$headerHtmlId} ul.sub-menu-sample li.active a { border-top:none; }\n";
		$headerWidgetCss .= $headerWidgetCss3333;

		//update_option("SubActive3DTop", $headerWidgetCss3333);
		//update_option("SubActive3DTopSG", $updatecss3333);

	}



//-----------------------------------------------------------------------------------------------------------------------//

		//For SubActive3DBottom
	if($_REQUEST['Enable3D'] == "checked")
	{

		if(get_option("SubActive3DBottom") == "")
		{
			$subactive3dbottomcss = "{$headerHtmlId} ul.sub-menu li a:hover, {$headerHtmlId} ul.sub-menu li.current-menu-item  a, {$headerHtmlId} ul.sub-menu-sample li.active a { border-bottom:none!important; }\n";
		}
		else
		{
			$subactive3dbottomcsscss = "";
		}

		if(get_option("SubActive3DBottomSG") == "")
		{
			$subactive3dtopcssSG = "ul.sub-menu li a:hover, ul.sub-menu li.current-menu-item  a, ul.sub-menu-sample li.active a { border-bottom:none!important; }\n";
		}
		else
		{
			$subactive3dbottomcssSG = "";
		}

		$updateCss .= "\n /* Sub Active 3D Bottom Color CSS */ \n";

        $updateCss33333 = ( $_REQUEST['SubActive3DTop'] != NULL ) ? "@media only screen and (max-width: 600px) {ul.sub-menu li a:hover, ul.sub-menu li.current-menu-item  a, ul.sub-menu-sample li.active a {border-top:none;border-bottom:none;}}@media only screen and (min-width: 700px) {ul.sub-menu li a:hover, ul.sub-menu li.current-menu-item  a, ul.sub-menu-sample li.active a {border-bottom:". $_REQUEST['SubActive3DBottom']. "; }} \n" : $subactivebottomcssSG;
		$updateCss .= $updateCss33333;





		$headerWidgetCss .= "\n /* Sub Menu Active Bottom Color CSS */ \n";

		$headerWidgetCss33333 = ( $_REQUEST['SubActive3DBottom'] != NULL ) ? "@media only screen and (max-width: 600px) {{$headerHtmlId} ul.sub-menu li a:hover, {$headerHtmlId} ul.sub-menu li.current-menu-item  a, {$headerHtmlId} ul.sub-menu-sample li.active a {border-top:none;border-bottom:none;}}@media only screen and (min-width: 700px) {{$headerHtmlId} ul.sub-menu li a:hover, {$headerHtmlId} ul.sub-menu li.current-menu-item  a, {$headerHtmlId} ul.sub-menu-sample li.active a { border-bottom:". $_REQUEST['SubActive3DBottom']. "; }}\n" : $subactive3dbottomcss;

		$headerWidgetCss .= $headerWidgetCss33333;

		update_option("SubActive3DBottom", $headerWidgetCss33333);
		update_option("SubActive3DBottomSG", $updatecss33333);
	}
	else
	{
		$updateCss .= "\n /* Sub Active 3D Bottom Color CSS */ \n";

        $updateCss33333 = "ul.sub-menu li a:hover, ul.sub-menu li.current-menu-item  a, ul.sub-menu-sample li.active a {border-bottom:none; } \n" ;
		$updateCss .= $updateCss33333;





		$headerWidgetCss .= "\n /* Sub Menu Active Bottom Color CSS */ \n";

		$headerWidgetCss33333 = "{$headerHtmlId} ul.sub-menu li a:hover, {$headerHtmlId} ul.sub-menu li.current-menu-item  a, {$headerHtmlId} ul.sub-menu-sample li.active a { border-bottom:none; }\n";

		$headerWidgetCss .= $headerWidgetCss33333;

		//update_option("SubActive3DBottom", $headerWidgetCss33333);
		//update_option("SubActive3DBottomSG", $updatecss33333);
	}

//-----------------------------------------------------------------------------------------------------------------------//

		//For SubInactiveMenuColor

		if(get_option("SubInactiveMenuColor") == "")
		{
			$subinactivecss = "{$headerHtmlId} ul.sub-menu li a, {$headerHtmlId} ul.sub-menu-sample .inactive a { color:#006500; }\n";
		}
		else
		{
			$subinactivecss = get_option("SubInactiveMenuColor");
		}
		if(get_option("SubInactiveMenuColorSG") == "")
		{
			$subinactivecssSG = "ul.sub-menu li a, ul.sub-menu-sample .inactive a { color:#006500; }\n";
		}
		else
		{
			$subinactivecssSG = get_option("SubInactiveMenuColorSG");
		}
		$updateCss .= "\n /* Sub Menu Inactive Menu Color CSS */ \n";
        $updateCss4 = ( $_REQUEST['SubInactiveMenuColor'] != NULL ) ? "ul.sub-menu li a, ul.sub-menu-sample .inactive a { color:".$_REQUEST['SubInactiveMenuColor']."; }.golf-club-list-items a {color:".$_REQUEST['SubInactiveMenuColor'].";}\n.golf-club-list-nav ul li a:hover, .golf-club-list-nav ul li.sel a{color:".$_REQUEST['SubInactiveMenuColor'].";}\n #popularProducts .productRow { border-color: ".$_REQUEST['SubInactiveMenuColor'].";}\n .product_reviewButton a { background-color: ".$_REQUEST['SubInactiveMenuColor'].";}\n" : $subinactivecssSG ;
		$updateCss .= $updateCss4;
		$headerWidgetCss .= "\n /* Sub Menu Inactive Menu Color CSS */ \n";

		$headerWidgetCss4 = ( $_REQUEST['SubInactiveMenuColor'] != NULL ) ? "{$headerHtmlId} ul.sub-menu li a, {$headerHtmlId} ul.sub-menu-sample .inactive a { color:".$_REQUEST['SubInactiveMenuColor']."; }\n .golf-club-list-nav ul li a:hover, .golf-club-list-nav ul li.sel a{color:".$_REQUEST['SubInactiveMenuColor'].";}\n #popularProducts .productRow { border-color: ".$_REQUEST['SubInactiveMenuColor'].";}\n .product_reviewButton a { background-color: ".$_REQUEST['SubInactiveMenuColor'].";}\n" : $subinactivecss ;

		$headerWidgetCss .= $headerWidgetCss4; color:

		update_option("SubInactiveMenuColor", $headerWidgetCss4);
		update_option("SubInactiveMenuColorSG", $updateCss4);
		update_option("BrandSelectInactiveColor",$_REQUEST['SubInactiveMenuColor']);


//-----------------------------------------------------------------------------------------------------------------------//



		// For SubActiveMenuBackground
		if(get_option("SubActiveMenuBackground") == "")
		{
			$subinactivecss = "";
			//$mobile_menu_background_css = "#096f00";
		}
		else
		{
			$subinactivecss = get_option("SubActiveMenuBackground");
			//$mobile_menu_background_css = get_option("mobile_menu_background");
		}
		if(get_option("SubActiveMenuBackgroundSG") == "")
		{
			$subinactivecssSG = "";
		}
		else
		{
			$subinactivecssSG = get_option("SubActiveMenuBackgroundSG");
		}
		$updateCss .= "\n /* Sub Menu Active Menu Background Color CSS */ \n";
        $updateCss5 = ( $_REQUEST['SubActiveMenuBackground'] != NULL ) ? "@media only screen and (min-width: 700px) {ul.sub-menu li a:hover, ul.sub-menu-sample li a:hover, ul.sub-menu li.current-menu-item  a, ul.sub-menu-sample li.active a { background-color: ".$_REQUEST['SubActiveMenuBackground']."; }}\n" : $subinactivecssSG;
		$updateCss .= $updateCss5;
		$headerWidgetCss .= "\n /* Sub Menu Active Menu Background Color CSS */ \n";

		$headerWidgetCss5 = ( $_REQUEST['SubActiveMenuBackground'] != NULL ) ? "{$headerHtmlId} ul.sub-menu li a:hover, {$headerHtmlId} ul.sub-menu li.current-menu-item  a, {$headerHtmlId} ul.sub-menu-sample li.active a { background-color: ".$_REQUEST['SubActiveMenuBackground']." !important; }\n" : $subinactivecss;
		$headerWidgetCss .= $headerWidgetCss5;
		//$mobile_menu_background = ( $_REQUEST['SubActiveMenuBackground'] != NULL ) ? $_REQUEST['SubActiveMenuBackground'] : $mobile_menu_background_css;

		//update_option("mobile_menu_background", $mobile_menu_background);
		update_option("BrandSelectBGColor",$_REQUEST['SubActiveMenuBackground']);
		update_option("SubActiveMenuBackground", $headerWidgetCss5);
		update_option("SubActiveMenuBackgroundSG", $updateCss5);

//-----------------------------------------------------------------------------------------------------------------------//
//-----------------------------------------------------------------------------------------------------------------------//



		// For TopLoginRegister
		if(get_option("TopLoginRegister") == "")
		{
			$TopLoginRegistercss = "";
		}
		else
		{
			$TopLoginRegistercss = get_option("TopLoginRegister");
		}

		$updateCss .= "\n /* TopLoginRegister */ \n";
        $updateCss55555 = ( $_REQUEST['TopLoginRegister'] != NULL ) ? ".TopLoginRegister { color: ".$_REQUEST['TopLoginRegister']." !important; }\n.TopLoginRegister a{ color: ".$_REQUEST['TopLoginRegister']." !important; }\n" : $TopLoginRegistercss;
		$updateCss .= $updateCss55555;

        $TopLoginRegister5 = ( $_REQUEST['TopLoginRegister'] != NULL ) ? $_REQUEST['TopLoginRegister'] : $TopLoginRegistercss;


		update_option("TopLoginRegister", $updateCss55555);

//-----------------------------------------------------------------------------------------------------------------------//


		// For FooterColor
		if(get_option("FooterColor") == "")
		{
			$FooterColorcss = "";
		}
		else
		{
			$FooterColorcss = get_option("FooterColor");
		}

		$updateCss .= "\n /* FooterColor */ \n";
		$headerWidgetCss .= "\n /* FooterColor */ \n";
        $updateCss555555555 = ( $_REQUEST['FooterColor'] != NULL ) ? ".FooterColor { color: ".$_REQUEST['FooterColor']."; }\n" : $FooterColorcss;
		$updateCss .= $updateCss555555555;

		$headerWidgetCss .= $updateCss555555555;

        $FooterColor5 = ( $_REQUEST['FooterColor'] != NULL ) ? $_REQUEST['FooterColor'] : $FooterColorcss;


		update_option("FooterColor", $updateCss555555555);

//-----------------------------------------------------------------------------------------------------------------------//

	// For Main Menu

		if(get_option("MainMenuGradient") == "")
		{
			$maindbcss = "";
			$mobile_menu_background_css = "#096f00";
		}
		else
		{
			$maindbcss = get_option("MainMenuGradient");
			$mobile_menu_background_css = get_option("mobile_menu_background");
		}
		if(get_option("MainMenuGradientSG") == "")
		{
			$maindbcssSG = "";
		}
		else
		{
			$maindbcssSG = get_option("MainMenuGradientSG");
		}

		$headerWidgetCss .= "\n /* Main Menu Gradient CSS */ \n";
		$updateCss .= "\n /* Main Menu Gradient CSS */ \n";
		$updateCss6 = ( $_REQUEST['MainMenuGradient'] != NULL ) ? "#site-navigation{ " . $sitenavigation ." }\n .golf-club-list-nav {" . $sitenavigation ." }\n" : $maindbcssSG;
		$updateCss .= $updateCss6;
		$headerWidgetCss6 = ( $_REQUEST['MainMenuGradient'] != NULL ) ? "{$headerHtmlId}-site-navigation{ " . $sitenavigation ." }\n .golf-club-list-nav {" . $sitenavigation ." }\n" : $maindbcss;
		$headerWidgetCss .= $headerWidgetCss6;
		$mobile_menu_background = ( $_REQUEST['MainMenuGradient'] != NULL ) ? $_REQUEST['MainMenuGradient'] : $mobile_menu_background_css;

		update_option("mobile_menu_background", $mobile_menu_background);

		update_option("MainMenuGradient", $headerWidgetCss6);
		update_option("MainMenuGradientSG", $updateCss6);

//-----------------------------------------------------------------------------------------------------------------------//
	// For Sub Menu

		$headerWidgetCss .= "\n /* Sub Menu Gradient CSS */ \n";
		$updateCss .= "\n /* Sub Menu Gradient CSS */ \n";

		if(get_option("SubMenuGradient") == "")
		{
			$subdbcss = "";
		}
		else
		{
			$subdbcss = get_option("SubMenuGradient");
		}
		if(get_option("SubMenuGradientSG") == "")
		{
			$subdbcssSG = "";
		}
		else
		{
			$subdbcssSG = get_option("SubMenuGradientSG");
		}


		$updateCss7 = ( $_REQUEST['SubMenuGradient'] != NULL ) ? "#header, .header_sub_menu_sample { " . $header ." }\n .golf-club-list, { ".$_REQUEST['SubMenuGradient'].";}\n" : $subdbcssSG;
		$updateCss	.= $updateCss7;
		$headerWidgetCss7 = ( $_REQUEST['SubMenuGradient'] != NULL ) ? "{$headerHtmlId}-header { " . $header ." }\n .golf-club-list { ".$_REQUEST['SubMenuGradient'].";}\n" : $subdbcss;
		$headerWidgetCss .= $headerWidgetCss7;


		update_option("SubMenuGradient", $headerWidgetCss7);
		update_option("SubMenuGradientSG", $updateCss7);


//-----------------------------------------------------------------------------------------------------------------------//




		$headerWidgetCssStart = file_get_contents(__DIR__ . '/header-widget.css.template');
		$headerWidgetCssFile = get_template_directory() . "/header-widget.css";
		update_option("menu_gradient_full","$headerWidgetCssStart \n\n\n $headerWidgetCss");

		file_put_contents($headerWidgetCssFile, "$headerWidgetCssStart \n\n\n $headerWidgetCss");





        //SET background color of Golf Clubs brand, product page, overall rating
		if(get_option("Sub_Brand_Background_Color1") == "")
		{
			$Sub_Brand_Background_Color1 = "";
		}
		else
		{
			$Sub_Brand_Background_Color1 = get_option("Sub_Brand_Background_Color1");
		}
		if(get_option("Sub_Brand_Background_Color2") == "")
		{
			$Sub_Brand_Background_Color2 = "";
		}
		else
		{
			$Sub_Brand_Background_Color2 = get_option("Sub_Brand_Background_Color2");
		}
		if(get_option("Sub_Brand_Background_Color3") == "")
		{
			$Sub_Brand_Background_Color3 = "";
		}
		else
		{
			$Sub_Brand_Background_Color3 = get_option("Sub_Brand_Background_Color3");
		}
		if(get_option("Sub_Brand_Background_Color4") == "")
		{
			$Sub_Brand_Background_Color4 = "";
		}
		else
		{
			$Sub_Brand_Background_Color4 = get_option("Sub_Brand_Background_Color4");
		}
		if(get_option("Sub_Brand_Background_Color5") == "")
		{
			$Sub_Brand_Background_Color5 = "";
		}
		else
		{
			$Sub_Brand_Background_Color5 = get_option("Sub_Brand_Background_Color5");
		}
		$sub1 = ( $_REQUEST['SubMenuGradient'] != NULL ) ? ".product-partner-detail, .course-detail, .course-search, .user-review-rating, .product-overview, .golf-club-list, .Virtual-Class-list { background-image:none; background-color: ". $Brand_Background_Color ."; }\n" : $Brand_Background_Color1;
		$sub2 = ( $_REQUEST['SubMenuGradient'] != NULL ) ? ".featured-item #featured-nav li a { background-color: ". $Brand_Background_Color ."; border: 1px solid ". $Brand_Background_Color ."; }\n" : $Brand_Background_Color2;
		$sub3 = ( $_REQUEST['SubMenuGradient'] != NULL ) ? ".product-partner-detail, .course-detail, .course-search, .user-review-rating, .product-overview, .golf-club-list, .Virtual-Class-list { background-image:none; background-color: ". $Brand_Background_Color ."; }\n" : $Brand_Background_Color3;
		$sub4 = ( $_REQUEST['SubMenuGradient'] != NULL ) ? ".Virtual-Class-list-nav ul li.sel a, .Virtual-Class-list-nav ul li a:hover { background:".$_REQUEST['SubMenuGradient']."; }\n.Virtual-Class-list-nav ul li, .golf-club-list-nav ul  li{ position: relative; }\n" : $Brand_Background_Color4;
		$sub5 = ( $_REQUEST['SubMenuGradient'] != NULL ) ? ".Virtual-Class-list-nav li.sel a:before, .Virtual-Class-list-nav li a:hover:before, .golf-club-list-nav li.sel a:before, .golf-club-list-nav li a:hover:before { position:absolute;width:0px;height:0px;left:50%;top:17px;margin-left:-6px;border-left: 6px solid transparent; border-right: 6px solid transparent; border-bottom: 6px solid ". $Brand_Background_Color ."; }\n" : $Brand_Background_Color5;
		$updateCss .= "\n /* Sub Menu Gradient for all other */ \n";

		$updateCss .= $sub1;
		$updateCss .= $sub2;
		$updateCss .= $sub3;
		$updateCss .= $sub4;
		$updateCss .= $sub5;

		//update brand selector menu css//

		update_option("Sub_Brand_Background_Color1",$sub1);
		update_option("Sub_Brand_Background_Color2",$sub2);
		update_option("Sub_Brand_Background_Color3",$sub3);
		update_option("Sub_Brand_Background_Color4",$sub4);
		update_option("Sub_Brand_Background_Color5",$sub5);





		//SET Barnd list nav
		if(get_option("Main_Brand_Background_Color1") == "")
		{
			$Main_Brand_Background_Color1 = "";
		}
		else
		{
			$Main_Brand_Background_Color1 = get_option("Main_Brand_Background_Color1");
		}
		if(get_option("Main_Brand_Background_Color2") == "")
		{
			$Main_Brand_Background_Color2 = "";
		}
		else
		{
			$Main_Brand_Background_Color2 = get_option("Main_Brand_Background_Color2");
		}
		if(get_option("Main_Brand_Background_Color3") == "")
		{
			$Main_Brand_Background_Color3 = "";
		}
		else
		{
			$Main_Brand_Background_Color3 = get_option("Main_Brand_Background_Color3");
		}
		if(get_option("Main_Brand_Background_Color4") == "")
		{
			$Main_Brand_Background_Color4 = "";
		}
		else
		{
			$Main_Brand_Background_Color4 = get_option("Main_Brand_Background_Color4");
		}
		if(get_option("Main_Brand_Background_Color5") == "")
		{
			$Main_Brand_Background_Color5 = "";
		}
		else
		{
			$Main_Brand_Background_Color5 = get_option("Main_Brand_Background_Color5");
		}
		if(get_option("Main_Brand_Background_Color6") == "")
		{
			$Main_Brand_Background_Color6 = "";
		}
		else
		{
			$Main_Brand_Background_Color6 = get_option("Main_Brand_Background_Color6");
		}
		$main1= ( $_REQUEST['MainMenuGradient'] != NULL ) ? "table thead, .Virtual-Class-list-nav, .golf-club-list-nav,#footer-separator{ background-image:none; " . $sitenavigation . "; /*border-radius:0px 0px 0px 0px;*/ }.text-header{ background-image:none; " . $sitenavigation . ";     border-top-left-radius: 5px; border-top-right-radius: 5px; height: 24px;  padding: 10px 0 0 0px;  width: 350px; }\nh1,h2 { color: ". $_REQUEST['ArticleTitleTextColor'] ."; font-weight:bold; }\n" : $Main_Brand_Background_Color1;
		$main2 = ( $_REQUEST['MainMenuGradient'] != NULL ) ? "#product-rating-detail table td.dr, .course-search .txt input, .course-search .sub, .review-index-header, .product-score-box  { background-image:none; background-color: " . $_REQUEST['ArticleTitleTextColor'] . "; }\n.reviews-nav a, .product-score-inner, .article-index-nav, .course-detail td { color: ". $_REQUEST['ArticleTitleTextColor'] ."; }\n.featured-item #featured-nav li a.cur { background-color: ". $_REQUEST['ArticleTitleTextColor'] ."; border: 1px solid ". $_REQUEST['ArticleTitleTextColor'] ."; }\n" : $Main_Brand_Background_Color2;
		$main3 = ( $_REQUEST['MainMenuGradient'] != NULL ) ? "div.lwr, ul.states a, .top-courses ul li a, .hot-deals-module, .hot-deals-text-main a, .green, .golf-club-list, .product-list .product-list-nav a, .product-list .product-list-nav, .Virtual-Class-list, .VirtualClass table.VirtualClass-list td a, p.subheadertext, ul.popular-golf-clubs li a, .VirtualClass-list-items ul li a, .pn a, .brand a, .rev a { color: ". $_REQUEST['ArticleTitleTextColor'] ."; }\n" : $Main_Brand_Background_Color3;
		$main4 = ( $_REQUEST['MainMenuGradient'] != NULL ) ? "ul.popular-golf-clubs li a,.title a, a.arrow-right { color: " . $_REQUEST['ArticleTitleTextColor'] . "; }\n#golf-courses ul.states li { border-right: 1px solid " . $_REQUEST['ArticleTitleTextColor'] . "; }\n.product-score-outer { border-left: 1px solid " . $_REQUEST['ArticleTitleTextColor'] . "; }\n" : $Main_Brand_Background_Color4;
		$main5 = ( $_REQUEST['MainMenuGradient'] != NULL ) ? "a.BtnWriteReview  span{ visibility: visible; }\na.BtnWriteReview { background-color: ". $_REQUEST['ArticleTitleTextColor'] ."; margin-left:0px; background-image:none; -moz-border-radius:28; border-radius: 28px; font-family: 'Lucida Sans', sans-serif; color: #ffffff; font-size: 12px; font-weight:600; padding: 6px 15px 6px 15px; text-decoration: none; border: 1px solid ". $_REQUEST['ArticleTitleTextColor'] ."; }\n " : $Main_Brand_Background_Color5;
		$main6 = ( $_REQUEST['MainMenuGradient'] != NULL ) ? "a.writereview  span{ visibility: visible; }\ndiv.imgdiv { margin-top:5px; width:140px;}\na.writereview { background-image:none; -webkit-border-radius: 25px; -moz-border-radius: 25px; border-radius: 25px; font-size:13px;	font-family: 'Lucida Sans', sans-serif;	padding: 8px 22px;text-decoration:none;	font-weight:bold; color: #FFF; background-color: ". $_REQUEST['ArticleTitleTextColor'] . "; border: 1px solid ". $_REQUEST['ArticleTitleTextColor'] . "; }\n" : $Main_Brand_Background_Color6;
		$updateCss .= "\n /* Main Menu Gradient for all other */ \n";
		$updateCss	.= $main1;
		$updateCss	.= $main2;
		$updateCss	.= $main3;
		$updateCss	.= $main4;
		$updateCss	.= $main5;
		$updateCss	.= $main6;
		update_option("Main_Brand_Background_Color1",$main1);
		update_option("Main_Brand_Background_Color2",$main2);
		update_option("Main_Brand_Background_Color3",$main3);
		update_option("Main_Brand_Background_Color4",$main4);
		update_option("Main_Brand_Background_Color5",$main5);
		update_option("Main_Brand_Background_Color6",$main6);
		if(isset($_REQUEST['ArticleTitleTextColor']))
		{
			update_option('loginregistercolor',$_REQUEST['ArticleTitleTextColor']);
		}
		else
		{
			update_option('loginregistercolor','#137211');
		}



		$updateCss .= "\n /* ArticleHeaderGradient */ \n";
		$updateCss	.= ( $_REQUEST['ArticleHeaderGradient'] != NULL ) ? ".text-header{  width:595px; " . $ArticleHeaderGradient ." border-radius:5px 5px 0px 0px; }\n" : '';
		$updateCss	.= ( $_REQUEST['ArticleHeaderTextColor'] != NULL ) ? ".text-header h2, .text-header h3{ color: " . $_REQUEST['ArticleHeaderTextColor'] ."; }\n" : '';
		$updateCss	.= ( $_REQUEST['ArticleTitleTextColor'] != NULL ) ? ".title a, .title a:visited { color: " . $_REQUEST['ArticleTitleTextColor'] ."; }\n" : '';

		$updateCss .= "\n /* SaveBottomDividerColor */ \n";
		$updateCss	.= ( $_REQUEST['SaveBottomDividerColor'] != NULL ) ? "#footer{ background:none; }\n#footer-separator { ".$_REQUEST['SaveBottomDividerColor'] ." }\n" : '';

		$updateCss .= "\n /* FeaturedGolfDealsBackgroundColor */ \n";
		$updateCss	.= ( $_REQUEST['FeaturedGolfDealsBackgroundColor'] != NULL ) ? "#product-rating-detail table td, #featured-golf-deals table td, .golf-deals table td, .featured-golf-deals-sample { background-color: " . $_REQUEST['FeaturedGolfDealsBackgroundColor'] .";  }\n" : '';


		$updateCss .= "\n /* CircleArrowImage */ \n";

		$updateCss	.= ( $_REQUEST['CircleArrowImage'] != NULL ) ? "a.arrow-right{ background:none; color: ".$_REQUEST['CircleArrowImage']."; display: inline-block; position: relative; padding-right: -10px; }\n a.arrow-right:before{ content:' ';position: absolute;top: 0px;right: 0px; opacity: 1;width: 15px;height: 15px;border-radius: 50%;background: " .$_REQUEST['CircleArrowImage']." ;transition: opacity 0.2s, top 0.2s, right 0.2s; }\na.arrow-right:after{ content:' '; position: absolute; top: 0px; right: 5px;opacity: 1; width: 3px; height: 3px;margin-top: 5px;background: transparent;	border: 3px solid #FFF;	border-top: none; border-right: none; transition: opacity 0.2s, top 0.2s, right 0.2s;	transform: rotate(225deg); }\n" : '';

		$updateCss	.= ( $_REQUEST['CircleArrowImage'] != NULL ) ? ".best-clubs ul li.top div{ background:none; display: inline-block; position: relative; }\n" : '';
		$updateCss	.= ( $_REQUEST['CircleArrowImage'] != NULL ) ? ".best-clubs ul li.top div:before{ content:' ';position: absolute;top: 6px;right: 171px; opacity: 1;width: 15px;height: 15px;border-radius: 50%;background: ".$_REQUEST['CircleArrowImage']."; transition: opacity 0.2s, top 0.2s, left 0.2s; }\n" : '';
		$updateCss	.= ( $_REQUEST['CircleArrowImage'] != NULL ) ? ".best-clubs ul li.top div:after{ content:' ';position: absolute; top:5px; right: 176px;opacity: 1;width: 3px;height: 3px;margin-top: 5px;background: transparent;	border: 3px solid #FFF;	border-top: none; border-right: none; transition: opacity 0.2s, top 0.2s, left 0.2s; transform: rotate(225deg); }\n" : '';
		$updateCss	.= ( $_REQUEST['BestGolfClubsGradient'] != NULL ) ? ".best-clubs ul li.top, .best-golf-clubs-sample { ". $BestGolfClubsGradient . "  }\n" : '';



		$updateCss .= "\n /* WhiteTriangle */ \n";

		$updateCss	.= ( $_REQUEST['WhiteTriangle'] != NULL ) ? "li.current-menu-parent a:before, li.current-menu-item a:before, ul.nav-menu a:hover:before{ position:relative; width:0px; height:0px; left:50%; top:-2px; margin-left:-11px; border-left:6px solid transparent;border-right:6px solid transparent; border-top: 6px solid ".$_REQUEST['WhiteTriangle']."; }\nul.sub-menu li a:before{ content:none; }\nul.sub-menu li a:hover:before{ content:none; }\n" : '';





			$updateCss .= "\n /* Others Static */ \n";
		$updateCss	.= "ul.image-thumb-list li.left-arrow, ul.image-thumb-list li.right-arrow { position: relative; }\n";
		$updateCss	.= "ul.image-thumb-list li.left-arrow a, ul.image-thumb-list li.right-arrow a {  background-image:none;  }\n";
		$updateCss	.= "ul.image-thumb-list li.right-arrow a:after {content:' ';position: absolute;top: 0px;right: -1px;opacity: 1;width: 12px;height: 12px;margin-top: 20px;background: transparent; border: 4px solid #939693; border-top: none; border-right: none; transition: opacity 0.2s, top 0.2s, right 0.2s; transform: rotate(225deg); }\n";
		$updateCss	.= "ul.image-thumb-list li.left-arrow a:after {content:' ';position: absolute;top: 0px;right: -1px;opacity: 1;width: 12px;height: 12px;margin-top: 20px;background: transparent; border: 4px solid #939693; border-bottom: none; border-left: none; transition: opacity 0.2s, top 0.2s, right 0.2s; transform: rotate(225deg); }\n";
		$updateCss	.= ".search_box { background-image:none; margin-top:2px; }\n";
		$updateCss	.= ".search_box input { -moz-border-radius: 10px; -webkit-border-radius: 10px; border-radius: 10px; -khtml-border-radius: 10px; height:20px; width:142px; padding:0px 8px 0px 27px; border: 2px solid #d8d9d8; background-color:#FFF;}\n";
		$updateCss	.= "input.search-page-box { -moz-border-radius: 10px; -webkit-border-radius: 10px; border-radius: 10px; -khtml-border-radius: 10px; height:20px; width:340px; padding:0px 8px 0px 27px; border: 2px solid #d8d9d8; background-color:#FFF; background-image:none; }\n";

		$template_url = bloginfo('template_url');
		$file = get_template_directory() . "/style-gradient.css";
		update_option("style_gradient_full",$defaultCSS . $updateCss);
		file_put_contents( $file, $defaultCSS . $updateCss );

		//Cache Version

		update_option("cache_version","?version=1.0.".rand(10,99));

		// Upload files to S3
		uploadFileToS3('style-gradient.css', __DIR__ . '/../../themes/site/style-gradient.css', 'text/css');
		uploadFileToS3('header-widget.css', __DIR__ . '/../../themes/site/header-widget.css', 'text/css');
		uploadFileToS3('topnav-bg.png', __DIR__ . '/../../themes/site/images/topnav-bg.png', 'image/png');
		uploadFileToS3('search-bg.png', __DIR__ . '/../../themes/site/images/search-bg.png', 'image/png');
		uploadFileToS3('topnav-cur.png', __DIR__ . '/../../themes/site/images/topnav-cur.png', 'image/png');
		uploadFileToS3('top-bg.png', __DIR__ . '/../../themes/site/images/top-bg.png', 'image/png');
		uploadFileToS3('top-bg-line.png', __DIR__ . '/../../themes/site/images/top-bg-line.png', 'image/png');

	}

	function ShowChildMenu() {

		echo wp_get_nav_menu_child_items_list( $_REQUEST['pid'], $_REQUEST['a'], '' );
		die();

	}

	if( is_admin() ) {

		add_action('admin_menu', 'golfreview_sparkle_site' );
		add_action( 'admin_init', 'modal_window_scripts');
		register_activation_hook( __FILE__, 'sparkle_plugin_activate' );
	}



function uploadFileToS3($fileKeyName, $srcFile, $fileContentType) {

	include(__DIR__ .'/../../../wp-config-extra.php');

	$s3config = array(
		'region' => $AWS_REGION,
		'version' => '2006-03-01'
	);

	// Check for explicit IAM credentials
	if (!empty($AWS_KEY)) {
		$s3config = array(
		'region' => $AWS_REGION,
		'version' => '2006-03-01',
				'credentials' => array('key'=>$AWS_KEY,'secret'=>$AWS_SECRET)
			);
		}

	$s3 = S3Client::factory($s3config);

	// Construct S3 key
	$key = $S3_FOLDER ."/". $fileKeyName;
	$key_image = $S3_FOLDER ."/images/". $fileKeyName;

	try {
		if($fileContentType == 'image/png')
		{
			$result = $s3->putObject(array(
				'Bucket' => $S3_BUCKET,
				'Key' => $key_image,
				'SourceFile' => $srcFile,
				'ContentType' => $fileContentType
			));
		}
		else
		{
			$result = $s3->putObject(array(
				'Bucket' => $S3_BUCKET,
				'Key' => $key,
				'SourceFile' => $srcFile,
				'ContentType' => $fileContentType
			));
		}
	} catch (S3Exception $e) {
		error_log($e->getMessage());
	}

}
