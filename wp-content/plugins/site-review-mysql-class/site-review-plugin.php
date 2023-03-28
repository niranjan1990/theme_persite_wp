<?Php

	/**
	Plugin Name: Mysql Content Plugin
	Plugin URI:		http://www.invenda.com
	Version: 1.0
	Description: Read venice database content and load into Wordpress Theme.
	Author: ConsumerReview
	Author URI: http://www.invenda.com
	**/

	require_once 'config.php';

	$GRclass = new SiteReviewClass();

	/**Product Views on Index Page **/
	function site_product_views_page($PSite) {

		global $GRclass, $Product;
			$views = $GRclass->get_top_product_views($PSite);
			return $views;
	}
	/**

	/**Product Reviews on Index Page **/
	function site_product_reviews_page($PSite) {

		global $GRclass, $Product;
			$reviews = $GRclass->get_top_product_reviews($PSite);
			return $reviews;
	}


	// To get address from zipcode
 	
 	
 	
	function getaddress($zipcode)
	{
	 $doc = new DOMDocument();
	  $url = 'https://maps.googleapis.com/maps/api/geocode/xml?address='.$zipcode.'&sensor=true&key=AIzaSyAJjShDxiZKFTCkv0YwWuiTmZ9BNzEh68M';
	  $doc->load($url);
	  $result = $doc->getElementsByTagName( "address_component" );
	  $i=0;
	  foreach( $result as $address_component )
	  {
		  $short_names = $address_component->getElementsByTagName( "long_name" );
		  $short_name = $short_names->item(0)->nodeValue;
		  if($i==1)
		  {
			echo  $city=$short_name;
			 //break;  
			 echo ", ";
		  }
		  if($i==2)
		  {
			echo  $city=$short_name;
			echo "<br>";
			 break;  
		  }
		  
		  $i++;

	  }
	}
	
	


	function cookie_redirect ($url)
	{
		global $bb_pre_url;
		if (strpos($url, '.gif') !== false || strpos($url, '.ico') !== false || strpos($url, '.js') !== false || strpos($url, '.png') !== false || strpos($url, '.jpg') !== false ||  strpos($url, '.css') !== false)
		{
			$bb_pre_url = get_site_url();
		}
		else
		{
			$bb_pre_url = $url;
		}

		return $bb_pre_url;
	}



	/**

	* @function site_review_product_page(): gets all product info for single product
	* along with reviews of product.
	* @return global vars: $Product object, $Reviews object array*/

	function site_review_product_page_outdoor_product() {
		//$item['product_category']},{$item['category'],$item[product]
		global $GRclass, $Product, $Reviews; /* $ProductRatings, $Ratings */
		/** Parse URL */
		$parts = $GRclass->parse_url();

		// Product Channel ID
		$PSite = explode(":", get_option( 'PSiteName' ) ); // Added by Zaved as on 16/Nov/2016

		// Course channel ID
		$SSite = explode(":", get_option( 'SSiteName' ) ); //Added by Zaved as on 24/Nov/2016

		// Course channel ID
		$TSite = explode(":", get_option( 'TSiteName' ) ); //Added by Zaved as on 24/Nov/2016


		//echo $PSite[0];
		//echo $SSite[0];
		/** Product Info */
		$pname = explode('/',$_SERVER[REQUEST_URI]);
		$parray_name = explode(".",$pname[count($pname)-1]);

		$parray['product']=$parray_name[0]; //product name
		$parray['product_category']=$pname[count($pname)-3]; //;ast level category
		$parray['category']=$pname[count($pname)-2]; //brand name

		//print_r($parray);
		$Product = $GRclass->get_product_detail( $parray, $PSite[0], $SSite[0], $TSite[0] );
		//print_r($Product);

		/** If product exists, get Product reviews */
		if($Product) {

			$Reviews = $GRclass->get_product_reviews($Product->productid,$_GET['p']);
			$views = $GRclass->update_product_views($Product->productid);

			$Product->review_url	= $GRclass->parse_review_url();
			$Product->product_page	= true;

			// product external ratings
			$external_ratings = $GRclass->get_external_product_ratings($Product->productid);

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

			/** get product media **/
			$Product->media = $GRclass->get_product_media($Product->productid);
		}
	}

	function site_review_product_page() {

		global $GRclass, $Product, $Reviews; /* $ProductRatings, $Ratings */
		/** Parse URL */
		$parts = $GRclass->parse_url();

		// Product Channel ID
		$PSite = explode(":", get_option( 'PSiteName' ) ); // Added by Zaved as on 16/Nov/2016

		// Course channel ID
		$SSite = explode(":", get_option( 'SSiteName' ) ); //Added by Zaved as on 24/Nov/2016

		// Course channel ID
		$TSite = explode(":", get_option( 'TSiteName' ) ); //Added by Zaved as on 24/Nov/2016


		//echo $PSite[0];
		//echo $SSite[0];
		/** Product Info */
		$pname = explode('/',$_SERVER[REQUEST_URI]);

		$Product = $GRclass->get_product_detail( $parts, $PSite[0], $SSite[0], $TSite[0] );
		//print_r($Product);

		/** If product exists, get Product reviews */
		if($Product) {

			$Reviews = $GRclass->get_product_reviews($Product->productid,$_GET['p']);
			$views = $GRclass->update_product_views($Product->productid);

			$Product->review_url	= $GRclass->parse_review_url();
			$Product->product_page	= true;

			// product external ratings
			$external_ratings = $GRclass->get_external_product_ratings($Product->productid);

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

			/** get product media **/
			$Product->media = $GRclass->get_product_media($Product->productid);
		}
	}

	/*** Just test to make one template for both product ***/

	function Get_SubCatgeory_Link( $Categories ) {

		for($i=0;$i<count($Categories);$i++) {

			if(strpos($Categories[$i]->nodeid,'007.001') !== false && $Categories[$i]->node_level == 3) { ?>

				<a href="/<?php echo $Categories[$i]->category_path; ?>.html">
				<?php cr_category_name($Categories[$i]->category_name); ?> (
				<?php echo number_format($Categories[$i]->product_count); ?>
				)</a><?php

				$BrandList[] = str_replace(" ", "-", strtolower( $Categories[$i]->category_name ) );
				// get total product count of golf-equipment categories
				$Category->product_count += $Categories[$i]->product_count;
			}
		}

		$a['BrandList'] = $BrandList;
		$a['prod_count'] = $Category->product_count;

		return $a;
	}

	/**
	* @function cr_review_pagination(): determines current page and outputs next/prev pages for product reviews
	* @param $p: Current page
	* @param $total: total number of reviews
	* @return echo's paginated next/prev links */

	function cr_review_pagination($p,$total) {

		$page = (!is_numeric($p)) ? 1 : $p;
		$pre_page = $page-1;
		$next_page = $page+1;
		$total_pages = ceil($total/10);
		$cur_s = $page * 10 - 9;
		$cur_e = ($total > 10 && $total_pages != $page) ? $page * 10 : $total;

		if($total > 10) {

			echo '<div style="float:right;">';

			if($page > 1) echo '<a  class="BtnWriteReview" id="featured-nav"  style="    font-weight: normal;text-decoration:none;padding: 4px;border-radius: 2px;color: #fff;" href="?p='.$pre_page.'#reviews">Prev 10</a>';
			else echo '';

			if($page != $total_pages) echo ' <a  class="BtnWriteReview" id="featured-nav"  style="    font-weight: normal;text-decoration:none;padding: 4px;border-radius: 2px;color: #fff;" href="?p='.$next_page.'#reviews">Next 10</a>';

				echo '</div>';

					echo '<div class="label" style="float:right;">Showing '.$cur_s.'-'.$cur_e.' of '.$total.'&nbsp;&nbsp;</div>';
		} else {

			echo '<div style="float:right;">Showing 1-'.$total.' of '.$total.'&nbsp;&nbsp;</div>';
		}
	}

	function site_review_product_list_outdoor_sub() {

		global $GRclass, $Category, $Products, $Location;

		/** Parse URL */
		$parts1 = explode('/',$_SERVER['REQUEST_URI']);
		$partsnew=explode("-subcat",$parts1[3]);
		$parts['category'] = $partsnew[0];

		$PSite = explode(":", get_option( 'PSiteName' ) ); // Added by Zaved as on 14/Nov/2016
		$Category = $GRclass->get_category_detail_outdoor_sub( $parts, $PSite[0] );
		$Category->category_name = cr_category_name($Category->category_name,1);
		if($Category) {

			$Products = $GRclass->get_product_list( $parts, array(), $PSite[0] );

			if($Products) {

				for($i=0;$i<count($Products);$i++) {

					$Products[$i]->total_rating 		= cr_get_overall_rating_average($Products[$i]);
					$Products[$i]->total_rating_count	= cr_get_overall_rating_count($Products[$i]);
				}
			}

			if( !isset($Location) ) {

				$Location = (object)array();
			}

			$Location->Path = $parts['base'];
			$Location->Name = $GRclass->parse_city_name($parts['base']);
		}
	}


	function site_review_product_list() {

		global $GRclass, $Category, $Products, $Location;

		/** Parse URL */
		$parts = $GRclass->parse_url();
		//print_r($parts);
		/**
		* get and return object of category detail info
		* this function only when on a category specific page
		* Returns $Category object with values:
		* 	->CategoryID
		* 	->Category_Name
		* 	->Product_Count */

		$PSite = explode(":", get_option( 'PSiteName' ) ); // Added by Zaved as on 14/Nov/2016
		//echo $PSite[0].$PSite[1];
		$Category = $GRclass->get_category_detail( $parts, $PSite[0] );
		//print_r($Category);
		//exit;

		/*** Convert category name from DB to website*/
		$Category->category_name = cr_category_name($Category->category_name,1);

		/*** if category exists, get product list based on category ($type) value*/
		if($Category) {

			$Products = $GRclass->get_product_list( $parts, array(), $PSite[0] );
			//$Products = $GRclass->get_product_list($parts);

			if($Products) {

				for($i=0;$i<count($Products);$i++) {

					$Products[$i]->total_rating 		= cr_get_overall_rating_average($Products[$i]);
					$Products[$i]->total_rating_count	= cr_get_overall_rating_count($Products[$i]);
				}
			}

			if( !isset($Location) ) {

				$Location = (object)array();
			}

			$Location->Path = $parts1[1];
			$Location->Name = $parts1[1];
		}
	}

	function site_top_rated_product_list($limit)
	{
		$PSite = explode(":", get_option( 'PSiteName' ) ); // Added by Zaved as on 16/Nov/2016
		global $GRclass, $Products;
		$Products = $GRclass->site_top_rated_product_list($limit,$PSite);
	}
	function site_latest_rated_product_list($limit)
	{
		$PSite = explode(":", get_option( 'PSiteName' ) ); // Added by Zaved as on 16/Nov/2016
		global $GRclass, $Products;
		$Products = $GRclass->site_latest_rated_product_list($limit,$PSite);
	}
	function site_latest_product_list($limit)
	{
		$PSite = explode(":", get_option( 'PSiteName' ) ); // Added by Zaved as on 16/Nov/2016
		global $GRclass, $Products;
		$Products = $GRclass->site_latest_product_list($limit,$PSite);
	}


	function site_review_product_brand_list_outdoor_brand_subcat_four($cat=0) {

		global $GRclass, $Brand, $Products;

		/** Parse URL */
		$parts = $GRclass->parse_brand_url();
		//print_r($parts);
		$parts1 = explode( '/', $_SERVER['REQUEST_URI']);

		$brand_temp = explode(".",$parts1[count($parts1)-1]);
		$brand['brand'] =$brand_temp[0];
		$PSite = explode(":", get_option( 'PSiteName' ) ); // Added by Zaved as on 16/Nov/2016
		//print_r($PSite);



		if($cat == 1) {
			$Brand = $GRclass->get_product_brand_cat_list_total_outdoor_subcat_four($parts);
		}
		else {

			$Brand = $GRclass->get_product_brand_list_total($parts,$PSite[0]);
		}

		if($Brand && $cat == 1) {

			$Products = $GRclass->get_product_brand_cat_list_outdoor_subcat_four( $brand, $PSite[0] );

		}
		else if($Brand)
		{

			$Products = $GRclass->get_product_brand_list( $parts, $PSite[0] ); //changed for category/brand only

		}
		else {

			$Brand = (object)array();
			$Brand->manufacturer_name	= $parts['brand'];
			$Brand->category_name 		= $parts['category'];
		}

		if($Products) {

			for($i=0;$i<count($Products);$i++) {

				$Products[$i]->total_rating			= cr_get_overall_rating_average($Products[$i]);
				$Products[$i]->total_rating_count	= cr_get_overall_rating_count($Products[$i]);
			}
		}
	}


	function site_review_product_brand_list_outdoor_brand_subcat_three($cat=0) {

		global $GRclass, $Brand, $Products;

		/** Parse URL */
		$parts = $GRclass->parse_brand_url();
		//print_r($parts);
		$parts1 = explode( '/', $_SERVER['REQUEST_URI']);

		$brand_temp = explode(".",$parts1[count($parts1)-1]);
		$brand['brand'] =$brand_temp[0];
		$PSite = explode(":", get_option( 'PSiteName' ) ); // Added by Zaved as on 16/Nov/2016
		//print_r($PSite);



		if($cat == 1) {
			$Brand = $GRclass->get_product_brand_cat_list_total_outdoor_subcat_three($parts);
		}
		else {

			$Brand = $GRclass->get_product_brand_list_total($parts,$PSite[0]);
		}

		if($Brand && $cat == 1) {

			$Products = $GRclass->get_product_brand_cat_list_outdoor_subcat_three( $brand, $PSite[0] );

		}
		else if($Brand)
		{

			$Products = $GRclass->get_product_brand_list( $parts, $PSite[0] ); //changed for category/brand only

		}
		else {

			$Brand = (object)array();
			$Brand->manufacturer_name	= $parts['brand'];
			$Brand->category_name 		= $parts['category'];
		}

		if($Products) {

			for($i=0;$i<count($Products);$i++) {

				$Products[$i]->total_rating			= cr_get_overall_rating_average($Products[$i]);
				$Products[$i]->total_rating_count	= cr_get_overall_rating_count($Products[$i]);
			}
		}
	}


	function site_review_product_brand_list_outdoor_brand_subcat($cat=0) {

		global $GRclass, $Brand, $Products;

		/** Parse URL */
		//$parts = $GRclass->parse_brand_url();
		//print_r($parts);
		$parts1 = explode( '/', $_SERVER['REQUEST_URI']);

		$brand_temp = explode(".",$parts1[count($parts1)-1]);
		$brand['brand'] =$brand_temp[0];
		$PSite = explode(":", get_option( 'PSiteName' ) ); // Added by Zaved as on 16/Nov/2016
		//print_r($PSite);



		if($cat == 1) {
			$Brand = $GRclass->get_product_brand_cat_list_total_outdoor_subcat($parts);
		}
		else {

			$Brand = $GRclass->get_product_brand_list_total($parts,$PSite[0]);
		}

		if($Brand && $cat == 1) {

			$Products = $GRclass->get_product_brand_cat_list_outdoor_subcat( $brand, $PSite[0] );

		}
		else if($Brand)
		{

			$Products = $GRclass->get_product_brand_list( $parts, $PSite[0] ); //changed for category/brand only

		}
		else {

			$Brand = (object)array();
			$Brand->manufacturer_name	= $parts['brand'];
			$Brand->category_name 		= $parts['category'];
		}

		if($Products) {

			for($i=0;$i<count($Products);$i++) {

				$Products[$i]->total_rating			= cr_get_overall_rating_average($Products[$i]);
				$Products[$i]->total_rating_count	= cr_get_overall_rating_count($Products[$i]);
			}
		}
	}


	function site_review_product_brand_list_product($cat=0) {

		global $GRclass, $Brand, $Products;

		/** Parse URL */
		$parts = $GRclass->parse_brand_url();
		//print_r($parts);
		$PSite = explode(":", get_option( 'PSiteName' ) ); // Added by Zaved as on 16/Nov/2016
		//print_r($PSite);



		if($cat == 1) {
			$Brand = $GRclass->get_product_brand_cat_list_total($parts);
		}
		else {

			$Brand = $GRclass->get_product_brand_list_total($parts,$PSite[0]);
		}






		if($Brand && $cat == 1) {

			$Products = $GRclass->get_product_brand_cat_list( $parts, $PSite[0] );

		}
		else if($Brand)
		{

			$Products = $GRclass->get_product_brand_list( $parts, $PSite[0] ); //changed for category/brand only

		}
		else {

			$Brand = (object)array();
			$Brand->manufacturer_name	= $parts['brand'];
			$Brand->category_name 		= $parts['category'];
		}

		if($Products) {

			for($i=0;$i<count($Products);$i++) {

				$Products[$i]->total_rating			= cr_get_overall_rating_average($Products[$i]);
				$Products[$i]->total_rating_count	= cr_get_overall_rating_count($Products[$i]);
			}
		}
	}


	function site_review_product_brand_list($cat=0) {

		global $GRclass, $Brand, $Products;

		/** Parse URL */
		$parts = $GRclass->parse_brand_url();
		//print_r($parts);
		$PSite = explode(":", get_option( 'PSiteName' ) ); // Added by Zaved as on 16/Nov/2016
		//print_r($PSite);



		if($cat == 1) {
			$Brand = $GRclass->get_product_brand_cat_list_total($parts);
		}
		else {

			$Brand = $GRclass->get_product_brand_list_total($parts,$PSite[0]);
		}






		if($Brand && $cat == 1) {

			$Products = $GRclass->get_product_brand_cat_list( $parts, $PSite[0] );

		}
		else if($Brand)
		{

			$Products = $GRclass->get_product_brand_list( $parts, $PSite[0] ); //changed for category/brand only

		}
		else {

			$Brand = (object)array();
			$Brand->manufacturer_name	= $parts['brand'];
			$Brand->category_name 		= $parts['category'];
		}

		if($Products) {

			for($i=0;$i<count($Products);$i++) {

				$Products[$i]->total_rating			= cr_get_overall_rating_average($Products[$i]);
				$Products[$i]->total_rating_count	= cr_get_overall_rating_count($Products[$i]);
			}
		}
	}

	function site_review_location_type() {

		global $GRclass, $CourseType;

		$CourseType = $GRclass->parse_location_url();
		$CourseTypeName	=	str_replace("-"," ", $CourseType['course_type']);
		/***Added By Zaved as on 24/Nov/2016 ***/
		$SigulerName =	( substr( $CourseTypeName, -1 ) == 's' || substr( $CourseTypeName, -1 ) == 'S' ) ? substr($CourseTypeName, 0, -1) : $CourseTypeName;
		$CourseType['course_type_name'] = $CourseTypeName;
		$CourseType['singuler_type_name'] = $SigulerName;
	}

	function mtbr_site_review_location_type() {

		global $GRclass, $CourseType;

		$CourseType = $GRclass->mtbr_parse_location_url();
		$CourseTypeName	=	str_replace("-"," ", $CourseType['course_type']);
		/***Added By Zaved as on 24/Nov/2016 ***/
		$SigulerName =	( substr( $CourseTypeName, -1 ) == 's' || substr( $CourseTypeName, -1 ) == 'S' ) ? substr($CourseTypeName, 0, -1) : $CourseTypeName;
		$CourseType['course_type_name'] = $CourseTypeName;
		$CourseType['singuler_type_name'] = $SigulerName;
	}

	function site_review_courses_by_state_new() {

		global $GRclass, $State, $CourseType;

			$url = explode("/",$_SERVER['REQUEST_URI']);
			$urlnew = explode(".",$url[3]);
			$CourseType = $GRclass->parse_state_url();

			$CourseTypeName	=	str_replace("-"," ", $CourseType['course_type']);
			$SigulerName =	( substr( $CourseTypeName, -1 ) == 's' || substr( $CourseTypeName, -1 ) == 'S' ) ? substr($CourseTypeName, 0, -1) : $CourseTypeName;

			$CourseType['course_type_name'] = $CourseTypeName;
			$CourseType['singuler_type_name'] = $SigulerName;

			$State = $GRclass->get_site_location_state_count($urlnew[0]);
			$State->name = $urlnew[0];
	}


	function site_review_courses_by_state() {

		global $GRclass, $State, $CourseType;

			$url = explode("/",$_SERVER['REQUEST_URI']);
			$urlnew = explode(".",$url[2]);
			//echo $urlnew[0];
			$CourseType = $GRclass->parse_state_url();
		//$CourseType['course_type_name'] = str_replace("-"," ", $CourseType['course_type']); //Commented By Zaved as on 24/Nov/2016
		/***Added By Zaved as on 24/Nov/2016 ***/
		//echo "hi";
		$CourseTypeName	=	str_replace("-"," ", $CourseType['course_type']);

		$SigulerName =	( substr( $CourseTypeName, -1 ) == 's' || substr( $CourseTypeName, -1 ) == 'S' ) ? substr($CourseTypeName, 0, -1) : $CourseTypeName;

		$CourseType['course_type_name'] = $CourseTypeName;
		$CourseType['singuler_type_name'] = $SigulerName;

		//$State = $GRclass->get_site_location_state_count($CourseType['state']);
		$State = $GRclass->get_site_location_state_count($urlnew[0]);
		//$State->name = cr_state_name($CourseType['state']);
		$State->name = $urlnew[0];
	}




	function mtbr_site_review_courses_by_state_new() {

		global $GRclass, $State, $CourseType;


		$parts = explode('/',$_SERVER['REQUEST_URI']);
		$partsnew=explode(".",$parts[3]);
		if($parts[1]=="trails")
		{
			if($parts[2]=="canada")
			{
				$sta = "trails-canada-".str_replace(".html","",$partsnew[0]);
			}
			else if($parts[2]=="europe")
			{
				$sta = "trails-europe-".str_replace(".html","",$partsnew[0]);
			}
			else if($parts[2]=="asia-and-pacific")
			{
				$sta = "trails-asia-and-pacific-".str_replace(".html","",$partsnew[0]);
			}
			else
			{
				$sta = "trails-".str_replace(".html","",$partsnew[0]);
			}
		}
		else
		{
			if($parts[2]=="canada")
			{
				$sta = "canada-".str_replace(".html","",$partsnew[0]);
			}
			else if($parts[2]=="europe")
			{
				$sta = "europe-".str_replace(".html","",$partsnew[0]);
			}
			else if($parts[2]=="asia-and-pacific")
			{
				$sta = "asia-and-pacific-".str_replace(".html","",$partsnew[0]);
			}
			else
			{
				$sta = str_replace(".html","",$partsnew[0]);
			}
		}
		//echo $sta;
		$CourseType = $GRclass->mtbr_parse_state_url();
		$CourseTypeName	=	str_replace("-"," ", $CourseType['course_type']);

		$SigulerName =	( substr( $CourseTypeName, -1 ) == 's' || substr( $CourseTypeName, -1 ) == 'S' ) ? substr($CourseTypeName, 0, -1) : $CourseTypeName;
		$CourseType['course_type_name'] = $CourseTypeName;
		$CourseType['singuler_type_name'] = $SigulerName;

		$State = $GRclass->mtbr_get_site_location_state_count($sta);
		$State->name = mtbr_cr_state_name($CourseType['state']);
	}


	function mtbr_site_review_courses_by_state() {

		global $GRclass, $State, $CourseType;


		$parts = explode('/',$_SERVER['REQUEST_URI']);

		if($parts[1]=="trails")
		{
			if($parts[2]=="canada")
			{
				$sta = "trails-canada-".str_replace(".html","",$parts[3]);
			}
			else if($parts[2]=="europe")
			{
				$sta = "trails-europe-".str_replace(".html","",$parts[3]);
			}
			else if($parts[2]=="asia-and-pacific")
			{
				$sta = "trails-asia-and-pacific-".str_replace(".html","",$parts[3]);
			}
			else
			{
				$sta = "trails-".str_replace(".html","",$parts[3]);
			}
		}
		else
		{
			if($parts[2]=="canada")
			{
				$sta = "canada-".str_replace(".html","",$parts[3]);
			}
			else if($parts[2]=="europe")
			{
				$sta = "europe-".str_replace(".html","",$parts[3]);
			}
			else if($parts[2]=="asia-and-pacific")
			{
				$sta = "asia-and-pacific-".str_replace(".html","",$parts[3]);
			}
			else
			{
				$sta = str_replace(".html","",$parts[3]);
			}
		}
		//echo $sta;
		$CourseType = $GRclass->mtbr_parse_state_url();
		$CourseTypeName	=	str_replace("-"," ", $CourseType['course_type']);

		$SigulerName =	( substr( $CourseTypeName, -1 ) == 's' || substr( $CourseTypeName, -1 ) == 'S' ) ? substr($CourseTypeName, 0, -1) : $CourseTypeName;
		$CourseType['course_type_name'] = $CourseTypeName;
		$CourseType['singuler_type_name'] = $SigulerName;

		$State = $GRclass->mtbr_get_site_location_state_count($sta);
		$State->name = mtbr_cr_state_name($CourseType['state']);
	}

	function site_review_city_list() {

		global $GRclass;

			$url = explode("/",$_SERVER['REQUEST_URI']);
			$urlnew = explode(".",$url[2]);
		$st = $GRclass->parse_state_url();
		/*** Get alphabetical list of City list by state*/
		//$Cities = $GRclass->get_site_location_cities($st);
		$Cities = $GRclass->get_site_location_cities($urlnew[0]);

		if($Cities) {

			foreach($Cities as $bn) {

				if( $bn->city[0] != 1 ) {

					$letters[] = strtoupper($bn->city[0]);
				}
			}

			$let = array_unique($letters);
			echo "<div class='golf-club-list-nav'><ul style='margin-left:10px;'>";

			foreach($let as $k => $ltr) {

				$cur = ($k == 0) ? ' class="sel"' : '';
				echo '<li id="let-'.$ltr.'"'.$cur.'><a href="#" onClick="showLetter(\''.$ltr.'\');return false">'.$ltr.'</a></li>';
			}

			echo "</ul></div>";
			echo "<div class='golf-club-list-items'>";
			//$alpha = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
			$c = 0;
			echo '<div id="'. $let[$c] .'"><ul>';
			foreach($Cities as $city) {

				while(strtoupper($city->city[0]) != $let[$c] && $city->city[0] != '1') {

					$c++;
					echo '</ul></div>';
					echo '<div id="'.$let[$c].'" style="display:none;"><ul>';
				}

				if(strtoupper($city->city[0]) == $let[$c] || $city->city[0] == '1') {

					echo '<li><a href="';
					cr_location_city_url($st, $GRclass->parse_city_permalink($city->city));
					echo '">'.$city->city.'</a></li>';
				}
			}
			echo '</ul></div>';

			if($c != 26) {

				for($j = $c; $j < 26; $j++) {

					echo '<div id="'.$alpha[$j].'" style="display:none;"></div>';
				}
			}
			echo "</div>";
		}
	}



	function site_review_product_list_outdoor_new() {

		global $GRclass, $Category, $Products, $Location;
		$url = explode("/",$_SERVER['REQUEST_URI']);

		/** Parse URL */
		$category = explode(".",$url[2]);
		$parts['category'] = $category[0];
		$parts['base'] = $url[1];
		//$parts = $GRclass->parse_url();
		//print_r($parts);
		/**
		* get and return object of category detail info
		* this function only when on a category specific page
		* Returns $Category object with values:
		* 	->CategoryID
		* 	->Category_Name
		* 	->Product_Count */

		$PSite = explode(":", get_option( 'PSiteName' ) ); // Added by Zaved as on 14/Nov/2016
		//echo $PSite[0].$PSite[1];
		$Category = $GRclass->get_category_detail( $parts, $PSite[0] );
		//print_r($Category);
		//exit;

		/*** Convert category name from DB to website*/
		$Category->category_name = cr_category_name($Category->category_name,1);

		/*** if category exists, get product list based on category ($type) value*/
		if($Category) {

			$Products = $GRclass->get_product_list_new_outdoor( $parts, array(), $PSite[0] );
			//$Products = $GRclass->get_product_list($parts);
			$Category->product_count = count($Products);
			if($Products) {

				for($i=0;$i<count($Products);$i++) {

					$Products[$i]->total_rating 		= cr_get_overall_rating_average($Products[$i]);
					$Products[$i]->total_rating_count	= cr_get_overall_rating_count($Products[$i]);
				}
			}

			if( !isset($Location) ) {

				$Location = (object)array();
			}

			$Location->Path = $parts['base'];
			$Location->Name = $GRclass->parse_city_name($parts['base']);
		}
	}

	function site_review_product_brand_list_outdoor($cat=0) {

		global $GRclass, $Brand, $Products;
		$url = explode( '/', $_SERVER['REQUEST_URI']);
		$brand = explode(".",$url[count($url)-1]);
		$parts['brand']= $brand[0];
		//print_r($parts);
		$PSite = explode(":", get_option( 'PSiteName' ) ); // Added by Zaved as on 16/Nov/2016
		//print_r($PSite);



		if($cat == 1) {
			$Brand = $GRclass->get_product_brand_cat_list_total($parts);
		}
		else {

			$Brand = $GRclass->get_product_brand_list_total_outdoor($parts,$PSite[0]);
		}

		if($Brand && $cat == 1) {

			$Products = $GRclass->get_product_brand_cat_list( $parts, $PSite[0] );

		}
		else if($Brand)
		{

			$Products = $GRclass->get_product_brand_list_outdoor( $parts, $PSite[0] ); //changed for category/brand only

		}
		else {

			$Brand = (object)array();
			$Brand->manufacturer_name	= $parts['brand'];
			$Brand->category_name 		= $parts['category'];
		}

		if($Products) {

			for($i=0;$i<count($Products);$i++) {

				$Products[$i]->total_rating			= cr_get_overall_rating_average($Products[$i]);
				$Products[$i]->total_rating_count	= cr_get_overall_rating_count($Products[$i]);
			}
		}
	}






	function site_review_product_accessory_list_outdoor_sub($Categories, $term_cat ) {

		global $GRclass, $Products;

		$PSite = explode(":", get_option( 'PSiteName' ) );
		$Products = $GRclass->get_product_list_outdoor( $cat, $term_cat, $PSite[0] );
	}


	function site_review_product_accessory_list_outdoor($Categories, $term_cat ) {

		global $GRclass, $Products;

		$PSite = explode(":", get_option( 'PSiteName' ) );
		$Products = $GRclass->get_product_list_outdoor( $cat, $term_cat, $PSite[0] );
	}



	function site_review_product_accessory_list_brand_outdoor($Categories, $term_cat ) {

		global $GRclass, $Products;

		$PSite = explode(":", get_option( 'PSiteName' ) );
		$Products = $GRclass->get_product_list_outdoor_brand( $cat, $term_cat, $PSite[0] );
	}

	function site_review_city_list_course_new() {


		global $GRclass;

			$url = explode("/",$_SERVER['REQUEST_URI']);
			$urlnew = explode(".",$url[3]);


		$st = $GRclass->parse_state_url();
		/*** Get alphabetical list of City list by state*/
		//$Cities = $GRclass->get_site_location_cities($st);
		$Cities = $GRclass->get_site_location_cities($urlnew[0]);

		if($Cities) {

			foreach($Cities as $bn) {

				if( $bn->city[0] != 1 ) {

					$letters[] = strtoupper($bn->city[0]);
				}
			}

			$let = array_unique($letters);
			echo "<div class='golf-club-list-nav'><ul style='margin-left:10px;'>";

			foreach($let as $k => $ltr) {

				$cur = ($k == 0) ? ' class="sel"' : '';
				echo '<li id="let-'.$ltr.'"'.$cur.'><a href="#" onClick="showLetter(\''.$ltr.'\');return false">'.$ltr.'</a></li>';
			}

			echo "</ul></div>";
			echo "<div class='golf-club-list-items'>";
			//$alpha = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
			$c = 0;
			echo '<div id="'. $let[$c] .'"><ul>';
			foreach($Cities as $city) {

				while(strtoupper($city->city[0]) != $let[$c] && $city->city[0] != '1') {

					$c++;
					echo '</ul></div>';
					echo '<div id="'.$let[$c].'" style="display:none;"><ul>';
				}

				if(strtoupper($city->city[0]) == $let[$c] || $city->city[0] == '1') {

					echo '<li><a href="/'.$url[1].'/'.$url[2].'/'.$urlnew[0].'/'.str_replace(" ","-",$city->city).'.html">'.$city->city.'</a></li>';
				}
			}
			echo '</ul></div>';

			if($c != 26) {

				for($j = $c; $j < 26; $j++) {

					echo '<div id="'.$alpha[$j].'" style="display:none;"></div>';
				}
			}
			echo "</div>";
		}
	}

	function site_review_city_list_course() {


		global $GRclass;

			$url = explode("/",$_SERVER['REQUEST_URI']);
			$urlnew = explode(".",$url[3]);


		$st = $GRclass->parse_state_url();
		/*** Get alphabetical list of City list by state*/
		//$Cities = $GRclass->get_site_location_cities($st);
		$Cities = $GRclass->get_site_location_cities($urlnew[0]);

		if($Cities) {

			foreach($Cities as $bn) {

				if( $bn->city[0] != 1 ) {

					$letters[] = strtoupper($bn->city[0]);
				}
			}

			$let = array_unique($letters);
			echo "<div class='golf-club-list-nav'><ul style='margin-left:10px;'>";

			foreach($let as $k => $ltr) {

				$cur = ($k == 0) ? ' class="sel"' : '';
				echo '<li id="let-'.$ltr.'"'.$cur.'><a href="#" onClick="showLetter(\''.$ltr.'\');return false">'.$ltr.'</a></li>';
			}

			echo "</ul></div>";
			echo "<div class='golf-club-list-items'>";
			//$alpha = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
			$c = 0;
			echo '<div id="'. $let[$c] .'"><ul>';
			foreach($Cities as $city) {

				while(strtoupper($city->city[0]) != $let[$c] && $city->city[0] != '1') {

					$c++;
					echo '</ul></div>';
					echo '<div id="'.$let[$c].'" style="display:none;"><ul>';
				}

				if(strtoupper($city->city[0]) == $let[$c] || $city->city[0] == '1') {

					echo '<li><a href="/'.$url[1].'/'.$urlnew[0].'/'.str_replace(" ","-",$city->city).'.html">'.$city->city.'</a></li>';
				}
			}
			echo '</ul></div>';

			if($c != 26) {

				for($j = $c; $j < 26; $j++) {

					echo '<div id="'.$alpha[$j].'" style="display:none;"></div>';
				}
			}
			echo "</div>";
		}
	}

