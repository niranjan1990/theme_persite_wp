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
	
	include_once( ABSPATH.'wp-content/plugins/venice_class.php');
	
	/* creating a class object $pages */
	$ClsPage	= new ClassPages( get_option("dbconnection") );
	
	$PSiteName = get_option( 'PSiteName' );
	$SSiteName = get_option( 'SSiteName' );
	
	if( get_option( 'PSiteName' ) && !get_option( 'SSiteName' ) ) {
		
		$PSite = explode(":", $PSiteName);
		$getallpages = $ClsPage->GetAllPages( $_GET['PChannelID'], $_GET['SChannelID'], $_GET['PMNODE_LVL'], $_GET['PSNODE_LVL'], $_GET['SMNODE_LVL'], $_GET['SSNODE_LVL'] );
	}	
	if( get_option( 'PSiteName' ) && get_option( 'SSiteName' ) ) {
		
		$PSite = explode(":", $PSiteName);
		$SSite = explode(":", $SSiteName);
		$getallpages = $ClsPage->GetAllPages( $_GET['PChannelID'], $_GET['SChannelID'], $_GET['PMNODE_LVL'], $_GET['PSNODE_LVL'], $_GET['SMNODE_LVL'], $_GET['SSNODE_LVL'] );
	}
	
	function Get_Rows( $Category_Name, $CID, $slug ) {
	
		return $tr = "<tr><td width='20%'><input type='hidden' value='". $Category_Name ."' name='page[]' /> ". $Category_Name ."</td><td width='25%'><input type='hidden' value='". $Category_Name ." Top Products' name='QueueSetTitle[]' /> ". $Category_Name ." Top Products</td><td width='25%'><input type='hidden' value='". $slug ."-top-products' name='QueueTag[]' />". $slug ."-top-products</td><td width='20%'><input type='text' name='QueueTitlePage[]' value='Top Products' /></td><td width='10%'><select class='pqueue' name='h[]'><option value='h1'>H1</option><option selected='selected' value='h2'>H2</option><option value='h3'>H3</option></select></td></tr>";
	}
		
	
	function get_top_products_promo_queues_list( $pages ) {
			
		if( count($pages) > 0) {
			
			$tr .="<tr><td colspan='5'>&nbsp;</td></tr>";
			$tr .="<tr><td><b>Page</b></td><td><b>Queue Set Title</b></td><td><b>Queue Tag</b></td><td><b>Queue Title on Page</b></td><td><b>H?</b></td></tr>";
			$tr .="<tr><td colspan='5'>&nbsp;</td></tr>";
			$tr .="<tr><td width='20%'><input type='hidden' value='Home Page' name='page[]' /> Home Page</td><td width='25%'><input type='hidden' value='Homepage Featured' name='QueueSetTitle[]' /> Homepage Featured</td><td width='25%'><input type='hidden' value='homepage-featured' name='QueueTag[]' />homepage-featured</td><td width='20%'><input type='text' name='QueueTitlePage[]' value='Top Golf Clubs & Courses' /></td><td width='10%'><select class='pqueue' name='h[]'><option selected='selected' value='h1'>H1</option><option value='h2'>H2</option><option value='h3'>H3</option></select></td></tr>";
			$tr .="<tr><td width='20%'><input type='hidden' value='Home Page' name='page[]' /> Home Page</td><td width='25%'><input type='hidden' value='Homepage Top Products 1' name='QueueSetTitle[]' /> Homepage Top Products 1</td><td width='25%'><input type='hidden' value='homepage-top-products1' name='QueueTag[]' />homepage-top-products1</td><td width='20%'><input type='text' name='QueueTitlePage[]' value='Best Golf Clubs' /></td><td width='10%'><select class='pqueue' name='h[]'><option value='h1'>H1</option><option selected='selected' value='h2'>H2</option><option value='h3'>H3</option></select></td></tr>";
			$tr .="<tr><td width='20%'><input type='hidden' value='Home Page' name='page[]' /> Home Page</td><td width='25%'><input type='hidden' value='Homepage Top Products 2' name='QueueSetTitle[]' /> Homepage Top Products 2</td><td width='25%'><input type='hidden' value='homepage-top-products2' name='QueueTag[]' />homepage-top-products2</td><td width='20%'><input type='text' name='QueueTitlePage[]' value='Best Golf Courses' /></td><td width='10%'><select class='pqueue' name='h[]'><option value='h1'>H1</option><option selected='selected' value='h2'>H2</option><option value='h3'>H3</option></select></td></tr>";
			$tr .="<tr><td colspan='5'>&nbsp;</td></tr>";			
			$tr .="<tr><td align='left' colspan='5'><b>Top Products - </b></td></tr>";
					
			foreach( $pages as $k => $page ) {
			
				$slug = preg_replace('/ /', '-', strtolower($page->category_name) );
				$tr .= Get_Rows( $page->category_name, $CID, $slug );
			}
		}
		else {
		
			$tr .="<tr><td align='center' colspan='5'><b>No Top Products Found. </b></td></tr>";
		}
		return $tr;
	}
	
	function get_featured_article_promo_queues_list( $pages ) {
		
		if( count($pages) > 0) {
		
			$tr .= "<tr><td colspan='5'>&nbsp;</td></tr>";
			$tr .= "<tr><td align='left' colspan='5'><b>Featured Article - </b></td></tr>";
			
			foreach( $pages as $k => $page ) {
			
				$slug = preg_replace('/ /', '-', strtolower($page->category_name) );
				$tr .= Get_Rows( $page->category_name, $CID, $slug );
			}
			
			$tr .= "<tr><td width='20%'><input type='hidden' value='Public Golf Courses' name='page[]' /> Public Golf Courses</td><td width='25%'><input type='hidden' value='Public Golf Courses Featured' name='QueueSetTitle[]' /> Public Golf Courses Featured</td><td width='25%'><input type='hidden' value='public-golf-courses-featured' name='QueueTag[]' />public-golf-courses-featured</td><td width='20%'><input type='text' name='QueueTitlePage[]' value='Featured Article' /></td><td width='10%'><select class='pqueue' name='h[]'><option value='h1'>H1</option><option selected='selected' value='h2'>H2</option><option value='h3'>H3</option></select></td></tr>";
			$tr .= "<tr><td width='20%'><input type='hidden' value='Best Golf Courses' name='page[]' /> Best Golf Courses</td><td width='25%'><input type='hidden' value='Best Golf Courses Featured' name='QueueSetTitle[]' /> Best Golf Courses Featured</td><td width='25%'><input type='hidden' value='best-golf-courses-featured' name='QueueTag[]' />best-golf-courses-featured</td><td width='20%'><input type='text' name='QueueTitlePage[]' value='Featured Article' /></td><td width='10%'><select class='pqueue' name='h[]'><option value='h1'>H1</option><option selected='selected' value='h2'>H2</option><option value='h3'>H3</option></select></td></tr>";
			$tr .= "<tr><td width='20%'><input type='hidden' value='Driving Ranges' name='page[]' /> Driving Ranges</td><td width='25%'><input type='hidden' value='Driving Ranges Featured' name='QueueSetTitle[]' /> Driving Ranges Featured</td><td width='25%'><input type='hidden' value='driving-ranges-featured' name='QueueTag[]' />driving-ranges-featured</td><td width='20%'><input type='text' name='QueueTitlePage[]' value='Featured Article' /></td><td width='10%'><select class='pqueue' name='h[]'><option value='h1'>H1</option><option selected='selected' value='h2'>H2</option><option value='h3'>H3</option></select></td></tr>";
			$tr .= "<tr><td width='20%'><input type='hidden' value='Callaway' name='page[]' /> Callaway</td><td width='25%'><input type='hidden' value='Callaway Featured' name='QueueSetTitle[]' /> Callaway Featured</td><td width='25%'><input type='hidden' value='callaway-featured' name='QueueTag[]' />callaway-featured</td><td width='20%'><input type='text' name='QueueTitlePage[]' value='Featured Article' /></td><td width='10%'><select class='pqueue' name='h[]'><option value='h1'>H1</option><option selected='selected' value='h2'>H2</option><option value='h3'>H3</option></select></td></tr>";
			$tr .= "<tr><td width='20%'><input type='hidden' value='Callaway1' name='page[]' /> Callaway1</td><td width='25%'><input type='hidden' value='Callaway1 Featured' name='QueueSetTitle[]' /> Callaway1 Featured</td><td width='25%'><input type='hidden' value='callaway1-featured' name='QueueTag[]' />callaway1-featured</td><td width='20%'><input type='text' name='QueueTitlePage[]' value='Featured Article' /></td><td width='10%'><select class='pqueue' name='h[]'><option value='h1'>H1</option><option selected='selected' value='h2'>H2</option><option value='h3'>H3</option></select></td></tr>";
			$tr .= "<tr><td colspan='5'>&nbsp;</td></tr>";
			$tr .= "<tr><td colspan='5' align='center'><input type='submit' name='CreatePromoQueues' class='button' value='Create Promo Queues' /></td></tr>";
			$tr .= "<tr><td colspan='5'>&nbsp;</td></tr>";
		}
		else {
		
			$tr .="<tr><td align='center' colspan='5'><b>No Featured Article Found. </b></td></tr>";
		}
		return $tr;
	}
	
	if( isset($_POST['CreatePromoQueues']) ) {
		
		global $wpdb;
		if( count($_POST['QueueTitlePage']) > 0 ) {
			
			$d = "delete from site_review_queue_set";
			$q = "Insert Into site_review_queue_set ( queue_set_id, queue_set_name, queue_set_location, queue_set_duration, queue_set_expire,queue_set_limit, queue_set_low_threshold, queue_set_email, queue_set_email_duration, queue_set_email_timer, queue_set_queue_title, queue_set_article_title, queue_set_title_tag ) Values ";
			for( $i = 0; $i < count($_POST['QueueTitlePage']); $i++ ) {
				
				$q .= " ( " . ($i+1) . ", '" . $_POST['QueueSetTitle'][$i] . "', '" . $_POST['QueueTag'][$i] . "', 24, '".time()."',3,50,'',24,0, '". $_POST['QueueTitlePage'][$i] ."',0, '".$_POST['h'][$i] ."'  ) ";
				
				if( $i < count($_POST['QueueTitlePage']) -1 ) {
				
					$q .= ", ";
				}
			}
			
			$wpdb->query($d);
			$wpdb->query($q);
			echo '<div id="message" class="updated fade"><p>Promo Queue has been Set.</p></div>';
		}
	}
?>