<?php

	ini_set('max_execution_time', 300);

	/**Class for mysql query result**/

	class SiteReviewClass {

		var $query;
		var $result;
		var $db;
		var $currentpage;
		var $meta_vars = array();

		//db params
		//var $user = "root";
		//var $pass	= "";
		//var $mydb	= "golf_venice_new_feb";
		//var $host	= "localhost";

		function __construct() {

			$db = explode("|", get_option('dbconnection') );
			//$this->db = new wpdb( $this->user, $this->pass, $this->mydb, $this->host );
			$this->db = new wpdb( $db[0], $db[1], $db[2], $db[3] );
		}

		/*** Get product detail page query ****/
		function get_product_detail( $item, $PSite, $SSite, $TSite )
		{
			$newparts = explode('/',$_SERVER[REQUEST_URI]);

			$cat_sql = "";

			//print_r($item);
			//echo $item['is_course'];
			if($item['is_course'] == false)
			{
				$cat_sql = "AND c.url_safe_category_name = '{$item['product_category']}' AND m.url_safe_manufacturer_name = '{$item['category']}'";
			}

			if($SSite == "" && $TSite == "")
			{
				$CIds = $PSite;
			}
			else if($TSite == "")
			{
				$CIds = $PSite.','.$SSite;
			}
			else
			{
				$CIds = $PSite.','.$SSite.','.$TSite;
			}


			$this->query = "SELECT a.hasgallery,a.productid, a.product_name, a.categoryid, a.product_description, a.total_reviews, a.average_rating, a.new_product, a.product_image, a.buy_button_type, a.msrp, a.choice_award, a.hasproreview, a.quickrating_count, a.quickrating_average, a.combined_average, a.articleid, a.url_safe_product_name, c.category_name, c.url_safe_category_name, m.manufacturer_name, m.url_safe_manufacturer_name FROM products a LEFT JOIN categories c ON a.categoryid = c.categoryid LEFT JOIN manufacturers m ON a.manufacturerid = m.manufacturerid WHERE a.url_safe_product_name = '$item[product]' $cat_sql AND a.channelid IN ( $CIds ) AND a.visible = 1";

			return $this->db->get_row( $this->query, object );
		}

		function golf_get_product_detail( $item, $PSite, $SSite, $TSite )
		{

			$newparts = explode('/',$_SERVER[REQUEST_URI]);

			$cat_sql = "";


			//echo $item['is_course'];
			if($item['is_course'] == false)
			{
				$cat_sql = "AND c.url_safe_category_name = '{$item['product_category']}' AND m.url_safe_manufacturer_name = '{$item['category']}'";
			}
		else if($item['is_course'] == true){

			$cat_sql = "AND c.url_safe_category_name = '{$item['category']}' ";
			}


			if($SSite == "" && $TSite == "")
			{
				$CIds = $PSite;
			}
			else if($TSite == "")
			{
				$CIds = $PSite.','.$SSite;
			}
			else
			{
				$CIds = $PSite.','.$SSite.','.$TSite;
			}


			$this->query = "SELECT a.productid, a.product_name, a.product_description, a.total_reviews,
				a.average_rating, a.new_product, a.product_image, a.buy_button_type,
				a.msrp, a.choice_award, a.hasproreview, a.quickrating_count, a.quickrating_average,
				a.combined_average, a.articleid, a.url_safe_product_name, c.category_name,
				c.url_safe_category_name, m.manufacturer_name, m.url_safe_manufacturer_name
				FROM products a LEFT JOIN categories c ON a.categoryid = c.categoryid
				LEFT JOIN manufacturers m ON a.manufacturerid = m.manufacturerid
				WHERE a.url_safe_product_name = '$item[product]' $cat_sql
				AND a.channelid IN ( $CIds ) AND a.visible = 1";


			return $this->db->get_row( $this->query, object );
		}





		function get_product_list_outdoor($cat,$catList=array(), $psite)
		{

			include(__DIR__."/../../../wp-config-extra.php");
			$cont="";


			if(!empty($catList))
			{

				$cat_sql = "AND c.categoryid IN (".implode(",",$catList).")";
				$cat_sql_sc = "AND sc.categoryid IN (".implode(",",$catList).")";
			}
			else
			{

				$cat_sql = "AND c.categoryid = '{$cat['category']}'";
				$cat_sql_sc = "AND sc.categoryid = '{$cat['category']}'";
			}

			/*** make sure $page value is 1 or greater ***/
			$page = ($_GET['pg'] > 1) ? $_GET['pg'] : 1;


			if($page == 1)
			{

				$offset_sql = "WHERE c.channelid = '$psite'";
				$offset 	= 0;

			}
			else
			{

				$offset 	= $page * 50 - 50;
				$offset_sql = "WHERE p.productid NOT IN (
								Select * From (
									Select sp.productid from categories sc
									left join products sp on sc.categoryid = sp.categoryid
									left join manufacturers sm on sp.manufacturerid = sm.manufacturerid
									Where sc.channelid = '$psite' $cat_sql_sc and sp.visible = 1
								order by sm.manufacturer_name asc limit $offset, 50
								) as productid
						) AND c.channelid = '$psite'";
			}

			//Filter Sort by Asc, Sort by Top Review, Sort by Latest Reviews
			//Check for sort value in session
			if(isset($_SESSION['sort']))
			{
				if($_SESSION['sort'] == "asc")
				{
					$filter = "m.manufacturer_name ASC, p.product_name ASC";
				}
				else if($_SESSION['sort'] == "reviews")
				{
					$filter = "reviewdate DESC";
				}
				else if($_SESSION['sort'] == "views")
				{
					$filter = "p.views DESC";
				}
				else if($_SESSION['sort'] == "score")
				{
					$filter = "avg_rating DESC, p.total_reviews DESC";
				}
				else if($_SESSION['sort'] == "maxreviews")
				{
					$filter = "p.total_reviews DESC";
				}
				else if($_SESSION['sort'] == "latest")
				{
					$filter = "p.date_created DESC";
				}
				else
				{
					$filter = "avg_rating DESC, p.total_reviews DESC";
				}
			}
			else
			{
				$filter = "avg_rating DESC, p.total_reviews DESC";
			}

			/*** query list of products, sort by manufacturer name*/

			if($ARCHIVED == 1){

				if(isset($_SESSION['archive']))
				{
					if($_SESSION['archive'] == "1")
					{
						$cont = "and p.archived=1 ";
					}
					else
					{
						$cont = "and p.archived=0 ";
					}
				}
				else
				{
					$cont = "and p.archived=0 ";
				}
			}

			if(get_option('nophotoformid') == 1)
			{
			$nophoto = "imagepresent DESC ,";
			}else{
			$nophoto = "";
			}


		 $this->query = "Select c.url_safe_category_name, p.date_created, p.productid, p.categoryid, p.product_name,  p.product_image, cc.category_path, p.total_reviews, p.average_rating, ROUND(p.average_rating,1) as avg_rating, p.quickrating_average, p.quickrating_count, p.url_safe_product_name, m.manufacturer_name, m.url_safe_manufacturer_name, IF(lower(p.product_image) = 'nophoto.jpg',0,1) as imagepresent, (select avg(review_rating) from products_site_reviews psr where psr.productid = p.productid) as web_score_rating, (select sum(review_count) from products_site_reviews psr where psr.productid = p.productid) as web_score_count, (select max(date_created) from reviews usr where usr.productid = p.productid) as reviewdate, p.views from categories c left join products p on c.categoryid = p.categoryid inner join manufacturers m on p.manufacturerid = m.manufacturerid inner join channel_categories cc on cc.categoryid = p.categoryid WHERE p.visible=1 $cont and c.channelid = '$psite' $cat_sql ORDER BY $nophoto $filter limit $offset, 50";




			//echo $this->query;


			//$this->db->show_errors();
			return $this->db->get_results( $this->query, object );
		}


		/* 20-12-2017 brand category page */

		function get_product_list_outdoor_brand($cat,$catList=array(), $psite)
		{

			include(__DIR__."/../../../wp-config-extra.php");
			$cont="";
			if($ARCHIVED == 1)
			{


				if(isset($_SESSION['archive']))
				{
					if($_SESSION['archive'] == "1")
					{
						$cont = "and p.archived=1 ";
					}
					else
					{
						$cont = "and p.archived=0 ";
					}
				}
				else
				{
					$cont = "and p.archived=0 ";
				}


			}
			$url = explode("/",$_SERVER['REQUEST_URI']);
			$urlnew = explode(".",$url[2]);
			if(!empty($catList))
			{

				$cat_sql = "AND c.categoryid IN (".implode(",",$catList).")";
				$cat_sql_sc = "AND sc.categoryid IN (".implode(",",$catList).")";
			}
			else
			{

				$cat_sql = "AND c.categoryid = '{$cat['category']}'";
				$cat_sql_sc = "AND sc.categoryid = '{$cat['category']}'";
			}

			/*** make sure $page value is 1 or greater ***/
			$page = ($_GET['pg'] > 1) ? $_GET['pg'] : 1;


			if($page == 1) {

				$offset_sql = "WHERE c.channelid = '$psite'";
				$offset 	= 0;

			} else {

				$offset 	= $page * 50 - 50;
				$offset_sql = "WHERE p.productid NOT IN (
								Select * From (
									Select sp.productid from categories sc
									left join products sp on sc.categoryid = sp.categoryid
									left join manufacturers sm on sp.manufacturerid = sm.manufacturerid
									Where sc.channelid = '$psite' $cat_sql_sc and sp.visible = 1
								order by sm.manufacturer_name asc limit $offset, 50
								) as productid
						) AND c.channelid = '$psite'";
			}



			//Filter Sort by Asc, Sort by Top Review, Sort by Latest Reviews
			if(isset($_SESSION['sort']))
			{
				if($_SESSION['sort'] == "asc")
				{
					$filter = "m.manufacturer_name ASC, p.product_name ASC";
				}
				else if($_SESSION['sort'] == "reviews")
				{
					$filter = "reviewdate DESC";
				}
				else if($_SESSION['sort'] == "views")
				{
					$filter = "p.views DESC";
				}
				else if($_SESSION['sort'] == "score")
				{
					$filter = "p.average_rating DESC, p.total_reviews DESC";
				}
				else if($_SESSION['sort'] == "maxreviews")
				{
					$filter = "p.total_reviews DESC";
				}
				else if($_SESSION['sort'] == "latest")
				{
					$filter = "p.date_created DESC";
				}
				else
				{
					$filter = "p.average_rating DESC, p.total_reviews DESC";
				}
			}
			else
			{
				$filter = "p.average_rating DESC, p.total_reviews DESC";
			}

			/*** query list of products, sort by manufacturer name*/



			if(get_option('nophotoformid') == 1)
			{
				$nophoto = "imagepresent DESC ,";
			}
			else
			{
				$nophoto = "";
			}



			/*** query list of products, sort by manufacturer name*/

			$this->query = "Select c.url_safe_category_name, p.date_created, p.productid, p.categoryid, p.product_name,  p.product_image, cc.category_path, p.total_reviews, p.average_rating, p.quickrating_average, p.quickrating_count, p.url_safe_product_name, m.manufacturer_name, m.url_safe_manufacturer_name,IF(lower(p.product_image) = 'nophoto.jpg',0,1) as imagepresent, (select avg(review_rating) from products_site_reviews psr where psr.productid = p.productid) as web_score_rating, (select sum(review_count) from products_site_reviews psr where psr.productid = p.productid) as web_score_count, (select max(date_created) from reviews usr where usr.productid = p.productid) as reviewdate, p.views from categories c left join products p on c.categoryid = p.categoryid inner join manufacturers m on p.manufacturerid = m.manufacturerid inner join channel_categories cc on cc.categoryid = p.categoryid WHERE p.visible=1 and c.channelid = '$psite' $cat_sql and m.url_safe_manufacturer_name='$urlnew[0]' $cont ORDER BY $nophoto $filter limit $offset, 50";

			//echo $this->query;
			//$this->db->show_errors();
			return $this->db->get_results( $this->query, object );
		}


		function get_product_list_new_outdoor($cat,$catList=array(), $psite)
		{

			//$cat_sql = "AND cc.category_path like '%/".$cat['category']."%'";
			$cat_sql = "AND (locate('".$cat['base']."/".$cat['category']."/',cc.category_path) > 0 OR cc.category_path = '".$cat['base']."/".$cat['category']."')";



			/*** make sure $page value is 1 or greater ***/
			$page = ($_GET['pg'] > 1) ? $_GET['pg'] : 1;


			if($page == 1) {

				$offset_sql = "WHERE c.channelid = '$psite'";
				$offset 	= 0;

			} else {

				$offset 	= $page * 50 - 50;
				$offset_sql = "WHERE p.productid NOT IN (
								Select * From (
									Select sp.productid from categories sc
									left join products sp on sc.categoryid = sp.categoryid
									left join manufacturers sm on sp.manufacturerid = sm.manufacturerid
									Where sc.channelid = '$psite' $cat_sql_sc and sp.visible = 1
								order by sm.manufacturer_name asc limit $offset, 50
								) as productid
						) AND c.channelid = '$psite'";
			}






			//Filter Sort by Asc, Sort by Top Review, Sort by Latest Reviews

			if(isset($_SESSION['sort']))
			{
				if($_SESSION['sort'] == "asc")
				{
					$filter = "m.manufacturer_name ASC, p.product_name ASC";
				}
				else if($_SESSION['sort'] == "reviews")
				{
					$filter = "reviewdate DESC";
				}
				else if($_SESSION['sort'] == "views")
				{
					$filter = "p.views DESC";
				}
				else if($_SESSION['sort'] == "score")
				{
					$filter = "p.average_rating DESC, p.total_reviews DESC";
				}
				else if($_SESSION['sort'] == "maxreviews")
				{
					$filter = "p.total_reviews DESC";
				}
				else if($_SESSION['sort'] == "latest")
				{
					$filter = "p.date_created DESC";
				}
				else
				{
					$filter = "p.average_rating DESC, p.total_reviews DESC";
				}
			}
			else
			{
				$filter = "p.average_rating DESC, p.total_reviews DESC,";
			}

			/*** query list of products, sort by manufacturer name*/
			include(__DIR__."/../../../wp-config-extra.php");
			$cont="";
			if($ARCHIVED == 1){

				if(isset($_SESSION['archive']))
				{
					if($_SESSION['archive'] == "1")
					{
						$cont = "and p.archived=1 ";
					}
					else
					{
						$cont = "and p.archived=0 ";
					}
				}
				else
				{
					$cont = "and p.archived=0 ";
				}
			}


			if(get_option('nophotoformid') == 1)
			{
				$nophoto = "imagepresent DESC ,";
			}
			else
			{
				$nophoto = "";
			}




			/*** query list of products, sort by manufacturer name*/

			 $this->query = "Select c.url_safe_category_name, p.productid, p.categoryid, p.product_name, p.date_created, p.product_image, p.total_reviews, p.average_rating, p.quickrating_average, cc.category_path, p.quickrating_count, p.url_safe_product_name, m.manufacturer_name, m.url_safe_manufacturer_name, IF(lower(p.product_image) = 'nophoto.jpg',0,1) as imagepresent, (select avg(review_rating) from products_site_reviews psr where psr.productid = p.productid) as web_score_rating, (select sum(review_count) from products_site_reviews psr where psr.productid = p.productid) as web_score_count, (select max(date_created) from reviews usr where usr.productid = p.productid) as reviewdate, p.views from categories c left join products p on c.categoryid = p.categoryid inner join manufacturers m on p.manufacturerid = m.manufacturerid inner join channel_categories cc on c.categoryid = cc.categoryid WHERE p.visible='1' $cont and c.channelid = '$psite' $cat_sql ORDER BY $nophoto $filter limit $offset, 50";

			//$this->db->show_errors();

			//echo $this->query;
			return $this->db->get_results( $this->query, object );
		}



		function get_product_brand_list_total_outdoor($brand1,$PSite) {

			include(__DIR__."/../../../wp-config-extra.php");
			$cont="";
			if($ARCHIVED == 1)
			{


				if(isset($_SESSION['archive']))
				{
					if($_SESSION['archive'] == "1")
					{
						$cont = "and p.archived=1 ";
					}
					else
					{
						$cont = "and p.archived=0 ";
					}
				}
				else
				{
					$cont = "and p.archived=0 ";
				}


			}
			$url = explode( '/', $_SERVER['REQUEST_URI']);

			$this->query = "select cc.nodeid from channel_categories cc where cc.channelid = $PSite and cc.node_level = 2 and cc.category_path = '$url[2]'";
			$row = $this->db->get_row( $this->query, object );

			//$row = $this->get_node_id($url[1], $PSite );




			//$this->query = "Select count(p.productid) as product_count, m.manufacturer_name, m.url_safe_manufacturer_name From products p, categories c, manufacturers m, channel_categories cc WHERE cc.categoryid = c.categoryid and p.manufacturerid = m.manufacturerid and c.categoryid = p.categoryid and c.channelid = '$PSite' and cc.node_level >= 3 and cc.nodeid like '".$row->nodeid."%' and m.url_safe_manufacturer_name = '$brand1[brand]' and p.visible = 1 Group by m.manufacturer_name, m.url_safe_manufacturer_name";


			$this->query = "Select count(p.productid) as product_count, m.manufacturer_name, m.url_safe_manufacturer_name From products p, categories c, manufacturers m, channel_categories cc WHERE cc.categoryid = c.categoryid and p.manufacturerid = m.manufacturerid and c.categoryid = p.categoryid and c.channelid = '$PSite' and cc.node_level >= 3 and (locate('$url[2]/',cc.category_path) > 0 OR cc.category_path = '$url[2]') and m.url_safe_manufacturer_name = '$brand1[brand]' and p.visible = 1 $cont Group by m.manufacturer_name, m.url_safe_manufacturer_name";


			//$this->db->show_errors();
			return $this->db->get_row( $this->query, object );
		}


		function get_product_brand_list_outdoor($brand, $PSite) {
			include(__DIR__."/../../../wp-config-extra.php");

			$cont="";
			if($ARCHIVED == 1)
			{


				if(isset($_SESSION['archive']))
				{
					if($_SESSION['archive'] == "1")
					{
						$cont = "and p.archived=1 ";
					}
					else
					{
						$cont = "and p.archived=0 ";
					}
				}
				else
				{
					$cont = "and p.archived=0 ";
				}


			}

			/** make sure $page value is 1 or greater */
			//$page = (is_numeric($brand['pg'])) ? $brand['pg'] : 1;

			$page = (is_numeric($_GET['pg']) && $_GET['pg'] > 1) ? $_GET['pg'] : 1;
			/**
			* 50 results per page
			* if page != 1, create sub query to create offset
			* subquery only used because this is mssql and does not support LIMIT X,Y
			* once converted to mysql, the subquery is not needed
			*/
			if($page == 1) {

				//$offset_sql = "WHERE c.channelid = '7'";
				$offset_sql = " Limit 0, 50";

			} else {

				$offset = $page * 50 - 50;
				$offset_sql = "Limit $offset, 50";

			}



				$url = explode( '/', $_SERVER['REQUEST_URI']);
				//$row = $this->get_node_id($url[2], $PSite );


			//Filter Sort by Asc, Sort by Top Review, Sort by Latest Reviews
			if(isset($_SESSION['sort']))
			{
				if($_SESSION['sort'] == "asc")
				{
					$filter = "m.manufacturer_name ASC, p.product_name ASC";
				}
				else if($_SESSION['sort'] == "reviews")
				{
					$filter = "reviewdate DESC";
				}
				else if($_SESSION['sort'] == "views")
				{
					$filter = "p.views DESC";
				}
				else if($_SESSION['sort'] == "score")
				{
					$filter = "p.average_rating DESC, p.total_reviews DESC";
				}
				else if($_SESSION['sort'] == "maxreviews")
				{
					$filter = "p.total_reviews DESC";
				}
				else if($_SESSION['sort'] == "latest")
				{
					$filter = "p.date_created DESC";
				}
				else
				{
					$filter = "p.average_rating DESC, p.total_reviews DESC";
				}
			}
			else
			{
				$filter = "p.average_rating DESC, p.total_reviews DESC";
			}

			/*** query list of products, sort by manufacturer name*/



			if(get_option('nophotoformid') == 1)
			{
				$nophoto = "imagepresent DESC ,";
			}
			else
			{
				$nophoto = "";
			}



				$this->query = "Select c.url_safe_category_name, c.category_name, p.productid, p.date_created, p.product_name,cc.category_path, p.product_image, p.total_reviews, p.average_rating, p.quickrating_average, p.quickrating_count, p.url_safe_product_name, m.manufacturer_name, m.url_safe_manufacturer_name, IF(lower(p.product_image) = 'nophoto.jpg',0,1) as imagepresent, (select avg(review_rating) from products_site_reviews psr where psr.productid = p.productid) as web_score_rating, (select sum(review_count) from products_site_reviews psr where psr.productid = p.productid) as web_score_count, (select max(date_created) from reviews usr where usr.productid = p.productid) as reviewdate, p.views  from products p, categories c, manufacturers m, channel_categories cc WHERE cc.categoryid = c.categoryid and p.manufacturerid = m.manufacturerid and c.categoryid = p.categoryid and c.channelid = '$PSite' and cc.node_level >= 3 and (locate('$url[2]/',cc.category_path) > 0 OR cc.category_path = '$url[2]') and m.url_safe_manufacturer_name = '$brand[brand]' and p.visible = 1 $cont order by $nophoto $filter $offset_sql";


				//echo $this->query;

			//$this->db->show_errors();
			return $this->db->get_results( $this->query, object );
		}




		function get_product_partner_links($id) {

			//$this->query = "select linkid, productid, categoryid, link_name, use_graphic, tracking_url, link_url, tagline, graphic, cp.partner_graphic, partner_product_text, sale_price, original_price from partner_links pl, partner_campaigns pc, commerce_partners cp where cp.partnerid = pc.partnerid and pl.campaignid = pc.campaignid and pl.productid = '$id' and pl.valid = 1 order by linkid desc";
			$this->query = "Select * from (select linkid, cp.partnerid, link_name, tracking_url, link_url, tagline, graphic, cp.partner_graphic, partner_product_text, sale_price, original_price,  pl.date_created from partner_links pl, partner_campaigns pc, commerce_partners cp where cp.partnerid = pc.partnerid and pl.campaignid = pc.campaignid and pl.productid = '$id' and pl.valid = 1 And pc.valid = 1 and expired = 0 and curdate() Between start_date And end_date order by pl.date_created desc) as s GROUP BY  partnerid";
			//$this->db->show_errors();
			return $this->db->get_results( $this->query, object );
		}

		// returning an array instead of an object
		function get_product_partner_links_sidebar($id) {

			//$this->query = "select linkid, productid, categoryid, link_name, use_graphic, tracking_url, link_url, tagline, graphic, cp.partner_graphic, partner_product_text, sale_price, original_price from partner_links pl, partner_campaigns pc, commerce_partners cp where cp.partnerid = pc.partnerid and pl.campaignid = pc.campaignid and pl.productid = '$id' and pl.valid = 1 order by linkid desc";
			$this->query = "select linkid, cp.partnerid, productid, categoryid, link_name, use_graphic, tracking_url, link_url, tagline, graphic, cp.partner_graphic, partner_product_text, sale_price, original_price,  pl.date_created from partner_links pl, partner_campaigns pc, commerce_partners cp where cp.partnerid = pc.partnerid and pl.campaignid = pc.campaignid and pl.productid = '$id' and pl.valid = 1 And pc.valid = 1 and expired = 0 and curdate() Between start_date And end_date order by pl.date_created desc";
			//$this->db->show_errors();
			return $this->db->get_results( $this->query, ARRAY_A);
		}



		function get_category_list_outdoor($type=0, $psite, $cat,$archive) {
			include(__DIR__."/../../../wp-config-extra.php");

			if($ARCHIVED == 1)
			{
				//$archive is $_SESSION['archive']
				if($archive == "1")
				{

				$view = "archived_product_count_by_category";

				}else{

				$view = "current_product_count_by_category";

				}
			}else{

				$view = "product_count_by_category";

			}


			$this->query = "select c.categoryid, c.category_name, c.url_safe_category_name, cc.node_level, cc.nodeid, cc.category_path, pc.productCount AS product_count from categories c INNER join channel_categories cc ON cc.categoryid = c.categoryid LEFT JOIN $view pc ON cc.categoryid = pc.categoryid where cc.channelid = $psite AND cc.node_level > 1 and (locate('$cat/',cc.category_path) > 0 OR cc.category_path = '$cat') ORDER by nodeid";


			return $this->db->get_results( $this->query, object );
		}

		function get_category_list_outdoor_fourth($type=0, $psite, $cat,$archive) {
			include(__DIR__."/../../../wp-config-extra.php");

			if($ARCHIVED == 1)
			{
				//$archive is $_SESSION['archive']
				if($archive == "1")
				{

				$view = "archived_product_count_by_category";

				}else{

				$view = "current_product_count_by_category";

				}
			}else{

				$view = "product_count_by_category";

			}



			$this->query = "select c.categoryid, c.category_name, c.url_safe_category_name, cc.node_level, cc.nodeid, cc.category_path, pc.productCount AS product_count from categories c INNER join channel_categories cc ON cc.categoryid = c.categoryid LEFT JOIN $view pc ON cc.categoryid = pc.categoryid where cc.channelid = $psite AND cc.node_level > 1 and (locate('$cat/',cc.category_path) > 0 OR cc.category_path = '$cat') ORDER by nodeid";

			return $this->db->get_results( $this->query, object );
		}

		/*  brand category page 20/12/90  */

		function get_category_list_outdoor_brand($whtml, $PSite) {

			include(__DIR__."/../../../wp-config-extra.php");
			$cont="";
			if($ARCHIVED == 1)
			{


				if(isset($_SESSION['archive']))
				{
					if($_SESSION['archive'] == "1")
					{
						$cont = "and p.archived=1 ";
					}
					else
					{
						$cont = "and p.archived=0 ";
					}
				}
				else
				{
					$cont = "and p.archived=0 ";
				}


			}

			$this->query = "Select c.category_name, c.url_safe_category_name,cc.category_path, p.productid, p.categoryid, p.product_name,  p.product_image, p.total_reviews, p.average_rating, p.quickrating_average, p.quickrating_count, p.url_safe_product_name, m.manufacturer_name, m.url_safe_manufacturer_name, (select avg(review_rating) from products_site_reviews psr where psr.productid = p.productid) as web_score_rating, (select sum(review_count) from products_site_reviews psr where psr.productid = p.productid) as web_score_count from categories c left join products p on c.categoryid = p.categoryid inner join manufacturers m on p.manufacturerid = m.manufacturerid inner join channel_categories cc on cc.categoryid = p.categoryid WHERE c.channelid = $PSite and m.url_safe_manufacturer_name = '$whtml[0]' AND p.visible = '1' AND cc.node_level >= 2 $cont";




			return $this->db->get_results( $this->query, object );
		}





		function get_external_product_ratings($id) {

			$this->query = "SELECT 	SUM(review_count) as web_score_count, AVG(review_rating) as web_score_rating
							FROM products_site_reviews WHERE productid = '$id'";

			//$this->db->show_errors();
			return $this->db->get_row( $this->query, object );
		}

		/*** Get product media*****/

		function get_product_media($id) {

			$this->query = "select mediaid, type, caption, value from product_media where productid = '$id' order by type";
			//$this->db->show_errors();
			return $this->db->get_results( $this->query, object );
		}

		/*** Get product reviews query****/

		function get_product_reviews($id, $page, $sort=1) {

			$sortSql 	= ($sort == 1) ? 'reviewid' : 'overall_rating';
			$page 		= ($page > 1) ? $page : 1;

			/*** 10 reviews per page * if page != 1, create sub query to create offset ****/

			if($page == 1) {

				$offset_sql = "WHERE productid = '$id'";

			} else {

				$offset = $page * 10 - 10;
				$offset_sql = "WHERE reviewid NOT IN ( Select * From ( SELECT reviewid FROM reviews WHERE productid = '$id'
				AND valid = 1 ORDER BY $sortSql DESC Limit $offset ) as productid ) AND productid = '$id'";
			}

			$this->query = "Select reviewid, model, value_rating, overall_rating,
			date_format( date_created,'%b %d, %Y') as date_created, misc_rating1, misc_rating2,
			misc_rating3, misc_rating4, performance_rating, visitors_rating, total_visitors,
			total_visitor_ratings, customer_service, summary, pros, cons, strength, weakness, year_purchased, price, product_type,  product_experience, reviewer_experience, purchase_price, purchased_at, quality, similar_products, vbulletinid,
			user_screen_name, vbulletin_database, validated_email From reviews $offset_sql
			and valid = 1 order by $sortSql desc Limit 10";

                        $con = mysqli_connect(DB_RHOST,DB_RUSER,DB_RPASSWORD,DB_RNAME) or die("Some error occurred during connection " . mysqli_error($con));

                        $qry = mysqli_query($con, $this->query);

                        while ($res1=mysqli_fetch_object($qry))
                        {
                                $resultnew[] = $res1;
                        }

                        return $resultnew;
		}

		/**************this need to be changed*************************/

		function get_category_list($type=0, $psite) {

			$this->query = "select c.categoryid, c.category_name, c.url_safe_category_name, cc.node_level, cc.nodeid, cc.category_path, pc.productCount AS product_count from categories c INNER join channel_categories cc ON cc.categoryid = c.categoryid LEFT JOIN product_count_by_category pc ON cc.categoryid = pc.categoryid where cc.channelid = '$psite' AND cc.node_level > 1 and product_count !='NULL' ORDER by nodeid ";
			return $this->db->get_results( $this->query, object );
		}




		//Top Rated product reviews
		function site_top_rated_product_list($limit, $PSite) {

$this->query = "Select c.category_name, c.url_safe_category_name,cc.category_path, p.productid, p.categoryid, p.product_name,  p.product_image, p.Views, p.Average_Rating, p.Total_Reviews, p.url_safe_product_name, m.manufacturer_name, m.url_safe_manufacturer_name from categories c inner join products p on c.categoryid = p.categoryid inner join manufacturers m on p.manufacturerid = m.manufacturerid inner join channel_categories cc on cc.categoryid = p.categoryid WHERE cc.channelid = $PSite[0] AND p.visible = '1' and UPPER(p.product_image) !='NOPHOTO.JPG' order by p.Views desc limit ".$limit;
/*
			$this->query = "select * from products p INNER JOIN reviews r ON r.ProductID=p.ProductID and p.ChannelID = $PSite[0] ORDER BY p.Average_Rating DESC limit ".$limit;
*/
			return $this->db->get_results( $this->query, object );
		}
		//Latest Rated product reviews
		function site_latest_rated_product_list($limit, $PSite) {

$this->query = "Select r.latest,c.category_name, c.url_safe_category_name,cc.category_path, p.productid, p.categoryid, p.product_name,  p.product_image, p.Views, p.Average_Rating, p.Total_Reviews, p.url_safe_product_name, m.manufacturer_name, m.url_safe_manufacturer_name from categories c inner join products p on c.categoryid = p.categoryid inner join manufacturers m on p.manufacturerid = m.manufacturerid inner join channel_categories cc on cc.categoryid = p.categoryid INNER JOIN (select productid, max(date_created) as latest from reviews group by productid) r ON r.ProductID=p.ProductID WHERE cc.channelid = $PSite[0] AND p.visible = '1' and UPPER(p.product_image) !='NOPHOTO.JPG' order by r.latest desc limit ".$limit;
			return $this->db->get_results( $this->query, object );
		}


		//Latest product list
		function site_latest_product_list($limit, $PSite) {

$this->query = "Select c.category_name, c.url_safe_category_name,cc.category_path, p.productid, p.categoryid, p.product_name,  p.product_image, p.Date_Created, p.Views, p.Average_Rating, p.Total_Reviews, p.url_safe_product_name, m.manufacturer_name, m.url_safe_manufacturer_name from categories c inner join products p on c.categoryid = p.categoryid inner join manufacturers m on p.manufacturerid = m.manufacturerid inner join channel_categories cc on cc.categoryid = p.categoryid WHERE cc.channelid = $PSite[0] AND p.visible = '1' and UPPER(p.product_image) !='NOPHOTO.JPG' order by p.Date_Created desc limit ".$limit;
/*
			$this->query = "select * from products p INNER JOIN reviews r ON r.ProductID=p.ProductID and p.ChannelID = $PSite[0] ORDER BY p.Date_Created DESC limit ".$limit;
*/
			return $this->db->get_results( $this->query, object );
		}




		function get_main_category_list($type=0, $psite, $mainmenu,$archive) {
			include(__DIR__."/../../../wp-config-extra.php");

			$cond = "";
			foreach ( $mainmenu as $navItem1 )
			{
				if($navItem1->menu_item_parent == 0)
				{
					$cond .= "'".$navItem1->title."', ";
				}
				$menuss = strtolower($navItem1->title)."/";
				$cond2 .= " and cc.category_path NOT LIKE '".$menuss."%'";

			}

			$cond1 = substr(trim($cond), 0, -1);




			if($ARCHIVED == 1)
			{
				//$archive is $_SESSION['archive']
				if($archive == "1")
				{

				$view = "archived_product_count_by_category";

				}else{

				$view = "current_product_count_by_category";

				}
			}else{

				$view = "product_count_by_category";

			}



			$this->query = " select c.categoryid, c.category_name, c.url_safe_category_name, cc1.node_level, cc1.nodeid, cc1.category_path, (select sum(pcc.productCount) from $view pcc, channel_categories cc where cc.CategoryID = pcc.categoryid and (cc.category_path like concat(cc1.category_path,'/%') OR cc.category_path = cc1.category_path )) as product_count from channel_categories cc1, categories c where c.categoryid = cc1.categoryid and cc1.node_level >= 2 and cc1.channelid = '$psite'";

			return $this->db->get_results( $this->query, object );
		}










		function get_category_detail_outdoor_sub( $cat, $psite ) {

			$this->query = "select c.categoryid, c.category_name, c.url_safe_category_name, (select count(*) from products p where p.categoryid = c.categoryid and p.ManufacturerID !='NULL' and p.visible='1') as product_count from categories c where c.channelid = '$psite' and c.url_safe_category_name = '$cat[category]'";

			return $this->db->get_row( $this->query, object );
		}

		function get_category_detail( $cat, $psite ) {

			include(__DIR__."/../../../wp-config-extra.php");
			$cont="";
			if($ARCHIVED == 1)
			{


				if(isset($_SESSION['archive']))
				{
					if($_SESSION['archive'] == "1")
					{
						$cont = "and p.archived=1 ";
					}
					else
					{
						$cont = "and p.archived=0 ";
					}
				}
				else
				{
					$cont = "and p.archived=0 ";
				}


			}
			$this->query = "select c.categoryid, c.category_name, c.url_safe_category_name,
			(select count(*) from products p where p.categoryid = c.categoryid and p.ManufacturerID !='NULL' and p.visible='1' $cont) as product_count
			from categories c where c.channelid = '$psite' and c.url_safe_category_name = '$cat[category]'";
			return $this->db->get_row( $this->query, object );
		}



		function get_product_list1($cat,$catList=array(), $psite) {


			if(!empty($catList)) {

				$cat_sql = "AND c.url_safe_category_name IN ('".implode("','",$catList)."')";
				$cat_sql_sc = "AND sc.url_safe_category_name IN ('".implode("','",$catList)."')";
			}
			else {

				$cat_sql = "AND c.url_safe_category_name = '{$cat['category']}'";
				$cat_sql_sc = "AND sc.url_safe_category_name = '{$cat['category']}'";
			}

			$page = ($_GET['pg'] > 1) ? $_GET['pg'] : 1;

			$this->query = "Select c.url_safe_category_name, p.productid, p.product_name,  p.product_image,
			p.total_reviews, p.average_rating, p.quickrating_average, p.quickrating_count,
			p.url_safe_product_name, m.manufacturer_name, m.url_safe_manufacturer_name,
			(select avg(review_rating) from products_site_reviews psr where psr.productid = p.productid) as
			web_score_rating, (select sum(review_count) from products_site_reviews psr
			where psr.productid = p.productid) as web_score_count from categories c left join products p on
			c.categoryid = p.categoryid left join manufacturers m on p.manufacturerid = m.manufacturerid
			WHERE c.channelid = '$psite' $cat_sql AND
			p.visible = 1 ORDER BY m.manufacturer_name ASC, p.product_name ASC ";



			//$this->db->show_errors();
			return $this->db->get_results( $this->query, object );
		}


		function get_product_list($cat,$catList=array(), $psite)
		{

			if(!empty($catList))
			{

				$cat_sql = "AND c.url_safe_category_name IN ('".implode("','",$catList)."')";
				$cat_sql_sc = "AND sc.url_safe_category_name IN ('".implode("','",$catList)."')";
			}
			else
			{

				$cat_sql = "AND c.url_safe_category_name = '{$cat['category']}'";
				$cat_sql_sc = "AND sc.url_safe_category_name = '{$cat['category']}'";
			}

			/*** make sure $page value is 1 or greater ***/
			$page = ($_GET['pg'] > 1) ? $_GET['pg'] : 1;


			if($page == 1) {

				$offset_sql = "WHERE c.channelid = '$psite'";
				$offset 	= 0;

			} else {

				$offset 	= $page * 50 - 50;
				$offset_sql = "WHERE p.productid NOT IN (
								Select * From (
									Select sp.productid from categories sc
									left join products sp on sc.categoryid = sp.categoryid
									left join manufacturers sm on sp.manufacturerid = sm.manufacturerid
									Where sc.channelid = '$psite' $cat_sql_sc and sp.visible = 1
								order by sm.manufacturer_name asc limit $offset, 50
								) as productid
						) AND c.channelid = '$psite'";
			}

			/*** query list of products, sort by manufacturer name*/

			$this->query = "Select c.url_safe_category_name, p.productid, p.categoryid, p.product_name,  p.product_image,
			p.total_reviews, p.average_rating, p.quickrating_average, p.quickrating_count,
			p.url_safe_product_name, m.manufacturer_name, m.url_safe_manufacturer_name,
			(select avg(review_rating) from products_site_reviews psr where psr.productid = p.productid) as
			web_score_rating, (select sum(review_count) from products_site_reviews psr
			where psr.productid = p.productid) as web_score_count from categories c left join products p on
			c.categoryid = p.categoryid inner join manufacturers m on p.manufacturerid = m.manufacturerid
			WHERE p.visible='1' and c.channelid = '$psite' $cat_sql ORDER BY m.manufacturer_name ASC, p.product_name ASC limit $offset, 50";

			/*echo "Select c.url_safe_category_name, p.productid, p.categoryid, p.product_name,  p.product_image,
			p.total_reviews, p.average_rating, p.quickrating_average, p.quickrating_count,
			p.url_safe_product_name, m.manufacturer_name, m.url_safe_manufacturer_name,
			(select avg(review_rating) from products_site_reviews psr where psr.productid = p.productid) as
			web_score_rating, (select sum(review_count) from products_site_reviews psr
			where psr.productid = p.productid) as web_score_count from categories c left join products p on
			c.categoryid = p.categoryid inner join manufacturers m on p.manufacturerid = m.manufacturerid
			WHERE c.channelid = '$psite' $cat_sql ORDER BY m.manufacturer_name ASC, p.product_name ASC limit $offset, 50";*/


			//$this->db->show_errors();
			return $this->db->get_results( $this->query, object );
		}

		function get_main_product_list($cat,$catList=array(), $psite)
		{

			//$cat['category'] = $this->category_permalink_to_db($cat['category']);

			if(!empty($catList)) {

				$cat_sql = "AND c.url_safe_category_name IN ('".implode("','",$catList)."')";
				$cat_sql_sc = "AND sc.url_safe_category_name IN ('".implode("','",$catList)."')";
			}
			else {

				$cat_sql = "AND c.url_safe_category_name = '{$cat['category']}'";
				$cat_sql_sc = "AND sc.url_safe_category_name = '{$cat['category']}'";
			}

			/*** make sure $page value is 1 or greater ***/
			$page = ($_GET['pg'] > 1) ? $_GET['pg'] : 1;
			/**
			* 50 results per page
			* if page != 1, create sub query to create offset
			* subquery only used because this is mssql and does not support LIMIT X,Y
			* once converted to mysql, the subquery is not needed */

			if($page == 1) {

				$offset_sql = "WHERE c.channelid = '$psite'";
				$offset 	= 0;

			} else {

				$offset 	= $page * 50 - 50;
				$offset_sql = "WHERE p.productid NOT IN (
								Select * From (
									Select sp.productid from categories sc
									left join products sp on sc.categoryid = sp.categoryid
									left join manufacturers sm on sp.manufacturerid = sm.manufacturerid
									Where sc.channelid = '$psite' $cat_sql_sc and sp.visible = 1
								order by sm.manufacturer_name asc limit $offset, 50
								) as productid
						) AND c.channelid = '$psite'";
			}

			/*** query list of products, sort by manufacturer name*/

			$this->query = "Select c.url_safe_category_name, p.productid, p.categoryid, p.product_name,  p.product_image,