function mtbr_site_review_location_list_new() {

		global $GRclass;
		$parts = explode('/',$_SERVER['REQUEST_URI']);
		$partsnew = explode(".",$parts[3]);
		$cpartsnew = explode(".",$parts[4]);
		if($parts[1]=="trails")
		{
			if($parts[2]=="canada")
			{
				$sta = "trails-canada-".str_replace(".html","",$partsnew[0]);
			}
			else if($parts[2]=="europe")
			{
				$sta = "trails-europe-".str_replace(".html","",$partsnew[0]);
			}
			else if($parts[2]=="asia-and-pacific")
			{
				$sta = "trails-asia-and-pacific-".str_replace(".html","",$partsnew[0]);
			}
			else
			{
				$sta = "trails-".str_replace(".html","",$partsnew[0]);
			}
		}
		else
		{
			if($parts[2]=="canada")
			{
				$sta = "canada-".str_replace(".html","",$partsnew[0]);
			}
			else if($parts[2]=="europe")
			{
				$sta = "europe-".str_replace(".html","",$partsnew[0]);
			}
			else if($parts[2]=="asia-and-pacific")
			{
				$sta = "asia-and-pacific-".str_replace(".html","",$partsnew[0]);
			}
			else
			{
				$sta = str_replace(".html","",$partsnew[0]);
			}
		}
		//echo $sta;
		$st = $GRclass->mtbr_parse_state_url();
		/*** Get alphabetical list of City list by state*/
		$Cities = $GRclass->mtbr_get_site_location_cities($parts,$sta);

		if($Cities) {

			foreach($Cities as $bn) {

				if( $bn->city[0] != 1 ) {

					$letters[] = strtoupper($bn->city[0]);
				}
			}

			$let = array_unique($letters);
			echo "<div class='golf-club-list-nav'><ul style='margin-left:10px;'>";

			echo '<li id="let-All"><a href="../'.$parts[3].'.html">All</a></li>';
			foreach($let as $k => $ltr) {

				$cur = ($cpartsnew[0][0]==$ltr) ? ' class="sel"' : '';
				echo '<li id="let-'.$ltr.'"'.$cur.'><a href="#" onClick="showLetter(\''.$ltr.'\');return false">'.$ltr.'</a></li>';
			}

			echo "</ul></div>";
			echo "<div class='golf-club-list-items'>";
			//$alpha = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
			$c = 0;

			echo '<div id="All" style="display:none;"></div>';

					if($cpartsnew[0][0]==$let[$c])
					{
						echo '<div id="'. $let[$c] .'"><ul>';
					}
					else
					{
						echo '<div id="'.$let[$c].'" style="display:none;"><ul>';
					}
			foreach($Cities as $city) {

				while(strtoupper($city->city[0]) != $let[$c] && $city->city[0] != '1') {

					$c++;
					echo '</ul></div>';

					if($cpartsnew[0][0]==$let[$c])
					{
						echo '<div id="'.$let[$c].'"><ul>';
					}
					else
					{
						echo '<div id="'.$let[$c].'" style="display:none;"><ul>';
					}
				}

				if(strtoupper($city->city[0]) == $let[$c] || $city->city[0] == '1') {

					/*echo '<li><a href="';
					mtbr_cr_location_city_url($st, $GRclass->mtbr_parse_city_permalink($city->city));
					echo '">'.$city->city.'</a></li>';*/
					//print_r($city);
					echo '<li><a href="/'.$parts[1]."/".$parts[2]."/".str_replace(array("trails-",".html"),array("",""),$partsnew[0])."/".str_replace(array(" "),array("-"),$city->city).'.html">'.$city->city.'</a></li>';
				}
			}
			echo '</ul></div>';

			if($c != 26) {

				for($j = $c; $j < 26; $j++) {

					echo '<div id="'.$alpha[$j].'" style="display:none;"></div>';
				}
			}
			echo "</div>";
		}
	}


function mtbr_site_review_city_list_new() {

		global $GRclass;
		$parts = explode('/',$_SERVER['REQUEST_URI']);
		$partsnew = explode(".",$parts[3]);
		if($parts[1]=="trails")
		{
			if($parts[2]=="canada")
			{
				$sta = "trails-canada-".str_replace(".html","",$partsnew[0]);
			}
			else if($parts[2]=="europe")
			{
				$sta = "trails-europe-".str_replace(".html","",$partsnew[0]);
			}
			else if($parts[2]=="asia-and-pacific")
			{
				$sta = "trails-asia-and-pacific-".str_replace(".html","",$partsnew[0]);
			}
			else
			{
				$sta = "trails-".str_replace(".html","",$partsnew[0]);
			}
		}
		else
		{
			if($parts[2]=="canada")
			{
				$sta = "canada-".str_replace(".html","",$partsnew[0]);
			}
			else if($parts[2]=="europe")
			{
				$sta = "europe-".str_replace(".html","",$partsnew[0]);
			}
			else if($parts[2]=="asia-and-pacific")
			{
				$sta = "asia-and-pacific-".str_replace(".html","",$partsnew[0]);
			}
			else
			{
				$sta = str_replace(".html","",$partsnew[0]);
			}
		}
		//echo $sta;
		$st = $GRclass->mtbr_parse_state_url();
		/*** Get alphabetical list of City list by state*/
		$Cities = $GRclass->mtbr_get_site_location_cities($parts,$sta);

		if($Cities) {

			foreach($Cities as $bn) {

				if( $bn->city[0] != 1 ) {

					$letters[] = strtoupper($bn->city[0]);
				}
			}

			$let = array_unique($letters);
			echo "<div class='golf-club-list-nav'><ul style='margin-left:10px;'>";

			echo '<li id="let-All" class="sel"><a href="#" onClick="showLetter(\''.$ltr.'\');return false">All</a></li>';
			foreach($let as $k => $ltr) {

				$cur = ($k == 0) ? ' class=""' : '';
				echo '<li id="let-'.$ltr.'"'.$cur.'><a href="#" onClick="showLetter(\''.$ltr.'\');return false">'.$ltr.'</a></li>';
			}

			echo "</ul></div>";
			echo "<div class='golf-club-list-items'>";
			//$alpha = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
			$c = 0;

			echo '<div id="All" ></div>';
			echo '<div id="'. $let[$c] .'" style="display:none;"><ul>';
			foreach($Cities as $city) {

				while(strtoupper($city->city[0]) != $let[$c] && $city->city[0] != '1') {

					$c++;
					echo '</ul></div>';
					echo '<div id="'.$let[$c].'" style="display:none;"><ul>';
				}

				if(strtoupper($city->city[0]) == $let[$c] || $city->city[0] == '1') {

					/*echo '<li><a href="';
					mtbr_cr_location_city_url($st, $GRclass->mtbr_parse_city_permalink($city->city));
					echo '">'.$city->city.'</a></li>';*/
					//print_r($city);
					echo '<li><a href="/'.$parts[1]."/".$parts[2]."/".str_replace(array("trails-",".html"),array("",""),$partsnew[0])."/".str_replace(array(" "),array("-"),$city->city).'.html">'.$city->city.'</a></li>';
				}
			}
			echo '</ul></div>';

			if($c != 26) {

				for($j = $c; $j < 26; $j++) {

					echo '<div id="'.$alpha[$j].'" style="display:none;"></div>';
				}
			}
			echo "</div>";
		}
	}



	function mtbr_site_review_city_list() {

		global $GRclass;
		$parts = explode('/',$_SERVER['REQUEST_URI']);
		if($parts[1]=="trails")
		{
			if($parts[2]=="canada")
			{
				$sta = "trails-canada-".str_replace(".html","",$parts[3]);
			}
			else if($parts[2]=="europe")
			{
				$sta = "trails-europe-".str_replace(".html","",$parts[3]);
			}
			else if($parts[2]=="asia-and-pacific")
			{
				$sta = "trails-asia-and-pacific-".str_replace(".html","",$parts[3]);
			}
			else
			{
				$sta = "trails-".str_replace(".html","",$parts[3]);
			}
		}
		else
		{
			if($parts[2]=="canada")
			{
				$sta = "canada-".str_replace(".html","",$parts[3]);
			}
			else if($parts[2]=="europe")
			{
				$sta = "europe-".str_replace(".html","",$parts[3]);
			}
			else if($parts[2]=="asia-and-pacific")
			{
				$sta = "asia-and-pacific-".str_replace(".html","",$parts[3]);
			}
			else
			{
				$sta = str_replace(".html","",$parts[3]);
			}
		}
		//echo $sta;
		$st = $GRclass->mtbr_parse_state_url();
		/*** Get alphabetical list of City list by state*/
		$Cities = $GRclass->mtbr_get_site_location_cities($parts,$sta);

		if($Cities) {

			foreach($Cities as $bn) {

				if( $bn->city[0] != 1 ) {

					$letters[] = strtoupper($bn->city[0]);
				}
			}

			$let = array_unique($letters);
			echo "<div class='golf-club-list-nav'><ul style='margin-left:10px;'>";

			foreach($let as $k => $ltr) {

				$cur = ($k == 0) ? ' class="sel"' : '';
				echo '<li id="let-'.$ltr.'"'.$cur.'><a href="#" onClick="showLetter(\''.$ltr.'\');return false">'.$ltr.'</a></li>';
			}

			echo "</ul></div>";
			echo "<div class='golf-club-list-items'>";
			//$alpha = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
			$c = 0;
			echo '<div id="'. $let[$c] .'"><ul>';
			foreach($Cities as $city) {

				while(strtoupper($city->city[0]) != $let[$c] && $city->city[0] != '1') {

					$c++;
					echo '</ul></div>';
					echo '<div id="'.$let[$c].'" style="display:none;"><ul>';
				}

				if(strtoupper($city->city[0]) == $let[$c] || $city->city[0] == '1') {

					/*echo '<li><a href="';
					mtbr_cr_location_city_url($st, $GRclass->mtbr_parse_city_permalink($city->city));
					echo '">'.$city->city.'</a></li>';*/
					//print_r($city);
					echo '<li><a href="/'.$parts[1]."/".$parts[2]."/".str_replace(array("trails-",".html"),array("",""),$parts[3])."/".str_replace(array(" "),array("-"),$city->city).'.html">'.$city->city.'</a></li>';
				}
			}
			echo '</ul></div>';

			if($c != 26) {

				for($j = $c; $j < 26; $j++) {

					echo '<div id="'.$alpha[$j].'" style="display:none;"></div>';
				}
			}
			echo "</div>";
		}
	}

	function site_review_location_listing_new() {

		global $GRclass, $CourseList, $Courses, $CourseType;

		$parts = $GRclass->parse_location_url();

		site_review_location_type();
		$CourseList = (object)array();


		if(is_numeric($parts['zip'])) {

			$CourseList->location 	= 'Zip Code ' . $parts['zip'];
			$CourseList->zip 		= $parts['zip'];

		} else {

			$CourseList->location	= $GRclass->parse_city_name($parts['city']) . ', ' . cr_state_name($parts['state']);
			$CourseList->city		= $GRclass->parse_city_name($parts['city']);
			$CourseList->state		= cr_state_name($parts['state']);
		}

		$Courses = $GRclass->get_site_location_listing($parts);

		if($Courses) {

			for($i=0;$i<count($Courses);$i++) {

				$Courses[$i]->total_rating			= cr_get_overall_rating_average($Courses[$i]);
				$Courses[$i]->total_rating_count	= cr_get_overall_rating_count($Courses[$i]);
			}
		}

		$CourseList->list_count = $GRclass->db->num_rows;
	}


	function site_review_location_listing() {

		global $GRclass, $CourseList, $Courses, $CourseType;
		$parts1 = explode('/',$_SERVER['REQUEST_URI']);
		$parts = $GRclass->parse_location_url();


		site_review_location_type();
		$CourseList = (object)array();


		if(is_numeric($parts['zip'])) {

			$CourseList->location 	= 'Zip Code ' . $parts['zip'];
			$CourseList->zip 		= $parts['zip'];

		} else {

			$CourseList->location	= $GRclass->parse_city_name($parts['city']) . ', ' . cr_state_name($parts['state']);
			$CourseList->city		= $GRclass->parse_city_name($parts['city']);
			$CourseList->state		= cr_state_name($parts['state']);
		}

		$Courses = $GRclass->get_site_location_listing($parts,$parts1);

		if($Courses) {

			for($i=0;$i<count($Courses);$i++) {

				$Courses[$i]->total_rating			= cr_get_overall_rating_average($Courses[$i]);
				$Courses[$i]->total_rating_count	= cr_get_overall_rating_count($Courses[$i]);
			}
		}

		$CourseList->list_count = $GRclass->db->num_rows;
	}

