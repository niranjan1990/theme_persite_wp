<?php

	//error_reporting(E_ALL ^ E_NOTICE);
	/**
	 * Website Permalink URLs
	 * 	- cr_product_url(): /golf_clubs/[brand]/[product].html Product URL
	 *  - cr_brand_url(): /[brand].html Manufacturer URL
	 *  - cr_brand_category_url(): /[brand]/[category].html URL
	 *  - cr_brand_category_url_wcat(): /[brand]/[category].html URL, passing [category] in 2nd parameter
	 *  - cr_category_url(): /golf_clubs/[category].html (parameter is Category Object)
	 *  - cr_category_url_from_cat(): /golf_clubs/[category].html (parameter is variable)
	 *  - cr_state_url(): /golf_courses/[state].html
	 *  - cr_location_city_url() /golf_courses/[state]/[city].html
	 * 
	 *  Optional parameter $return = 1 returns url instead of echo
	 */
  	
	function cr_adhoc_product_url($permalink, $post ) {
		
		$adhoc_permalink_url = get_option( $post->ID.'_adhoc_permalink_url' );
		return ( $adhoc_permalink_url ) ? $adhoc_permalink_url : $permalink ;
	}
	
	function cr_product_url_outdoor_cat($Product, $return=0) {
		
		if($return == 1)
		{
			$ch = explode('/',$Product->category_path);
			return get_bloginfo('url') . '/'. $ch[0]."/" . $Product->url_safe_manufacturer_name . '/' . strtolower($Product->url_safe_category_name) . '/' . $Product->url_safe_product_name . '.html';
			
		}
		
		else
		{
			echo get_bloginfo('url') . '/product/' . $Product->category_path . '/' . $Product->url_safe_manufacturer_name . '/' . $Product->url_safe_product_name . '.html';
			
		}
	}

	
	function cr_product_url($Product, $return=0) {
		
		if($return == 1)
		{
			$ch = explode('/',$Product->category_path);
			return get_bloginfo('url') . '/'. $ch[0]."/" . $Product->url_safe_manufacturer_name . '/' . strtolower($Product->url_safe_category_name) . '/' . $Product->url_safe_product_name . '.html';
			
		}
		
		else
		{
			echo get_bloginfo('url') . '/' . cr_product_base_path($Product->url_safe_category_name) . '/' . $Product->url_safe_manufacturer_name . '/' . strtolower($Product->url_safe_category_name) . '/' . $Product->url_safe_product_name . '.html';
			
		}
	}