p.total_reviews, p.average_rating, p.quickrating_average, p.quickrating_count, p.url_safe_product_name, m.manufacturer_name,cc.nodeid as cc_nodeid, m.url_safe_manufacturer_name, (select avg(review_rating) from products_site_reviews psr where psr.productid = p.productid) as web_score_rating, (select sum(review_count) from products_site_reviews psr where psr.productid = p.productid) as web_score_count from products p, categories c, channel_categories cc, manufacturers m where p.manufacturerid = m.manufacturerid and p.categoryid = c.categoryid and c.categoryid = cc.categoryid and cc.channelid = $psite and c.categoryid in (SELECT cc.categoryid FROM channel_categories cc1 WHERE EXISTS (SELECT NULL FROM channel_categories b, categories c1 WHERE b.categoryid = c1.categoryid and b.node_level = 2 and cc1.nodeid LIKE CONCAT('%', b.nodeid, '%') and c1.category_name not in ('bikes'))) ORDER BY m.manufacturer_name ASC, p.product_name ASC limit $offset, 50";


			//$this->db->show_errors();
			return $this->db->get_results( $this->query, object );
		}


		//Zaved Added as on 07-Dec-2016

		function GetChannelIDByCat( $cat ){

			$this->query = "Select cc.channelid From channel_categories cc Where cc.category_path = '$cat'";
			return $this->db->get_row( $this->query, object );
		}

		function GetCategoryIDByCat( $cat, $PSite ){
			$this->query = "select CategoryID, Url_Safe_Category_Name from categories Where Url_Safe_Category_Name = '$cat'";
			return $this->db->get_row( $this->query, object );
		}
		function GetManufacturerIDByCat( $cat, $PSite ){
			//echo "Select cc.ManufacturerID From manufacturers cc Where cc.Url_Safe_Manufacturer_Name = '$cat'";
			$this->query = "Select cc.ManufacturerID From manufacturers cc Where cc.Url_Safe_Manufacturer_Name = '$cat'";
			return $this->db->get_row( $this->query, object );
		}

		function get_node_id_outdoor( $cat, $PSite ){

			$this->query = "select cc.nodeid from channel_categories cc where cc.channelid = $PSite and cc.category_path = '$cat'";
			return $this->db->get_row( $this->query, object );
		}
		function get_node_id( $cat, $PSite ){

			$this->query = "select cc.nodeid from channel_categories cc where cc.channelid = $PSite and cc.node_level = 2 and cc.category_path = '$cat'";
			return $this->db->get_row( $this->query, object );
		}

		/**
		* get_product_brand_list is same as get_product_list, except get products
		* by brand name and not category
		*/


	function get_product_brand_list1($brand, $PSite) {



			/** make sure $page value is 1 or greater */
			//$page = (is_numeric($brand['pg'])) ? $brand['pg'] : 1;

			$page = (is_numeric($_GET['pg']) && $_GET['pg'] > 1) ? $_GET['pg'] : 1;
			/**
			* 50 results per page
			* if page != 1, create sub query to create offset
			* subquery only used because this is mssql and does not support LIMIT X,Y
			* once converted to mysql, the subquery is not needed
			*/


				$url = explode( '/', $_SERVER['REQUEST_URI']);
				$row = $this->get_node_id($url[1], $PSite );

				$this->query = "Select c.url_safe_category_name, c.category_name, p.productid, p.product_name, p.product_image, p.total_reviews,
				p.average_rating, p.quickrating_average, p.quickrating_count, p.url_safe_product_name, m.manufacturer_name, m.url_safe_manufacturer_name,
				(select avg(review_rating) from products_site_reviews psr where psr.productid = p.productid) as web_score_rating,
				(select sum(review_count) from products_site_reviews psr where psr.productid = p.productid) as web_score_count
				from products p, categories c, manufacturers m, channel_categories cc WHERE
				cc.categoryid = c.categoryid and p.manufacturerid = m.manufacturerid and c.categoryid = p.categoryid and
				c.channelid = '$PSite' and cc.node_level = 3 and cc.nodeid like '".$row->nodeid."%' and m.url_safe_manufacturer_name = '$brand[brand]' and
				p.visible = 1 order by p.product_name asc ";



			//$this->db->show_errors();
			return $this->db->get_results( $this->query, object );
		}

		function get_product_brand_list($brand, $PSite) {


			/** make sure $page value is 1 or greater */
			//$page = (is_numeric($brand['pg'])) ? $brand['pg'] : 1;

			$page = (is_numeric($_GET['pg']) && $_GET['pg'] > 1) ? $_GET['pg'] : 1;
			/**
			* 50 results per page
			* if page != 1, create sub query to create offset
			* subquery only used because this is mssql and does not support LIMIT X,Y
			* once converted to mysql, the subquery is not needed
			*/
			if($page == 1) {

				//$offset_sql = "WHERE c.channelid = '7'";
				$offset_sql = " Limit 0, 50";

			} else {

				$offset = $page * 50 - 50;
				$offset_sql = "Limit $offset, 50";

				/*$offset_sql = "WHERE p.productid NOT IN (
					Select * From (
					Select sp.productid from products sp
					left join categories sc on sc.categoryid = sp.categoryid
					left join manufacturers sm on sp.manufacturerid = sm.manufacturerid
					where sc.channelid = '7' and sm.url_safe_manufacturer_name = '$brand[brand]'
					and sp.visible = 1 order by sp.product_name asc Limit $offset ) as productid
				) AND c.channelid = '7'"; */
			}



				$url = explode( '/', $_SERVER['REQUEST_URI']);
				$row = $this->get_node_id($url[1], $PSite );

				$this->query = "Select c.url_safe_category_name, c.category_name, p.productid, p.product_name, p.product_image, p.total_reviews, p.average_rating, p.quickrating_average, p.quickrating_count, p.url_safe_product_name, m.manufacturer_name, m.url_safe_manufacturer_name, (select avg(review_rating) from products_site_reviews psr where psr.productid = p.productid) as web_score_rating, (select sum(review_count) from products_site_reviews psr where psr.productid = p.productid) as web_score_count from products p, categories c, manufacturers m, channel_categories cc WHERE cc.categoryid = c.categoryid and p.manufacturerid = m.manufacturerid and c.categoryid = p.categoryid and c.channelid = '$PSite' and cc.node_level = 3 and cc.nodeid like '".$row->nodeid."%' and m.url_safe_manufacturer_name = '$brand[brand]' and p.visible = 1 order by p.product_name asc $offset_sql";



			//$this->db->show_errors();
			return $this->db->get_results( $this->query, object );
		}

		/**
		* get_product_brand_cat_list is same as get_product_list, except get products
		* by brand name and within a specific category
		*/

		function get_product_brand_cat_list_outdoor_subcat_four( $brand, $PSite ) {

				$parts = explode( '/', $_SERVER['REQUEST_URI']);
			$lastpart_temp = explode('.',$parts[3]);
			$lastpart_end_temp = explode('.',$parts[4]);

			if(count($parts) == 5)
			{
				$lastpart = $lastpart_temp[0]."/".$lastpart_end_temp[0];
			}
			else
			{
				$lastpart = $lastpart_temp[0];
			}
			/** make sure $page value is 1 or greater */
			$page = (is_numeric($_GET['pg']) && $_GET['pg'] > 1) ? $_GET['pg'] : 1;

			if($page == 1) {

				$offset_sql = "WHERE c.channelid = '$PSite'";
				$offset = $page * 50 - 50;

			} else {

				$offset = $page * 50 - 50;
				$offset_sql = "WHERE p.productid NOT IN (
						Select * From (
						Select sp.productid from categories sc
						left join products sp on sc.categoryid = sp.categoryid
						left join manufacturers sm on sp.manufacturerid = sm.manufacturerid
						where sc.channelid = '$PSite' and sm.url_safe_manufacturer_name = '$brand[brand]'
						and sc.url_safe_category_name = '$brand[category]' and sp.visible = 1
						order by sp.product_name asc Limit $offset ) as productid
				) AND c.channelid = '$PSite'";
			}
			//Filter Sort by Asc, Sort by Top Review, Sort by Latest Reviews
			if(isset($_SESSION['sort']))
			{
				if($_SESSION['sort'] == "asc")
				{
					$filter = "m.manufacturer_name ASC, p.product_name ASC";
				}
				else if($_SESSION['sort'] == "reviews")
				{
					$filter = "reviewdate DESC";
				}
				else if($_SESSION['sort'] == "views")
				{
					$filter = "p.views DESC";
				}
				else if($_SESSION['sort'] == "score")
				{
					$filter = "p.average_rating DESC, p.total_reviews DESC";
				}
				else if($_SESSION['sort'] == "maxreviews")
				{
					$filter = "p.total_reviews DESC";
				}
				else if($_SESSION['sort'] == "latest")
				{
					$filter = "p.date_created DESC";
				}
				else
				{
					$filter = "p.average_rating DESC, p.total_reviews DESC";
				}
			}
			else
			{
				$filter = "p.average_rating DESC, p.total_reviews DESC";
			}

			/*** query list of products, sort by manufacturer name*/



			if(get_option('nophotoformid') == 1)
			{
				$nophoto = "imagepresent DESC ,";
			}
			else
			{
				$nophoto = "";
			}






			//$this->query = "Select c.url_safe_category_name, p.productid, p.categoryid, p.product_name,  p.product_image, cc.category_path, p.total_reviews, p.average_rating, p.quickrating_average, p.quickrating_count, p.url_safe_product_name, m.manufacturer_name, m.url_safe_manufacturer_name, IF(lower(p.product_image) = 'nophoto.jpg',0,1) as imagepresent, (select avg(review_rating) from products_site_reviews psr where psr.productid = p.productid) as web_score_rating, (select sum(review_count) from products_site_reviews psr where psr.productid = p.productid) as web_score_count, (select max(date_created) from reviews usr where usr.productid = p.productid) as reviewdate, p.views from categories c left join products p on c.categoryid = p.categoryid inner join manufacturers m on p.manufacturerid = m.manufacturerid inner join channel_categories cc on cc.categoryid = p.categoryid WHERE p.visible=1 and c.channelid = '$psite' $cat_sql ORDER BY $nophoto $filter limit $offset, 50";

			$this->query = "Select c.url_safe_category_name, c.category_name, p.productid, cc.category_path, p.product_name, p.date_created, p.product_image, p.total_reviews, p.average_rating, p.quickrating_average,p.quickrating_count,p.url_safe_product_name, m.manufacturer_name,m.url_safe_manufacturer_name, IF(lower(p.product_image) = 'nophoto.jpg',0,1) as imagepresent, (select avg(review_rating) From products_site_reviews psr where psr.productid = p.productid) as web_score_rating, (select sum(review_count) from products_site_reviews psr where psr.productid = p.productid) as web_score_count, (select max(date_created) from reviews usr where usr.productid = p.productid) as reviewdate, p.views from categories c left join products p on c.categoryid = p.categoryid INNER join channel_categories cc ON cc.categoryid = c.categoryid Left join manufacturers m on p.manufacturerid = m.manufacturerid $offset_sql and m.url_safe_manufacturer_name = '$brand[brand]' and (locate('$parts[2]/$parts[3]/$parts[4]/$parts[5]/',cc.category_path) > 0 OR cc.category_path = '$parts[2]/$parts[3]/$parts[4]/$parts[5]') and p.visible = 1 order by $nophoto $filter Limit $offset, 50";

					//$this->db->show_errors();
			return $this->db->get_results( $this->query, object );
		}


		function get_product_brand_cat_list_outdoor_subcat_three( $brand, $PSite ) {

				$parts = explode( '/', $_SERVER['REQUEST_URI']);
			$lastpart_temp = explode('.',$parts[3]);
			$lastpart_end_temp = explode('.',$parts[4]);

			if(count($parts) == 5)
			{
				$lastpart = $lastpart_temp[0]."/".$lastpart_end_temp[0];
			}
			else
			{
				$lastpart = $lastpart_temp[0];
			}
			/** make sure $page value is 1 or greater */
			$page = (is_numeric($_GET['pg']) && $_GET['pg'] > 1) ? $_GET['pg'] : 1;

			if($page == 1) {

				$offset_sql = "WHERE c.channelid = '$PSite'";
				$offset = $page * 50 - 50;

			} else {

				$offset = $page * 50 - 50;
				$offset_sql = "WHERE p.productid NOT IN (
						Select * From (
						Select sp.productid from categories sc
						left join products sp on sc.categoryid = sp.categoryid
						left join manufacturers sm on sp.manufacturerid = sm.manufacturerid
						where sc.channelid = '$PSite' and sm.url_safe_manufacturer_name = '$brand[brand]'
						and sc.url_safe_category_name = '$brand[category]' and sp.visible = 1
						order by sp.product_name asc Limit $offset ) as productid
				) AND c.channelid = '$PSite'";
			}

			//Filter Sort by Asc, Sort by Top Review, Sort by Latest Reviews
			if(isset($_SESSION['sort']))
			{
				if($_SESSION['sort'] == "asc")
				{
					$filter = "m.manufacturer_name ASC, p.product_name ASC";
				}
				else if($_SESSION['sort'] == "reviews")
				{
					$filter = "reviewdate DESC";
				}
				else if($_SESSION['sort'] == "views")
				{
					$filter = "p.views DESC";
				}
				else if($_SESSION['sort'] == "score")
				{
					$filter = "p.average_rating DESC, p.total_reviews DESC";
				}
				else if($_SESSION['sort'] == "maxreviews")
				{
					$filter = "p.total_reviews DESC";
				}
				else if($_SESSION['sort'] == "latest")
				{
					$filter = "p.date_created DESC";
				}
				else
				{
					$filter = "p.average_rating DESC, p.total_reviews DESC";
				}
			}
			else
			{
				$filter = "p.average_rating DESC, p.total_reviews DESC";
			}

			/*** query list of products, sort by manufacturer name*/



			if(get_option('nophotoformid') == 1)
			{
				$nophoto = "imagepresent DESC ,";
			}
			else
			{
				$nophoto = "";
			}


			$this->query = "Select c.url_safe_category_name,c.category_name,p.productid,p.date_created, cc.category_path, p.product_name, p.product_image, p.total_reviews, p.average_rating, p.quickrating_average,p.quickrating_count,p.url_safe_product_name, m.manufacturer_name,m.url_safe_manufacturer_name, IF(lower(p.product_image) = 'nophoto.jpg',0,1) as imagepresent, (select avg(review_rating) From products_site_reviews psr where psr.productid = p.productid) as web_score_rating, (select sum(review_count) from products_site_reviews psr where psr.productid = p.productid) as web_score_count, (select max(date_created) from reviews usr where usr.productid = p.productid) as reviewdate, p.views from categories c left join products p on c.categoryid = p.categoryid INNER join channel_categories cc ON cc.categoryid = c.categoryid Left join manufacturers m on p.manufacturerid = m.manufacturerid $offset_sql and m.url_safe_manufacturer_name = '$brand[brand]' and (locate('$parts[2]/$parts[3]/$parts[4]/',cc.category_path) > 0 OR cc.category_path = '$parts[2]/$parts[3]/$parts[4]') and p.visible = 1 order by $nophoto $filter Limit $offset, 50";


			//echo $this->query;
					//$this->db->show_errors();
			return $this->db->get_results( $this->query, object );
		}


		function get_product_brand_cat_list_outdoor_subcat( $brand, $PSite ) {

			include(__DIR__."/../../../wp-config-extra.php");

			$cont="";
			if($ARCHIVED == 1)
			{


				if(isset($_SESSION['archive']))
				{
					if($_SESSION['archive'] == "1")
					{
						$cont = "and p.archived=1 ";
					}
					else
					{
						$cont = "and p.archived=0 ";
					}
				}
				else
				{
					$cont = "and p.archived=0 ";
				}


			}


			$parts = explode( '/', $_SERVER['REQUEST_URI']);


			/** make sure $page value is 1 or greater */
			$page = (is_numeric($_GET['pg']) && $_GET['pg'] > 1) ? $_GET['pg'] : 1;

			if($page == 1) {

				$offset_sql = "WHERE c.channelid = '$PSite'";
				$offset = $page * 50 - 50;

			} else {

				$offset = $page * 50 - 50;
				$offset_sql = "WHERE p.productid NOT IN (
						Select * From (
						Select sp.productid from categories sc
						left join products sp on sc.categoryid = sp.categoryid
						left join manufacturers sm on sp.manufacturerid = sm.manufacturerid
						where sc.channelid = '$PSite' and sm.url_safe_manufacturer_name = '$brand[brand]'
						and sc.url_safe_category_name = '$brand[category]' and sp.visible = 1
						order by sp.product_name asc Limit $offset ) as productid
				) AND c.channelid = '$PSite'";
			}


			//Filter Sort by Asc, Sort by Top Review, Sort by Latest Reviews
			if(isset($_SESSION['sort']))
			{
				if($_SESSION['sort'] == "asc")
				{
					$filter = "m.manufacturer_name ASC, p.product_name ASC";
				}
				else if($_SESSION['sort'] == "reviews")
				{
					$filter = "reviewdate DESC";
				}
				else if($_SESSION['sort'] == "views")
				{
					$filter = "p.views DESC";
				}
				else if($_SESSION['sort'] == "score")
				{
					$filter = "p.average_rating DESC, p.total_reviews DESC";
				}
				else if($_SESSION['sort'] == "maxreviews")
				{
					$filter = "p.total_reviews DESC";
				}
				else if($_SESSION['sort'] == "latest")
				{
					$filter = "p.date_created DESC";
				}
				else
				{
					$filter = "p.average_rating DESC, p.total_reviews DESC";
				}
			}
			else
			{
				$filter = "p.average_rating DESC, p.total_reviews DESC";
			}

			/*** query list of products, sort by manufacturer name*/



			if(get_option('nophotoformid') == 1)
			{
				$nophoto = "imagepresent DESC ,";
			}
			else
			{
				$nophoto = "";
			}






			//$this->query = "Select c.url_safe_category_name, p.productid, p.categoryid, p.product_name,  p.product_image, cc.category_path, p.total_reviews, p.average_rating, p.quickrating_average, p.quickrating_count, p.url_safe_product_name, m.manufacturer_name, m.url_safe_manufacturer_name, IF(lower(p.product_image) = 'nophoto.jpg',0,1) as imagepresent, (select avg(review_rating) from products_site_reviews psr where psr.productid = p.productid) as web_score_rating, (select sum(review_count) from products_site_reviews psr where psr.productid = p.productid) as web_score_count, (select max(date_created) from reviews usr where usr.productid = p.productid) as reviewdate, p.views from categories c left join products p on c.categoryid = p.categoryid inner join manufacturers m on p.manufacturerid = m.manufacturerid inner join channel_categories cc on cc.categoryid = p.categoryid WHERE p.visible=1 and c.channelid = '$psite' $cat_sql ORDER BY $nophoto $filter limit $offset, 50";


			$this->query = "Select c.url_safe_category_name,c.category_name,p.productid, cc.category_path, p.product_name, p.date_created, p.product_image, p.total_reviews, p.average_rating, p.quickrating_average,p.quickrating_count,p.url_safe_product_name, m.manufacturer_name,m.url_safe_manufacturer_name, IF(lower(p.product_image) = 'nophoto.jpg',0,1) as imagepresent, (select avg(review_rating) From products_site_reviews psr where psr.productid = p.productid) as web_score_rating, (select sum(review_count) from products_site_reviews psr where psr.productid = p.productid) as web_score_count, (select max(date_created) from reviews usr where usr.productid = p.productid) as reviewdate, p.views  from categories c left join products p on c.categoryid = p.categoryid INNER join channel_categories cc ON cc.categoryid = c.categoryid Left join manufacturers m on p.manufacturerid = m.manufacturerid $offset_sql and m.url_safe_manufacturer_name = '$brand[brand]' and (locate('$parts[2]/$parts[3]/',cc.category_path) > 0 OR cc.category_path = '$parts[2]/$parts[3]') and p.visible = 1 $cont order by $nophoto $filter Limit $offset, 50";

			//echo $this->query;

					//$this->db->show_errors();
			return $this->db->get_results( $this->query, object );
		}


		function get_product_brand_cat_list( $brand, $PSite ) {

			/*$brand['category'] = $this->category_permalink_to_db($brand['category']);*/ //Zaved commented as on 5Dec2016

			/** make sure $page value is 1 or greater */
			$page = (is_numeric($_GET['pg']) && $_GET['pg'] > 1) ? $_GET['pg'] : 1;
			/**
			* 50 results per page
			* if page != 1, create sub query to create offset
			* subquery only used because this is mssql and does not support LIMIT X,Y
			* once converted to mysql, the subquery is not needed*/

			if($page == 1) {

				$offset_sql = "WHERE c.channelid = '$PSite'";

			} else {

				$offset = $page * 50 - 50;
				$offset_sql = "WHERE p.productid NOT IN (
						Select * From (
						Select sp.productid from categories sc
						left join products sp on sc.categoryid = sp.categoryid
						left join manufacturers sm on sp.manufacturerid = sm.manufacturerid
						where sc.channelid = '$PSite' and sm.url_safe_manufacturer_name = '$brand[brand]'
						and sc.url_safe_category_name = '$brand[category]' and sp.visible = 1
						order by sp.product_name asc Limit $offset ) as productid
				) AND c.channelid = '$PSite'";
			}

			/** * query list of products, sort by manufacturer name */
			$this->query = "Select c.url_safe_category_name,c.category_name,p.productid, p.product_name, p.product_image,
			p.total_reviews, p.average_rating, p.quickrating_average,p.quickrating_count,p.url_safe_product_name,
			m.manufacturer_name,m.url_safe_manufacturer_name, (select avg(review_rating)
			From products_site_reviews psr where psr.productid = p.productid) as web_score_rating,
			(select sum(review_count) from products_site_reviews psr where psr.productid = p.productid)
			as web_score_count from categories c left join products p on c.categoryid = p.categoryid
			Left join manufacturers m on p.manufacturerid = m.manufacturerid $offset_sql
			and m.url_safe_manufacturer_name = '$brand[brand]'
			and c.url_safe_category_name = '$brand[category]' and p.visible = 1	order by p.product_name asc Limit 50";

					//$this->db->show_errors();
			return $this->db->get_results( $this->query, object );
		}

		/** UPDATE PRODUCT VIEWS***/

		function update_product_views($id){
		$this->db->query("update products set Views = Views + 1 Where ProductID = $id");

		}

		/*** GET PRODUCT VIEWS ****/

		function get_top_product_views($PSite) {

			$this->query = "Select c.category_name, c.url_safe_category_name,cc.category_path, p.productid, p.categoryid, p.product_name,  p.product_image, p.Views, p.Average_Rating, p.Total_Reviews, p.url_safe_product_name, m.manufacturer_name, m.url_safe_manufacturer_name from categories c inner join products p on c.categoryid = p.categoryid inner join manufacturers m on p.manufacturerid = m.manufacturerid inner join channel_categories cc on cc.categoryid = p.categoryid WHERE cc.channelid = $PSite[0] AND p.visible = '1' and UPPER(p.product_image) !='NOPHOTO.JPG' order by p.Views desc limit 10";


			//$this->db->show_errors();
			return $this->db->get_results( $this->query, object );
		}

		/*** GET PRODUCT REVIEWS ****/

		function get_top_product_reviews($PSite) {


			$this->query = "Select r.latest,c.category_name, c.url_safe_category_name,cc.category_path, p.productid, p.categoryid, p.product_name,  p.product_image, p.Views, p.Average_Rating, p.Total_Reviews, p.url_safe_product_name, m.manufacturer_name, m.url_safe_manufacturer_name from categories c inner join products p on c.categoryid = p.categoryid inner join manufacturers m on p.manufacturerid = m.manufacturerid inner join channel_categories cc on cc.categoryid = p.categoryid INNER JOIN (select productid, max(date_created) as latest from reviews group by productid) r ON r.ProductID=p.ProductID WHERE cc.channelid = $PSite[0] AND p.visible = '1' and UPPER(p.product_image) !='NOPHOTO.JPG' order by r.latest desc limit 10";


			//$this->db->show_errors();
			return $this->db->get_results( $this->query, object );
		}

		/*** get_product_brand_list_total() returns count of all products in a specific brand ****/

		function get_product_brand_list_total($brand1,$PSite) {

			$url = explode( '/', $_SERVER['REQUEST_URI']);

			$this->query = "select cc.nodeid from channel_categories cc where cc.channelid = $PSite and cc.node_level = 2 and cc.category_path = '$url[1]'";
			$row = $this->db->get_row( $this->query, object );

			//$row = $this->get_node_id($url[1], $PSite );




			$this->query = "Select count(p.productid) as product_count, m.manufacturer_name, m.url_safe_manufacturer_name From
			  products p, categories c, manufacturers m, channel_categories cc WHERE cc.categoryid = c.categoryid and p.manufacturerid = m.manufacturerid and c.categoryid = p.categoryid and c.channelid = '$PSite' and cc.node_level = 3 and cc.nodeid like '".$row->nodeid."%' and m.url_safe_manufacturer_name = '$brand1[brand]' and p.visible = 1 Group by m.manufacturer_name, m.url_safe_manufacturer_name";


			//$this->db->show_errors();
			return $this->db->get_row( $this->query, object );
		}

		/**
		* get_product_brand_cat_list_total() returns count of all products in a specific category
		* in a specific brand*/

		function get_product_brand_cat_list_total_outdoor_subcat_four($brand) {
			include(__DIR__."/../../../wp-config-extra.php");
			$cont="";
			if($ARCHIVED == 1)
			{


				if(isset($_SESSION['archive']))
				{
					if($_SESSION['archive'] == "1")
					{
						$cont = "and p.archived=1 ";
					}
					else
					{
						$cont = "and p.archived=0 ";
					}
				}
				else
				{
					$cont = "and p.archived=0 ";
				}


			}

			$parts = explode('/',$_SERVER[REQUEST_URI]);
			$brand_temp = explode(".",$parts[count($parts)-1]);


			if(count($parts) == 5)
			{
				$brand = $brand_temp[0];
			}
			else
			{
				$brand = $brand_temp[0];
			}
			$this->query = "Select count(p.productid) as product_count, m.manufacturer_name, cc.category_path, m.url_safe_manufacturer_name, c.category_name, c.url_safe_category_name from products p left join categories c on p.categoryid = c.categoryid INNER join channel_categories cc ON cc.categoryid = c.categoryid left join manufacturers m on p.manufacturerid = m.manufacturerid where m.url_safe_manufacturer_name = '$brand' and p.visible = 1  $cont and (locate('$parts[2]/$parts[3]/$parts[4]/$parts[5]/',cc.category_path) > 0 OR cc.category_path = '$parts[2]/$parts[3]/$parts[4]/$parts[5]') group by m.url_safe_manufacturer_name";

			//$this->db->show_errors();
			return $this->db->get_row( $this->query, object );
		}


		function get_product_brand_cat_list_total_outdoor_subcat_three($brand) {
			include(__DIR__."/../../../wp-config-extra.php");
			$cont="";
			if($ARCHIVED == 1)
			{


				if(isset($_SESSION['archive']))
				{
					if($_SESSION['archive'] == "1")
					{
						$cont = "and p.archived=1 ";
					}
					else
					{
						$cont = "and p.archived=0 ";
					}
				}
				else
				{
					$cont = "and p.archived=0 ";
				}


			}

			$parts = explode('/',$_SERVER[REQUEST_URI]);
			$brand_temp = explode(".",$parts[count($parts)-1]);


			if(count($parts) == 5)
			{
				$brand = $brand_temp[0];
			}
			else
			{
				$brand = $brand_temp[0];
			}
			$this->query = "Select count(p.productid) as product_count, m.manufacturer_name, cc.category_path, m.url_safe_manufacturer_name, c.category_name, c.url_safe_category_name from products p left join categories c on p.categoryid = c.categoryid INNER join channel_categories cc ON cc.categoryid = c.categoryid left join manufacturers m on p.manufacturerid = m.manufacturerid where m.url_safe_manufacturer_name = '$brand' and p.visible = 1 $cont and (locate('$parts[2]/$parts[3]/$parts[4]/',cc.category_path) > 0 OR cc.category_path = '$parts[2]/$parts[3]/$parts[4]') group by m.url_safe_manufacturer_name";

			//$this->db->show_errors();
			return $this->db->get_row( $this->query, object );
		}


		function get_product_brand_cat_list_total_outdoor_subcat($brand) {
			include(__DIR__."/../../../wp-config-extra.php");
			$cont="";
			if($ARCHIVED == 1)
			{


				if(isset($_SESSION['archive']))
				{
					if($_SESSION['archive'] == "1")
					{
						$cont = "and p.archived=1 ";
					}
					else
					{
						$cont = "and p.archived=0 ";
					}
				}
				else
				{
					$cont = "and p.archived=0 ";
				}


			}

			$parts = explode('/',$_SERVER[REQUEST_URI]);
			$brand_temp = explode(".",$parts[count($parts)-1]);

			if(count($parts) == 5)
			{
				$brand = $brand_temp[0];
			}
			else
			{
				$brand = $brand_temp[0];
			}
			$this->query = "Select count(p.productid) as product_count, m.manufacturer_name, cc.category_path, m.url_safe_manufacturer_name, c.category_name, c.url_safe_category_name from products p left join categories c on p.categoryid = c.categoryid INNER join channel_categories cc ON cc.categoryid = c.categoryid left join manufacturers m on p.manufacturerid = m.manufacturerid where m.url_safe_manufacturer_name = '$brand' and p.visible = 1 $cont and (locate('$parts[2]/$parts[3]/',cc.category_path) > 0 OR cc.category_path = '$parts[2]/$parts[3]') group by m.url_safe_manufacturer_name";


			//echo $this->query;
			//$this->db->show_errors();
			return $this->db->get_row( $this->query, object );
		}

		function get_product_brand_cat_list_total($brand) {

			/*$brand['category'] = $this->category_permalink_to_db($brand['category']);*/
			/*** query product count, brand name, category name*/

			$this->query = "Select count(p.productid) as product_count, m.manufacturer_name,
				m.url_safe_manufacturer_name, c.category_name, c.url_safe_category_name from products p
				left join categories c on p.categoryid = c.categoryid
				left join manufacturers m on p.manufacturerid = m.manufacturerid
				where m.url_safe_manufacturer_name = '$brand[brand]' and p.visible = 1
				and c.url_safe_category_name = '$brand[category]' group by m.manufacturer_name,
				m.url_safe_manufacturer_name, c.category_name, c.url_safe_category_name";


			//$this->db->show_errors();
			return $this->db->get_row( $this->query, object );
		}

		/**
		* Get alphabetical list of manufacturers for brand page listing
		* Due to database structure, need to get list through Categories > Products > Manufactureres
		* with where clause of Category.channelid = 7
		* Let me know if there is a shorter way to identify manufactureres of GolfReview products only.
		*/


		function cr_get_brand_list_outdoor_brand_three($cat, $all, $psite) {
			include(__DIR__."/../../../wp-config-extra.php");
			$cont="";
			if($ARCHIVED == 1)
			{


				if(isset($_SESSION['archive']))
				{
					if($_SESSION['archive'] == "1")
					{
						$cont = "and p.archived=1 ";
					}
					else
					{
						$cont = "and p.archived=0 ";
					}
				}
				else
				{
					$cont = "and p.archived=0 ";
				}


			}

			$cal_sql = "";
			if($all == 0) {
				$cat_sql = "AND c.url_safe_category_name = '$cat[category]'";
			}
			else {

				$cat_sql = "AND c.categoryid IN ('$cat[cat_list]')";
			}

			$this->query = "Select m.manufacturer_name, m.url_safe_manufacturer_name from categories c inner join products p on c.categoryid = p.categoryid inner join manufacturers m on p.manufacturerid = m.manufacturerid where c.channelid = '$psite' $cat_sql and p.visible = 1 and m.manufacturer_name !='' $cont group by m.manufacturer_name,m.url_safe_manufacturer_name  order by m.manufacturer_name asc";

			return $this->db->get_results( $this->query, object );
		}


		function cr_get_brand_list_outdoor_sub_four($cat, $all, $psite) {
			include(__DIR__."/../../../wp-config-extra.php");
			$cont="";
			if($ARCHIVED == 1)
			{


				if(isset($_SESSION['archive']))
				{
					if($_SESSION['archive'] == "1")
					{
						$cont = "and p.archived=1 ";
					}
					else
					{
						$cont = "and p.archived=0 ";
					}
				}
				else
				{
					$cont = "and p.archived=0 ";
				}


			}

			$cal_sql = "";
			if($all == 0) {
				$cat_sql = "AND c.url_safe_category_name = '$cat[category]'";
			}
			else {

				$cat_sql = "AND c.categoryid IN ('$cat[cat_list]')";
			}

			$this->query = "Select m.manufacturer_name, m.url_safe_manufacturer_name from categories c inner join products p on c.categoryid = p.categoryid inner join manufacturers m on p.manufacturerid = m.manufacturerid where c.channelid = '$psite' $cat_sql and p.visible = 1 and m.manufacturer_name !='' $cont group by m.manufacturer_name,m.url_safe_manufacturer_name  order by m.manufacturer_name asc";

			return $this->db->get_results( $this->query, object );
		}

		function cr_get_brand_list_outdoor_sub($cat, $all, $psite) {

			$cal_sql = "";
			if($all == 0) {
				$cat_sql = "AND c.url_safe_category_name = '$cat[category]'";
			}
			else {

				$cat_sql = "AND c.categoryid IN ('$cat[cat_list]')";
			}

			$this->query = "Select m.manufacturer_name, m.url_safe_manufacturer_name from categories c inner join products p on c.categoryid = p.categoryid inner join manufacturers m on p.manufacturerid = m.manufacturerid where c.channelid = '$psite' $cat_sql and p.visible = 1 and m.manufacturer_name !='' group by m.manufacturer_name,m.url_safe_manufacturer_name  order by m.manufacturer_name asc";

			return $this->db->get_results( $this->query, object );
		}


		function cr_get_brand_list_components($cat, $all, $psite) {
			include(__DIR__."/../../../wp-config-extra.php");
			$cont="";
			if($ARCHIVED == 1)
			{


				if(isset($_SESSION['archive']))
				{
					if($_SESSION['archive'] == "1")
					{
						$cont = "and p.archived=1 ";
					}
					else
					{
						$cont = "and p.archived=0 ";
					}
				}
				else
				{
					$cont = "and p.archived=0 ";
				}


			}


			$cal_sql = "";
			if($all == 0)
			{
				$cat_sql = "AND c.url_safe_category_name = '$cat[category]'";
			}
			else
			{

				$cat_sql = "AND c.categoryid IN ('$cat[cat_list]')";
			}

			$this->query = "Select m.manufacturer_name, m.url_safe_manufacturer_name from categories c inner join products p on c.categoryid = p.categoryid inner join manufacturers m on p.manufacturerid = m.manufacturerid where c.channelid = '$psite' $cat_sql and p.visible = 1 and m.manufacturer_name !='' $cont group by m.manufacturer_name,m.url_safe_manufacturer_name  order by m.manufacturer_name asc";


			//$this->db->show_errors();
			return $this->db->get_results( $this->query, object );
		}


		function cr_get_brand_list_outdoor_first($cat, $all, $psite) {

			include(__DIR__."/../../../wp-config-extra.php");
			$cont="";
			if($ARCHIVED == 1)
			{


				if(isset($_SESSION['archive']))
				{
					if($_SESSION['archive'] == "1")
					{
						$cont = "and p.archived=1 ";
					}
					else
					{
						$cont = "and p.archived=0 ";
					}
				}
				else
				{
					$cont = "and p.archived=0 ";
				}


			}

			$cal_sql = "";
			if($all == 0) {

				//$cat['category'] = $this->category_permalink_to_db($cat['category']); Commnetde
				//if($cat['base'] == 'golf-equipment') $cat_sql = "AND c.url_safe_category_name IN ('$cat[cat_list]')";
				//else $cat_sql = "AND c.url_safe_category_name = '$cat[category]'";
				$cat_sql = "AND c.url_safe_category_name = '$cat[category]'";
			}
			else {

				$cat_sql = "AND c.categoryid IN ('$cat[cat_list]')";
			}

			$this->query = "Select m.manufacturer_name, m.url_safe_manufacturer_name from categories c inner join products p on c.categoryid = p.categoryid inner join manufacturers m on p.manufacturerid = m.manufacturerid where c.channelid = '$psite' $cat_sql and p.visible = 1 and m.manufacturer_name !='' $cont group by m.manufacturer_name,m.url_safe_manufacturer_name  order by m.manufacturer_name asc";

			//$this->db->show_errors();
			return $this->db->get_results( $this->query, object );
		}


		function cr_get_brand_list($cat, $all, $psite) {
			include(__DIR__."/../../../wp-config-extra.php");
			$cont="";
			if($ARCHIVED == 1)
			{


				if(isset($_SESSION['archive']))
				{
					if($_SESSION['archive'] == "1")
					{
						$cont = "and p.archived=1 ";
					}
					else
					{
						$cont = "and p.archived=0 ";
					}
				}
				else
				{
					$cont = "and p.archived=0 ";
				}


			}
			$cal_sql = "";
			if($all == 0) {

				//$cat['category'] = $this->category_permalink_to_db($cat['category']); Commnetde
				//if($cat['base'] == 'golf-equipment') $cat_sql = "AND c.url_safe_category_name IN ('$cat[cat_list]')";
				//else $cat_sql = "AND c.url_safe_category_name = '$cat[category]'";
				$cat_sql = "AND c.url_safe_category_name = '$cat[category]'";
			}
			else {

				$cat_sql = "AND c.categoryid IN ('$cat[cat_list]')";
			}

			$this->query = "Select m.manufacturer_name, m.url_safe_manufacturer_name from categories c
			inner join products p on c.categoryid = p.categoryid
			inner join manufacturers m on p.manufacturerid = m.manufacturerid
			where c.channelid = '$psite' $cat_sql
			and p.visible = 1 and m.manufacturer_name !='' $cont group by m.manufacturer_name,m.url_safe_manufacturer_name
			order by m.manufacturer_name asc";

			//$this->db->show_errors();
			return $this->db->get_results( $this->query, object );
		}

		function cr_get_brand_list_search($cat, $all, $psite) {

			$cal_sql = "";
			if($all == 0) {

				//$cat['category'] = $this->category_permalink_to_db($cat['category']); Commnetde
				//if($cat['base'] == 'golf-equipment') $cat_sql = "AND c.url_safe_category_name IN ('$cat[cat_list]')";
				//else $cat_sql = "AND c.url_safe_category_name = '$cat[category]'";
				$cat_sql = "AND c.url_safe_category_name = '$cat[category]'";
			}
			else {

				$cat_sql = "AND c.url_safe_category_name IN ('$cat[cat_list]')";
			}

			$this->query = "Select m.manufacturer_name, m.url_safe_manufacturer_name from categories c
			left join products p on c.categoryid = p.categoryid
			left join manufacturers m on p.manufacturerid = m.manufacturerid
			where c.channelid = '$psite' $cat_sql
			and p.visible = 1 group by m.manufacturer_name,m.url_safe_manufacturer_name
			order by m.manufacturer_name asc";


			//$this->db->show_errors();
			return $this->db->get_results( $this->query, object );
		}
		/*** Get all cities of location in a state ****/

		function get_site_location_cities($st) {


			//Golf Course Query

			/*$this->query = "Select av_ct.attribute_value as city from products p
			left join attribute_values av_st on p.productid = av_st.productid
			left join attribute_values av_ct on p.productid = av_ct.productid
			where p.manufacturerid = 8051 and av_st.attributeid = 70
			and av_st.attribute_value = '$st[state]' and av_ct.attributeid = 69
			Group by av_ct.attribute_value Order by av_ct.attribute_value";*/

                        $st1 = '/'.$st;
			$this->query = "Select av.attribute_value as city from products p, attribute_values av,channel_categories cc where p.productid = av.productid and p.categoryid = cc.categoryid and p.manufacturerid = 8051 and av.attributeid = 69 and LOCATE('$st1',cc.category_path) > 0 Group by av.attribute_value Order by av.attribute_value";


			//$this->db->show_errors();
			return $this->db->get_results( $this->query, object );
		}