function mtbr_site_review_location_listing() {

		global $GRclass, $CourseList, $Courses, $CourseType;

		$parts1 = explode('/',$_SERVER['REQUEST_URI']);

		$parts = $GRclass->mtbr_parse_location_url();

		mtbr_site_review_location_type();
		$CourseList = (object)array();


		if(is_numeric($parts['zip'])) {

			$CourseList->location 	= 'Zip Code ' . $parts['zip'];
			$CourseList->zip 		= $parts['zip'];

		} else {

			$CourseList->location	= $GRclass->parse_city_name($parts['city']) . ', ' . cr_state_name($parts['state']);
			$CourseList->city		= $GRclass->parse_city_name($parts['city']);
			$CourseList->state		= cr_state_name($parts['state']);
		}

		$Courses = $GRclass->mtbr_get_site_location_listing($parts, $parts1);

		if($Courses) {

			for($i=0;$i<count($Courses);$i++) {

				$Courses[$i]->total_rating			= cr_get_overall_rating_average($Courses[$i]);
				$Courses[$i]->total_rating_count	= cr_get_overall_rating_count($Courses[$i]);
			}
		}

		$CourseList->list_count = $GRclass->db->num_rows;
	}

function site_review_location_state_listing_new() {

		global $GRclass, $CourseList, $Courses, $CourseType;

		$parts1 = explode('/',$_SERVER['REQUEST_URI']);
		//print_r($parts1);
		$parts = $GRclass->mtbr_parse_location_url();
		//print_r($parts);
		mtbr_site_review_location_type();
		$CourseList = (object)array();


		if(is_numeric($parts['zip'])) {

			$CourseList->location 	= 'Zip Code ' . $parts['zip'];
			$CourseList->zip 		= $parts['zip'];

		} else {

			$CourseList->location	= $GRclass->parse_city_name($parts['city']) . ', ' . cr_state_name($parts['state']);
			$CourseList->city		= $GRclass->parse_city_name($parts['city']);
			$CourseList->state		= cr_state_name($parts['state']);
		}

		$Courses = $GRclass->get_site_location_state_listing($parts, $parts1);

		//print_r($Courses);

		if($Courses) {

			for($i=0;$i<count($Courses);$i++) {

				$Courses[$i]->total_rating			= cr_get_overall_rating_average($Courses[$i]);
				$Courses[$i]->total_rating_count	= cr_get_overall_rating_count($Courses[$i]);
			}
		}

		$CourseList->list_count = $GRclass->db->num_rows;
	}

function mtbr_site_review_location_state_listing_new() {

		global $GRclass, $CourseList, $Courses, $CourseType;

		$parts1 = explode('/',$_SERVER['REQUEST_URI']);
		//print_r($parts1);
		$parts = $GRclass->mtbr_parse_location_url();
		//print_r($parts);
		mtbr_site_review_location_type();
		$CourseList = (object)array();


		if(is_numeric($parts['zip'])) {

			$CourseList->location 	= 'Zip Code ' . $parts['zip'];
			$CourseList->zip 		= $parts['zip'];

		} else {

			$CourseList->location	= $GRclass->parse_city_name($parts['city']) . ', ' . cr_state_name($parts['state']);
			$CourseList->city		= $GRclass->parse_city_name($parts['city']);
			$CourseList->state		= cr_state_name($parts['state']);
		}

		$Courses = $GRclass->mtbr_get_site_location_state_listing($parts, $parts1);

		//print_r($Courses);

		if($Courses) {

			for($i=0;$i<count($Courses);$i++) {

				$Courses[$i]->total_rating			= cr_get_overall_rating_average($Courses[$i]);
				$Courses[$i]->total_rating_count	= cr_get_overall_rating_count($Courses[$i]);
			}
		}

		$CourseList->list_count = $GRclass->db->num_rows;
	}


function mtbr_site_review_location_state_listing() {

		global $GRclass, $CourseList, $Courses, $CourseType;

		$parts1 = explode('/',$_SERVER['REQUEST_URI']);
		//print_r($parts1);
		$parts = $GRclass->mtbr_parse_location_url();
		//print_r($parts);
		mtbr_site_review_location_type();
		$CourseList = (object)array();


		if(is_numeric($parts['zip'])) {

			$CourseList->location 	= 'Zip Code ' . $parts['zip'];
			$CourseList->zip 		= $parts['zip'];

		} else {

			$CourseList->location	= $GRclass->parse_city_name($parts['city']) . ', ' . cr_state_name($parts['state']);
			$CourseList->city		= $GRclass->parse_city_name($parts['city']);
			$CourseList->state		= cr_state_name($parts['state']);
		}

		$Courses = $GRclass->mtbr_get_site_location_state_listing($parts, $parts1);

		//print_r($Courses);

		if($Courses) {

			for($i=0;$i<count($Courses);$i++) {

				$Courses[$i]->total_rating			= cr_get_overall_rating_average($Courses[$i]);
				$Courses[$i]->total_rating_count	= cr_get_overall_rating_count($Courses[$i]);
			}
		}

		$CourseList->list_count = $GRclass->db->num_rows;
	}


	function mtbr_golfreview_course_search($post) {

		global $GRclass, $CourseList, $Courses;

		$Courses = $GRclass->get_site_location_search($post);
		$CourseList->list_count = $GRclass->db->num_rows;
		site_review_location_type();
	}

	function golfreview_course_search($post) {

		global $GRclass, $CourseList, $Courses;

		$Courses = $GRclass->get_site_location_search($post);
		$CourseList->list_count = $GRclass->db->num_rows;
		site_review_location_type();
	}

	function site_review_location() {

		global $GRclass, $Course, $Reviews;

		$parts	= $GRclass->parse_location_url();
		$Course = $GRclass->get_site_location($parts);
		//print_r($Course);
		if($Course) {
			// product external ratings
			$external_ratings = $GRclass->get_external_product_ratings($Course->productid);
			if($external_ratings && is_numeric($external_ratings->web_score_count) && is_numeric($external_ratings->web_score_rating)) {

				$Course->web_score_count	= $external_ratings->web_score_count;
				$Course->web_score_rating	= $external_ratings->web_score_rating;

			} else {

				$Course->web_score_count	= 0;
				$Course->web_score_rating	= 0;
			}

			// get total ratings and averages
			$Course->total_rating		= cr_get_overall_rating_average($Course);
			$Course->total_rating_count = cr_get_overall_rating_count($Course);

			/** get product media **/
			$Course->media	= $GRclass->get_product_media($Course->productid);
			$Reviews		= $GRclass->get_product_reviews($Course->productid,$_GET['p']);

			$Course->review_url = cr_location_review_url($parts, 1);
		}
	}

