<?php
error_reporting(E_ALL ^ E_DEPRECATED);
require_once('wp-reviewconfig.php');
/************************************************
	The Search PHP File
************************************************/


/************************************************
	MySQL Connect
************************************************/

//	Connection
global $tutorial_db;

$tutorial_db = new mysqli();
$tutorial_db->connect(DB_RHOST, DB_RUSER, DB_RPASSWORD, DB_RNAME);
$tutorial_db->set_charset("utf8");
mysql_connect(DB_RHOST,DB_RUSER,DB_RPASSWORD);
mysql_select_db(DB_RNAME);

//	Check Connection
if ($tutorial_db->connect_errno) {
    printf("Connect failed: %s\n", $tutorial_db->connect_error);
    exit();
}

/************************************************
	Search Functionality
************************************************/
$search_string = preg_replace("/[^A-Za-z0-9]/", " ", $_POST['query']);
$url_string = $_POST['url'];
// Define Output HTML Formating
if($url_string=='golf-courses'){
$html = '';
$html .= '<li class="result">';
$html .= '<a href="urlString">';
$html .= '<h3 style="font-size:13px;   margin-left: 5px;  line-height: 1em;    margin-top: 7px;">functionString</h3>';
$html .= '<h3 style="font-size:10px;   margin-left: 5px;  line-height: 1em;">nameString</h3>';
$html .= '<h4 style="float:right">typeString</h4>';
$html .= '</a>';
$html .= '</li>';
}else{
$html = '';
$html .= '<li class="result">';
$html .= '<a href="urlString">';
$html .= '<h3 style="font-size:13px ;   margin-top: 7px;    margin-left: 5px;">nameString</h3>';
//$html .= '<h3 style="font-size:13px">functionString</h3>';
$html .= '<h4 style="float:right">typeString</h4>';
$html .= '</a>';
$html .= '</li>';

}


//$url = explode( '/', $_SERVER['REQUEST_URI']);
//$url1 = explode( '.', $url[1]);
//echo $url1[0].$url1[1];
// Get Search

//echo "URL_STRING: ".$url_string;
$search_string = $tutorial_db->real_escape_string($search_string);

// Check Length More Than One Character
if (strlen($search_string) >= 1 && $search_string !== ' ') {
	// Build Query
	//$query = 'SELECT * FROM search WHERE search_term LIKE "%'.$search_string.'%" OR name LIKE "%'.$search_string.'%"';
	if($url_string == "golf-courses")
	{
		
		$query = 'SELECT * FROM search WHERE search_term LIKE "%'.$search_string.'%" and manufacturer_name LIKE "%golf-co%"  Limit 20';
	}
	else if($url_string == "" || $url_string == 'user-login' || $url_string == 'user-profile' || $url_string == 'user-profile-1' || $url_string == 'user-profile-2' || $url_string == 'user-profile-3' || $url_string == 'user-registration')
	{
		$query = 'SELECT * FROM search WHERE search_term LIKE "%'.$search_string.'%" Limit 10';
		//$url_string = "golf-clubs";
	}
	else
	{
		$query = 'SELECT * FROM search WHERE search_term LIKE "%'.$search_string.'%" and (category_path LIKE "%'.$url_string.'%" or url LIKE "%brand%" ) Limit 10';		
	}
	// Do Search
	$result = $tutorial_db->query($query);
	while($results = $result->fetch_array()) {
		$result_array[] = $results;
	}

	// Check If We Have Results
	if (isset($result_array)) {
		foreach ($result_array as $result) {

			// Format Output Strings And Hightlight Matches
		//	$display_function = preg_replace("/".$search_string."/i", "<b class='highlight'>".$search_string."</b>", $result['search_term']);
		
			if($url_string=='golf-courses'){
				$display_function = $result['search_term'];
				$pieces = explode(",",$result['search_term']);
				$display_function=$pieces[0];
			}else{
				$display_function = $result['search_term'];	
				
			}
		//	$display_name = preg_replace("/".$search_string."/i", "<b class='highlight'>".$search_string."</b>", $result['display_term']);
			if($url_string=='golf-courses'){
				$pieces = explode(",",$result['display_term']);
				
				if ( ! isset($pieces[1])) {
				   $pieces[1] = null;
				}
				if ( ! isset($pieces[2])) {
				   $pieces[2] = null;
				}
				$pieces=$pieces[1].','.$pieces[2];
				$display_name = $pieces;
			}
			else{
				$display_name = $result['display_term'];
			}
			
			
/* for filtering in homepage */
			
				if($url_string == '' || $url_string == 'user-login' || $url_string == 'user-profile' || $url_string == 'user-profile-1' || $url_string == 'user-profile-2' || $url_string == 'user-profile-3'  || $url_string == 'user-registration')
				{
					if($result['channelid']=='82')
					{
						$display_url = '/golf-courses/'.$result['url'];
					}
					else
					{
						$brandurl = explode('/',$result['url']);
						if($brandurl[0] =="brand")
						{
							$display_url = '/golf-clubs/'.$result['url'];
						}
						else
						{
							$newurl = explode('/',$result['category_path']);
							$display_url = $newurl[0].'/'.$result['url'];
						}
					}
				}
				else
				{
					$display_url = '/'.$url_string.'/'.$result['url'];
				}
/* for filtering in homepage */
				
				
				
				
				
			$display_type = "";

			// Insert Name
			$output = str_replace('nameString', $display_name, $html);

			// Insert Function
			$output = str_replace('functionString', $display_function, $output);

			// Insert URL
			$output = str_replace('urlString', $display_url, $output);

			$output = str_replace('typeString', $display_type, $output);
			// Output
			echo($output);
		}
	}else{

		// Format No Results Output
		$output = str_replace('urlString', 'javascript:void(0);', $html);
		$output = str_replace('nameString', '<b>No Results Found.</b>', $output);
		$output = str_replace('functionString', 'Sorry :(', $output);
		$output = str_replace('typeString', 'Sorry :(', $output);

		// Output
		echo($output);
	}
}


/*
// Build Function List (Insert All Functions Into DB - From PHP)

// Compile Functions Array
$functions = get_defined_functions();
$functions = $functions['internal'];

// Loop, Format and Insert
foreach ($functions as $function) {
	$function_name = str_replace("_", " ", $function);
	$function_name = ucwords($function_name);

	$query = '';
	$query = 'INSERT INTO search SET id = "", function = "'.$function.'", name = "'.$function_name.'"';

	$tutorial_db->query($query);
}
*/
?>