/*** Get all cities of location in a state ****/

		function mtbr_get_site_location_cities($parts,$st) {

			if($parts[1]=='trails')
			{
				$manufacturerid = 9668;
			}
			else if($parts[1]=='bikeshops')
			{
				$manufacturerid = 13444;
			}



			if($st=='trails-Texas' || $st=='trails-California' || $st=='trails-texas' || $st=='trails-california')
			{
				$sta1 = explode('-',$st);
				$this->query = "Select av_ct.attribute_value as city from products p inner join categories c on p.categoryid = c.categoryid left join attribute_values av_ct on p.productid = av_ct.productid where p.manufacturerid = 9668 and c.url_safe_category_name like '$sta1[1]-%' and (av_ct.attribute_value not like  '%/%' AND av_ct.attribute_value not like '%-%' AND av_ct.attribute_value not like '%,%' AND av_ct.attribute_value not like '%;%') and av_ct.attributeid = 69 Group by av_ct.attribute_value Order by av_ct.attribute_value";

			}
			else if($st=='trails-Colorado' || $st=='trails-colorado')
			{
				//$sta1 = explode('-',$st);
				$this->query = "Select av_ct.attribute_value as city from products p inner join categories c on p.categoryid = c.categoryid left join attribute_values av_ct on p.productid = av_ct.productid where p.manufacturerid = 9668 and c.url_safe_category_name in ('front-range','western-slope') and av_ct.attributeid = 69 Group by av_ct.attribute_value Order by av_ct.attribute_value;";

			}
			else if($st=='Texas' || $st=='California' || $st=='texas' || $st=='california')
			{
				$this->query = "Select av_ct.attribute_value as city from products p inner join categories c on p.categoryid = c.categoryid left join attribute_values av_ct on p.productid = av_ct.productid where p.manufacturerid = 13444 and c.url_safe_category_name like '$st-%' and av_ct.attributeid = 69 Group by av_ct.attribute_value Order by av_ct.attribute_value";

			}
			else if($st=='Colorado' || $st=='colorado')
			{
				$this->query = "Select av_ct.attribute_value as city from products p inner join categories c on p.categoryid = c.categoryid left join attribute_values av_ct on p.productid = av_ct.productid where p.manufacturerid = 13444 and c.url_safe_category_name in ('front-range','western-slope') and av_ct.attributeid = 69 Group by av_ct.attribute_value Order by av_ct.attribute_value;";

			}
			else
			{
				$this->query = "Select av_ct.attribute_value as city from products p inner join categories c on p.categoryid = c.categoryid left join attribute_values av_ct on p.productid = av_ct.productid where p.manufacturerid = $manufacturerid and c.url_safe_category_name = '$st' and av_ct.attributeid = 69 Group by av_ct.attribute_value Order by av_ct.attribute_value";
			}
			return $this->db->get_results( $this->query, object );
		}
		/** * Get count of all location in a State ****/

		function get_site_location_state_count($st) {
			//Golf Course Query

                $st1 = '/'.$st;
				//Added city filter to the count - if the city is not populated then do not count
$this->query = "select count(p.productid) as total_count from products p, channel_categories cc, attribute_values av where p.categoryid = cc.categoryid and av.productid = p.productid and p.manufacturerid = 8051 and p.channelid = 82 and p.visible = 1 and LOCATE('$st1',cc.category_path) > 0 and av.attributeid = 69";



			return $this->db->get_row( $this->query, object );
		}

	/** * Get count of all location in a State ****/

		function mtbr_get_site_location_state_count($st) {

			if($st=='trails-Texas' || $st=='trails-California' || $st=='trails-texas' || $st=='trails-california')
			{
				$sta1 = explode('-',$st);
				$this->query = "select count(p.productid) as total_count from products p inner join categories c on c.categoryid = p.categoryid Left join attribute_values av_ct on p.productid = av_ct.productid where c.channelid = 84 and c.url_safe_category_name like '$sta1[1]-%' and av_ct.attributeid = 69";


			}
			else if($st=='trails-Colorado' || $st=='trails-colorado')
			{
				//$sta1 = explode('-',$st);
				$this->query = "select count(p.productid) as total_count from products p inner join categories c on c.categoryid = p.categoryid Left join attribute_values av_ct on p.productid = av_ct.productid where c.channelid = 84 and c.url_safe_category_name in ('front-range','western-slope') and av_ct.attributeid = 69";

			}
			else if($st=='Colorado' || $st=='colorado')
			{
				//$sta1 = explode('-',$st);
				$this->query = "select count(p.productid) as total_count from products p inner join categories c on c.categoryid = p.categoryid where c.channelid = 90 and c.url_safe_category_name in ('front-range','western-slope') and p.visible !=0";
			}
			else if($st=='Texas' || $st=='California' || $st=='texas' || $st=='california')
			{
				$this->query = "select count(p.productid) as total_count from products p inner join categories c on c.categoryid = p.categoryid where c.channelid = 90 and c.url_safe_category_name like '$st-%' and p.visible !=0";

			}
			else
			{
				$this->query = "select count(p.productid) as total_count from products p inner join categories c on c.categoryid = p.categoryid Left join attribute_values av_ct on p.productid = av_ct.productid where c.url_safe_category_name = '$st' and av_ct.attributeid = 69";

			}


			return $this->db->get_row( $this->query, object );
		}

		function get_site_location_listing($parts,$parts1) {
			/*$sql_where = "";

			if(!is_numeric($parts['zip'])) {

				$parts['city'] = $this->parse_city_name($parts['city']);
				//$sql_where = "AND (av_st.attributeid = 70 and av_st.attribute_value = '$parts[state]')
							// AND (av_ct.attributeid = 69 and av_ct.attribute_value = '$parts[city]')";
				$sql_where = " AND (av_ct.attributeid = 69 and av_ct.attribute_value = '$parts[city]')";
			} else {

				$sql_where = "AND (av_zp.attributeid = 71 and av_zp.attribute_value LIKE '$parts[zip]%')
							AND av_st.attributeid = 70 AND av_ct.attributeid = 69";
			}*/

$sql_where = "";
			if(!is_numeric($parts['zip'])) {

				$parts['city'] = $this->parse_city_name($parts['city']);
				$sql_where = "AND (av_ct.attributeid = 69 and av_ct.attribute_value = '$parts[city]')";
			} else {

				$sql_where = "AND (av_zp.attributeid = 71 and av_zp.attribute_value LIKE '$parts[zip]%')
							AND av_st.attributeid = 70 AND av_ct.attributeid = 69";
			}
$manufacturerid=8051;
			$sta = $parts1[3];

				$usafe = "c.url_safe_category_name = '$sta'";


				$this->query = "Select p.productid, p.product_name, p.total_reviews, p.average_rating, p.product_image,	p.quickrating_average, p.quickrating_count, p.url_safe_product_name, (select avg(review_rating) from products_site_reviews wsr Where wsr.productid = p.productid) as web_score_rating, (select sum(review_count)  From products_site_reviews wsc where wsc.productid = p.productid) As web_score_count, (select attribute_value from attribute_values avs Where avs.productid = p.productid and avs.attributeid = 343) as latitude, (select attribute_value from attribute_values avs1 Where avs1.productid = p.productid and avs1.attributeid = 342) as longitude,'$sta' as state, av_ct.attribute_value As city from products p inner join categories c on p.categoryid = c.categoryid Left join attribute_values av_ct on p.productid = av_ct.productid Left join attribute_values av_zp on p.productid = av_zp.productid Where $usafe and p.manufacturerid = $manufacturerid $sql_where	Group by p.productid, p.product_name, p.total_reviews, p.average_rating, p.quickrating_average, p.quickrating_count, p.url_safe_product_name, av_ct.attribute_value order by p.product_name";

						
			//$this->db->show_errors();
			return $this->db->get_results( $this->query, object );
		}