function mtbr_site_review_location() {

		global $GRclass, $Course, $Reviews;
		$parts1 = explode('/',$_SERVER['REQUEST_URI']);
		$parts	= $GRclass->mtbr_parse_location_url();
		$Course = $GRclass->mtbr_get_site_location($parts,$parts1);
		//print_r($Course);
		if($Course) {
			// product external ratings
			$external_ratings = $GRclass->get_external_product_ratings($Course->productid);
			if($external_ratings && is_numeric($external_ratings->web_score_count) && is_numeric($external_ratings->web_score_rating)) {

				$Course->web_score_count	= $external_ratings->web_score_count;
				$Course->web_score_rating	= $external_ratings->web_score_rating;

			} else {

				$Course->web_score_count	= 0;
				$Course->web_score_rating	= 0;
			}

			// get total ratings and averages
			$Course->total_rating		= cr_get_overall_rating_average($Course);
			$Course->total_rating_count = cr_get_overall_rating_count($Course);

			/** get product media **/
			$Course->media	= $GRclass->get_product_media($Course->productid);
			$Reviews		= $GRclass->get_product_reviews($Course->productid,$_GET['p']);

			$Course->review_url = cr_location_review_url($parts, 1);
		}
	}

	function site_review_title() {

		global $GRclass;

		$val = site_review_meta_data($GRclass->get_site_review_location());
		echo $val['title'];
	}

	function site_review_meta_description() {

		global $GRclass;
		$val = site_review_meta_data($GRclass->get_site_review_location());
		echo $val['desc'];

	}

	function site_review_meta_keywords() {

		global $GRclass;
		$val = site_review_meta_data($GRclass->get_site_review_location());
		echo $val['keyw'];
	}


	function site_review_meta_data( $page, $cat='', $subcat='', $brand='') {

		global $GRclass;

		switch($page) {

			case 'golf-deals' :
					$arr = array(
							'title' => "Golf deals on golf clubs, golf balls, golf shoes, golf bags, golf courses and more",
							'keyw' => "Golf club deals, golf ball deals, golf shoe deals, golf bags deals",
							'desc' => "GolfReview bring you daily golf deals from the best brands and best retailers on the web."
						);
			break;
			case 'golf-courses' :
					$arr = array(
							'title' => "Reviews and information on golf courses in the United States and many other locations",
							'keyw' => "US golf courses, Florida golf courses, California golf courses, South Carolina golf courses, Hawaii golf courses",
							'desc' => "GolfReview brings you the latest reviews and information on golf courses in the US and many other locations."
						);
			break;
			case 'golf-courses/public-golf-courses' :
					$arr = array(
							'title' => "Public golf courses in the United States",
							'keyw' => "public golf courses, US public golf courses, Florida public golf courses, Hawaii public golf courses",
							'desc' => "GolfReview's listings, reviews and information on public golf courses in the US."
						);
			break;
			case 'golf-courses/best-golf-courses' :
					$arr = array(
							'title' => "The best golf courses in the United States and elsewhere",
							'keyw' => "Best golf courses, best US golf courses, best Florida golf courses, best Hawaii golf courses",
							'desc' => "GolfReview's selection, reviews and information on the best golf courses in the US and elsewhere."
						);
			break;
			case 'golf-courses/driving-ranges' :
					$arr = array(
							'title' => "Driving ranges in the United States",
							'keyw' => "driving ranges, US driving ranges, Florida driving ranges, Hawaii driving ranges",
							'desc' => "GolfReview's listings, reviews and information on driving ranges in the US."
						);
			break;
			case 'reviews' :
					$arr = array(
							'title' => "Reviews, editorials, news and information on golf clubs and golf courses",
							'keyw' => "Golf clubs, golf courses, golf club reviews, golf course reviews, golf news, golf editorials",
							'desc' => "GolfReview's editorial reviews and news on golf clubs and golf courses."
						);
			break;
			case 'golf-clubs' :
					$arr = array(
							'title' => "Golf Clubs",
							'keyw' => "Golf Clubs, Drivers, Fairway Woods, Hybrid Clubs, Irons, Wedges, Putters",
							'desc' => "Information and reviews on golf clubs."
						);
			break;
			case 'golf-equipment' :
					$arr = array(
							'title' => "Golf Equipment, Golf Balls, Golf Bags, Golf Shoes",
							'keyw' => "Golf Equipment, Golf Balls, Golf Bags, Golf Shoes, Golf GPS, Golf Range Finders",
							'desc' => "Information and reviews on golf equipment, golf balls, golf bags, golf shoes, golf GPS, golf range finders"
						);
			break;
			case 'golf-course-state' :
					$vars = $GRclass->get_golfreview_meta_vars();
					for($i=0;$i<count($vars);$i++) { $vars[$i] = strip_tags($vars[$i]); }
					$arr = array(
							'title' => $vars[1] . " " . $vars[0],
							'keyw' => $vars[1] . " " . $vars[0],
							'desc' => "Information and reviews on " . $vars[2] . " " . $vars[1] . " golf courses."
						);
			break;
			case 'golf-clubs-sub-category' :
					$vars = $GRclass->get_golfreview_meta_vars();
					for($i=0;$i<count($vars);$i++) { $vars[$i] = strip_tags($vars[$i]); }
					$arr = array(
							'title' => $vars[1] . " - " . $vars[0],
							'keyw' => $vars[1] . ", " . $vars[1] . " " . $vars[0] . ", " . $vars[1] . " reviews, " . $vars[1] . " news",
							'desc' => "Information and reviews on " . $vars[2] . " " . $vars[1] . "."
						);
			break;
			case 'manufacturer-pages' :
					$vars = $GRclass->get_golfreview_meta_vars();
					for($i=0;$i<count($vars);$i++) { $vars[$i] = strip_tags($vars[$i]); }
					$arr = array(
							'title' => $vars[0],
							'keyw' => $vars[0] . ", " . $vars[0] . " golf clubs, " . $vars[0] . " reviews, " . $vars[0] . " news",
							'desc' => "Information and reviews on " . $vars[1] . " " . $vars[0] . "."
						);
			break;
			case 'manufacturer-category-pages' :
					$vars = $GRclass->get_golfreview_meta_vars();
					for($i=0;$i<count($vars);$i++) { $vars[$i] = strip_tags($vars[$i]); }
					$arr = array(
							'title' => $vars[1] . " - " . $vars[0],
							'keyw' => $vars[1] . ", " . $vars[1] . " " . $vars[0] . "," . $vars[1] . " " . $vars[0] . " reviews, " . $vars[1] . " " . $vars[0] . " news",
							'desc' => "Information and reviews on " . $vars[2] . " " . $vars[1] . " " . $vars[0] . "."
						);
		break;
		case 'product-page' :
					$vars = $GRclass->get_golfreview_meta_vars();
					for($i=0;$i<count($vars);$i++) { $vars[$i] = strip_tags($vars[$i]); }
					$desc = "";
					if($vars[3] > 0 && $vars[4] != "")
						$desc = $vars[1] . " " . $vars[2] . " " . $vars[0] . " Reviews - " . $vars[3] . " out of 5 - " . $vars[4];
					else if($vars[3] == 0 && $vars[4] != "")
						$desc = $vars[1] . " " . $vars[2] . " " . $vars[0] . " Reviews - " . $vars[4];
					else if($vars[3] > 0)
						$desc = $vars[1] . " " . $vars[2] . " " . $vars[0] . " Reviews - " . $vars[3] . " out of 5";
					else
						$desc = $vars[1] . " " . $vars[2] . " " . $vars[0] . " Reviews";

					$arr = array(
							'title' => $vars[1] . " " . $vars[2] . " " . $vars[0],
							'keyw' => $vars[1] . ", " . $vars[1] . " golf clubs, " . $vars[1] . " reviews, " . $vars[1] . " news",
							'desc' => $desc
						);
			break;
			case 'course-listing' :
					$vars = $GRclass->get_golfreview_meta_vars();
					for($i=0;$i<count($vars);$i++) { $vars[$i] = strip_tags($vars[$i]); }
					$arr = array(
							'title' => "Reviews and information on golf courses in " . $vars[1] . " " . $vars[0],
							'keyw' => $vars[1] . " golf courses, " . $vars[0] . " golf courses",
							'desc' => "Information and reviews on " . $vars[2] . " " . $vars[1] . " " . $vars[0] . " golf courses."
						);
			break;
			case 'course-page' :
					$vars = $GRclass->get_golfreview_meta_vars();
					for($i=0;$i<count($vars);$i++) { $vars[$i] = strip_tags($vars[$i]); }
					$desc = "";
					if($vars[3] > 0 && $vars[4] != "")
						$desc = "Reviews of " . $vars[0] . " in " . $vars[1] . " - " . $vars[3] . " out of 5 - " . $vars[4];
					else if($vars[3] == 0 && $vars[4] != "")
						$desc = "Reviews of " . $vars[0] . " in " . $vars[1] . " - " . $vars[4];
					else if($vars[3] > 0)
						$desc ="Reviews of " . $vars[0] . " in " . $vars[1] . " - " . $vars[3] . " out of 5";
					else
						$desc = "Reviews of " . $vars[0] . " in " . $vars[1];

					$arr = array(
							'title' => "Reviews and information on " . $vars[0] . " in " . $vars[1] . " " . $vars[2],
							'keyw' => $vars[0] . ", " . $vars[1] . " golf courses, " . $vars[2] . " golf courses",
							'desc' => $desc
						);
			break;
			case 'article-page' :
					$vars = $GRclass->get_golfreview_meta_vars();
					for($i=0;$i<count($vars);$i++) { $vars[$i] = strip_tags($vars[$i]); }
					$desc = ($vars[1]) ? $vars[1] : $vars[0];
					$arr = array(
							'title' => $vars[0],
							'keyw' => $vars[1],
							'desc' => $desc
						);
			break;

			default :

				//put here mysql query to reading meta tags from db. if not show to default.
				include(__DIR__."/../../../wp-config-extra.php");
				$arr = array(
					'title' => $SITE_TITLE,
					'keyw' => $SITE_KEYWORD,
					'desc' => $SITE_DESC
				);
		}

		$arr['title'] = $arr['title'] . " - " . str_replace( 'http://','', home_url());
		return $arr;
	}


	function set_site_review_location($loc='') {

		global $GRclass, $location;
		$location = $loc;
		$GRclass->set_site_review_location($loc);

	}

	function set_site_review_meta_vars($var1,$var2='',$var3='',$var4='',$var5='') {

		global $GRclass;
		$vars = array($var1,$var2,$var3,$var4,$var5);
		$GRclass->set_site_review_meta_vars($vars);
	}

	function site_review_category_list() {

		global $GRclass, $Categories;

		/**
		* get and return object array of all categories
		* Returns $Categories object array with values:
		* 	->CategoryID
		* 	->Category_Name
		* 	->Product_Count
		* 	->Url_Safe_Category_Name
		*/

		$PSite = explode(":", get_option( 'PSiteName' ) );
		$Categories = $GRclass->get_category_list( 0, $PSite[0] );
	}

	//$archive is $_SESSION['archive']
	function site_review_main_category_list($primaryNav,$archive) {

		global $GRclass, $Categories;
		$PSite = explode(":", get_option( 'PSiteName' ) );
		$Categories = $GRclass->get_main_category_list( 0, $PSite[0], $primaryNav,$archive);
	}

	/* ------ Added By Zaved - Get the Nodeid 07/Dec/2016 --------*/

	/*  brand category page 20/12/90  */

	function site_review_brand_cat_outdoor() {

		global $GRclass, $Categories;
		$category->url_safe_category_name = $subcat[0];
		$id = explode('/',$_SERVER['REQUEST_URI']);
		$whtml = explode('.html',$id[2]);
		$PSite = explode(":", get_option( 'PSiteName' ) );

		$Categories = $GRclass->get_category_list_outdoor_brand( $whtml, $PSite[0]);
		return $Categories;
	}



	function site_review_category_list_outdoor_sub_fourth($archive,$lvl=0) {

		global $GRclass, $Categories;
		$UrlCat	= explode( '/', $_SERVER['REQUEST_URI']);
		if($lvl == 4)
		{
			$subcat = explode(".",$UrlCat[4]);
			$cat = $UrlCat[1]."/".$UrlCat[2]."/".$UrlCat[3]."/".$subcat[0];
		}
		else if($lvl == 3)
		{
			$subcat = explode(".",$UrlCat[3]);
			$cat = $UrlCat[1]."/".$UrlCat[2]."/".$subcat[0];
		}
		else if($lvl == 2)
		{
			$subcat = explode(".",$UrlCat[2]);
			$cat = $UrlCat[1]."/".$subcat[0];
		}
		else
		{
			$subcat = explode(".",$UrlCat[1]);
			$cat = $subcat[0];
		}
		/**
		* get and return object array of all categories
		* Returns $Categories object array with values:
		* 	->CategoryID
		* 	->Category_Names
		* 	->Product_Count
		* 	->Url_Safe_Category_Name
		*/
		$category->url_safe_category_name = $subcat[0];
		$PSite = explode(":", get_option( 'PSiteName' ) );
		$Categories = $GRclass->get_category_list_outdoor_fourth( 0, $PSite[0], $cat,$archive);
	}


	function site_review_category_list_outdoor_sub($archive,$lvl=0) {

		global $GRclass, $Categories;
		$UrlCat	= explode( '/', $_SERVER['REQUEST_URI']);
		if($lvl == 3)
		{
			$subcat = explode(".",$UrlCat[3]);
			$cat = $UrlCat[1]."/".$UrlCat[2]."/".$subcat[0];
		}
		else if($lvl == 2)
		{
			$subcat = explode(".",$UrlCat[2]);
			$cat = $UrlCat[1]."/".$subcat[0];
		}
		else
		{
			$subcat = explode(".",$UrlCat[1]);
			$cat = $subcat[0];
		}
		/**
		* get and return object array of all categories
		* Returns $Categories object array with values:
		* 	->CategoryID
		* 	->Category_Names
		* 	->Product_Count
		* 	->Url_Safe_Category_Name
		*/
		$category->url_safe_category_name = $subcat[0];
		$PSite = explode(":", get_option( 'PSiteName' ) );
		$Categories = $GRclass->get_category_list_outdoor( 0, $PSite[0], $cat,$archive);
	}





	function site_review_category_list_outdoor($archive,$lvl=0) {

		global $GRclass, $Categories;
		$UrlCat	= explode( '/', $_SERVER['REQUEST_URI']);
		if($lvl == 2)
		{
			$subcat = explode(".",$UrlCat[2]);
			$cat = $UrlCat[1]."/".$subcat[0];
		}
		else
		{
			$subcat = explode(".",$UrlCat[1]);
			$cat = $subcat[0];
		}
		/**
		* get and return object array of all categories
		* Returns $Categories object array with values:
		* 	->CategoryID
		* 	->Category_Names
		* 	->Product_Count
		* 	->Url_Safe_Category_Name
		*/

		$PSite = explode(":", get_option( 'PSiteName' ) );
		$Categories = $GRclass->get_category_list_outdoor( 0, $PSite[0], $cat,$archive);
	}










	function Get_ChannelID_By_Category_outdoor_review() {

		global $GRclass, $ReviewPage;

		$UrlCat	= explode( '/', $_SERVER['REQUEST_URI']);
		$PSite	= explode(":", get_option( 'PSiteName' ) );
		$PRD	= explode("$", get_option( 'PrimaryReviewDetails' ) );
		$SRD	= explode("$", get_option( 'SecondryReviewDetails' ) );
		$ChannelID = $GRclass->GetChannelIDByCat( $UrlCat[2] );
		$ReviewPage = (object)array();

		if( $ChannelID->channelid == $PRD[0] ) {

			$ReviewPage->Title_2	= $PRD[1];
			$ReviewPage->Reviewed	= $PRD[2];
			$ReviewPage->Similar	= $PRD[3];
			$ReviewPage->Title_3	= $PRD[4];
		}
		if( $ChannelID->channelid == $SRD[0] ) {

			$ReviewPage->Title_2	= $SRD[1];
			$ReviewPage->Reviewed	= $SRD[2];
			$ReviewPage->Similar	= $SRD[3];
			$ReviewPage->Title_3	= $SRD[4];
		}
	}


	function Get_ChannelID_By_Category() {

		global $GRclass, $ReviewPage;

		$UrlCat	= explode( '/', $_SERVER['REQUEST_URI']);
		$PSite	= explode(":", get_option( 'PSiteName' ) );
		$PRD	= explode("$", get_option( 'PrimaryReviewDetails' ) );
		$SRD	= explode("$", get_option( 'SecondryReviewDetails' ) );
		$ChannelID = $GRclass->GetChannelIDByCat( $UrlCat[1] );
		$ReviewPage = (object)array();

		if( $ChannelID->channelid == $PRD[0] ) {

			$ReviewPage->Title_2	= $PRD[1];
			$ReviewPage->Reviewed	= $PRD[2];
			$ReviewPage->Similar	= $PRD[3];
			$ReviewPage->Title_3	= $PRD[4];
		}
		if( $ChannelID->channelid == $SRD[0] ) {

			$ReviewPage->Title_2	= $SRD[1];
			$ReviewPage->Reviewed	= $SRD[2];
			$ReviewPage->Similar	= $SRD[3];
			$ReviewPage->Title_3	= $SRD[4];
		}
	}

	/* ------ Added By Zaved - Get the Nodeid 26/Nov/2016------------*/

	function Get_NodeID_By_Path_outdoor( $path ) {

		global $GRclass;

		$PSite = explode(":", get_option( 'PSiteName' ) );
		$row = $GRclass->get_node_id_outdoor($path, $PSite[0]);

		return $row->nodeid;
	}
	function Get_NodeID_By_Path( $path ) {

		global $GRclass;

		$PSite = explode(":", get_option( 'PSiteName' ) );
		$row = $GRclass->get_node_id($path, $PSite[0]);

		return $row->nodeid;
	}


	function Get_CategoryID_By_Path( $path ) {

		global $GRclass;
		//echo "hi hello";
		$PSite = explode(":", get_option( 'PSiteName' ) );
		$row = $GRclass->GetCategoryIDByCat($path, $PSite[0]);
		return $row->CategoryID;

	}

	function Get_ManufacturerID_By_Path( $path ) {

		global $GRclass;
		//echo "hi hello";
		$PSite = explode(":", get_option( 'PSiteName' ) );
		$row = $GRclass->GetManufacturerIDByCat($path, $PSite[0]);
		return $row->ManufacturerID;

	}

	function site_review_category_sub_list() {

		global $GRclass, $Categories;
		/**
		 * get and return object array of all categories
		 * Returns $Categories object array with values:
		 * 	->CategoryID
		 * 	->Category_Name
		 * 	->Product_Count
		 * 	->Url_Safe_Category_Name
		 */

		$PSite = explode(":", get_option( 'PSiteName' ) );
		$Categories = $GRclass->get_category_list( 0, $PSite[0] );

		$url = explode( '/', $_SERVER['REQUEST_URI']);
		$row = $GRclass->get_node_id($url[1], $PSite[0]);

		for($i=0;$i<count($Categories);$i++) { /* NODEID is also database driven */

			if(strpos($Categories[$i]->nodeid, $row->nodeid) !== false && $Categories[$i]->node_level == 3) {

				$BrandList[] = str_replace(' ', '-', strtolower( $Categories[$i]->category_name ) );
			}
		}

		return $BrandList;
	}

	function site_review_brand_list_outdoor_brand_three($all=0,$catName='',$catList=array())
	{

		global $GRclass;

		//$parts = $GRclass->parse_url();
		//print_r($parts);
		$url = explode( '/', $_SERVER['REQUEST_URI']);
		$second = $url[2];
		$final = explode("-subcat",$url[3]);
		$parts['category'] = $final[0];

		if($catName != '') $parts['category'] = $catName;
		if(!empty($catList)) $parts['cat_list'] = implode("','", $catList);
		/*** Get alphabetical list of Golfreview product list ***/

		$PSite = explode(":", get_option( 'PSiteName' ) );
		$Brands = $GRclass->cr_get_brand_list_outdoor_brand_three( $parts, $all, $PSite[0] );
		//print_r($PSite);
		//print_r($Brands);

		//exit;

		if($Brands) {

			foreach($Brands as $bn) {

				/*if( $bn->manufacturer_name[0] != 1 ) {*/

					$letters[] = strtoupper($bn->manufacturer_name[0]);
				/*}*/
			}

			$let = array_unique($letters);

			echo "<div class='golf-club-list-nav'><ul style='margin-left:10px;'>";
			echo '<li id="let-All" class="sel"><a href="#" onClick="showLetter(\'All\');return false">All</a></li>';
			foreach($let as $k => $ltr) {

				$cur = ($k == 0) ? ' class=""' : '';
				echo '<li id="let-'.$ltr.'"'.$cur.'><a href="#" onClick="showLetter(\''.$ltr.'\');return false">'.$ltr.'</a></li>';
			}

			echo "</ul></div>";
			echo "<div class='golf-club-list-items'>";
			//$alpha = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
			$c = 0;
			echo '<div id="All"></div>';
			echo '<div id="'. $let[$c] .'" style="display:none;"><ul>';

			foreach($Brands as $b) {

				while(strtoupper($b->manufacturer_name[0]) != $let[$c] && $b->manufacturer_name[0] != '1') {
					$c++;
					echo '</ul></div>';
					echo '<div id="'.$let[$c].'" style="display:none;"><ul>';
				}
				if(strtoupper($b->manufacturer_name[0]) == $let[$c] || $b->manufacturer_name[0] == '1') {

					echo '<li><a href="';
					//if($all == 1) echo cr_brand_url($b,1);
					//else
					echo cr_brand_category_url_wcat_outdoor_brand_three($b,$parts['category'],1);
					echo '">'.$b->manufacturer_name.'</a></li>';
				}
			}
			echo '</ul></div>';
			if($c != 26) {

				for($j = $c; $j < 26; $j++) {

					echo '<div id="'.$alpha[$j].'" style="display:none;"></div>';
				}
			}
			echo "</div>";
		}
	}

	function site_review_brand_list_outdoor_sub_four($all=0,$catName='',$catList=array())
	{

		global $GRclass;

		//$parts = $GRclass->parse_url();
		//print_r($parts);
		$url = explode( '/', $_SERVER['REQUEST_URI']);
		$second = $url[2];
		$final = explode("-subcat",$url[3]);
		$parts['category'] = $final[0];

		if($catName != '') $parts['category'] = $catName;
		if(!empty($catList)) $parts['cat_list'] = implode("','", $catList);
		/*** Get alphabetical list of Golfreview product list ***/

		$PSite = explode(":", get_option( 'PSiteName' ) );
		$Brands = $GRclass->cr_get_brand_list_outdoor_sub_four( $parts, $all, $PSite[0] );
		//print_r($PSite);
		//print_r($Brands);

		//exit;

		if($Brands) {

			foreach($Brands as $bn) {

				/*if( $bn->manufacturer_name[0] != 1 ) {*/

					$letters[] = strtoupper($bn->manufacturer_name[0]);
				/*}*/
			}

			$let = array_unique($letters);

			echo "<div class='golf-club-list-nav'><ul style='margin-left:10px;'>";
			echo '<li id="let-All" class="sel"><a href="#" onClick="showLetter(\'All\');return false">All</a></li>';
			foreach($let as $k => $ltr) {

				$cur = ($k == 0) ? ' class=""' : '';
				echo '<li id="let-'.$ltr.'"'.$cur.'><a href="#" onClick="showLetter(\''.$ltr.'\');return false">'.$ltr.'</a></li>';
			}

			echo "</ul></div>";
			echo "<div class='golf-club-list-items'>";
			//$alpha = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
			$c = 0;
			echo '<div id="All"></div>';
			echo '<div id="'. $let[$c] .'" style="display:none;"><ul>';

			foreach($Brands as $b) {

				while(strtoupper($b->manufacturer_name[0]) != $let[$c] && $b->manufacturer_name[0] != '1') {
					$c++;
					echo '</ul></div>';
					echo '<div id="'.$let[$c].'" style="display:none;"><ul>';
				}
				if(strtoupper($b->manufacturer_name[0]) == $let[$c] || $b->manufacturer_name[0] == '1') {

					echo '<li><a href="';
					echo cr_brand_category_url_wcat_outdoor_brand_four($b,$parts['category'],1);
					echo '">'.$b->manufacturer_name.'</a></li>';
				}
			}
			echo '</ul></div>';
			if($c != 26) {

				for($j = $c; $j < 26; $j++) {

					echo '<div id="'.$alpha[$j].'" style="display:none;"></div>';
				}
			}
			echo "</div>";
		}
	}

	function site_review_brand_list_outdoor_sub($all=0,$catName='',$catList=array())
	{

		global $GRclass;

		//$parts = $GRclass->parse_url();
		//print_r($parts);
		$url = explode( '/', $_SERVER['REQUEST_URI']);
		$second = $url[2];
		$final = explode("-subcat",$url[3]);
		$parts['category'] = $final[0];

		if($catName != '') $parts['category'] = $catName;
		if(!empty($catList)) $parts['cat_list'] = implode("','", $catList);
		/*** Get alphabetical list of Golfreview product list ***/

		$PSite = explode(":", get_option( 'PSiteName' ) );
		$Brands = $GRclass->cr_get_brand_list_outdoor_sub( $parts, $all, $PSite[0] );
		//print_r($PSite);
		//print_r($Brands);

		//exit;

		if($Brands) {

			foreach($Brands as $bn) {

				/*if( $bn->manufacturer_name[0] != 1 ) {*/

					$letters[] = strtoupper($bn->manufacturer_name[0]);
				/*}*/
			}

			$let = array_unique($letters);

			echo "<div class='golf-club-list-nav'><ul style='margin-left:10px;'>";
			foreach($let as $k => $ltr) {

				$cur = ($k == 0) ? ' class="sel"' : '';
				echo '<li id="let-'.$ltr.'"'.$cur.'><a href="#" onClick="showLetter(\''.$ltr.'\');return false">'.$ltr.'</a></li>';
			}

			echo "</ul></div>";
			echo "<div class='golf-club-list-items'>";
			//$alpha = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
			$c = 0;
			echo '<div id="'. $let[$c] .'"><ul>';

			foreach($Brands as $b) {

				while(strtoupper($b->manufacturer_name[0]) != $let[$c] && $b->manufacturer_name[0] != '1') {
					$c++;
					echo '</ul></div>';
					echo '<div id="'.$let[$c].'" style="display:none;"><ul>';
				}
				if(strtoupper($b->manufacturer_name[0]) == $let[$c] || $b->manufacturer_name[0] == '1') {

					echo '<li><a href="';
					if($all == 1) echo cr_brand_url($b,1);
					else echo cr_brand_category_url_wcat_outdoor_sub($b,$parts['category'],1);
					echo '">'.$b->manufacturer_name.'</a></li>';
				}
			}
			echo '</ul></div>';
			if($c != 26) {

				for($j = $c; $j < 26; $j++) {

					echo '<div id="'.$alpha[$j].'" style="display:none;"></div>';
				}
			}
			echo "</div>";
		}
	}


	function site_review_brand_list_outdoor_subcat($all=0,$catName='',$catList=array())
	{

		global $GRclass;

		$parts = $GRclass->parse_url();
		//print_r($parts);

		if($catName != '') $parts['category'] = $catName;
		if(!empty($catList)) $parts['cat_list'] = implode("','", $catList);
		/*** Get alphabetical list of Golfreview product list ***/

		$PSite = explode(":", get_option( 'PSiteName' ) );
		$Brands = $GRclass->cr_get_brand_list( $parts, $all, $PSite[0] );
		//print_r($PSite);
		//print_r($Brands);

		//exit;

		if($Brands) {

			foreach($Brands as $bn) {

				/*if( $bn->manufacturer_name[0] != 1 ) {*/

					$letters[] = strtoupper($bn->manufacturer_name[0]);
				/*}*/
			}

			$let = array_unique($letters);

			echo "<div class='golf-club-list-nav'><ul style='margin-left:10px;'>";
			echo '<li id="let-All" class="sel"><a href="#" onClick="showLetter(\'All\');return false">All</a></li>';
			foreach($let as $k => $ltr) {

				$cur = ($k == 0) ? ' class=""' : '';
				echo '<li id="let-'.$ltr.'"'.$cur.'><a href="#" onClick="showLetter(\''.$ltr.'\');return false">'.$ltr.'</a></li>';
			}

			echo "</ul></div>";
			echo "<div class='golf-club-list-items'>";
			$c = 0;
			echo '<div id="All"></div>';
			echo '<div id="'. $let[$c] .'" style="display:none;"><ul>';

			foreach($Brands as $b) {

				while(strtoupper($b->manufacturer_name[0]) != $let[$c] && $b->manufacturer_name[0] != '1') {
					$c++;
					echo '</ul></div>';
					echo '<div id="'.$let[$c].'" style="display:none;"><ul>';
				}
				if(strtoupper($b->manufacturer_name[0]) == $let[$c] || $b->manufacturer_name[0] == '1') {

					echo '<li><a href="';
					echo cr_brand_category_url_wcat_outdoor_subcat($b,$parts['category'],1);
					echo '">'.$b->manufacturer_name.'</a></li>';
				}
			}
			echo '</ul></div>';
			if($c != 26) {

				for($j = $c; $j < 26; $j++) {

					echo '<div id="'.$alpha[$j].'" style="display:none;"></div>';
				}
			}
			echo "</div>";
		}
	}

















// Siva for brand page /brands/category/category/category/category/brandname.html


/*** print out brand list alphabetically and select the current brand */


	function site_review_brand_list_components_brand_category_four($all=0,$catName='',$catList=array())
	{

		global $GRclass;

		$burl = explode("/",$_SERVER['REQUEST_URI']);

		$bname = explode(".",$burl[count($burl)-1]);



		// Prints first character of brandname
		//echo $bname[0][0];


		$parts = $GRclass->parse_url();
		//print_r($parts);

		if($catName != '') $parts['category'] = $catName;
		if(!empty($catList)) $parts['cat_list'] = implode("','", $catList);
		/*** Get alphabetical list of Golfreview product list ***/

		$PSite = explode(":", get_option( 'PSiteName' ) );
		$Brands = $GRclass->cr_get_brand_list_components( $parts, $all, $PSite[0] );
		//print_r($PSite);
		//print_r($Brands);

		//exit;

		if($Brands) {

			foreach($Brands as $bn) {

				/*if( $bn->manufacturer_name[0] != 1 ) {*/

					$letters[] = strtoupper($bn->manufacturer_name[0]);
				/*}*/
			}

			$let = array_unique($letters);

			echo "<div class='golf-club-list-nav'><ul style='margin-left:10px;'>";
			echo '<li id="let-All"><a href="/'.$burl[2].'/'.$burl[3].'/'.$burl[4].'/'.$burl[5].'.html" >All</a></li>';
			foreach($let as $k => $ltr) {

				$cur = ($ltr == strtoupper($bname[0][0])) ? 'class="sel" ' : '';
				echo '<li id="let-'.$ltr.'"'.$cur.'><a href="#" onClick="showLetter(\''.$ltr.'\');return false">'.$ltr.'</a></li>';
			}

			echo "</ul></div>";
			echo "<div class='golf-club-list-items'>";
			//$alpha = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
			$c = 0;



			echo '<div id="All" style="display:none;"></div>';



			//echo $let[0];
			if(strtoupper($bname[0][0]) == $let[$c])
			{
				echo '<div id="'. $let[$c] .'" style="display:none"><ul>';
			}
			else
			{
				echo '<div id="'. $let[$c] .'" style="display:none;" ><ul>';
			}

			foreach($Brands as $b)
			{

				//echo $let[$c];
				while(strtoupper($b->manufacturer_name[0]) != $let[$c] && $b->manufacturer_name[0] != '1')
				{
					$c++;
					echo '</ul></div>';
					if($let[$c] == strtoupper($bname[0][0]))
					{
						//echo "<script>alert('Matched".strtoupper($bname[0][0])."');</script>";
						echo '<div id="'.$let[$c].'" style="display:none"><ul>';
					}
					else
					{
						echo '<div id="'.$let[$c].'" style="display:none;"><ul>';
					}

				}
				if(strtoupper($b->manufacturer_name[0]) == $let[$c] || $b->manufacturer_name[0] == '1')
				{
					if($b->url_safe_manufacturer_name == $bname[0])
					{
						echo '<li><a style="font-weight: bold;text-decoration: none;" href="/brand/'.$burl[2].'/'.$burl[3].'/'.$burl[4].'/'.$burl[5].'/'.$b->url_safe_manufacturer_name.'.html">'.$b->manufacturer_name.'</a></li>';
					}
					else
					{
						echo '<li><a href="/brand/'.$burl[2].'/'.$burl[3].'/'.$burl[4].'/'.$burl[5].'/'.$b->url_safe_manufacturer_name.'.html">'.$b->manufacturer_name.'</a></li>';
					}
				}

			}
			echo '</ul></div>';





			if($c != 26) {

				for($j = $c; $j < 26; $j++) {

					echo '<div id="'.$alpha[$j].'" style="display:none;"></div>';
				}
			}
			echo "</div>";
		}
	}


// Siva for brand page /brands/category/category/category/brandname.html


/*** print out brand list alphabetically and select the current brand */


	function site_review_brand_list_components_brand_category_three($all=0,$catName='',$catList=array())
	{

		global $GRclass;

		$burl = explode("/",$_SERVER['REQUEST_URI']);

		$bname = explode(".",$burl[count($burl)-1]);



		// Prints first character of brandname
		//echo $bname[0][0];


		$parts = $GRclass->parse_url();
		//print_r($parts);

		if($catName != '') $parts['category'] = $catName;
		if(!empty($catList)) $parts['cat_list'] = implode("','", $catList);
		/*** Get alphabetical list of Golfreview product list ***/

		$PSite = explode(":", get_option( 'PSiteName' ) );
		$Brands = $GRclass->cr_get_brand_list_components( $parts, $all, $PSite[0] );
		//print_r($PSite);
		//print_r($Brands);

		//exit;

		if($Brands) {

			foreach($Brands as $bn) {

				/*if( $bn->manufacturer_name[0] != 1 ) {*/

					$letters[] = strtoupper($bn->manufacturer_name[0]);
				/*}*/
			}

			$let = array_unique($letters);

			echo "<div class='golf-club-list-nav'><ul style='margin-left:10px;'>";
			echo '<li id="let-All"><a href="/'.$burl[2].'/'.$burl[3].'/'.$burl[4].'.html" >All</a></li>';
			foreach($let as $k => $ltr) {

				$cur = ($ltr == strtoupper($bname[0][0])) ? 'class="sel" ' : '';
				echo '<li id="let-'.$ltr.'"'.$cur.'><a href="#" onClick="showLetter(\''.$ltr.'\');return false">'.$ltr.'</a></li>';
			}

			echo "</ul></div>";
			echo "<div class='golf-club-list-items'>";
			//$alpha = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
			$c = 0;



			echo '<div id="All" style="display:none;"></div>';



			//echo $let[0];
			if(strtoupper($bname[0][0]) == $let[$c])
			{
				echo '<div id="'. $let[$c] .'" style="display:none"><ul>';
			}
			else
			{
				echo '<div id="'. $let[$c] .'" style="display:none;" ><ul>';
			}

			foreach($Brands as $b)
			{

				//echo $let[$c];
				while(strtoupper($b->manufacturer_name[0]) != $let[$c] && $b->manufacturer_name[0] != '1')
				{
					$c++;
					echo '</ul></div>';
					if($let[$c] == strtoupper($bname[0][0]))
					{
						//echo "<script>alert('Matched".strtoupper($bname[0][0])."');</script>";
						echo '<div id="'.$let[$c].'" style="display:none"><ul>';
					}
					else
					{
						echo '<div id="'.$let[$c].'" style="display:none;"><ul>';
					}

				}
				if(strtoupper($b->manufacturer_name[0]) == $let[$c] || $b->manufacturer_name[0] == '1')
				{
					if($b->url_safe_manufacturer_name == $bname[0])
					{
						echo '<li><a style="font-weight: bold;text-decoration: none;" href="/brand/'.$burl[2].'/'.$burl[3].'/'.$burl[4].'/'.$b->url_safe_manufacturer_name.'.html">'.$b->manufacturer_name.'</a></li>';
					}
					else
					{
						echo '<li><a href="/brand/'.$burl[2].'/'.$burl[3].'/'.$burl[4].'/'.$b->url_safe_manufacturer_name.'.html">'.$b->manufacturer_name.'</a></li>';
					}
				}

			}
			echo '</ul></div>';





			if($c != 26) {

				for($j = $c; $j < 26; $j++) {

					echo '<div id="'.$alpha[$j].'" style="display:none;"></div>';
				}
			}
			echo "</div>";
		}
	}


