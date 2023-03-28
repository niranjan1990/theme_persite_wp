<?Php
	
	ini_set('max_execution_time', 300);
	
	/*
		Class to connect with mysql Venice database.
		Getting the result to have categories based on level 2.
		Getting the result to have sub categories based on level 3.
	*/
	
	class ClassPages {
	
		var $query;
		var $result;
		var $db;		
		
		public $param;	
		
		/* construction of mysql venice database  */
		
		function __construct( $param ) {
			
			$db = explode("|", $param );
			$this->db = new wpdb( $db[0], $db[1], $db[2], $db[3] );
		}		
		
		function GetPage( $ID ) {
			
			$this->query = "SELECT c.categoryid, category_name, cc.node_level, cc.nodeid, cc.category_path, cc.channelid FROM categories c INNER JOIN channel_categories cc ON cc.categoryid = c.categoryid WHERE  c.categoryid  = '$ID'";
			return $this->db->get_row( $this->query, object );
		}
		
		function Check_if_sub_page_has_products_outdoor( $cat, $PSiteName ) 
		{		 
			//$this->query = "Select count(p.productid) as products from categories c left join products p on c.categoryid = p.categoryid left join manufacturers m on p.manufacturerid = m.manufacturerid WHERE c.channelid = ". $PSiteName ." AND c.category_name = '". $cat ."' AND p.visible = 1";
			
			
			$this->query = "select count(p.productid) as products from categories c left join products p on c.categoryid = p.categoryid left join manufacturers m on p.manufacturerid = m.manufacturerid left join channel_categories cc on cc.categoryid = p.categoryid WHERE c.channelid = ". $PSiteName ." AND locate('/".$cat."',cc.category_path) > 0 AND p.visible = 1";

			
			$products = $this->db->get_row( $this->query, object );			
			$r = ( $products->products > 0 ) ? $products->products : 0;
			return $r;
		}
		
		
		function Check_if_sub_page_has_products( $cat, $PSiteName ) {		 
			
			//$cat = str_replace(" &","", strtolower($cat) );
			//$cat = str_replace(" ","-", strtolower($cat) );	
			
			$this->query = "Select count(p.productid) as products from categories c left join products p on c.categoryid = p.categoryid left join manufacturers m on p.manufacturerid = m.manufacturerid WHERE c.channelid = ". $PSiteName ." AND c.category_name = '". $cat ."' AND p.visible = 1";
			//$this->query = "Select count(p.productid) as products from categories c left join products p on c.categoryid = p.categoryid left join manufacturers m on p.manufacturerid = m.manufacturerid WHERE c.channelid = ". $PSiteName ." AND c.url_safe_category_name = '". $cat ."' AND p.visible = 1";
			
			$products = $this->db->get_row( $this->query, object );			
			//echo $cat . ' ' . $products->products . ', ';
			$r = ( $products->products > 0 ) ? $products->products : 0;
			return $r;
		}
		
		/* Getting the result to have categories based on level 3. */
		function Get_Sub_Pages( $PChannelID, $PSNODE_LVL, $SChannelID, $SSNODE_LVL, $TChannelID, $TSNODE_LVL ) {
			
			if( $PChannelID != '' && $SChannelID == '' && $TChannelID == '' ) {
			
				$this->query = "SELECT c.categoryid, url_safe_category_name,category_name, cc.node_level, cc.nodeid, cc.category_path, 
				cc.channelid FROM categories c INNER JOIN channel_categories cc ON cc.categoryid = c.categoryid 
				WHERE cc.channelid = ".$PChannelID." AND cc.node_level = ".$PSNODE_LVL." ORDER BY nodeid";
			}
			else if( $PChannelID != '' && $SChannelID != '' && $TChannelID == '' ) {
			
				$this->query = "SELECT c.categoryid, url_safe_category_name,category_name, cc.node_level, cc.nodeid, cc.category_path, 
				cc.channelid FROM categories c INNER JOIN channel_categories cc ON cc.categoryid = c.categoryid 
				WHERE cc.channelid = ".$PChannelID." AND cc.node_level = ".$PSNODE_LVL." 
				OR cc.channelid = ".$SChannelID." AND cc.node_level = ".$SSNODE_LVL." ORDER BY nodeid";
			}
			else if( $PChannelID != '' && $SChannelID != '' && $TChannelID != '' ) {
			
				$this->query = "SELECT c.categoryid, url_safe_category_name,category_name, cc.node_level, cc.nodeid, cc.category_path, 
				cc.channelid FROM categories c INNER JOIN channel_categories cc ON cc.categoryid = c.categoryid 
				WHERE cc.channelid = ".$PChannelID." AND cc.node_level = ".$PSNODE_LVL." 
				OR cc.channelid = ".$SChannelID." AND cc.node_level = ".$SSNODE_LVL." OR cc.channelid = ".$TChannelID." AND cc.node_level = ".$TSNODE_LVL." ORDER BY nodeid";
			}
			
			return $this->db->get_results( $this->query, object );
		}
		
		/* Getting the result to have categories based on level 3. */
		function Get_SubPages( $CID, $NODEID ) {
			
			$this->query = "SELECT c.categoryid, url_safe_category_name,category_name, cc.node_level, cc.nodeid, cc.category_path, cc.channelid FROM categories c INNER JOIN channel_categories cc ON cc.categoryid = c.categoryid WHERE cc.channelid = ".$CID." AND nodeid like '".$NODEID."%' AND nodeid != '".$NODEID."' ORDER BY nodeid";
			return $this->db->get_results( $this->query, object );
		}
		
		function GetAllMain( $PChannelID, $PMNODE_LVL, $SChannelID, $SMNODE_LVL, $TChannelID, $TMNODE_LVL ) {
			
			if( $PChannelID != '' && $SChannelID == '' && $TChannelID == '' ) {
			
				$this->query = "SELECT c.categoryid, category_name, cc.node_level, cc.nodeid, cc.category_path, cc.channelid 
					FROM categories c INNER JOIN channel_categories cc ON cc.categoryid = c.categoryid 
					WHERE cc.channelid = ".$PChannelID." AND cc.node_level = ".$PMNODE_LVL." ORDER BY nodeid";
			}
			else if( $PChannelID != '' && $SChannelID != '' && $TChannelID == '' ) {
					
				$this->query = "SELECT c.categoryid, category_name, cc.node_level, cc.nodeid, cc.category_path, cc.channelid 
					FROM categories c INNER JOIN channel_categories cc ON cc.categoryid = c.categoryid 
					WHERE cc.channelid = ".$PChannelID." AND cc.node_level = ".$PMNODE_LVL." OR cc.channelid = ".$SChannelID." AND cc.node_level = ".$SMNODE_LVL." ORDER BY nodeid";
			}
			else if( $PChannelID != '' && $SChannelID != '' && $TChannelID != '' ) {
					
				$this->query = "SELECT c.categoryid, category_name, cc.node_level, cc.nodeid, cc.category_path, cc.channelid 
					FROM categories c INNER JOIN channel_categories cc ON cc.categoryid = c.categoryid 
					WHERE cc.channelid = ".$PChannelID." AND cc.node_level = ".$PMNODE_LVL." OR cc.channelid = ".$SChannelID." AND cc.node_level = ".$SMNODE_LVL." OR cc.channelid = ".$TChannelID." AND cc.node_level = ".$TMNODE_LVL." ORDER BY nodeid";
			}
			
			return $this->db->get_results( $this->query, object );
		}		
		
		function GetAllPages( $PChannelID, $SChannelID, $PMNODE_LVL, $PSNODE_LVL, $SMNODE_LVL, $SSNODE_LVL ) {		
						
			if( $PChannelID != '' && $SChannelID == '' ) {
			
				$this->query = "SELECT c.categoryid, category_name, cc.node_level, cc.nodeid, cc.category_path, cc.channelid 
					FROM categories c INNER JOIN channel_categories cc ON cc.categoryid = c.categoryid 
					WHERE cc.channelid = ".$PChannelID." AND cc.node_level = ".$PMNODE_LVL." OR cc.channelid = ".$PChannelID." AND cc.node_level = ".$PSNODE_LVL." ORDER BY nodeid";
			}
			else if( $PChannelID != '' && $SChannelID != '' ) {
					
				$this->query = "SELECT c.categoryid, category_name, cc.node_level, cc.nodeid, cc.category_path, cc.channelid 
					FROM categories c INNER JOIN channel_categories cc ON cc.categoryid = c.categoryid 
					WHERE cc.channelid = ".$PChannelID." AND cc.node_level = ".$PMNODE_LVL." OR cc.channelid = ".$PChannelID." AND cc.node_level = ".$PSNODE_LVL." 
					OR cc.channelid = ".$SChannelID." AND cc.node_level = ".$SMNODE_LVL." OR cc.channelid = ".$SChannelID." AND cc.node_level = ".$SSNODE_LVL." 
					ORDER BY nodeid";
			}
			
			return $this->db->get_results( $this->query, object );
		}
		
		function GetAllMainCategory( $PChannelID, $PMNODE_LVL, $SChannelID, $SMNODE_LVL, $TChannelID, $TMNODE_LVL ) {
			
			if( $PChannelID != '' && $SChannelID == '' && $TChannelID == '' ) 
			{
			
				$this->query = "SELECT c.categoryid,url_safe_category_name,category_name, cc.node_level, cc.nodeid, cc.category_path,
				cc.channelid FROM categories c INNER JOIN channel_categories cc ON cc.categoryid = c.categoryid 
				WHERE cc.channelid = ".$PChannelID." AND cc.node_level = ".$PMNODE_LVL." ORDER BY nodeid";
			}
			else if( $PChannelID != '' && $SChannelID !== '' && $TChannelID == '' ) 
			{
			
				$this->query = "SELECT c.categoryid, url_safe_category_name,category_name, cc.node_level, cc.nodeid, cc.category_path,
				cc.channelid FROM categories c INNER JOIN channel_categories cc ON cc.categoryid = c.categoryid 
				WHERE cc.channelid = ".$PChannelID." AND cc.node_level = ".$PMNODE_LVL." 
				OR cc.channelid = ".$SChannelID." AND cc.node_level = ".$SMNODE_LVL." ORDER BY nodeid";
			}
			else if( $PChannelID != '' && $SChannelID != '' && $TChannelID != '' ) {
					
				$this->query = "SELECT c.categoryid, url_safe_category_name,category_name, cc.node_level, cc.nodeid, cc.category_path, 
				cc.channelid FROM categories c INNER JOIN channel_categories cc ON cc.categoryid = c.categoryid 
				WHERE cc.channelid = ".$PChannelID." AND cc.node_level = ".$PMNODE_LVL." 
				OR cc.channelid = ".$SChannelID." AND cc.node_level = ".$SMNODE_LVL." OR cc.channelid = ".$TChannelID." AND cc.node_level = ".$TMNODE_LVL." ORDER BY nodeid";
			}
			
			return $this->db->get_results( $this->query, object );
		}
	}
?>