function mtbr_get_site_location_listing($parts,$parts1) {
			$sta = $parts1[3];
			if($parts1[1]=='trails')
			{
				if($parts1[2]=="canada" || $parts1[2]=="europe" || $parts1[2]=="asia-and-pacific")
				{
					$sta = $parts1[2]."-".$sta;
				}
				$sta1 = "trails-".$sta;
				$manufacturerid=9668;
			}
			else if($parts1[1]=='bikeshops')
			{
				if($parts1[2]=="canada" || $parts1[2]=="europe" || $parts1[2]=="asia-and-pacific")
				{
					$sta = $parts1[2]."-".$sta;
				}
				$sta1 = $sta;
				$manufacturerid=13444;
			}
			$sql_where = "";
			if(!is_numeric($parts['zip'])) {

				$parts['city'] = $this->parse_city_name($parts['city']);
				$sql_where = "AND (av_ct.attributeid = 69 and av_ct.attribute_value = '$parts[city]')";
			} else {

				$sql_where = "AND (av_zp.attributeid = 71 and av_zp.attribute_value LIKE '$parts[zip]%')
							AND av_st.attributeid = 70 AND av_ct.attributeid = 69";
			}

			if($sta1 == 'trails-Texas' || $sta1 == 'trails-California' || $sta1 == 'trails-texas' || $sta1 == 'trails-california')
			{
				$usafe = "c.url_safe_category_name like '$sta-%'";
			}
			else if($sta1 == 'trails-Colorado' || $sta1 == 'trails-colorado')
			{
				$usafe = " c.url_safe_category_name in ('front-range','western-slope')";
			}
			else if($sta1 == 'Texas' || $sta1 == 'California' || $sta1 == 'texas' || $sta1 == 'california')
			{
				$usafe = "c.url_safe_category_name like '$sta-%'";
			}
			else if($sta1 == 'Colorado' || $sta1 == 'colorado')
			{
				$usafe = " c.url_safe_category_name in ('front-range','western-slope')";
			}
			else
			{
				$usafe = "c.url_safe_category_name = '$sta1'";
			}

			$this->query = "Select p.productid, p.product_name, p.total_reviews, p.average_rating, p.product_image, p.quickrating_average, p.quickrating_count, p.url_safe_product_name, (select avg(review_rating) from products_site_reviews wsr Where wsr.productid = p.productid) as web_score_rating, (select sum(review_count)	From products_site_reviews wsc where wsc.productid = p.productid) As web_score_count, (select attribute_value from attribute_values avs Where avs.productid = p.productid and avs.attributeid = 343) as latitude, (select attribute_value from attribute_values avs1 Where avs1.productid = p.productid and avs1.attributeid = 342) as longitude, '$sta' as state, av_ct.attribute_value As city from products p inner join categories c on p.categoryid = c.categoryid	Left join attribute_values av_ct on p.productid = av_ct.productid Left join attribute_values av_zp on p.productid = av_zp.productid	Where $usafe and p.manufacturerid = $manufacturerid $sql_where Group by p.productid, p.product_name, p.total_reviews, p.average_rating,	p.quickrating_average, p.quickrating_count, p.url_safe_product_name, av_ct.attribute_value order by p.product_name";


			//$this->db->show_errors();
			return $this->db->get_results( $this->query, object );
		}