// Siva for brand page /brands/category/category/brandname.html


/*** print out brand list alphabetically and select the current brand */


	function site_review_brand_list_components_brand_category_two($all=0,$catName='',$catList=array())
	{

		global $GRclass;

		$burl = explode("/",$_SERVER['REQUEST_URI']);

		$bname = explode(".",$burl[count($burl)-1]);



		// Prints first character of brandname
		//echo $bname[0][0];


		$parts = $GRclass->parse_url();
		//print_r($parts);

		if($catName != '') $parts['category'] = $catName;
		if(!empty($catList)) $parts['cat_list'] = implode("','", $catList);
		/*** Get alphabetical list of Golfreview product list ***/

		$PSite = explode(":", get_option( 'PSiteName' ) );
		$Brands = $GRclass->cr_get_brand_list_components( $parts, $all, $PSite[0] );
		//print_r($PSite);
		//print_r($Brands);

		//exit;

		if($Brands) {

			foreach($Brands as $bn) {

				/*if( $bn->manufacturer_name[0] != 1 ) {*/

					$letters[] = strtoupper($bn->manufacturer_name[0]);
				/*}*/
			}

			$let = array_unique($letters);

			echo "<div class='golf-club-list-nav'><ul style='margin-left:10px;'>";
			echo '<li id="let-All"><a href="/'.$burl[2].'/'.$burl[3].'.html">All</a></li>';
			foreach($let as $k => $ltr) {

				$cur = ($ltr == strtoupper($bname[0][0])) ? 'class="sel" ' : '';
				echo '<li id="let-'.$ltr.'"'.$cur.'><a href="#" onClick="showLetter(\''.$ltr.'\');return false">'.$ltr.'</a></li>';
			}

			echo "</ul></div>";
			echo "<div class='golf-club-list-items'>";
			//$alpha = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
			$c = 0;



			echo '<div id="All" style="display:none;"><ul><li><a href="/'.$burl[2].'/'.$burl[3].'.html">All Brands</a></li></ul></div>';



			//echo $let[0];
			if(strtoupper($bname[0][0]) == $let[$c])
			{
				echo '<div id="'. $let[$c] .'" style="display:none;"><ul>';
			}
			else
			{
				echo '<div id="'. $let[$c] .'" style="display:none;" ><ul>';
			}

			foreach($Brands as $b)
			{

				//echo $let[$c];
				while(strtoupper($b->manufacturer_name[0]) != $let[$c] && $b->manufacturer_name[0] != '1')
				{
					$c++;
					echo '</ul></div>';
					if($let[$c] == strtoupper($bname[0][0]))
					{
						//echo "<script>alert('Matched".strtoupper($bname[0][0])."');</script>";
						echo '<div id="'.$let[$c].'" style="display:none"><ul>';
					}
					else
					{
						echo '<div id="'.$let[$c].'" style="display:none;"><ul>';
					}

				}
				if(strtoupper($b->manufacturer_name[0]) == $let[$c] || $b->manufacturer_name[0] == '1')
				{
					if($b->url_safe_manufacturer_name == $bname[0])
					{
						echo '<li><a style="font-weight: bold;text-decoration: none;" href="/brand/'.$burl[2].'/'.$burl[3].'/'.$b->url_safe_manufacturer_name.'.html">'.$b->manufacturer_name.'</a></li>';
					}
					else
					{
						echo '<li><a href="/brand/'.$burl[2].'/'.$burl[3].'/'.$b->url_safe_manufacturer_name.'.html">'.$b->manufacturer_name.'</a></li>';
					}
				}

			}
			echo '</ul></div>';





			if($c != 26) {

				for($j = $c; $j < 26; $j++) {

					echo '<div id="'.$alpha[$j].'" style="display:none;"></div>';
				}
			}
			echo "</div>";
		}
	}


// Siva for brand page /brand/category/brandname.html


/*** print out brand list alphabetically and select the current brand */


	function site_review_brand_list_components_brand_category_one($all=0,$catName='',$catList=array())
	{

		global $GRclass;

		$burl = explode("/",$_SERVER['REQUEST_URI']);

		$bname = explode(".",$burl[count($burl)-1]);



		// Prints first character of brandname
		//echo $bname[0][0];


		$parts = $GRclass->parse_url();
		//print_r($parts);

		if($catName != '') $parts['category'] = $catName;
		if(!empty($catList)) $parts['cat_list'] = implode("','", $catList);
		/*** Get alphabetical list of Golfreview product list ***/

		$PSite = explode(":", get_option( 'PSiteName' ) );
		$Brands = $GRclass->cr_get_brand_list_components( $parts, $all, $PSite[0] );
		//print_r($PSite);
		//print_r($Brands);

		//exit;

		if($Brands) {

			foreach($Brands as $bn) {

				/*if( $bn->manufacturer_name[0] != 1 ) {*/

					$letters[] = strtoupper($bn->manufacturer_name[0]);
				/*}*/
			}

			$let = array_unique($letters);

			echo "<div class='golf-club-list-nav'><ul style='margin-left:10px;'>";
			echo '<li id="let-All"><a href="/'.$burl[2].'.html" >All</a></li>';
			foreach($let as $k => $ltr) {

				$cur = ($ltr == strtoupper($bname[0][0])) ? 'class="sel" ' : '';
				echo '<li id="let-'.$ltr.'"'.$cur.'><a href="#" onClick="showLetter(\''.$ltr.'\');return false">'.$ltr.'</a></li>';
			}

			echo "</ul></div>";
			echo "<div class='golf-club-list-items'>";
			//$alpha = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
			$c = 0;



			echo '<div id="All" style="display:none;"><ul><li><a href="/'.$burl[2].'.html">All Brands</a></li></ul></div>';



			//echo $let[0];
			if(strtoupper($bname[0][0]) == $let[$c])
			{
				echo '<div id="'. $let[$c] .'" style="display:none" ><ul>';
			}
			else
			{
				echo '<div id="'. $let[$c] .'" style="display:none;" ><ul>';
			}

			foreach($Brands as $b)
			{

				//echo $let[$c];
				while(strtoupper($b->manufacturer_name[0]) != $let[$c] && $b->manufacturer_name[0] != '1')
				{
					$c++;
					echo '</ul></div>';
					if($let[$c] == strtoupper($bname[0][0]))
					{
						//echo "<script>alert('Matched".strtoupper($bname[0][0])."');</script>";
						echo '<div id="'.$let[$c].'" style="display:none"><ul>';
					}
					else
					{
						echo '<div id="'.$let[$c].'" style="display:none;"><ul>';
					}

				}
				if(strtoupper($b->manufacturer_name[0]) == $let[$c] || $b->manufacturer_name[0] == '1')
				{
					if($b->url_safe_manufacturer_name == $bname[0])
					{
						echo '<li><a style="font-weight: bold;text-decoration: none;" href="/brand/'.$burl[2].'/'.$b->url_safe_manufacturer_name.'.html">'.$b->manufacturer_name.'</a></li>';
					}
					else
					{
						echo '<li><a href="/brand/'.$burl[2].'/'.$b->url_safe_manufacturer_name.'.html">'.$b->manufacturer_name.'</a></li>';
					}
				}

			}
			echo '</ul></div>';





			if($c != 26) {

				for($j = $c; $j < 26; $j++) {

					echo '<div id="'.$alpha[$j].'" style="display:none;"></div>';
				}
			}
			echo "</div>";
		}
	}





// Siva for brand page /brands/brandname.html


/*** print out brand list alphabetically and select the current brand */


	function site_review_brand_list_components_brand($all=0,$catName='',$catList=array())
	{

		global $GRclass;

		$burl = explode("/",$_SERVER['REQUEST_URI']);

		$bname = explode(".",$burl[count($burl)-1]);



		// Prints first character of brandname
		//echo $bname[0][0];


		$parts = $GRclass->parse_url();
		//print_r($parts);

		if($catName != '') $parts['category'] = $catName;
		if(!empty($catList)) $parts['cat_list'] = implode("','", $catList);
		/*** Get alphabetical list of Golfreview product list ***/

		$PSite = explode(":", get_option( 'PSiteName' ) );
		$Brands = $GRclass->cr_get_brand_list_components( $parts, $all, $PSite[0] );
		//print_r($PSite);
		//print_r($Brands);

		//exit;

		if($Brands) {

			foreach($Brands as $bn) {

				/*if( $bn->manufacturer_name[0] != 1 ) {*/

					$letters[] = strtoupper($bn->manufacturer_name[0]);
				/*}*/
			}

			$let = array_unique($letters);

			echo "<div class='golf-club-list-nav'><ul style='margin-left:10px;'>";
			echo '<li id="let-All"><a href="/brands.html" >All</a></li>';
			foreach($let as $k => $ltr) {

				$cur = ($ltr == strtoupper($bname[0][0])) ? 'class="sel" ' : '';
				echo '<li id="let-'.$ltr.'"'.$cur.'><a href="#" onClick="showLetter(\''.$ltr.'\');return false">'.$ltr.'</a></li>';
			}

			echo "</ul></div>";
			echo "<div class='golf-club-list-items'>";
			//$alpha = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
			$c = 0;



			echo '<div id="All" style="display:none;"><ul><li><a href="/brands.html">All Brands</a></li></ul></div>';



			//echo $let[0];
			if(strtoupper($bname[0][0]) == $let[$c])
			{
				echo '<div id="'. $let[$c] .'" ><ul>';
				//echo '<div id="'. $let[$c] .'" style="display:none;" ><ul>';
			}
			else
			{
				echo '<div id="'. $let[$c] .'" style="display:none;" ><ul>';
			}

			foreach($Brands as $b)
			{

				//echo $let[$c];
				while(strtoupper($b->manufacturer_name[0]) != $let[$c] && $b->manufacturer_name[0] != '1')
				{
					$c++;
					echo '</ul></div>';
					if($let[$c] == strtoupper($bname[0][0]))
					{
						//echo "<script>alert('Matched".strtoupper($bname[0][0])."');</script>";
						//echo '<div id="'.$let[$c].'"><ul>';
						echo '<div id="'.$let[$c].'" style="display:none;"><ul>';
					}
					else
					{
						echo '<div id="'.$let[$c].'" style="display:none;"><ul>';
					}

				}
				if(strtoupper($b->manufacturer_name[0]) == $let[$c] || $b->manufacturer_name[0] == '1')
				{
					if($b->url_safe_manufacturer_name == $bname[0])
					{
						echo '<li><a style="font-weight: bold;text-decoration: none;" href="/brands/'.$b->url_safe_manufacturer_name.'.html">'.$b->manufacturer_name.'</a></li>';
					}
					else
					{
						echo '<li><a href="/brands/'.$b->url_safe_manufacturer_name.'.html">'.$b->manufacturer_name.'</a></li>';
					}
				}

			}
			echo '</ul></div>';





			if($c != 26) {

				for($j = $c; $j < 26; $j++) {

					echo '<div id="'.$alpha[$j].'" style="display:none;"></div>';
				}
			}
			echo "</div>";
		}
	}



	function site_review_brand_list_brands($all=0,$catName='',$catList=array())
	{

		global $GRclass;

		$parts = $GRclass->parse_url();
		//print_r($parts);

		if($catName != '') $parts['category'] = $catName;
		if(!empty($catList)) $parts['cat_list'] = implode("','", $catList);
		/*** Get alphabetical list of Golfreview product list ***/

		$PSite = explode(":", get_option( 'PSiteName' ) );
		$Brands = $GRclass->cr_get_brand_list_components( $parts, $all, $PSite[0] );
		//print_r($PSite);
		//print_r($Brands);

		//exit;

		if($Brands) {

			foreach($Brands as $bn) {

				/*if( $bn->manufacturer_name[0] != 1 ) {*/

					$letters[] = strtoupper($bn->manufacturer_name[0]);
				/*}*/
			}

			$let = array_unique($letters);

			echo "<div class='golf-club-list-nav'><ul style='margin-left:10px;'>";
			echo '<li id="let-All" class="sel"><a href="#" onClick="showLetter(\'All\');return false">All</a></li>';
			foreach($let as $k => $ltr) {

				$cur = ($k == 0) ? ' ' : '';
				echo '<li id="let-'.$ltr.'"'.$cur.'><a href="#" onClick="showLetter(\''.$ltr.'\');return false">'.$ltr.'</a></li>';
			}

			echo "</ul></div>";
			echo "<div class='golf-club-list-items'>";
			//$alpha = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
			$c = 0;



			echo '<div id="All"></div>';
			echo '<div id="'. $let[$c] .'" style="display:none;"><ul>';

			//print_r($Brands);
			foreach($Brands as $b) {

				while(strtoupper($b->manufacturer_name[0]) != $let[$c] && $b->manufacturer_name[0] != '1') {
					$c++;
					echo '</ul></div>';
					echo '<div id="'.$let[$c].'" style="display:none;"><ul>';
				}
				if(strtoupper($b->manufacturer_name[0]) == $let[$c] || $b->manufacturer_name[0] == '1') {

					echo '<li><a href="/brands/'.$b->url_safe_manufacturer_name.'.html">'.$b->manufacturer_name.'</a></li>';
				}

			}
			echo '</ul></div>';
			if($c != 26) {

				for($j = $c; $j < 26; $j++) {

					echo '<div id="'.$alpha[$j].'" style="display:none;"></div>';
				}
			}
			echo "</div>";
		}
	}
		function site_review_brand_list_components($all=0,$catName='',$catList=array())
	{

		global $GRclass;

		$parts = $GRclass->parse_url();
		//print_r($parts);

		if($catName != '') $parts['category'] = $catName;
		if(!empty($catList)) $parts['cat_list'] = implode("','", $catList);
		/*** Get alphabetical list of Golfreview product list ***/

		$PSite = explode(":", get_option( 'PSiteName' ) );
		$Brands = $GRclass->cr_get_brand_list_components( $parts, $all, $PSite[0] );
		//print_r($PSite);
		//print_r($Brands);

		//exit;

		if($Brands) {

			foreach($Brands as $bn) {

				/*if( $bn->manufacturer_name[0] != 1 ) {*/

					$letters[] = strtoupper($bn->manufacturer_name[0]);
				/*}*/
			}

			$let = array_unique($letters);

			echo "<div class='golf-club-list-nav'><ul style='margin-left:10px;'>";
			echo '<li id="let-All" class="sel"><a href="#" onClick="showLetter(\'All\');return false" >All</a></li>';
			foreach($let as $k => $ltr) {

				$cur = ($k == 0) ? ' ' : '';
				echo '<li id="let-'.$ltr.'"'.$cur.'><a href="#" onClick="showLetter(\''.$ltr.'\');return false">'.$ltr.'</a></li>';
			}

			echo "</ul></div>";
			echo "<div class='golf-club-list-items'>";
			//$alpha = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
			$c = 0;



			echo '<div id="All" style="display:none;"></div>';
			echo '<div id="'. $let[$c] .'" style="display:none;"><ul>';

			//print_r($Brands);
			foreach($Brands as $b) {

				while(strtoupper($b->manufacturer_name[0]) != $let[$c] && $b->manufacturer_name[0] != '1') {
					$c++;
					echo '</ul></div>';
					echo '<div id="'.$let[$c].'" style="display:none;"><ul>';
				}
				if(strtoupper($b->manufacturer_name[0]) == $let[$c] || $b->manufacturer_name[0] == '1') {

					echo '<li><a href="/brands/'.$b->url_safe_manufacturer_name.'.html">'.$b->manufacturer_name.'</a></li>';
				}

			}
			echo '</ul></div>';
			if($c != 26) {

				for($j = $c; $j < 26; $j++) {

					echo '<div id="'.$alpha[$j].'" style="display:none;"></div>';
				}
			}
			echo "</div>";
		}
	}


	function site_review_brand_list_outdoor_first($all=0,$catName='',$catList=array())
	{

		global $GRclass;

		$parts = $GRclass->parse_url();
		//print_r($parts);

		if($catName != '') $parts['category'] = $catName;
		if(!empty($catList)) $parts['cat_list'] = implode("','", $catList);
		/*** Get alphabetical list of Golfreview product list ***/

		$PSite = explode(":", get_option( 'PSiteName' ) );
		$Brands = $GRclass->cr_get_brand_list_outdoor_first( $parts, $all, $PSite[0] );
		//print_r($PSite);
		//print_r($Brands);

		//exit;

		if($Brands) {

			foreach($Brands as $bn) {

				/*if( $bn->manufacturer_name[0] != 1 ) {*/

					$letters[] = strtoupper($bn->manufacturer_name[0]);
				/*}*/
			}

			$let = array_unique($letters);

			echo "<div class='golf-club-list-nav'><ul style='margin-left:10px;'>";
			echo '<li id="let-All" class="sel"><a href="#" onClick="showLetter(\'All\');return false">All</a></li>';
			foreach($let as $k => $ltr) {

				$cur = ($k == 0) ? ' class=""' : '';
				echo '<li id="let-'.$ltr.'"'.$cur.'><a href="#" onClick="showLetter(\''.$ltr.'\');return false">'.$ltr.'</a></li>';
			}

			echo "</ul></div>";
			echo "<div class='golf-club-list-items'>";
			//$alpha = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
			$c = 0;
			echo '<div id="All"></div>';
			echo '<div id="'. $let[$c] .'" style="display:none;"><ul>';

			foreach($Brands as $b) {

				while(strtoupper($b->manufacturer_name[0]) != $let[$c] && $b->manufacturer_name[0] != '1') {
					$c++;
					echo '</ul></div>';
					echo '<div id="'.$let[$c].'" style="display:none;"><ul>';
				}
				if(strtoupper($b->manufacturer_name[0]) == $let[$c] || $b->manufacturer_name[0] == '1') {

					echo '<li><a href="';
					echo cr_brand_category_url_wcat_outdoor_first($b,$parts['category'],1);
					echo '">'.$b->manufacturer_name.'</a></li>';
				}
			}
			echo '</ul></div>';
			if($c != 26) {

				for($j = $c; $j < 26; $j++) {

					echo '<div id="'.$alpha[$j].'" style="display:none;"></div>';
				}
			}
			echo "</div>";
		}
	}


	function site_review_brand_list($all=0,$catName='',$catList=array())
	{

		global $GRclass;

		$parts = $GRclass->parse_url();
		//print_r($parts);

		if($catName != '') $parts['category'] = $catName;
		if(!empty($catList)) $parts['cat_list'] = implode("','", $catList);
		/*** Get alphabetical list of Golfreview product list ***/

		$PSite = explode(":", get_option( 'PSiteName' ) );
		$Brands = $GRclass->cr_get_brand_list( $parts, $all, $PSite[0] );
		//print_r($PSite);
		//print_r($Brands);

		//exit;

		if($Brands) {

			foreach($Brands as $bn) {

				/*if( $bn->manufacturer_name[0] != 1 ) {*/

					$letters[] = strtoupper($bn->manufacturer_name[0]);
				/*}*/
			}

			$let = array_unique($letters);

			echo "<div class='golf-club-list-nav'><ul style='margin-left:10px;'>";
			foreach($let as $k => $ltr) {

				$cur = ($k == 0) ? ' class="sel"' : '';
				echo '<li id="let-'.$ltr.'"'.$cur.'><a href="#" onClick="showLetter(\''.$ltr.'\');return false">'.$ltr.'</a></li>';
			}

			echo "</ul></div>";
			echo "<div class='golf-club-list-items'>";
			//$alpha = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
			$c = 0;
			echo '<div id="'. $let[$c] .'"><ul>';

			foreach($Brands as $b) {

				while(strtoupper($b->manufacturer_name[0]) != $let[$c] && $b->manufacturer_name[0] != '1') {
					$c++;
					echo '</ul></div>';
					echo '<div id="'.$let[$c].'" style="display:none;"><ul>';
				}
				if(strtoupper($b->manufacturer_name[0]) == $let[$c] || $b->manufacturer_name[0] == '1') {

					echo '<li><a href="';
					if($all == 1) echo cr_brand_url($b,1);
					else echo cr_brand_category_url_wcat($b,$parts['category'],1);
					echo '">'.$b->manufacturer_name.'</a></li>';
				}
			}
			echo '</ul></div>';
			if($c != 26) {

				for($j = $c; $j < 26; $j++) {

					echo '<div id="'.$alpha[$j].'" style="display:none;"></div>';
				}
			}
			echo "</div>";
		}
	}




	?>