function cr_main_product_url($Product, $return=0) {
		
		if($return == 1)
		{
			$ch = explode('/',$Product->category_path);
			return get_bloginfo('url') . '/'. $ch[0]."/" . $Product->url_safe_manufacturer_name . '/' . $Product->url_safe_category_name . '/' . $Product->url_safe_product_name . '.html';
			
		}
		
		else
		{
			echo get_bloginfo('url') . '/' . cr_product_base_path($Product->url_safe_category_name) . '/111' . $Product->url_safe_manufacturer_name . '/' . $Product->url_safe_category_name . '/' . $Product->url_safe_product_name . '.html';
			
		}
	}

	/*
	function cr_product_url($Product, $return=0) {
		
		if($return == 1)
			
			return get_bloginfo('url') . '/'. cr_product_base_path($Product->url_safe_category_name) . '/' . $Product->url_safe_manufacturer_name . '/' . $Product->url_safe_category_name . '/' . $Product->url_safe_product_name . '.html';
		
		else
			
			echo get_bloginfo('url') . '22/' . cr_product_base_path($Product->url_safe_category_name) . '/' . $Product->url_safe_manufacturer_name . '/' . $Product->url_safe_category_name . '/' . $Product->url_safe_product_name . '.html';
	}
	*/
	
	/* cr_brand_url */
	
	function cr_brand_url($Product,$return=0) {
		
		$parts	= explode('/',$_SERVER['REQUEST_URI']);
		$catry	= explode('.', $parts[1]);
		
		if($return == 1)
		
			//return get_bloginfo('url') . '/' . $Product->url_safe_manufacturer_name . '.html';
			return get_bloginfo('url') . '/' . $catry[0] . '/brand/' . $Product->url_safe_manufacturer_name . '.html';
			
		else
		
			echo get_bloginfo('url') . '/' . $catry[0] . '/brand/' . $Product->url_safe_manufacturer_name . '.html';	
	}

	function cr_brand_url_search($Product,$return=0) {
		
		$parts	= explode('/',$_SERVER['REQUEST_URI']);
		$catry	= explode('.', $parts[1]);
		
		if($return == 1)
		
			//return get_bloginfo('url') . '/' . $Product->url_safe_manufacturer_name . '.html';
			return get_bloginfo('url') . '/' . $catry[0] . '/brand/' . $Product->url_safe_manufacturer_name . '.html';
			
		else
		
			echo get_bloginfo('url') . '/' . $catry[0] . '/brand/' . $Product->url_safe_manufacturer_name . '.html';	
	}
		
	/* cr_brand_category_url */
	
	function cr_brand_category_url($Product,$return=0) {
		
		$parts	= explode('/',$_SERVER['REQUEST_URI']);
		
		if($return == 1)
		
			return get_bloginfo('url') . '/' .$parts[1]. '/' . $Product->url_safe_manufacturer_name . '/' . $Product->url_safe_category_name . '.html';
		else 
			
			echo get_bloginfo('url') . '/' .$parts[1]. '/' . $Product->url_safe_manufacturer_name . '/' . $Product->url_safe_category_name . '.html';	
	}
	
	/* cr_brand_category_url_wcat */
	
	function cr_brand_category_url_wcat_outdoor_brand_three($Product,$category,$return=0) {
		
		$parts	= explode('/',$_SERVER['REQUEST_URI']);
		
		if($return == 1)
		
			return get_bloginfo('url') . '/brand/' .$parts[1]. '/' .$parts[2]. '/'. $category . '/' . $Product->url_safe_manufacturer_name . '.html';
			
		else
			echo '';
			//echo get_bloginfo('url') . '/' . $Product->url_safe_manufacturer_name . '/' . $category . '.html';	
	}
	function cr_brand_category_url_wcat_outdoor_brand_four($Product,$category,$return=0) {
		
		$parts	= explode('/',$_SERVER['REQUEST_URI']);
		
		if($return == 1)
		
			return get_bloginfo('url') . '/brand/' .$parts[1]. '/' .$parts[2]. '/' .$parts[3]. '/'. $category . '/' . $Product->url_safe_manufacturer_name . '.html';
			
		else
		
			echo get_bloginfo('url') . '/' . $Product->url_safe_manufacturer_name . '/' . $category . '.html';	
	}

	function cr_brand_category_url_wcat_outdoor_sub($Product,$category,$return=0) {
		
		$parts	= explode('/',$_SERVER['REQUEST_URI']);
		
		if($return == 1)
		
			return get_bloginfo('url') . '/' .$parts[1]. '/' . $Product->url_safe_manufacturer_name . '/' .$parts[2]. '/'. $category . '-brand.html';
			
		else
		
			echo get_bloginfo('url') . '/' . $Product->url_safe_manufacturer_name . '/' . $category . '.html';	
	}

	function cr_brand_category_url_wcat_outdoor_first($Product,$category,$return=0) {
		
		$parts	= explode('/',$_SERVER['REQUEST_URI']);
		
		if($return == 1)
		
			return get_bloginfo('url') . '/brand/' .$category. '/' . $Product->url_safe_manufacturer_name .'.html';
			
		else
		
			echo get_bloginfo('url') . '/' . $Product->url_safe_manufacturer_name . '/' . $category . '.html';	
	}


	function cr_brand_category_url_wcat($Product,$category,$return=0) {
		
		$parts	= explode('/',$_SERVER['REQUEST_URI']);
		
		if($return == 1)
		
			return get_bloginfo('url') . '/' .$parts[1]. '/' . $Product->url_safe_manufacturer_name . '/' . $category . '.html';
			
		else
		
			echo get_bloginfo('url') . '/' . $Product->url_safe_manufacturer_name . '/' . $category . '.html';	
	}


	function cr_brand_category_url_wcat_outdoor_subcat($Product,$category,$return=0) {
		
		$parts	= explode('/',$_SERVER['REQUEST_URI']);
		$cat = explode(".",$parts[2]);
		if($return == 1)
		
			return get_bloginfo('url') . '/brand/' .$parts[1] . '/' . $cat[0] .'/'. $Product->url_safe_manufacturer_name . '.html';
			
		else
		
			echo get_bloginfo('url') . '/' . $Product->url_safe_manufacturer_name . '/' . $category . '.html';	
	}
	
	//mari added onclick search brand
		function cr_brand_category_url_wcat_search($Product,$category,$return=0) {
		
		$parts	= explode('/',$_SERVER['REQUEST_URI']);
		
		if($return == 1)
		
			return get_bloginfo('url') . '/' .$parts[1]. '/' . $Product->url_safe_manufacturer_name . '/' . $category . '.html';
			
		else
		
			echo get_bloginfo('url') . '/' . $Product->url_safe_manufacturer_name . '/' . $category . '.html';	
	}
	/*  cr_category_url */
	
	function cr_category_url($Category,$return=0) {
		
		$cat = cr_category_permalink($Category->url_safe_category_name,1);
		
		if($return == 1)
		
			return get_bloginfo('url') . '/' . cr_product_base_path($Category->url_safe_category_name) . '/' . $cat . '.html';
			
		else
		
			echo get_bloginfo('url') . '/' . cr_product_base_path($Category->url_safe_category_name) . '/' . $cat . '.html';
	}
	
	
	/* cr_cat_url_safe */
	
	function cr_cat_url_safe($Category,$return=0) {
		
		if($return == 1)
			
			return get_bloginfo('url') . '/' . $Category . '.html';
		else 
			
			echo get_bloginfo('url') . '/' . $Category . '.html';
	}
	
	
	/* cr_category_url_from_cat */
	
	function cr_category_url_from_cat($Category, $return=0) {
	
		
		$SubList = site_review_category_sub_list(); // Zaved added 15/Nov/2016
		//$base_url = ($Category == 'golf-equipment') ? 'golf-equipment' : cr_product_base_path($Category,1) . '/' . cr_category_permalink($Category,1);
		$base_url = cr_product_cat_base_path($Category, $SubList, 1);
		
		if($return == 1)
			
			return get_bloginfo('url') . '/' . $base_url . '.html';
			
		else 
			
			echo get_bloginfo('url') . '/' . $base_url . '.html';
	}
	
	/* cr_category_url_from_cat for sub category sub page*/
	
	function cr_category_url_from_cat_sub($Category, $return=0) {
	
		
		$SubList = site_review_category_sub_list(); // Zaved added 15/Nov/2016
		//$base_url = ($Category == 'golf-equipment') ? 'golf-equipment' : cr_product_base_path($Category,1) . '/' . cr_category_permalink($Category,1);
		$base_url = cr_product_cat_base_path($Category, $SubList, 1);
		
		if($return == 1)
			
			return get_bloginfo('url') . '/' . $base_url . '-subcat.html';
			
		else 
			
			echo get_bloginfo('url') . '/' . $base_url . '-subcat.html';
	}
	
	/* cr_state_url */
	
	function cr_state_url($state, $loc, $return=0) {
		
		$state = str_replace(" ", "-", $state);		
		$parts = explode( '/', $_SERVER['REQUEST_URI'] ); // Added by Zaved as on 23/Nov/2016
		$returnparts = explode('.html', $parts[1]); // Added by Zaved as on 23/Nov/2016		
		//$url = ( count( $parts ) == 2 ) ? $loc['course_type'] : $returnparts[0] .'/' . $loc['course_type']; //Commented by Zaved as on 23/Nov/2016		
		$url = ( count( $parts ) == 2 ) ?  $loc['course_type'] : $returnparts[0] .'/' . $loc['course_type']; // Changed by Zaved as on 23/Nov/2016
		
		if($return == 1)
			
			return get_bloginfo('url') . '/' . $url . '/united-states/' . $state . '.html';
		else 
			
			echo get_bloginfo('url') . '/' . $url . '/united-states/' . $state . '.html';
	}
	
	/* Get location by the city url */
	
	function cr_location_city_url($st, $city, $return=0) {
		
		$parts = explode( '/', $_SERVER['REQUEST_URI'] ); // Added by Zaved as on 23/Nov/2016
		$returnparts = explode('.html', $parts[1]); // Added by Zaved as on 23/Nov/2016		
		
		if(is_array($st)) {
		
			// Added and commneted by Zaved as on 23/Nov/2016
			//$url = ($st['course_type'] == 'golf-courses') ? $st['course_type'] . '/' . cr_state_name( $st['state'], 1 ) : 'golf-courses/' . $st['course_type'] . '/' . cr_state_name($st['state'], 1 );
			$url = ( count( $parts ) == 3 ) ? $st['course_type'] . '/' . cr_state_name( $st['state'], 1 ) : $returnparts[0] . '/' . $st['course_type'] . '/' . cr_state_name($st['state'], 1 );
			
		} else {
			
			$url = $returnparts[0] . '/' . cr_state_name( $st, 1 );
		}
		
		if($return == 1)
			
			return get_bloginfo('url') . '/'.$url.'/' . $city . '.html';
			
		else 
			
			echo get_bloginfo('url') . '/'.$url.'/' . $city . '.html';
	}
	
	/* get the location by the url */
	
	function cr_location_url($parts, $course, $return=0) {
		
		global $GRclass;
		
		$returnparts = explode( '/', $_SERVER['REQUEST_URI'] ); // Added by Zaved as on 23/Nov/2016
		
		if($return == 1)
			
			//variable $returnparts replaced by zaved as on 23/Nov/2016
			return get_bloginfo('url')  . '/golf-courses/' .$returnparts[2].'/'. cr_state_name($parts->state,1) . '/' . $GRclass->parse_city_permalink($parts->city) . '/' . $course . '.html';
			
		else
			
			//variable $returnparts replaced by zaved as on 23/Nov/2016
			echo get_bloginfo('url') . '/' . $returnparts[1] .'/'. $returnparts[2].'/' . $returnparts[3] . '/' . $GRclass->parse_city_permalink($parts->city) . '/' . $course . '.html';

		/*echo get_bloginfo('url') . '/' . $returnparts[1] .'/'. $returnparts[2].'/' . cr_state_name($parts->state,1) . '/' . $GRclass->parse_city_permalink($parts->city) . '/' . $course . '.html';*/
	}