function get_site_location_state_listing($parts,$parts1) {

			$strip=explode(".", $parts1[3]);
			$sta = $strip[0];

			/*** make sure $page value is 1 or greater ***/
			$page = ($_GET['pg'] > 1) ? $_GET['pg'] : 1;


			if($page == 1) {

				$offset 	= 0;

			} else {

				$offset 	= $page * 50 - 50;

			}

			$manufacturerid=8051;

			$usafe = "c.url_safe_category_name = '$sta'";

				$this->query = "Select p.productid, p.product_name, p.total_reviews, p.average_rating, p.product_image, p.quickrating_average, p.quickrating_count, p.url_safe_product_name, (select avg(review_rating) from products_site_reviews wsr Where wsr.productid = p.productid) as web_score_rating, (select sum(review_count) From products_site_reviews wsc where wsc.productid = p.productid) As web_score_count, (select attribute_value from attribute_values avs Where avs.productid = p.productid and avs.attributeid = 343) as latitude, (select attribute_value from attribute_values avs1 Where avs1.productid = p.productid and avs1.attributeid = 342) as longitude, '$sta' as state ,av_ct.attribute_value As city from products p inner join categories c on p.categoryid = c.categoryid Left join attribute_values av_ct on p.productid = av_ct.productid Where $usafe and p.manufacturerid =$manufacturerid and av_ct.attributeid = 69 Group by p.productid, p.product_name, p.total_reviews, p.average_rating, p.quickrating_average, p.quickrating_count, p.url_safe_product_name order by p.product_name limit $offset, 50";

			//$this->db->show_errors();
			return $this->db->get_results( $this->query, object );
		}