<?php
	//Mari Added for Products alphabet
	function site_review_brand_list_search1($all=0,$catName='',$catList=array()) {

		global $GRclass;

		$parts = $GRclass->parse_url();
		//print_r($parts);

		if($catName != '') $parts['category'] = $catName;
		if(!empty($catList)) $parts['cat_list'] = implode("','", $catList);
		/*** Get alphabetical list of Golfreview product list ***/

		$PSite = explode(":", get_option( 'PSiteName' ) );
		$Brands1 = $GRclass->cr_get_brand_list_search( $parts, $all, $PSite[0] );
		//print_r($PSite);

		//exit;

		if($Brands1) {

			foreach($Brands1 as $bn1) {

				if( $bn1->manufacturer_name[0] != 1 ) {

					$letters1[] = strtoupper($bn1->manufacturer_name[0]);
				}
			}

			$let1 = array_unique($letters1);

			echo "<div class='golf-club-list-items'>";
			//$alpha = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
			$c = 0;
			echo '<div id="'. $let1[$c] .'xyz"><ul>';

			foreach($Brands1 as $b) {

				while(strtoupper($b->manufacturer_name[0]) != $let1[$c] && $b->manufacturer_name[0] != '1') {
					$c++;
					echo '</ul></div>';
					echo '<div id="'.$let1[$c].'xyz" style="display:none;"><ul>';
				}
				if(strtoupper($b->manufacturer_name[0]) == $let1[$c] || $b->manufacturer_name[0] == '1') {

					//echo '<li><a href="';
					if($all == 1) //echo cr_brand_url_search($b,1);
					echo '<li><a href="#" onclick="abcd(\''.$b->manufacturer_name.'\');">'.$b->manufacturer_name.'</a></li>';
					else echo cr_brand_category_url_wcat_search($b,$parts['category'],1);
					//echo '">'.$b->manufacturer_name.'</a></li>';

				}
			}
			echo '</ul></div>';
			echo "<div class='golf-club-list-nav'><ul style='margin-left:10px;'>";
			foreach($let1 as $k => $ltr1) {

				$cur = ($k == 0) ? ' class="sel"' : '';
				echo '<li id="let1-'.$ltr1.'xyz"'.$cur.'><a href="#" onClick="showLetter1(\''.$ltr1.'xyz\');return false">'.$ltr1.'</a></li>';
			}

			echo "</ul></div>";
			if($c != 26) {

				for($j = $c; $j < 26; $j++) {

					echo '<div id="'.$alpha[$j].'" style="display:none;"></div>';
				}
			}
			echo "</div>";
		}
	}
//Mari Added this function for search
	function site_review_brand_list_search($all=0,$catName='',$catList=array()) {

		global $GRclass;

		$parts = $GRclass->parse_url();
		//print_r($parts);

		if($catName != '') $parts['category'] = $catName;
		if(!empty($catList)) $parts['cat_list'] = implode("','", $catList);
		/*** Get alphabetical list of Golfreview product list ***/
		$PSite = explode(":", get_option( 'PSiteName' ) );
		$Brands1 = $GRclass->cr_get_brand_list_search( $parts, $all, $PSite[0] );
		//echo "<pre>";print_r($Brands1);echo "<pre>";

		//exit;

		if($Brands1) {

			foreach($Brands1 as $bn1) {

				if( $bn1->manufacturer_name[0] != 1 ) {

					$letters1[] = strtoupper($bn1->manufacturer_name[0]);
				}
			}

			$let1 = array_unique($letters1);
			echo "Or choose from popular brands";
			echo "<div class='golf-club-list-items'>";
			//$alpha = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
			$c = 0;
			echo '<div id="'. $let1[$c] .'xyz"><ul>';

			foreach($Brands1 as $b) {

				while(strtoupper($b->manufacturer_name[0]) != $let1[$c] && $b->manufacturer_name[0] != '1') {
					$c++;
					echo '</ul></div>';
					echo '<div id="'.$let1[$c].'xyz" style="display:none;"><ul>';
				}
				if(strtoupper($b->manufacturer_name[0]) == $let1[$c] || $b->manufacturer_name[0] == '1') {

					//echo '<li><a href="';
					$parts	= explode('/',$_SERVER['REQUEST_URI']);
					$catry	= explode('.', $parts[1]);
					$sri= get_bloginfo('url') . '/' . $catry[0] . '/brand/' . $b->url_safe_manufacturer_name . '.html';

					if($all == 1) //echo cr_brand_url_search($b,1);
					echo '<li><a class="mari" href="#" onclick="abcd(\''.$sri.'\');">'.$b->manufacturer_name.'</a></li>';

					else
					echo '<li><a class="mari" href="#" onclick="abcd(\''.$sri.'\');">'.$b->manufacturer_name.'</a></li>';
					//echo cr_brand_category_url_wcat_search($b,$parts['category'],1);
					//echo '">'.$b->manufacturer_name.'</a></li>';

				}
			}
			echo '</ul></div>';
			echo "Or find brand alphabetically";
			echo "<div class='golf-club-list-nav'><ul style='margin-left:10px;'>";
			foreach($let1 as $k => $ltr1) {

				$cur = ($k == 0) ? ' class="sel"' : '';
				echo '<li id="let1-'.$ltr1.'xyz"'.$cur.'><a href="#" onClick="showLetter1(\''.$ltr1.'xyz\');return false">'.$ltr1.'</a></li>';
			}

			echo "</ul></div>";
			if($c != 26) {

				for($j = $c; $j < 26; $j++) {

					echo '<div id="'.$alpha[$j].'" style="display:none;"></div>';
				}
			}
			echo "</div>";
		}
	}


	function mtbr_site_review_product_review_page()
	{

		global $GRclass, $Product, $formAction;

		/** Parse URL */
		$parts = $GRclass->mtbr_parse_product_review_url();
		//print_r($parts);

		if($parts) {
			/** Product Info */
			$PSite = explode(":", get_option( 'PSiteName' ) ); //Added by Zaved as on 17/Nov/2016
			$SSite = explode(":", get_option( 'SSiteName' ) ); //Added by Zaved as on 24/Nov/2016
			$TSite = explode(":", get_option( 'TSiteName' ) ); //Added by Zaved as on 24/Nov/2016

			$Product = $GRclass->get_product_detail( $parts, $PSite[0], $SSite[0], $TSite[0] );

			//echo  $PSite[0].$SSite[0];

			$Product->review_url	= $_SERVER['REQUEST_URI'];
			$Product->product_url	= str_replace("-review","",$_SERVER['REQUEST_URI']);
		}
	}

	function golf_site_review_product_review_page() {

		global $GRclass, $Product, $formAction;

		/** Parse URL */
		$parts = $GRclass->golf_parse_product_review_url();
		//print_r($parts);

		if($parts) {
			/** Product Info */
			$PSite = explode(":", get_option( 'PSiteName' ) ); //Added by Zaved as on 17/Nov/2016
			$SSite = explode(":", get_option( 'SSiteName' ) ); //Added by Zaved as on 24/Nov/2016
			$TSite = explode(":", get_option( 'TSiteName' ) ); //Added by Zaved as on 24/Nov/2016

			$Product = $GRclass->golf_get_product_detail( $parts, $PSite[0], $SSite[0], $TSite[0] );

			$Product->review_url	= $_SERVER['REQUEST_URI'];
			$Product->product_url	= str_replace("-review","",$_SERVER['REQUEST_URI']);
		}
	}

	function site_review_product_review_page_outdoor() {

		global $GRclass, $Product, $formAction;

		/** Parse URL */
		$parts = $GRclass->parse_product_review_url_outdoor();
		//print_r($parts);

		if($parts) {
			/** Product Info */
			$PSite = explode(":", get_option( 'PSiteName' ) ); //Added by Zaved as on 17/Nov/2016
			$SSite = explode(":", get_option( 'SSiteName' ) ); //Added by Zaved as on 24/Nov/2016
			$TSite = explode(":", get_option( 'TSiteName' ) ); //Added by Zaved as on 24/Nov/2016

			$Product = $GRclass->get_product_detail( $parts, $PSite[0], $SSite[0], $TSite[0] );

			$Product->review_url	= $_SERVER['REQUEST_URI'];
			$Product->product_url	= str_replace("-review","",$_SERVER['REQUEST_URI']);
		}
	}

	function site_review_product_review_page() {

		global $GRclass, $Product, $formAction;

		/** Parse URL */
		$parts = $GRclass->parse_product_review_url();
		//print_r($parts);

		if($parts) {
			/** Product Info */
			$PSite = explode(":", get_option( 'PSiteName' ) ); //Added by Zaved as on 17/Nov/2016
			$SSite = explode(":", get_option( 'SSiteName' ) ); //Added by Zaved as on 24/Nov/2016
			$TSite = explode(":", get_option( 'TSiteName' ) ); //Added by Zaved as on 24/Nov/2016

			$Product = $GRclass->get_product_detail( $parts, $PSite[0], $SSite[0], $TSite[0] );

			$Product->review_url	= $_SERVER['REQUEST_URI'];
			$Product->product_url	= str_replace("-review","",$_SERVER['REQUEST_URI']);
		}
	}

	function site_review_product_submit_review($review) {

		global $GRclass, $Result;

		$PSite = explode(":", get_option( 'PSiteName' ) ); //Added by Zaved as on 17/Nov/2016
		$review['PSite'] = $PSite[0];
		$Result = $GRclass->submit_product_review($review);
	}

	/*** Pagination function for brand list page*/

	function cr_brand_pagination_outdoor_brand_two($brand,$current,$perpage=50) {

		$current = (!is_numeric($current)) ? 1 : $current;
		$curl = explode("?",$_SERVER['REQUEST_URI']);
		if($brand->product_count > $perpage) {

			echo 'Show ';

			$totalPages = ceil($brand->product_count/$perpage);
			$prev = $current - 1;
			$next = $current + 1;
				if(isset($_GET['sort']))
				{
						$sort="&sort=".$_GET['sort'];
				}

			if($current != 1) echo '<a  class="BtnWriteReview" id="featured-nav" style="font-weight: normal;text-decoration:none;padding: 4px;border-radius: 2px;color: #fff;" href="' . $curl[0] . '?pg='.$prev.$sort.'">Prev 50</a> ';
			else echo '';

			if($current != $totalPages)

				echo ' <a  class="BtnWriteReview" id="featured-nav"   style="font-weight: normal;text-decoration:none;padding: 4px; border-radius: 2px;color: #fff;" href="' . $curl[0] . '?pg='.$next.$sort.'">Next 50</a>';
		}
	}

	function cr_brand_pagination_outdoor_brand_one($brand,$current,$perpage=50) {

		$current = (!is_numeric($current)) ? 1 : $current;
		$curl = explode("?",$_SERVER['REQUEST_URI']);
		if($brand->product_count > $perpage) {

			echo 'Show ';

			$totalPages = ceil($brand->product_count/$perpage);
			$prev = $current - 1;
			$next = $current + 1;
				if(isset($_GET['sort']))
				{
						$sort="&sort=".$_GET['sort'];
				}

			if($current != 1) echo '<a  class="BtnWriteReview" id="featured-nav" style="font-weight: normal;text-decoration:none;padding: 4px;border-radius: 2px;color: #fff;" href="' . $curl[0] . '?pg='.$prev.$sort.'">Prev 50</a> ';
			else echo '';

			if($current != $totalPages)

				echo ' <a  class="BtnWriteReview" id="featured-nav"   style="font-weight: normal;text-decoration:none;padding: 4px; border-radius: 2px;color: #fff;" href="' . $curl[0] . '?pg='.$next.$sort.'">Next 50</a>';
		}
	}

	function cr_brand_pagination($brand,$current,$perpage=50) {

		$current = (!is_numeric($current)) ? 1 : $current;

		if($brand->product_count > $perpage) {

			echo 'Show ';

			$totalPages = ceil($brand->product_count/$perpage);
			$prev = $current - 1;
			$next = $current + 1;

			if($current != 1) echo '<a  class="BtnWriteReview" id="featured-nav" style="font-weight: normal;text-decoration:none;padding: 4px;border-radius: 2px;color: #fff;" href="' . cr_brand_url($brand,1) . '?pg='.$prev.'">Prev 50</a> ';
			else echo '';

			if($current != $totalPages)

				echo ' <a  class="BtnWriteReview" id="featured-nav"   style="font-weight: normal;text-decoration:none;padding: 4px; border-radius: 2px;color: #fff;" href="' . cr_brand_url($brand,1) . '?pg='.$next.'">Next 50</a>';
		}
	}

	/*** Pagination function for brand-cat list page */

	function cr_brand_cat_pagination($brand,$current,$perpage=50) {

		$current = (!is_numeric($current)) ? 1 : $current;

		if($brand->product_count > $perpage) {

			echo 'Show ';

			$totalPages = ceil($brand->product_count/$perpage);
			$prev = $current - 1;
			$next = $current + 1;
				if(isset($_GET['sort']))
				{
						$sort="&sort=".$_GET['sort'];
				}

			if($current != 1) echo '<a  class="BtnWriteReview" id="featured-nav" style="font-weight: normal;text-decoration:none;padding: 4px; /*background-color: rgb(9, 111, 0);*/border-radius: 2px;color: #fff;" href="' . cr_brand_category_url_wcat_outdoor_brand_three($brand,1) . '?pg='.$prev.$sort.'">Prev 50</a> ';
			else echo '';

			if($current != $totalPages)

				echo ' <a  class="BtnWriteReview" id="featured-nav"  style="font-weight: normal;text-decoration:none;padding: 4px;border-radius: 2px;color: #fff;" href="' . cr_brand_category_url_wcat_outdoor_brand_three($brand,1) . '?pg='.$next.$sort.'">Next 50</a>';





		}
	}

	/*** Output of 'Showing X-X+50 of Product'*/

	function cr_product_count_showing($total,$current,$perpage=50) {

		$current = (is_numeric($current)) ? $current : 1;
		$max = ceil($total->product_count/$perpage);
		$start = $current * $perpage - 49;
		if($current == $max) $end = $total->product_count;
		else $end = $current * $perpage;
		echo $start . '-' . $end;
	}

	function cr_category_permalink($category_safe_url,$return=0) {

		/**
		 * conversions of category permalink terms for website
		 *	 fairway-woods => fairway_woods
		 * 	 balls => golf_balls
		 *   shoes => golf_shoes
		 *   bags => golf_bags*/

		if($return == 1)

			return $category_safe_url;

		else

			echo $category_safe_url;
	}

	function cr_category_name($cat,$return=0) {

		/**
		 * Category Name from Database to Website conversion
		 * 	- Balls => Golf Balls
		 *  - Bags => Golf Bags
		 *  - Shoes => Golf Shoes
		 */

		global $wpdb;

		//Added By Zaved as on 12 Dec 2016
		/*$url = explode( '/', $_SERVER['REQUEST_URI']);
		$return1 = explode( '.html', $url[1] );
		$return2 = str_replace( '-', ' ', strtoupper($return1[0]) );

		$sql_id = "Select ID From wp_posts Where post_title = '".$return2."' AND post_type = 'page'";
		$ID = $wpdb->get_var($sql_id);
		$sql_cat = "Select post_title From wp_posts Where post_parent = '$ID' and post_type = 'page'";
		$Results = $wpdb->get_results($sql_cat);
		$rep = array(); $str = array();

		for ( $i=0; $i < count($Results); $i++  ) {

			$arry = get_object_vars($Results[$i]);
			$str[] = str_replace( 'Golf', '', ucwords( strtolower($arry['post_title']) ) );
			$rep[] = ucwords( strtolower ( $arry['post_title'] ) );
		}
		*/

		//Edit By Zaved as on 12 Dec 2016
		//$str = array('Bags','Balls','Shoes');
		//$c = in_array($cat, $str ) ? 'Golf '.$cat : $cat ;
		$c = $cat ;

		if($return == 1)

			return $c;

		else

			echo $c;
	}

	/*** cr_rating_image: returns the image for a rating*/

	function cr_rating_image($rating) {

		$imgUrl = get_bloginfo('template_url') . '/images/__star.png';

		if($rating > 4.5) return str_replace('__','5',$imgUrl);
		if($rating > 4) return str_replace('__','45',$imgUrl);
		if($rating > 3.5) return str_replace('__','4',$imgUrl);
		if($rating > 3) return str_replace('__','35',$imgUrl);
		if($rating > 2.5) return str_replace('__','3',$imgUrl);
		if($rating > 2) return str_replace('__','25',$imgUrl);
		if($rating > 1.5) return str_replace('__','2',$imgUrl);
		if($rating > 1) return str_replace('__','15',$imgUrl);
		if($rating > .5) return str_replace('__','1',$imgUrl);
		if($rating > 0) return str_replace('__','05',$imgUrl);
		if($rating == 0) return str_replace('__','0',$imgUrl);
	}


	function cr_state($i,$t) {

		$states = array(0 => array(	0 => 'AL',1 => 'Alabama',),
					1 => array( 0 => 'AK',1 => 'Alaska',),
					2 => array( 0 => 'AZ',1 => 'Arizona',),
					3 => array( 0 => 'AR',1 => 'Arkansas',),
					4 => array( 0 => 'CA',1 => 'California',),
					5 => array( 0 => 'CO',1 => 'Colorado',),
					6 => array( 0 => 'CT',1 => 'Connecticut',),
					7 => array( 0 => 'DE',1 => 'Delaware',),
					8 => array( 0 => 'DC',1 => 'District of Columbia',),
					9 => array( 0 => 'FL',1 => 'Florida',),
					10 => array( 0 => 'GA',1 => 'Georgia',),
					11 => array( 0 => 'HI',1 => 'Hawaii',),
					12 => array( 0 => 'ID',1 => 'Idaho',),
					13 => array( 0 => 'IL',1 => 'Illinois',),
					14 => array( 0 => 'IN',1 => 'Indiana',),
					15 => array( 0 => 'IA',1 => 'Iowa',),
					16 => array( 0 => 'KS',1 => 'Kansas',),
					17 => array( 0 => 'KY',1 => 'Kentucky',),
					18 => array( 0 => 'LA',1 => 'Louisiana',),
					19 => array( 0 => 'ME',1 => 'Maine',),
					20 => array( 0 => 'MD',1 => 'Maryland',),
					21 => array( 0 => 'MA',1 => 'Massachusetts',),
					22 => array( 0 => 'MI',1 => 'Michigan',),
					23 => array( 0 => 'MN',1 => 'Minnesota',),
					24 => array( 0 => 'MS',1 => 'Mississippi',),
					25 => array( 0 => 'MO',1 => 'Missouri',),
					26 => array( 0 => 'MT',1 => 'Montana',),
					27 => array( 0 => 'NE',1 => 'Nebraska',),
					28 => array( 0 => 'NV',1 => 'Nevada',),
					29 => array( 0 => 'NH',1 => 'New Hampshire',),
					30 => array( 0 => 'NJ',1 => 'New Jersey',),
					31 => array( 0 => 'NM',1 => 'New Mexico',),
					32 => array( 0 => 'NY',1 => 'New York',),
					33 => array( 0 => 'NC',1 => 'North Carolina',),
					34 => array( 0 => 'ND',1 => 'North Dakota',),
					35 => array( 0 => 'OH',1 => 'Ohio',),
					36 => array( 0 => 'OK',1 => 'Oklahoma',),
					37 => array( 0 => 'OR',1 => 'Oregon',),
					38 => array( 0 => 'PA',1 => 'Pennsylvania',),
					39 => array( 0 => 'RI',1 => 'Rhode Island',),
					40 => array( 0 => 'SC',1 => 'South Carolina',),
					41 => array( 0 => 'SD',1 => 'South Dakota',),
					42 => array( 0 => 'TN',1 => 'Tennessee',),
					43 => array( 0 => 'TX',1 => 'Texas',),
					44 => array( 0 => 'UT',1 => 'Utah',),
					45 => array( 0 => 'VT',1 => 'Vermont',),
					46 => array( 0 => 'VA',1 => 'Virginia',),
					47 => array( 0 => 'WA',1 => 'Washington',),
					48 => array( 0 => 'WV',1 => 'West Virginia',),
					49 => array( 0 => 'WI',1 => 'Wisconsin',),
					50 => array( 0 => 'WY',1 => 'Wyoming',),
					51 => array( 0 => 'PR',1 => 'Puerto Rico',),
					52 => array( 0 => 'VI',1 => 'Virgin Islands',),
				);
		return $states[$i][$t];
	}

	function cr_state_name($abbr,$url=0) {

		$state = array( 'AL' => 'Alabama','AK' => 'Alaska','AZ' => 'Arizona','AR' => 'Arkansas','CA' => 'California','CO' => 'Colorado','CT' => 'Connecticut','DE' => 'Delaware','DC' => 'District of Columbia','FL' => 'Florida','GA' => 'Georgia','HI' => 'Hawaii','ID' => 'Idaho','IL' => 'Illinois','IN' => 'Indiana','IA' => 'Iowa','KS' => 'Kansas','KY' => 'Kentucky','LA' => 'Louisiana','ME' => 'Maine','MD' => 'Maryland','MA' => 'Massachusetts',	'MI' => 'Michigan','MN' => 'Minnesota','MS' => 'Mississippi','MO' => 'Missouri','MT' => 'Montana','NE' => 'Nebraska','NV' => 'Nevada','NH' => 'New Hampshire','NJ' => 'New Jersey','NM' => 'New Mexico','NY' => 'New York','NC' => 'North Carolina','ND' => 'North Dakota','OH' => 'Ohio','OK' => 'Oklahoma','OR' => 'Oregon','PA' => 'Pennsylvania','RI' => 'Rhode Island','SC' => 'South Carolina','SD' => 'South Dakota','TN' => 'Tennessee','TX' => 'Texas','UT' => 'Utah','VT' => 'Vermont','VA' => 'Virginia','WA' => 'Washington','WV' => 'West Virginia','WI' => 'Wisconsin','WY' => 'Wyoming','PR' => 'Puerto Rico','VI' => 'Virgin Islands');
		if($url == 1) $state[$abbr] = str_replace(" ","-",$state[$abbr]);

		return $state[$abbr];
	}