/*	function cr_location_url($parts, $course, $return=0) {
		
		global $GRclass;
		
		$returnparts = explode( '/', $_SERVER['REQUEST_URI'] ); // Added by Zaved as on 23/Nov/2016
		
		if($return == 1)
			
			//variable $returnparts replaced by zaved as on 23/Nov/2016
			return get_bloginfo('url')  . '/' . $returnparts[1] . '/' . cr_state_name($parts->state,1) . '/' . $GRclass->parse_city_permalink($parts->city) . '/' . $course . '.html';
			
		else
			
			//variable $returnparts replaced by zaved as on 23/Nov/2016
			echo get_bloginfo('url') . '/' . $returnparts[1] . '/' . cr_state_name($parts->state,1) . '/' . $GRclass->parse_city_permalink($parts->city) . '/' . $course . '.html';
	}
	*/

	/* Get location by the city url */
	
	function mtbr_cr_location_city_url($st, $city, $return=0) {
		//$st['state'];
		$parts = explode( '/', $_SERVER['REQUEST_URI'] ); // Added by Zaved as on 23/Nov/2016
		//echo $_SERVER['REQUEST_URI']."<br>".$parts[1];
		$returnparts = explode('.html', $parts[1]); // Added by Zaved as on 23/Nov/2016		
		
		if(is_array($st)) 
		{
		
			$url = ( count( $parts ) == 3 ) ? $st['course_type'] . '/' . cr_state_name( $st['state'], 1 ) : $returnparts[0] . '/' . $st['course_type'] . '/' .cr_state_name($st['state'], 1 );
			
		} 
		else 
		{
			
			$url = $returnparts[0] . '/' . cr_state_name( $st, 1 );
		}
		
		if($return == 1)
			
			return get_bloginfo('url') . '/'.$url.'/' . $city . '.html';
			
		else 
			
			echo get_bloginfo('url') . '/'.$url.'/' . $city . '.html';
	}