function mtbr_get_site_location_state_listing($parts,$parts1) {

			$strip=explode(".", $parts1[3]);
			$sta = $strip[0];

			/*** make sure $page value is 1 or greater ***/
			$page = ($_GET['pg'] > 1) ? $_GET['pg'] : 1;


			if($page == 1) {

				$offset 	= 0;

			} else {

				$offset 	= $page * 50 - 50;

			}

			if($parts1[1]=='trails')
			{
				if($parts1[2]=="canada" || $parts1[2]=="europe" || $parts1[2]=="asia-and-pacific")
				{
					$sta = $parts1[2]."-".$sta;
				}
				$sta1 = "trails-".$sta;
				$manufacturerid=9668;
			}
			else if($parts1[1]=='bikeshops')
			{
				if($parts1[2]=="canada" || $parts1[2]=="europe" || $parts1[2]=="asia-and-pacific")
				{
					$sta = $parts1[2]."-".$sta;
				}
				$sta1 = $sta;
				$manufacturerid=13444;
			}

			if($sta1 == 'trails-Texas' || $sta1 == 'trails-California' || $sta1 == 'trails-texas' || $sta1 == 'trails-california')
			{
				$usafe = "c.url_safe_category_name like '$sta-%'";
			}
			else if($sta1 == 'trails-Colorado' || $sta1 == 'trails-colorado')
			{
				$usafe = " c.url_safe_category_name in ('front-range','western-slope')";
			}
			else if($sta1 == 'Texas' || $sta1 == 'California' || $sta1 == 'texas' || $sta1 == 'california')
			{
				$usafe = "c.url_safe_category_name like '$sta-%'";
			}
			else if($sta1 == 'Colorado' || $sta1 == 'colorado')
			{
				$usafe = " c.url_safe_category_name in ('front-range','western-slope')";
			}
			else
			{
				$usafe = "c.url_safe_category_name = '$sta1'";
			}

				$this->query = "Select p.productid, p.product_name, p.total_reviews, p.average_rating, p.product_image, p.quickrating_average, p.quickrating_count, p.url_safe_product_name, (select avg(review_rating) from products_site_reviews wsr Where wsr.productid = p.productid) as web_score_rating, (select sum(review_count) From products_site_reviews wsc where wsc.productid = p.productid) As web_score_count,(select attribute_value from attribute_values avs Where avs.productid = p.productid and avs.attributeid = 343) as latitude, (select attribute_value from attribute_values avs1 Where avs1.productid = p.productid and avs1.attributeid = 342) as longitude, '$sta' as state ,av_ct.attribute_value As city from products p inner join categories c on p.categoryid = c.categoryid Left join attribute_values av_ct on p.productid = av_ct.productid Where $usafe and p.manufacturerid =$manufacturerid and av_ct.attributeid = 69 Group by p.productid, p.product_name, p.total_reviews, p.average_rating, p.quickrating_average, p.quickrating_count, p.url_safe_product_name order by p.product_name limit $offset, 50";

			//$this->db->show_errors();
			return $this->db->get_results( $this->query, object );
		}




		function get_site_location_search($search) {
			$this->query = "Select p.product_name, p.url_safe_product_name, p.combined_average, p.total_reviews,
						av_st.attribute_value as state, av_ct.attribute_value as city from products p
						left join attribute_values av_st on p.productid = av_st.productid
						left join attribute_values av_ct on p.productid = av_ct.productid
						left join attribute_values av_zp on p.productid = av_zp.productid
						where p.manufacturerid = 8051 and p.product_name like '%$search[search]%'
						and av_st.attributeid = 70 and av_ct.attributeid = 69
						group by p.product_name, p.url_safe_product_name, p.combined_average,
						p.total_reviews, av_st.attribute_value, av_ct.attribute_value
						order by p.product_name ";

			//$this->db->show_errors();
			return $this->db->get_results( $this->query, object );
		}

		/*** Get location from name ***/

		function get_site_location($course) {

			$this->query = "Select p.productid, p.product_name, product_description, p.total_reviews,
					p.average_rating, p.product_image, p.quickrating_count, p.quickrating_average,
					p.combined_average, p.url_safe_product_name, p.articleid, (select attribute_value
					From attribute_values av where av.productid = p.productid and av.attributeid = 69)
					as city, (select attribute_value from attribute_values av
					Where av.productid = p.productid and av.attributeid = 70) as state
					From products p left join attribute_values av on p.productid = av.productid
					Where p.manufacturerid = 8051 And p.url_safe_product_name ='$course[name]'";

			/*
			"Select p.productid, p.product_name, product_description, p.total_reviews,
					p.average_rating, p.product_image, p.quickrating_count, p.quickrating_average,
					p.combined_average, p.url_safe_product_name, p.articleid, (select attribute_value
					From attribute_values av where av.productid = p.productid and av.attributeid = 69)
					as city, (select attribute_value from attribute_values av
					Where av.productid = p.productid and av.attributeid = 70) as state
					From products p left join attribute_values av on p.productid = av.productid
					Where p.manufacturerid = 8051  And p.url_safe_product_name ='$course[name]'";	*/

			//$this->db->show_errors();
			return $this->db->get_row( $this->query, object );

		}