function mtbr_cr_state_name($abbr,$url=0) {

		$state = array( 'AL' => 'Alabama','AK' => 'Alaska','AZ' => 'Arizona','AR' => 'Arkansas','CA' => 'California','CO' => 'Colorado','CT' => 'Connecticut','DE' => 'Delaware','DC' => 'District of Columbia','FL' => 'Florida','GA' => 'Georgia','HI' => 'Hawaii','ID' => 'Idaho','IL' => 'Illinois','IN' => 'Indiana','IA' => 'Iowa','KS' => 'Kansas','KY' => 'Kentucky','LA' => 'Louisiana','ME' => 'Maine','MD' => 'Maryland','MA' => 'Massachusetts',	'MI' => 'Michigan','MN' => 'Minnesota','MS' => 'Mississippi','MO' => 'Missouri','MT' => 'Montana','NE' => 'Nebraska','NV' => 'Nevada','NH' => 'New Hampshire','NJ' => 'New Jersey','NM' => 'New Mexico','NY' => 'New York','NC' => 'North Carolina','ND' => 'North Dakota','OH' => 'Ohio','OK' => 'Oklahoma','OR' => 'Oregon','PA' => 'Pennsylvania','RI' => 'Rhode Island','SC' => 'South Carolina','SD' => 'South Dakota','TN' => 'Tennessee','TX' => 'Texas','UT' => 'Utah','VT' => 'Vermont','VA' => 'Virginia','WA' => 'Washington','WV' => 'West Virginia','WI' => 'Wisconsin','WY' => 'Wyoming','PR' => 'Puerto Rico','VI' => 'Virgin Islands');
		if($url == 1) $state[$abbr] = str_replace(" ","-",$state[$abbr]);

		return $state[$abbr];
	}

	function cr_state_abbr($state) {

		$abbr = array('Alabama' => 'AL','Alaska' => 'AK','Arizona' => 'AZ','Arkansas' => 'AR','California' => 'CA','Colorado' => 'CO','Connecticut' => 'CT','Delaware' => 'DE','District-of-Columbia' => 'DC','Florida' => 'FL','Georgia' => 'GA','Hawaii' => 'HI','Idaho' => 'ID','Illinois' => 'IL','Indiana' => 'IN','Iowa' => 'IA','Kansas' => 'KS','Kentucky' => 'KY','Louisiana' => 'LA','Maine' => 'ME','Maryland' => 'MD','Massachusetts' => 'MA','Michigan' => 'MI','Minnesota' => 'MN','Mississippi' => 'MS','Missouri' => 'MO','Montana' => 'MT','Nebraska' => 'NE','Nevada' => 'NV','New-Hampshire' => 'NH','New-Jersey' => 'NJ','New-Mexico' => 'NM','New-York' => 'NY','North-Carolina' => 'NC','North-Dakota' => 'ND','Ohio' => 'OH','Oklahoma' => 'OK','Oregon' => 'OR','Pennsylvania' => 'PA','Rhode-Island' => 'RI','South-Carolina' => 'SC','South-Dakota' => 'SD','Tennessee' => 'TN','Texas' => 'TX','Utah' => 'UT','Vermont' => 'VT','Virginia' => 'VA','Washington' => 'WA','West-Virginia' => 'WV','Wisconsin' => 'WI','Wyoming' => 'WY','Puerto-Rico' => 'PR','Virgin-Islands' => 'VI');

		return $abbr[$state];
	}