function mtbr_cr_location_url($parts, $course, $return=0,$stateflag=0) {
		
		global $GRclass;
		$returnparts = explode( '/', $_SERVER['REQUEST_URI'] ); // Added by Zaved as on 23/Nov/2016
		$newurlnew1 = explode(".",$returnparts[2]);
		if($stateflag==1)
		{
			$returnpartsnew = explode(".",$returnparts[3]);
			$returnparts[3] = $returnpartsnew[0];
		}
		
		if($returnparts[1] == "golf-courses")
		{			
			//variable $returnparts replaced by zaved as on 23/Nov/2016
			echo get_bloginfo('url')  . '/golf-courses/' . $returnparts[2] . '/' .$returnparts[3] . '/' . $GRclass->parse_city_permalink($parts->city) . '/' . $course . '.html';
		}
		else
		{	
			//variable $returnparts replaced by zaved as on 23/Nov/2016
			echo get_bloginfo('url') . '/' . $returnparts[1] . '/' . $returnparts[2] . '/' . $returnparts[3] . '/' . $GRclass->parse_city_permalink($parts->city) . '/' . $course . '.html';
		}
	}
	/* get the location review by the url */
	
	function cr_location_review_url($parts, $return=0) {
		
		global $GRclass;
		
			$returnparts = explode( '/', $_SERVER['REQUEST_URI'] ); // Added by Zaved as on 23/Nov/2016
		
		if($return == 1)
			
			return get_bloginfo('url') . '/' . $returnparts[1] . '/' . cr_state_name($parts['state'],1) . '/' . $GRclass->parse_city_permalink($parts['city']) . '/' . $parts['name'] . '-review.html';
			
		else
			
			echo get_bloginfo('url') . '/' . $returnparts[1] . '/' . cr_state_name($parts['state'],1) . '/' . $GRclass->parse_city_permalink($parts['city']) . '/' . $parts['name'] . '-review.html';
	}
	
	function cr_product_cat_base_path( $cat, $SubList, $s ) { // I have changed the behaviour of method. 15/Nov/2016
	
		if( count($SubList) > 0 ) { //Sub Catgeory like drivers,digital lights
						
			$parts	= explode('/',$_SERVER['REQUEST_URI']);
			return  $parts[1] . '/' . cr_category_permalink($cat, 1);
		}
		else { // MAIN Catgeory Like Golf CLubs,Cameras
	
			return $cat;
		}
	}
	
	function cr_product_base_path($cat) {	//This Method added by Zaved to get rid of Hardcoded String as on 16/Nov/2016.		
		
		$parts	= explode('/',$_SERVER['REQUEST_URI']);
		$returnparts = explode('.html',$parts[1]);	
		return cr_category_permalink($returnparts[0], 1);
	}	
?>