/*** Get location from name ***/

		function mtbr_get_site_location($course,$parts1) {
			$sta = $parts1[3];
			if($parts1[1]=='trails')
			{
				if($parts1[2]=="canada" || $parts1[2]=="europe" || $parts1[2]=="asia-and-pacific")
				{
					$sta = $parts1[2]."-".$sta;
				}
				$manufacturerid = 9668;
				$sta1 = "trails-".$sta;
			}
			else if($parts1[1]=='bikeshops')
			{
				if($parts1[2]=="canada" || $parts1[2]=="europe" || $parts1[2]=="asia-and-pacific")
				{
					$sta = $parts1[2]."-".$sta;
				}
				$sta1 = $sta;
				$manufacturerid = 13444;
			}
			if($sta1 == 'trails-Texas' || $sta1 == 'trails-California' || $sta1 == 'trails-texas' || $sta1 == 'trails-california')
			{
				$usafe = "c.url_safe_category_name like '$sta-%'";
			}
			else if($sta1 == 'trails-Colorado' || $sta1 == 'trails-colorado')
			{
				$usafe = " c.url_safe_category_name in ('front-range','western-slope')";
			}
			else if($sta1 == 'Texas' || $sta1 == 'California' || $sta1 == 'texas' || $sta1 == 'california')
			{
				$usafe = "c.url_safe_category_name like '$sta-%'";
			}
			else if($sta1 == 'Colorado' || $sta1 == 'colorado')
			{
				$usafe = " c.url_safe_category_name in ('front-range','western-slope')";
			}
			else
			{
				$usafe = "c.url_safe_category_name = '$sta1'";
			}

			$this->query = "Select p.productid, p.product_name, product_description, p.total_reviews,p.average_rating, p.product_image,p.quickrating_count,p.quickrating_average, p.combined_average, p.url_safe_product_name, p.articleid, (select attribute_value From attribute_values av, products p1 where av.productid = p1.productid and av.attributeid = 69 and p1.productid = p.productid) as city, '$sta' as state From products p inner join categories c on c.categoryid = p.categoryid Where p.manufacturerid = $manufacturerid and $usafe  And p.url_safe_product_name ='$course[name]';";

			return $this->db->get_row( $this->query, object );

		}



		/*** Get location by ID */

		function get_site_location_by_id($id) {

			$this->query = "Select p.productid, p.product_name, product_description, p.total_reviews,
					p.average_rating, p.product_image, p.quickrating_count, p.quickrating_average,
					p.combined_average, p.url_safe_product_name, (select attribute_value
					From attribute_values av where av.productid = p.productid and av.attributeid = 69) as city,
					(select attribute_value from attribute_values av where av.productid = p.productid and
					av.attributeid = 70) as state from products p
					Left join attribute_values av on p.productid = av.productid
					Where p.productid = '$id'";

			//$this->db->show_errors();
			return $this->db->get_row( $this->query, object );
		}

		/*** Get location from name ***/

		function get_site_location_detail($course) {

			$parts1 = explode('/',$_SERVER[REQUEST_URI]);

			$newparts = explode('.',$parts1[5]);

			if($parts1[1]=="trails")
			{
				$manuid = "9668";
			$this->query = "select attributeid, attribute_value,'$parts1[3]' as state from products p, attribute_values av where p.productid = av.productid and p.manufacturerid = $manuid and p.url_safe_product_name ='$newparts[0]'";
			}
			else if($parts1[1]=="bikeshops")
			{
				$manuid = "13444";
			$this->query = "select attributeid, attribute_value,'$parts1[3]' as state from products p, attribute_values av where p.productid = av.productid and p.manufacturerid = $manuid and p.url_safe_product_name ='$newparts[0]'";
			}
			else
			{
			/*$newparts = explode('.',$parts1[5]);*/
				$manuid = "8051";
			$this->query = "select attributeid, attribute_value,'$parts1[3]' as state from products p, attribute_values av where p.productid = av.productid and p.manufacturerid = $manuid and p.url_safe_product_name ='$newparts[0]'";
			}


			//$this->db->show_errors();
			return $this->db->get_results( $this->query, object );
		}

		/*** get_product_permalink() - get product permalink info from product id*/
		function get_product_permalink($id) {

			$this->query = "SELECT p.url_safe_product_name,p.categoryid,p.manufacturerid,c.url_safe_category_name, ch.category_path,m.url_safe_manufacturer_name FROM products p JOIN categories c ON p.categoryid = c.categoryid JOIN manufacturers m ON p.manufacturerid = m.manufacturerid JOIN channel_categories ch on ch.categoryid = c.categoryid WHERE p.productid = '$id'";

			//$this->db->show_errors();
			return $this->db->get_row( $this->query, object );
		}

		function cr_do_qik_rate($id,$rating) { // Zaved changed to cr_ to cr_ as on 02/Dec/2016

			$this->query = "Select count(ratingid) as rated from rating Where foreignid = '$id'
							And ipaddress = '$_SERVER[REMOTE_ADDR]'";

			$CheckRated = $this->db->get_row($this->query);

			// if not rated product yet //
			if($CheckRated->rated == 0) {

				$this->db->query("INSERT INTO rating (foreignid, typeid, rate_value, ipaddress, username, vbulletinid)
								VALUES ('$id','1','$rating','$_SERVER[REMOTE_ADDR]','','')");

				// get current quick ratings
				$this->result	= "Select count(ratingid) as total, sum(rate_value) as points from rating where foreignid = '$id'";
				$Quick_Points	= $this->db->get_row($this->result);

				// get current review ratings
				$this->result	= "Select total_reviews, average_rating from products where productid = '$id'";
				$Review_ratings = $this->db->get_row($this->result);

				// set review ratings into Product object
				if($Review_ratings) {

					$Product->average_rating	= $Review_ratings->average_rating;
					$Product->total_reviews		= $Review_ratings->total_reviews;

				} else {

					// if no review ratings, set values to 0
					$Product->average_rating	= 0;
					$Product->total_reviews		= 0;
				}

				// new quick rating values

				if(!$Quick_Points) $Quick_Points->points = 0;
				if(!$Quick_Points) $Quick_Points->total = 0;

				$Product->quickrating_average 	= round($Quick_Points->points/$Quick_Points->total,1);
				$Product->quickrating_count 	= $Quick_Points->total;

				// get current external ratings
				$external_ratings = $this->get_external_product_ratings($id);

				// put external ratings into Product object
				if($external_ratings && is_numeric($external_ratings->web_score_count) && is_numeric($external_ratings->web_score_rating)) {

					$Product->web_score_count	= $external_ratings->web_score_count;
					$Product->web_score_rating	= $external_ratings->web_score_rating;

				} else {

					$Product->web_score_count	= 0;
					$Product->web_score_rating	= 0;
				}

				// get total ratings and averages
				$Product->total_rating			= cr_get_overall_rating_average($Product);
				$Product->total_rating_count	= cr_get_overall_rating_count($Product);
				$this->db->query("Update products set quickrating_count = '$Quick_Points->total', quickrating_average = '$Product->quickrating_average' Where productid = '$id'");

				// return new overall count, overall rating, quick count, quick rating
				return $Product->total_rating_count . '|' . $Product->total_rating . '|' . $Product->quickrating_count . '|' . $Product->quickrating_average;

			} else {

				return false;
			}
		}

		/*** Pagination function for category list page*/

		function submit_product_review($post) {

			if ( strtolower($post['6_letters_code']) != $_SESSION['captcha_id']) {

				return false;

			} else {

				/** Escape single quotes **/
				foreach($post as $key => $val) {

					$post[$key] = str_replace("'","''",$val);
				}

				$this->query = "INSERT INTO reviews (

					reviewerip, reviewer_email, productid, model, channelid, value_rating,
					overall_rating, customer_service, summary, reviewer_experience,
					similar_products, user_screen_name,	valid
				)
				VALUES (

					'$_SERVER[REMOTE_ADDR]', '$post[user_email]', '$post[ProductID]',
					'$post[model_reviewed]', '$post[PSite]', '$post[Value_Rating]', '$post[Overall_Rating]',
					'$post[Customer_Service]', '$post[Summary]', '$post[reviewer_experience]',
					'$post[Similar_Products]', '$post[user_name]', 1
				)";

				$this->db->query($this->query);

				/**
				* Get current review and QIK ratings from product to add new rating
				* and update average ratings for product
				*/

				$this->result 	= "Select total_reviews, quickrating_count from products where productid = '$post[ProductID]'";
				$Review 		= $this->db->get_row( $this->result, object );

				if($Review) $Review->total_reviews++;

				else $Review->total_reviews = 1;

				$this->result = "Select sum(rate_value) as points from rating where foreignid = '$post[ProductID]'";
				$Quick_Points = $this->db->get_row( $this->result, object );

				if(!$Quick_Points) $Quick_Points->points = 0;

				$this->result	= "Select sum(overall_rating) as points from reviews where productid = '$post[ProductID]'";
				$Review_Points	= $this->db->get_row( $this->result, object );

				if(!$Review_Points) $Review_Points->points = 0;

				$New['average_rating'] = round($Review_Points->points/$Review->total_reviews,2);

				$combinePoints	= $Review_Points->points + $Quick_Points->points;
				$combineRatings = $Review->total_reviews + $Review->quickrating_count;

				$New['combined_rating'] = round($combinePoints/$combineRatings, 2);

				$this->db->query("UPDATE products SET total_reviews = '$Review->total_reviews',
								average_rating = '$New[average_rating]', combined_average = '$New[combined_rating]'
								WHERE productid = '$post[ProductID]'");
				return true;
			}
		}

		/*** Pagination function for category list page ****/

		function cr_category_pagination_components( $category, $current, $perpage=50 ) {
			$current = (!is_numeric($_GET['pg'])) ? 1 : $current;
			$totalPages = ceil($category->product_count/$perpage);

			if($totalPages > 1)
			{
				echo 'Show ';
				$prev = $current - 1;
				$next = $current + 1;

				if(isset($_GET['sort']))
				{
						$sort="&sort=".$_GET['sort'];
				}

				if($current != 1)
					echo ' <a   class="BtnWriteReview" id="featured-nav"  style="font-weight: normal;text-decoration:none;padding: 4px;border-radius: 2px;color: #fff;" href="' . $category->url_safe_category_name.".html". '?pg='.$prev.$sort.'">Prev 50</a> ';
				else echo '';

				if($current != $totalPages)
					echo ' <a  class="BtnWriteReview" id="featured-nav" style="font-weight: normal;text-decoration:none;padding: 4px;/*background-color: rgb(9, 111, 0);*/border-radius: 2px;color: #fff;" href="' . $category->url_safe_category_name.".html". '?pg='.$next.$sort.'">Next 50</a>';
			}
		}


		function cr_category_pagination_outdoor( $category, $current, $perpage=50 ) {
			$current = (!is_numeric($_GET['pg'])) ? 1 : $current;
			$totalPages = ceil($category->product_count/$perpage);

			if($totalPages > 1)
			{
				echo 'Show ';
				$prev = $current - 1;
				$next = $current + 1;

				if($current != 1)
					echo ' <a   class="BtnWriteReview" id="featured-nav"  style="font-weight: normal;text-decoration:none;padding: 4px;border-radius: 2px;color: #fff;" href="' . $category->url_safe_category_name.".html". '?pg='.$prev.'">Prev 50</a> ';
				else echo '';

				if($current != $totalPages)
					echo ' <a  class="BtnWriteReview" id="featured-nav" style="font-weight: normal;text-decoration:none;padding: 4px;/*background-color: rgb(9, 111, 0);*/border-radius: 2px;color: #fff;" href="' . $category->url_safe_category_name.".html". '?pg='.$next.'">Next 50</a>';
			}
		}

		function cr_category_pagination_new( $category, $current, $perpage=50 ) {
			$current = (!is_numeric($_GET['pg'])) ? 1 : $current;
			$totalPages = ceil($category->product_count/$perpage);

			if($totalPages > 1)
			{
				echo 'Show ';
				$prev = $current - 1;
				$next = $current + 1;

				if($current != 1)
					echo ' <a   class="BtnWriteReview" id="featured-nav"  style="font-weight: normal;text-decoration:none;padding: 4px;border-radius: 2px;color: #fff;" href="' . $category->url_safe_category_name.".html". '?pg='.$prev.'">Prev 50</a> ';
				else echo '';

				if($current != $totalPages)
					echo ' <a  class="BtnWriteReview" id="featured-nav" style="font-weight: normal;text-decoration:none;padding: 4px;/*background-color: rgb(9, 111, 0);*/border-radius: 2px;color: #fff;" href="' . $category->url_safe_category_name.".html". '?pg='.$next.'">Next 50</a>';
			}
		}


		/*** Pagination function for category list page ****/

		function cr_category_pagination( $category, $current, $perpage=50 ) {
			$current = (!is_numeric($_GET['pg'])) ? 1 : $current;
			$totalPages = ceil($category->product_count/$perpage);

			if($totalPages > 1)
			{
				echo 'Show ';
				$prev = $current - 1;
				$next = $current + 1;

				if($current != 1)
					echo ' <a   class="BtnWriteReview" id="featured-nav"  style="font-weight: normal;text-decoration:none;padding: 4px;border-radius: 2px;color: #fff;" href="' . cr_category_url_from_cat($category->url_safe_category_name, 1) . '?pg='.$prev.'">Prev 50</a> ';
				else echo '';

				if($current != $totalPages)
					echo ' <a  class="BtnWriteReview" id="featured-nav" style="font-weight: normal;text-decoration:none;padding: 4px;/*background-color: rgb(9, 111, 0);*/border-radius: 2px;color: #fff;" href="' . cr_category_url_from_cat($category->url_safe_category_name, 1) . '?pg='.$next.'">Next 50</a>';
			}
		}






		/*** Pagination function for sub category sub pagelist page ****/

		function cr_sub_category_sub_pagination_sub_fourth( $category, $current, $perpage=50 ) {
			$UrlCat	= explode( '/', $_SERVER['REQUEST_URI']);
			$subcat = explode(".",$UrlCat[4]);
			$current = (!is_numeric($_GET['pg'])) ? 1 : $current;
			$totalPages = ceil($category->product_count/$perpage);

			if($totalPages > 1)
			{
				echo 'Show ';
				$prev = $current - 1;
				$next = $current + 1;

				if($current != 1)
					echo ' <a   class="BtnWriteReview" id="featured-nav"  style="font-weight: normal;text-decoration:none;padding: 4px;border-radius: 2px;color: #fff;" href="' . $subcat[0] . '.html?pg='.$prev.'">Prev 50</a> ';
				else echo '';

				if($current != $totalPages)
					echo ' <a  class="BtnWriteReview" id="featured-nav" style="font-weight: normal;text-decoration:none;padding: 4px;/*background-color: rgb(9, 111, 0);*/border-radius: 2px;color: #fff;" href="' . $subcat[0] . '.html?pg='.$next.'">Next 50</a>';
			}
		}
		/*** Pagination function for sub category sub pagelist page ****/

		function cr_sub_category_sub_pagination_sub_sub_cat( $category, $current, $perpage=50 ) {
			$UrlCat	= explode( '/', $_SERVER['REQUEST_URI']);
			$subcat = explode(".",$UrlCat[3]);
			$current = (!is_numeric($_GET['pg'])) ? 1 : $current;
			$totalPages = ceil($category->product_count/$perpage);

			if($totalPages > 1)
			{
				echo 'Show ';
				$prev = $current - 1;
				$next = $current + 1;

				if($current != 1)
					echo ' <a   class="BtnWriteReview" id="featured-nav"  style="font-weight: normal;text-decoration:none;padding: 4px;border-radius: 2px;color: #fff;" href="' . $subcat[0] . '.html?pg='.$prev.'">Prev 50</a> ';
				else echo '';

				if($current != $totalPages)
					echo ' <a  class="BtnWriteReview" id="featured-nav" style="font-weight: normal;text-decoration:none;padding: 4px;/*background-color: rgb(9, 111, 0);*/border-radius: 2px;color: #fff;" href="' . $subcat[0] . '.html?pg='.$next.'">Next 50</a>';
			}
		}
		/*** Pagination function for sub category sub pagelist page ****/

		function cr_sub_category_sub_pagination_sub_cat( $category, $current, $perpage=50 ) {

			$current = (!is_numeric($_GET['pg'])) ? 1 : $current;
			$totalPages = ceil($category->product_count/$perpage);

			if($totalPages > 1)
			{
				echo 'Show ';
				$prev = $current - 1;
				$next = $current + 1;

				if($current != 1)
					echo ' <a   class="BtnWriteReview" id="featured-nav"  style="font-weight: normal;text-decoration:none;padding: 4px;border-radius: 2px;color: #fff;" href="' . $category->url_safe_category_name . '-subcat.html?pg='.$prev.'">Prev 50</a> ';
				else echo '';

				if($current != $totalPages)
					echo ' <a  class="BtnWriteReview" id="featured-nav" style="font-weight: normal;text-decoration:none;padding: 4px;/*background-color: rgb(9, 111, 0);*/border-radius: 2px;color: #fff;" href="' . $category->url_safe_category_name . '-subcat.html?pg='.$next.'">Next 50</a>';
			}
		}
		function cr_sub_category_sub_pagination( $category, $current, $perpage=50 ) {

			$current = (!is_numeric($_GET['pg'])) ? 1 : $current;
			$totalPages = ceil($category->product_count/$perpage);

			if($totalPages > 1)
			{
				echo 'Show ';
				$prev = $current - 1;
				$next = $current + 1;

				if($current != 1)
					echo ' <a   class="BtnWriteReview" id="featured-nav"  style="font-weight: normal;text-decoration:none;padding: 4px;border-radius: 2px;color: #fff;" href="' . cr_category_url_from_cat_sub($category->url_safe_category_name, 1) . '?pg='.$prev.'">Prev 50</a> ';
				else echo '';

				if($current != $totalPages)
					echo ' <a  class="BtnWriteReview" id="featured-nav" style="font-weight: normal;text-decoration:none;padding: 4px;/*background-color: rgb(9, 111, 0);*/border-radius: 2px;color: #fff;" href="' . cr_category_url_from_cat_sub($category->url_safe_category_name, 1) . '?pg='.$next.'">Next 50</a>';
			}
		}

		/*** Output of 'Showing X-X+50 of Category'*/

		function cr_category_showing($category,$current,$perpage=50) {

			$current = (is_numeric($current)) ? $current : 1;
			$max = ceil($category->product_count/$perpage);
			$start = $current * $perpage - 49;
			if($current == $max) $end = $category->product_count;
			else $end = $current * $perpage;
			echo $start . '-' . $end;
		}

		/* Zaved Commented as on 5 Dec 2016
		function category_permalink_to_db($cat) { // These string does not get changed beacused this is only case for golf equipment.

			/** * Change website category permalink term to database permalink */
			/*$str = array('golf-balls','golf-shoes','golf-bags');
			$rep = array('balls','shoes','bags');
			return str_replace($str, $rep, $cat);
		}
		*/

		function do_review_rating_distribution($reviews,$Ratings) {

			foreach($reviews as $r) {

				$Ratings[$r->Overall_Rating] = $Ratings[$r->Overall_Rating] + 1;
			}

			return $Ratings;
		}

		function parse_url() {

			$return = false;

			//    /brakes/ac/brake-lever/magnum-resdsds.html
			$parts = explode('/',$_SERVER['REQUEST_URI']);

			if($parts[4] != '') {

				$returnparts = explode('.html',$parts[4]);

				//Product Name
				$return['product'] = $returnparts[0];

				//Product Category
				$return['product_category'] = $parts[3];
			}
			if($parts[2] != '') {

				$c_parts1 = explode(".html",$parts[2]);
				$c_parts = explode("-subcat",$c_parts1[0]);
				$return['category'] = $c_parts[0];
			}

			$return['base'] = $parts[1];
			return $return;
		}

		function parse_url_others() {

			$return = false;

			//    /brakes/ac/brake-lever/magnum-resdsds.html
			$parts = explode('/',$_SERVER['REQUEST_URI']);

			if($parts[4] != '') {

				$returnparts = explode('.html',$parts[4]);

				//Product Name
				$return['product'] = $returnparts[0];

				//Product Category
				$return['product_category'] = $parts[3];
			}
			if($parts[2] != '') {

				$c_parts = explode(".html",$parts[2]);
				$return['category'] = $c_parts[0];
			}

			$return['base'] = $parts[1];
			return $return;
		}

		function parse_brand_url() {

			$return	= false;
			$parts	= explode('/',$_SERVER['REQUEST_URI']);
			//print_r($parts);

			if($parts[2] != '') {

				$returnparts = explode(".html",$parts[3]);
				$return['category'] = $returnparts[0];
			}

			if($parts[1] != '') {

				$brand = ( $parts[2] == 'brand' ) ? $parts[3] : $parts[2] ; //added for main category.
				$returnparts = explode(".html", $brand);
				$return['brand'] = $returnparts[0];
			}

			return $return;
		}

		function parse_review_url() {

			$re = explode("?", str_replace(".html","-review.html", $_SERVER['REQUEST_URI']) );
			return $re[0];
		}

		function parse_product_review_url_outdoor() {

			$return['is_course'] = false;
			$parts = explode('/',$_SERVER['REQUEST_URI']);


				$name = explode('.html',$parts[count($parts)-1]);
				$return['product'] = str_replace("-review","",$name[0]);
				$return['product_category'] = $parts[count($parts)-3];
				$return['category'] = $parts[count($parts)-2];
				if($parts[2] == 'golf-courses') $return['is_course'] = true;	//Pennding to have redesign review pages.

			return $return;
		}
		function parse_product_review_url() {

			$return['is_course'] = false;
			$parts = explode('/',$_SERVER['REQUEST_URI']);

			if($parts[4] != '') {

				$name = explode('.html',$parts[4]);
				$return['product'] = str_replace("-review","",$name[0]);
				$return['product_category'] = $parts[3];
				$return['category'] = $parts[2];
				if($parts[1] == 'golf-courses') $return['is_course'] = true;	//Pennding to have redesign review pages.
			}
			return $return;
		}

		function golf_parse_product_review_url() {

			$return['is_course'] = false;
			$parts = explode('/',$_SERVER['REQUEST_URI']);

			if($parts[1] == 'golf-courses') {
				$name = explode('.html',$parts[5]);
				$return['product'] = str_replace("-review","",$name[0]);
				$return['product_category'] = $parts[4];
				$return['category'] = $parts[3];
				$return['is_course'] = true;
				}

				elseif($parts[4] != '') {
				//for golf products
				$name = explode('.html',$parts[4]);
				$return['product'] = str_replace("-review","",$name[0]);
				$return['product_category'] = $parts[3];
				$return['category'] = $parts[2];
			}
			return $return;
		}



		function mtbr_parse_product_review_url() {

			$return['is_course'] = false;
			$parts = explode('/',$_SERVER['REQUEST_URI']);

			if($parts[5] != '') {

				$name = explode('.html',$parts[5]);
				$return['product'] = str_replace("-review","",$name[0]);
				$return['product_category'] = $parts[4];
				$return['category'] = $parts[3];
				if($parts[1] == 'trails') $return['is_course'] = true;	//Pennding to have redesign review pages.
				if($parts[1] == 'bikeshops') $return['is_course'] = true;	//Pennding to have redesign review pages.

			}
			return $return;
		}

		function parse_state_url() {

			$parts = explode('/',$_SERVER['REQUEST_URI']);

			if(str_replace(".html","",$parts[2]) == 'public-golf-courses' ||
				str_replace(".html","",$parts[2]) == 'driving-ranges' ||
				str_replace(".html","",$parts[2]) == 'best-golf-courses') {

				$return['state'] = str_replace(".html","",$parts[3]);
				$return['course_type'] = $parts[2];

			} else {

				$return['state'] = str_replace(".html","",$parts[2]);
				$return['course_type'] = $parts[1]; // Changed By Zaved as on 22/Nov/2016
			}

			$return['state'] = cr_state_abbr($return['state']);
			return $return;
		}

		function parse_state_city_url() {

			$return = false;
			$parts = explode('/',$_SERVER['REQUEST_URI']);
			if($parts[3] != '') {

				$returnparts = explode('.',$parts[3]);
				$return['product'] = $returnparts[0];
			}
			if($parts[2] != '') {

				$c_parts = explode(".",$parts[2]);
				$return['category'] = $c_parts[0];
			}

			return $return;
		}

		function parse_city_permalink($city) {

			return str_replace(" ","-",$city);
		}

		function parse_city_name($city) {

			return str_replace("-"," ",$city);
		}

		function parse_location_url() {

			$parts = explode('/',$_SERVER['REQUEST_URI']);

			if( str_replace(".html","",$parts[2]) == 'public-golf-courses' ||
				str_replace(".html","",$parts[2]) == 'driving-ranges' ||
				str_replace(".html","",$parts[2]) == 'best-golf-courses' ) {

					if($parts[5] != '') {

						$name = explode(".html",$parts[5]);
						$return['name'] = $name[0];
					}
					if($parts[4] != '') {

						$return['city'] = str_replace(".html","",$parts[4]);
						$return['state'] = $parts[3];
					}
					else {

						$return['zip'] = str_replace(".html","",$parts[3]);
					}

					$return['course_type'] = str_replace(".html","",$parts[2]);
				}
				else {

					if($parts[5] != '') {

						$name = explode(".html",$parts[5]);
						$return['name'] = $name[0];
					}
					if($parts[4] != '') {

						$return['city'] = str_replace(".html","",$parts[4]);
						$return['state'] = $parts[3];
						$return['country'] = $parts[2];

					} else {

						$return['zip'] = str_replace(".html","",$parts[3]);
					}

					$return['course_type'] =  str_replace(".html","",$parts[1]); // Added by Zaved as on 22/Nov/2016
				}

			if($parts[2]=='united-states'){
			$return['state'] = cr_state_abbr($return['state']);}
			return $return;
		}



// mtbr trails
		function mtbr_parse_state_url() {

			$parts = explode('/',$_SERVER['REQUEST_URI']);

			if(str_replace(".html","",$parts[3]) == 'public-golf-courses' ||
				str_replace(".html","",$parts[3]) == 'driving-ranges' ||
				str_replace(".html","",$parts[3]) == 'best-golf-courses') {

				$return['state'] = str_replace(".html","",$parts[3]);
				$return['course_type'] = $parts[3];

			} else {

				$return['state'] = str_replace(".html","",$parts[3]);
				$return['course_type'] = $parts[2]; // Changed By Zaved as on 22/Nov/2016
			}

			$return['state'] = mtbr_cr_state_abbr($return['state']);
			return $return;
		}

		function mtbr_parse_state_city_url() {

			$return = false;
			$parts = explode('/',$_SERVER['REQUEST_URI']);
			if($parts[3] != '') {

				$returnparts = explode('.',$parts[3]);
				$return['product'] = $returnparts[0];
			}
			if($parts[2] != '') {

				$c_parts = explode(".",$parts[2]);
				$return['category'] = $c_parts[0];
			}

			return $return;
		}

		function mtbr_parse_city_permalink($city) {

			return str_replace(" ","-",$city);
		}

		function mtbr_parse_city_name($city) {

			return str_replace("-"," ",$city);
		}





		function mtbr_parse_location_url() {

			$parts = explode('/',$_SERVER['REQUEST_URI']);

			if( str_replace(".html","",$parts[2]) == 'public-golf-courses' ||
				str_replace(".html","",$parts[2]) == 'driving-ranges' ||
				str_replace(".html","",$parts[2]) == 'best-golf-courses' ) {

					if($parts[6] != '') {

						$name = explode(".html",$parts[5]);
						$return['name'] = $name[0];
					}
					if($parts[5] != '') {

						$return['city'] = str_replace(".html","",$parts[4]);
						$return['state'] = $parts[3];
					}
					else {

						$return['zip'] = str_replace(".html","",$parts[3]);
					}

					$return['course_type'] = str_replace(".html","",$parts[2]);
				}
				else {

					if($parts[5] != '') {

						$name = explode(".html",$parts[5]);
						$return['name'] = $name[0];
					}
					if($parts[4] != '') {

						$return['city'] = str_replace(".html","",$parts[4]);
						$return['state'] = $parts[3];

					} else {

						$return['zip'] = str_replace(".html","",$parts[2]);
					}

					$return['course_type'] =  str_replace(".html","",$parts[1]); // Added by Zaved as on 22/Nov/2016
				}

			$return['state'] = cr_state_abbr($return['state']);
			return $return;
		}

		function get_site_review_page($part) {

			if(strpos("page",$part)) {

				$thepage = explode(".html",str_replace("page","",$part));
				return $thepage[0];

			} else {

				return 1;
			}
		}

		function get_site_review_location() {

			return $this->currentpage;
		}

		function set_site_review_location($p) {

			$this->currentpage = $p;
		}

		function get_golfreview_meta_vars() {

			return $this->meta_vars;
		}

		function set_site_review_meta_vars($vars) {

			$this->meta_vars = $vars;
		}
	}
?>