function mtbr_cr_state_abbr($state) {

		$newstate = explode("-",$state);

		$abbr = array('Alabama' => 'AL','Alaska' => 'AK','Arizona' => 'AZ','Arkansas' => 'AR','California' => 'CA','Colorado' => 'CO','Connecticut' => 'CT','Delaware' => 'DE','District-of-Columbia' => 'DC','Florida' => 'FL','Georgia' => 'GA','Hawaii' => 'HI','Idaho' => 'ID','Illinois' => 'IL','Indiana' => 'IN','Iowa' => 'IA','Kansas' => 'KS','Kentucky' => 'KY','Louisiana' => 'LA','Maine' => 'ME','Maryland' => 'MD','Massachusetts' => 'MA','Michigan' => 'MI','Minnesota' => 'MN','Mississippi' => 'MS','Missouri' => 'MO','Montana' => 'MT','Nebraska' => 'NE','Nevada' => 'NV','New-Hampshire' => 'NH','New-Jersey' => 'NJ','New-Mexico' => 'NM','New-York' => 'NY','North-Carolina' => 'NC','North-Dakota' => 'ND','Ohio' => 'OH','Oklahoma' => 'OK','Oregon' => 'OR','Pennsylvania' => 'PA','Rhode-Island' => 'RI','South-Carolina' => 'SC','South-Dakota' => 'SD','Tennessee' => 'TN','Texas' => 'TX','Utah' => 'UT','Vermont' => 'VT','Virginia' => 'VA','Washington' => 'WA','West-Virginia' => 'WV','Wisconsin' => 'WI','Wyoming' => 'WY','Puerto-Rico' => 'PR','Virgin-Islands' => 'VI');

		if($newstate[0]=='trails')
		{
			$newfull = str_replace("trails-","",$state);

			return $abbr[$newfull];

		}
		else
		{
			//$newfull = str_replace("trails-","",$state);
			return $abbr[$state];
		}
}


	function site_review_recent_articles($title, $categories=array(), $count=5) {

		/*** category modifications ***/
		//Zaved - Since this is just a modifiction for string to the case of golf courses - then let be hard coded.
		$cat_str = array('Golf Balls','Golf Bags','Golf Shoes','golf courses','public golf courses', 'best golf courses','driving ranges');
		$cat_rep = array('Balls','Bags','Shoes','Golf Courses','Public Courses','Best Courses','Driving Ranges');

		for($i=0; $i < count($categories); $i++) {

			$categories[$i] = str_replace($cat_str, $cat_rep, $categories[$i]);
			$cat = get_term_by('name', $categories[$i], 'category');

			if($cat) {

				$cat_id[] = $cat->term_id;
			}
			else {

				$cat_id[] = 0;
			}
		}

		if(is_array($cat_id)) {

			$terms = implode(",",$cat_id);
			$terms = 'cat='.$terms.'&';

		} else {

			$terms = '';
		}

		if($terms != '' || is_home()) {

			query_posts($terms.'posts_per_page='.$count);?>
			<?php if( have_posts() ) : ?>
			<div class="text-header"><h2><?php echo $title; ?></h2></div>
			<?php while ( have_posts() ) : the_post(); ?>
			<div class="item-list-box clearfix">
				<div class="item-box">
					<div class="title"><a href="<?php the_permalink() ?>"><?php the_title() ?></a></div>
					<div class="meta"><div class="comments"><?php comments_number( '0 Comments', '1 Comment', '% Comments' ); ?></div>
						by <?php the_author() ?> &nbsp; <?php the_time('F d, Y') ?></div>
					<div class="text"><?php the_excerpt() ?></div>
				</div>
			</div>
			<?php endwhile; ?>
				<div class="see-more text-right"><a class="arrow-right" href="/reviews.html">see all</a></div>
			<?php endif; ?>
			<?php wp_reset_query(); ?><?php
		}
	}

	function cr_product_partner_links() {

		include(__DIR__.'/../../../wp-config-extra.php');
		global $GRclass, $Product;

		$parts = $GRclass->parse_url();

		// Product Channel ID
		$PSite = explode(":", get_option( 'PSiteName' ) ); // Added by Zaved as on 16/Nov/2016

		// Course channel ID
		$SSite = explode(":", get_option( 'SSiteName' ) ); //Added by Zaved as on 24/Nov/2016

		// Course channel ID
		$TSite = explode(":", get_option( 'TSiteName' ) ); //Added by Zaved as on 24/Nov/2016

		/** Product Info */
		$pname = explode('/',$_SERVER[REQUEST_URI]);
		$parray_name = explode(".",$pname[count($pname)-1]);

		$parray['product']=$parray_name[0]; //product name
		$parray['product_category']=$pname[count($pname)-3]; //;ast level category
		$parray['category']=$pname[count($pname)-2]; //brand name

		/** Product Info */
		$Product = $GRclass->get_product_detail( $parray, $PSite[0], $SSite[0], $TSite[0] );
		//print_r($Product);
		if($Product->productid) {

			$partner_links = $GRclass->get_product_partner_links($Product->productid);
			//print_r($partner_links);
			if($partner_links) { ?>

			<div class="sidebox product-partner-detail" style="clear:both;float:left;margin-top:20px;width: 304px!important;border: 2px solid #999;">
			<table cellspacing="0">

			<tr><th colspan="2" style="text-transform: uppercase;">Avaliable At The Following Stores</th></tr>
			<?php foreach($partner_links as $partner) { ?>
				<tr>

			<?php

			
			$pcdn = $PRODUCTIMAGE_CDNDOMAIN."/".$PRODUCTIMAGE_S3FOLDER; 
			$pnewimg3 = $pcdn.$partner->partner_graphic;

			 ?>
			<td width="180"><a href="/commerceredirect.html?linkid=<?php echo $partner->linkid; ?>&referrer=FML_PRD_Sidebar"><p style="text-align:center;"><img src="<?php echo  $pnewimg3;?>" /></p></a></td>
			<td><a href="/commerceredirect.html?linkid=<?php echo $partner->linkid; ?>&referrer=FML_PRD_Sidebar"><p class="hotdeal-buy-all-mer-fml" style="text-align:center; display: block; width: 89px;}">Shop Now</p></a></td>

				</tr>
			<?php } ?>
			</table>
			</div><?php
			}
		}
	}


	function cr_site_location_details_mobile() {

		global $GRclass;


		$parts	= $GRclass->parse_location_url();
		$Course = $GRclass->get_site_location_detail($parts);

					$city = "";
					$state = "";
					$zipcode = "";
					$phone = "";
					$lat = "";
					$long = "";
					$shophrs = "";
					$webaddr = "";
					$bikeshops = "";
					$info = "";
					$traillength = "";
					$traillevel = "";
					$trailtype = "";
					$traildirection = "";

			for ($x = 0; $x < count($Course); $x++)
			{
				//echo "The number is: ".$Course[$x]->attributeid." <br>";

				if($Course[$x]->attributeid == "69")
				{
					$city = $Course[$x]->attribute_value;
				}
				if($Course[$x]->attributeid == "70")
				{
					$state = $Course[$x]->attribute_value;
				}
				if($Course[$x]->attributeid == "71")
				{
					$zipcode = $Course[$x]->attribute_value;
				}

				if($Course[$x]->attributeid == "74")
				{
					$phone = $Course[$x]->attribute_value;
				}
				if($Course[$x]->attributeid == "343")
				{
					$lat = $Course[$x]->attribute_value;
				}
				if($Course[$x]->attributeid == "342")
				{
					$long = $Course[$x]->attribute_value;
				}
				if($Course[$x]->attributeid == "340")
				{
					$shophrs = $Course[$x]->attribute_value;
				}
				if($Course[$x]->attributeid == "87")
				{
					$webaddr = $Course[$x]->attribute_value;
				}
				if($Course[$x]->attributeid == "339")
				{
					$bikeshops = $Course[$x]->attribute_value;
				}
				if($Course[$x]->attributeid == "334")
				{
					$info = $Course[$x]->attribute_value;
				}
				if($Course[$x]->attributeid == "80")
				{
					$traillength = $Course[$x]->attribute_value;
				}
				if($Course[$x]->attributeid == "86")
				{
					$traillevel = $Course[$x]->attribute_value;
				}
				if($Course[$x]->attributeid == "81")
				{
					$trailtype = $Course[$x]->attribute_value;
				}
				if($Course[$x]->attributeid == "85")
				{
					$traildirection = $Course[$x]->attribute_value;
				}

			}
		?>
		<div class="sidebox course-detail" style="width: 100%;    height: 270px;">

			<table cellspacing="0">
				<tr><td>Latitude</td><td><?php echo $lat; ?></td></tr>
				<tr><td>Longitude</td><td><?php echo $long; ?></td></tr>
				<tr><td>City</td><td><?php echo $city; ?></td></tr>
				<tr><td>State</td><td><?php echo $state; ?></td></tr>
				<tr><td>Zip/Postal Code</td><td><?php echo $zipcode; ?></td></tr>
				<tr><td>Country</td><td><?php echo ucfirst($parts['state']); ?></td></tr>
				<tr><td>Phone</td><td><?php echo $phone; ?></td></tr>

			</table>

		</div>
		<?php

	}

	function cr_site_location_details_mobile_with_map($width,$height) {

		global $GRclass;
		include(__DIR__."/../../../wp-config-extra.php");

		$channel=explode("/",$_SERVER[REQUEST_URI]);
		$parts	= $GRclass->parse_location_url();
		$Course = $GRclass->get_site_location_detail($parts);

					$city = "";
					$state = "";
					$zipcode = "";
					$phone = "";
					$lat = "";
					$long = "";
					$shophrs = "";
					$webaddr = "";
					$bikeshops = "";
					$info = "";
					$traillength = "";
					$traillevel = "";
					$trailtype = "";
					$traildirection = "";

			for ($x = 0; $x < count($Course); $x++)
			{
				//echo "The number is: ".$Course[$x]->attributeid." <br>";

				if($Course[$x]->attributeid == "69")
				{
					$city = $Course[$x]->attribute_value;
				}
				if($Course[$x]->attributeid == "70")
				{
					$state = $Course[$x]->attribute_value;
				}
				if($Course[$x]->attributeid == "71")
				{
					$zipcode = $Course[$x]->attribute_value;
				}

				if($Course[$x]->attributeid == "74")
				{
					$phone = $Course[$x]->attribute_value;
				}
				if($Course[$x]->attributeid == "343")
				{
					$lat = $Course[$x]->attribute_value;
				}
				if($Course[$x]->attributeid == "342")
				{
					$long = $Course[$x]->attribute_value;
				}
				if($Course[$x]->attributeid == "340")
				{
					$shophrs = $Course[$x]->attribute_value;
				}
				if($Course[$x]->attributeid == "87")
				{
					$webaddr = $Course[$x]->attribute_value;
				}
				if($Course[$x]->attributeid == "339")
				{
					$bikeshops = $Course[$x]->attribute_value;
				}
				if($Course[$x]->attributeid == "334")
				{
					$info = $Course[$x]->attribute_value;
				}
				if($Course[$x]->attributeid == "80")
				{
					$traillength = $Course[$x]->attribute_value;
				}
				if($Course[$x]->attributeid == "86")
				{
					$traillevel = $Course[$x]->attribute_value;
				}
				if($Course[$x]->attributeid == "81")
				{
					$trailtype = $Course[$x]->attribute_value;
				}
				if($Course[$x]->attributeid == "85")
				{
					$traildirection = $Course[$x]->attribute_value;
				}

			}
		?>
		<div class="sidebox course-detail" style="width: 100%;">


			<?php if($channel[1]=='golf-courses') {
			$fullurl = explode('/',$_SERVER['REQUEST_URI']);
				?>
				<div id="mapmobile" style='width:<?php echo $width; ?>;height:<?php echo $height; ?>px; border:1px solid #b5b9a7;'></div>
				<script>
				function initMap() {
					var uluru = {lat: <?php echo $lat; ?>, lng: <?php echo $long; ?>};
					var map = new google.maps.Map(document.getElementById('mapmobile'), {
					zoom: 11,
					center: uluru
					});
					var marker = new google.maps.Marker({
					position: uluru,
					map: map
					});
					}
				</script>
				<script async defer src="https://maps.googleapis.com/maps/api/js?key=<?php echo $GOOGLEMAPAPIKEY; ?>&callback=initMap"></script>

		<?php } else if($channel[1]=='trails') {
			$fullurl = explode('/',$_SERVER['REQUEST_URI']);
			?>
				<div id="mapmobile" style='width:<?php echo $width; ?>;height:<?php echo $height; ?>px; border:1px solid #b5b9a7;'></div>
				<script>
				function initMap() {
					var uluru = {lat: <?php echo $lat; ?>, lng: <?php echo $long; ?>};
					var map = new google.maps.Map(document.getElementById('mapmobile'), {
					zoom: 11,
					center: uluru
					});
					var marker = new google.maps.Marker({
					position: uluru,
					map: map
					});
					}
				</script>
				<script async defer src="https://maps.googleapis.com/maps/api/js?key=<?php echo $GOOGLEMAPAPIKEY; ?>&callback=initMap"></script>
		<?php } ?>
		</div>
		<?php
	}

	
	function cr_site_location_details_with_map($width,$height) {

		global $GRclass;
		include(__DIR__."/../../../wp-config-extra.php");

		$channel=explode("/",$_SERVER[REQUEST_URI]);
		$parts	= $GRclass->parse_location_url();
		$Course = $GRclass->get_site_location_detail($parts);

					$city = "";
					$state = "";
					$zipcode = "";
					$phone = "";
					$lat = "";
					$long = "";
					$shophrs = "";
					$webaddr = "";
					$bikeshops = "";
					$info = "";
					$traillength = "";
					$traillevel = "";
					$trailtype = "";
					$traildirection = "";

			for ($x = 0; $x < count($Course); $x++)
			{
				//echo "The number is: ".$Course[$x]->attributeid." <br>";

				if($Course[$x]->attributeid == "69")
				{
					$city = $Course[$x]->attribute_value;
				}
				if($Course[$x]->attributeid == "70")
				{
					$state = $Course[$x]->attribute_value;
				}
				if($Course[$x]->attributeid == "71")
				{
					$zipcode = $Course[$x]->attribute_value;
				}

				if($Course[$x]->attributeid == "74")
				{
					$phone = $Course[$x]->attribute_value;
				}
				if($Course[$x]->attributeid == "343")
				{
					$lat = $Course[$x]->attribute_value;
				}
				if($Course[$x]->attributeid == "342")
				{
					$long = $Course[$x]->attribute_value;
				}
				if($Course[$x]->attributeid == "340")
				{
					$shophrs = $Course[$x]->attribute_value;
				}
				if($Course[$x]->attributeid == "87")
				{
					$webaddr = $Course[$x]->attribute_value;
				}
				if($Course[$x]->attributeid == "339")
				{
					$bikeshops = $Course[$x]->attribute_value;
				}
				if($Course[$x]->attributeid == "334")
				{
					$info = $Course[$x]->attribute_value;
				}
				if($Course[$x]->attributeid == "80")
				{
					$traillength = $Course[$x]->attribute_value;
				}
				if($Course[$x]->attributeid == "86")
				{
					$traillevel = $Course[$x]->attribute_value;
				}
				if($Course[$x]->attributeid == "81")
				{
					$trailtype = $Course[$x]->attribute_value;
				}
				if($Course[$x]->attributeid == "85")
				{
					$traildirection = $Course[$x]->attribute_value;
				}

			}
		?>
		<div class="sidebox course-detail" style="width: 410px;    height: 270px;">
			<?php if($channel[1]=='golf-courses') 
			{
				$fullurl = explode('/',$_SERVER['REQUEST_URI']);
			?>
				<div id="map" style='width:410px;height:378px; border:1px solid #b5b9a7;'></div>
				<script>
				function initMap() {
					var uluru = {lat: <?php echo $lat; ?>, lng: <?php echo $long; ?>};
					var map = new google.maps.Map(document.getElementById('map'), {
					zoom: 11,
					center: uluru
					});
					var marker = new google.maps.Marker({
					position: uluru,
					map: map
					});
					}
				</script>
				<script async defer src="https://maps.googleapis.com/maps/api/js?key=<?php echo $GOOGLEMAPAPIKEY; ?>&callback=initMap"></script>
		   <?php 
		   } 
		   else if($channel[1]=='trails') 
		   {
			$fullurl = explode('/',$_SERVER['REQUEST_URI']);
			?>
				<div id="map" style='width:<?php echo $width; ?>px;height:<?php echo $height; ?>px; border:1px solid #b5b9a7;'></div>
				<script>
				function initMap() {
					var uluru = {lat: <?php echo $lat; ?>, lng: <?php echo $long; ?>};
					var map = new google.maps.Map(document.getElementById('map'), {
					zoom: 11,
					center: uluru
					});
					var marker = new google.maps.Marker({
					position: uluru,
					map: map
					});
					}
				</script>
				<script async defer src="https://maps.googleapis.com/maps/api/js?key=<?php echo $GOOGLEMAPAPIKEY; ?>&callback=initMap"></script>
		<?php } ?>
		</div>
		<?php
	}



	function cr_site_location_details() {

		global $GRclass;


		$parts	= $GRclass->parse_location_url();
		$Course = $GRclass->get_site_location_detail($parts);

					$city = "";
					$state = "";
					$zipcode = "";
					$phone = "";
					$lat = "";
					$long = "";
					$shophrs = "";
					$webaddr = "";
					$bikeshops = "";
					$info = "";
					$traillength = "";
					$traillevel = "";
					$trailtype = "";
					$traildirection = "";

			for ($x = 0; $x < count($Course); $x++)
			{
				//echo "The number is: ".$Course[$x]->attributeid." <br>";

				if($Course[$x]->attributeid == "69")
				{
					$city = $Course[$x]->attribute_value;
				}
				if($Course[$x]->attributeid == "70")
				{
					$state = $Course[$x]->attribute_value;
				}
				if($Course[$x]->attributeid == "71")
				{
					$zipcode = $Course[$x]->attribute_value;
				}

				if($Course[$x]->attributeid == "74")
				{
					$phone = $Course[$x]->attribute_value;
				}
				if($Course[$x]->attributeid == "343")
				{
					$lat = $Course[$x]->attribute_value;
				}
				if($Course[$x]->attributeid == "342")
				{
					$long = $Course[$x]->attribute_value;
				}
				if($Course[$x]->attributeid == "340")
				{
					$shophrs = $Course[$x]->attribute_value;
				}
				if($Course[$x]->attributeid == "87")
				{
					$webaddr = $Course[$x]->attribute_value;
				}
				if($Course[$x]->attributeid == "339")
				{
					$bikeshops = $Course[$x]->attribute_value;
				}
				if($Course[$x]->attributeid == "334")
				{
					$info = $Course[$x]->attribute_value;
				}
				if($Course[$x]->attributeid == "80")
				{
					$traillength = $Course[$x]->attribute_value;
				}
				if($Course[$x]->attributeid == "86")
				{
					$traillevel = $Course[$x]->attribute_value;
				}
				if($Course[$x]->attributeid == "81")
				{
					$trailtype = $Course[$x]->attribute_value;
				}
				if($Course[$x]->attributeid == "85")
				{
					$traildirection = $Course[$x]->attribute_value;
				}

			}
		?>
		<div class="sidebox course-detail" style="width: 410px;    height: 270px;    padding-left: 7px;">


			<table cellspacing="0">
				<tr><td>Latitude</td><td><?php echo $lat; ?></td></tr>
				<tr><td>Longitude</td><td><?php echo $long; ?></td></tr>
				<tr><td>City</td><td><?php echo $city; ?></td></tr>
				<tr><td>State</td><td><?php echo $state; ?></td></tr>
				<tr><td>Zip/Postal Code</td><td><?php echo $zipcode; ?></td></tr>
				<?php if($parts['country']=='united-states' || $parts['country']=='canada'){ ?>
		<tr><td>Country</td><td><?php $state=str_replace(["-", "–"], ' ', $parts['country']);    echo ucwords($state); ?></td></tr>
				<?php }else{ ?>
				<tr><td>Country</td><td><?php echo ucfirst($parts['state']); ?></td></tr>
				<?php } ?>
				<tr><td>Phone</td><td><?php echo $phone; ?></td></tr>

			</table>

		</div>
		<?php
	}

	function mtbr_cr_site_location_details_with_map($width,$height) {

		global $GRclass;
		include(__DIR__."/../../../wp-config-extra.php");

		$channel=explode("/",$_SERVER[REQUEST_URI]);
		$parts	= $GRclass->mtbr_parse_location_url();
		$Course = $GRclass->get_site_location_detail($parts);



					$city = "";
					$state = "";
					$zipcode = "";
					$phone = "";
					$lat = "";
					$long = "";
					$shophrs = "";
					$webaddr = "";
					$bikeshops = "";
					$info = "";
					$traillength = "";
					$traillevel = "";
					$trailtype = "";
					$traildirection = "";

			for ($x = 0; $x < count($Course); $x++)
			{
				//echo "The number is: ".$Course[$x]->attributeid." <br>";

				if($Course[$x]->attributeid == "69")
				{
					$city = $Course[$x]->attribute_value;
				}
				if($Course[$x]->attributeid == "70")
				{
					$state = $Course[$x]->attribute_value;
				}
				if($Course[$x]->attributeid == "71")
				{
					$zipcode = $Course[$x]->attribute_value;
				}

				if($Course[$x]->attributeid == "74")
				{
					$phone = $Course[$x]->attribute_value;
				}
				if($Course[$x]->attributeid == "343")
				{
					$lat = $Course[$x]->attribute_value;
				}
				if($Course[$x]->attributeid == "342")
				{
					$long = $Course[$x]->attribute_value;
				}
				if($Course[$x]->attributeid == "340")
				{
					$shophrs = $Course[$x]->attribute_value;
				}
				if($Course[$x]->attributeid == "87")
				{
					$webaddr = $Course[$x]->attribute_value;
				}
				if($Course[$x]->attributeid == "339")
				{
					$bikeshops = $Course[$x]->attribute_value;
				}
				if($Course[$x]->attributeid == "334")
				{
					$info = $Course[$x]->attribute_value;
				}
				if($Course[$x]->attributeid == "80")
				{
					$traillength = $Course[$x]->attribute_value;
				}
				if($Course[$x]->attributeid == "86")
				{
					$traillevel = $Course[$x]->attribute_value;
				}
				if($Course[$x]->attributeid == "81")
				{
					$trailtype = $Course[$x]->attribute_value;
				}
				if($Course[$x]->attributeid == "85")
				{
					$traildirection = $Course[$x]->attribute_value;
				}

			}
		?>
		
		<div class="sidebox course-detail" style="width: 410px;    height: 270px;">
			<?php if($channel[1]=='bikeshops') 
			{
				$fullurl = explode('/',$_SERVER['REQUEST_URI']);
			?>
				<div id="map" style='width:410px;height:378px; border:1px solid #b5b9a7;'></div>
				<script>
				function initMap() {
					var uluru = {lat: <?php echo $lat; ?>, lng: <?php echo $long; ?>};
					var map = new google.maps.Map(document.getElementById('map'), {
					zoom: 11,
					center: uluru
					});
					var marker = new google.maps.Marker({
					position: uluru,
					map: map
					});
					}
				</script>
				<script async defer src="https://maps.googleapis.com/maps/api/js?key=<?php echo $GOOGLEMAPAPIKEY; ?>&callback=initMap"></script>
		   <?php 
		   } 
		   else if($channel[1]=='trails') 
		   {
			$fullurl = explode('/',$_SERVER['REQUEST_URI']);
			?>
				<div id="map" style='width:<?php echo $width; ?>px;height:<?php echo $height; ?>px; border:1px solid #b5b9a7;'></div>
				<script>
				function initMap() {
					var uluru = {lat: <?php echo $lat; ?>, lng: <?php echo $long; ?>};
					var map = new google.maps.Map(document.getElementById('map'), {
					zoom: 11,
					center: uluru
					});
					var marker = new google.maps.Marker({
					position: uluru,
					map: map
					});
					}
				</script>
				<script async defer src="https://maps.googleapis.com/maps/api/js?key=<?php echo $GOOGLEMAPAPIKEY; ?>&callback=initMap"></script>
		<?php } ?>
		</div>
	<?php


	}

	function mtbr_cr_site_location_details() {

		global $GRclass;

		$channel=explode("/",$_SERVER[REQUEST_URI]);
		$parts	= $GRclass->mtbr_parse_location_url();
		$Course = $GRclass->get_site_location_detail($parts);



					$city = "";
					$state = "";
					$zipcode = "";
					$phone = "";
					$lat = "";
					$long = "";
					$shophrs = "";
					$webaddr = "";
					$bikeshops = "";
					$info = "";
					$traillength = "";
					$traillevel = "";
					$trailtype = "";
					$traildirection = "";

			for ($x = 0; $x < count($Course); $x++)
			{
				//echo "The number is: ".$Course[$x]->attributeid." <br>";

				if($Course[$x]->attributeid == "69")
				{
					$city = $Course[$x]->attribute_value;
				}
				if($Course[$x]->attributeid == "70")
				{
					$state = $Course[$x]->attribute_value;
				}
				if($Course[$x]->attributeid == "71")
				{
					$zipcode = $Course[$x]->attribute_value;
				}

				if($Course[$x]->attributeid == "74")
				{
					$phone = $Course[$x]->attribute_value;
				}
				if($Course[$x]->attributeid == "343")
				{
					$lat = $Course[$x]->attribute_value;
				}
				if($Course[$x]->attributeid == "342")
				{
					$long = $Course[$x]->attribute_value;
				}
				if($Course[$x]->attributeid == "340")
				{
					$shophrs = $Course[$x]->attribute_value;
				}
				if($Course[$x]->attributeid == "87")
				{
					$webaddr = $Course[$x]->attribute_value;
				}
				if($Course[$x]->attributeid == "339")
				{
					$bikeshops = $Course[$x]->attribute_value;
				}
				if($Course[$x]->attributeid == "334")
				{
					$info = $Course[$x]->attribute_value;
				}
				if($Course[$x]->attributeid == "80")
				{
					$traillength = $Course[$x]->attribute_value;
				}
				if($Course[$x]->attributeid == "86")
				{
					$traillevel = $Course[$x]->attribute_value;
				}
				if($Course[$x]->attributeid == "81")
				{
					$trailtype = $Course[$x]->attribute_value;
				}
				if($Course[$x]->attributeid == "85")
				{
					$traildirection = $Course[$x]->attribute_value;
				}

			}
		?>
		

			<?php if($channel[1]=='bikeshops') {
				$fullurl = explode('/',$_SERVER['REQUEST_URI']);
			?>
<div class="sidebox course-detail" style="width: 410px;    height: 410px;    padding-left: 7px;">
			<table cellspacing="0">
				<tr><td>Latitude</td><td><?php echo $lat; ?></td></tr>
				<tr><td>Longitude</td><td><?php echo $long; ?></td></tr>
				<tr><td>City</td><td><?php echo $city; ?></td></tr>
				<tr><td>State</td><td><?php echo $state; ?></td></tr>
				<tr><td>Zip/Postal Code</td><td><?php echo $zipcode; ?></td></tr>
				<tr><td>Country</td><td><?php $state=str_replace(["-", "–"], ' ', $fullurl[2]);    echo ucwords($state); ?></td></tr>
				<tr><td>Phone</td><td><?php echo $phone; ?></td></tr>
				<tr><td>Shop Hours</td><td><?php echo $shophrs; ?></td></tr>
				<tr><td>Web Address</td><td><?php echo $webaddr; ?></td></tr>
				<tr><td>Bike Shop Sells</td><td><?php echo $bikeshops; ?></td></tr>
				<tr><td>Info Added By</td><td><?php echo $info; ?></td></tr>

			</table>
		<?php } else if($channel[1]=='trails') {
			$fullurl = explode('/',$_SERVER['REQUEST_URI']);
			?>
<div class="sidebox course-detail" style="width: 410px;    height: 270px;    padding-left: 7px;">
			<table cellspacing="0">
				<tr><td>Latitude</td><td><?php echo $lat; ?></td></tr>
				<tr><td>Longitude</td><td><?php echo $long; ?></td></tr>
				<tr><td>City</td><td><?php echo $city; ?></td></tr>
				<tr><td>State</td><td><?php $state=str_replace(["-", "–"], ' ', $fullurl[3]);    echo ucwords($state); ?></td></tr>
				<tr><td>Zip/Postal Code</td><td><?php echo $zipcode; ?></td></tr>
				<tr><td>Country</td><td><?php $state=str_replace(["-", "–"], ' ', $fullurl[2]);    echo ucwords($state); ?></td></tr>
				<tr><td>Phone</td><td><?php echo $phone; ?></td></tr>
				<tr><td>Trail Length</td><td><?php echo $traillength; ?></td></tr>
				<tr><td>Trail Level</td><td><?php echo $traillevel; ?></td></tr>
				<tr><td>Trail Type</td><td><?php echo $trailtype; ?></td></tr>
				<!--<tr><td>Trail Directions</td><td><?php echo $traildirection; ?></td></tr>-->

			</table>
		<?php } ?>
		</div>
	<?php


	}

	function mtbr_cr_site_location_details_mobile_with_map($width,$height) {

		global $GRclass;
		include(__DIR__."/../../../wp-config-extra.php");

		$channel=explode("/",$_SERVER[REQUEST_URI]);
		$parts	= $GRclass->mtbr_parse_location_url();
		$Course = $GRclass->get_site_location_detail($parts);
					$city = "";
					$state = "";
					$zipcode = "";
					$phone = "";
					$lat = "";
					$long = "";
					$shophrs = "";
					$webaddr = "";
					$bikeshops = "";
					$info = "";
					$traillength = "";
					$traillevel = "";
					$trailtype = "";
					$traildirection = "";

			for ($x = 0; $x < count($Course); $x++)
			{
				//echo "The number is: ".$Course[$x]->attributeid." <br>";

				if($Course[$x]->attributeid == "69")
				{
					$city = $Course[$x]->attribute_value;
				}
				if($Course[$x]->attributeid == "70")
				{
					$state = $Course[$x]->attribute_value;
				}
				if($Course[$x]->attributeid == "71")
				{
					$zipcode = $Course[$x]->attribute_value;
				}

				if($Course[$x]->attributeid == "74")
				{
					$phone = $Course[$x]->attribute_value;
				}
				if($Course[$x]->attributeid == "343")
				{
					$lat = $Course[$x]->attribute_value;
				}
				if($Course[$x]->attributeid == "342")
				{
					$long = $Course[$x]->attribute_value;
				}
				if($Course[$x]->attributeid == "340")
				{
					$shophrs = $Course[$x]->attribute_value;
				}
				if($Course[$x]->attributeid == "87")
				{
					$webaddr = $Course[$x]->attribute_value;
				}
				if($Course[$x]->attributeid == "339")
				{
					$bikeshops = $Course[$x]->attribute_value;
				}
				if($Course[$x]->attributeid == "334")
				{
					$info = $Course[$x]->attribute_value;
				}
				if($Course[$x]->attributeid == "80")
				{
					$traillength = $Course[$x]->attribute_value;
				}
				if($Course[$x]->attributeid == "86")
				{
					$traillevel = $Course[$x]->attribute_value;
				}
				if($Course[$x]->attributeid == "81")
				{
					$trailtype = $Course[$x]->attribute_value;
				}
				if($Course[$x]->attributeid == "85")
				{
					$traildirection = $Course[$x]->attribute_value;
				}

			}

?>
		<div class="sidebox course-detail" style="width: 100%;">


			<?php if($channel[1]=='bikeshops') {
			$fullurl = explode('/',$_SERVER['REQUEST_URI']);
				?>
				<div id="mapmobile" style='width:<?php echo $width; ?>;height:<?php echo $height; ?>px; border:1px solid #b5b9a7;'></div>
				<script>
				function initMap() {
					var uluru = {lat: <?php echo $lat; ?>, lng: <?php echo $long; ?>};
					var map = new google.maps.Map(document.getElementById('mapmobile'), {
					zoom: 11,
					center: uluru
					});
					var marker = new google.maps.Marker({
					position: uluru,
					map: map
					});
					}
				</script>
				<script async defer src="https://maps.googleapis.com/maps/api/js?key=<?php echo $GOOGLEMAPAPIKEY; ?>&callback=initMap"></script>

		<?php } else if($channel[1]=='trails') {
			$fullurl = explode('/',$_SERVER['REQUEST_URI']);
			?>
				<div id="mapmobile" style='width:<?php echo $width; ?>;height:<?php echo $height; ?>px; border:1px solid #b5b9a7;'></div>
				<script>
				function initMap() {
					var uluru = {lat: <?php echo $lat; ?>, lng: <?php echo $long; ?>};
					var map = new google.maps.Map(document.getElementById('mapmobile'), {
					zoom: 11,
					center: uluru
					});
					var marker = new google.maps.Marker({
					position: uluru,
					map: map
					});
					}
				</script>
				<script async defer src="https://maps.googleapis.com/maps/api/js?key=<?php echo $GOOGLEMAPAPIKEY; ?>&callback=initMap"></script>
		<?php } ?>
		</div>
		<?php
	}



	function mtbr_cr_site_location_details_mobile() {

		global $GRclass;
		$channel=explode("/",$_SERVER[REQUEST_URI]);
		$parts	= $GRclass->mtbr_parse_location_url();
		$Course = $GRclass->get_site_location_detail($parts);
					$city = "";
					$state = "";
					$zipcode = "";
					$phone = "";
					$lat = "";
					$long = "";
					$shophrs = "";
					$webaddr = "";
					$bikeshops = "";
					$info = "";
					$traillength = "";
					$traillevel = "";
					$trailtype = "";
					$traildirection = "";

			for ($x = 0; $x < count($Course); $x++)
			{
				//echo "The number is: ".$Course[$x]->attributeid." <br>";

				if($Course[$x]->attributeid == "69")
				{
					$city = $Course[$x]->attribute_value;
				}
				if($Course[$x]->attributeid == "70")
				{
					$state = $Course[$x]->attribute_value;
				}
				if($Course[$x]->attributeid == "71")
				{
					$zipcode = $Course[$x]->attribute_value;
				}

				if($Course[$x]->attributeid == "74")
				{
					$phone = $Course[$x]->attribute_value;
				}
				if($Course[$x]->attributeid == "343")
				{
					$lat = $Course[$x]->attribute_value;
				}
				if($Course[$x]->attributeid == "342")
				{
					$long = $Course[$x]->attribute_value;
				}
				if($Course[$x]->attributeid == "340")
				{
					$shophrs = $Course[$x]->attribute_value;
				}
				if($Course[$x]->attributeid == "87")
				{
					$webaddr = $Course[$x]->attribute_value;
				}
				if($Course[$x]->attributeid == "339")
				{
					$bikeshops = $Course[$x]->attribute_value;
				}
				if($Course[$x]->attributeid == "334")
				{
					$info = $Course[$x]->attribute_value;
				}
				if($Course[$x]->attributeid == "80")
				{
					$traillength = $Course[$x]->attribute_value;
				}
				if($Course[$x]->attributeid == "86")
				{
					$traillevel = $Course[$x]->attribute_value;
				}
				if($Course[$x]->attributeid == "81")
				{
					$trailtype = $Course[$x]->attribute_value;
				}
				if($Course[$x]->attributeid == "85")
				{
					$traildirection = $Course[$x]->attribute_value;
				}

			}

?>
		<div class="sidebox course-detail" style="width: 100%;">


			<?php if($channel[1]=='bikeshops') {
			$fullurl = explode('/',$_SERVER['REQUEST_URI']);
				?>
			<table cellspacing="0">
				<tr><td>Latitude</td><td><?php echo $lat; ?></td></tr>
				<tr><td>Longitude</td><td><?php echo $long; ?></td></tr>
				<tr><td>City</td><td><?php echo $city; ?></td></tr>
				<tr><td>State</td><td><?php echo $state; ?></td></tr>
				<tr><td>Zip/Postal Code</td><td><?php echo $zipcode; ?></td></tr>
				<tr><td>Country</td><td><?php $state=str_replace(["-", "–"], ' ', $fullurl[2]);    echo ucwords($state); ?></td></tr>
				<tr><td>Phone</td><td><?php echo $phone; ?></td></tr>
				<tr><td>Shop Hours</td><td><?php echo $shophrs; ?></td></tr>
				<tr><td>Web Address</td><td><?php echo $webaddr; ?></td></tr>
				<tr><td>Bike Shop Sells</td><td><?php echo $bikeshops; ?></td></tr>
				<tr><td>Info Added By</td><td><?php echo $info; ?></td></tr>

			</table>
		<?php } else if($channel[1]=='trails') {
			$fullurl = explode('/',$_SERVER['REQUEST_URI']);
			?>
			<table cellspacing="0">
				<tr><td>Latitude</td><td><?php echo $lat; ?></td></tr>
				<tr><td>Longitude</td><td><?php echo $long; ?></td></tr>
				<tr><td>City</td><td><?php echo $city; ?></td></tr>
				<tr><td>State</td><td><?php echo $state; ?></td></tr>
				<tr><td>Zip/Postal Code</td><td><?php echo $zipcode; ?></td></tr>
				<tr><td>Country</td><td><?php $state=str_replace(["-", "–"], ' ', $fullurl[2]);    echo ucwords($state); ?></td></tr>
				<tr><td>Phone</td><td><?php echo $phone; ?></td></tr>
				<tr><td>Trail Length</td><td><?php echo $traillength; ?></td></tr>
				<tr><td>Trail Level</td><td><?php echo $traillevel; ?></td></tr>
				<tr><td>Trail Type</td><td><?php echo $trailtype; ?></td></tr>
				<!--<tr><td>Trail Directions</td><td><?php echo $traildirection; ?></td></tr>-->

			</table>
		<?php } ?>
		</div>
		<?php
	}






	function lettr($id=-1) {

		$letters = array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z");
		if($id >=0) return $letters[$id];
		else return $letters;
	}

	function set_site_review_title($cat='',$brand='',$product='',$zip='') {

		global $GRclass;

		$arr = array('category' => $cat, 'brand' => $brand,	'product' => $product, 'zip' => $zip );
		$GRclass->set_site_review_location($arr);
	}

	function site_review_product_accessory_list($Categories, $term_cat ) {

		global $GRclass, $Products;

		$PSite = explode(":", get_option( 'PSiteName' ) );
		$Products = $GRclass->get_product_list( $cat, $term_cat, $PSite[0] );
	}

	function site_main_review_product_accessory_list($Categories, $term_cat ) {

		global $GRclass, $Products;

		$PSite = explode(":", get_option( 'PSiteName' ) );
		$Products = $GRclass->get_main_product_list( $cat, $term_cat, $PSite[0] );
	}


	function get_productlink_by_id($productid) {
		global $GRclass;
		$Product_Link = $GRclass->get_product_permalink($productid);
		return $Product_Link;
	}

	function article_to_product_page($permalink) {

		global $post, $GRclass;

		$product_id = get_post_meta($post->ID, 'product_id', true);

		$Product 	= $GRclass->get_product_permalink($product_id);

		if(is_numeric($product_id)) {

			$Product 	= $GRclass->get_product_permalink($product_id);

			if($Product->manufacturerid == 8051) {

				$course = $GRclass->get_site_location_by_id($product_id);
				return cr_location_url($course,$Product->url_safe_product_name, 1);

			} else {

				return cr_product_url($Product,1);
			}
		}
		else if ( !get_post_meta( $post->ID, 'product_id', true ) ) {

			return 	cr_adhoc_product_url($permalink, $post );
		}
	}

	function cr_get_overall_rating_average($Product) {

		if(!is_numeric($Product->quickrating_average)) $Product->quickrating_average = 0;

		$Product->total_rating = 0;
		$divider = 0;

		if($Product->total_reviews > 0) {

			$divider++;
			$Product->average_rating = round($Product->average_rating,1);
			$Product->total_rating += $Product->average_rating;
		}

		if($Product->quickrating_count > 0) {

			$divider++;
			$Product->quickrating_average = round($Product->quickrating_average,1);
			$Product->total_rating += $Product->quickrating_average;
		}

		if($Product->web_score_count > 0) {

			$divider++;
			$Product->web_score_rating = round($Product->web_score_rating,1);
			$Product->total_rating += $Product->web_score_rating;
		}

		if($divider > 0) {

			$Product->total_rating = $Product->total_rating / $divider;
		}

		return round($Product->total_rating,1);
	}


	function cr_get_overall_rating_count($Product) {

		if(!is_numeric($Product->total_reviews)) 		$Product->total_reviews 	= 0;
		if(!is_numeric($Product->quickrating_count)) 	$Product->quickrating_count = 0;
		if(!is_numeric($Product->web_score_count)) 		$Product->web_score_count 	= 0;

		//$Product->total_rating_count = $Product->total_reviews + $Product->quickrating_count + $Product->web_score_count;
		$Product->total_rating_count = $Product->total_reviews;
		return $Product->total_rating_count;
	}


	function cr_product_image($img,$type='original',$course=0) {

		if($course == 1) $path = GR_CIMG;
		else $path = GR_IMG;


		$img=str_replace('NoPhoto','nophoto',$img);

		switch($type) {

			case '175x175' :
			$imgp = explode("_",$img);
			$ext = explode(".",$imgp[2]);
			$img = $imgp[0] . '_' . $imgp[1] . ' . ' . $ext[1];
			return $path . $img;

			break;
			case '300x225' :
				return $path . 'medium450/' . $img;
			break;
			case 'large' :
				return $path . 'large/' . $img;
			break;

			case '80x60' :
				return $path . 'thumb/' . $img;

			break;
			case '396x200' :
				$imgp = explode("_",$img);
				$ext = explode(".",$imgp[2]);
				$img = $imgp[0] . '_' . $imgp[1] . ' . ' . $ext[1];
				return $path . 'featured/' . $img;

			break;
			case 'product' :
				return $path . $img;

			break;

			default :
				return $path . 'medium450/' . $img;
		}
	}

	add_filter('the_permalink', 'article_to_product_page');

?>
