<?php
/**
	Plugin Name: URLs Redirects
	Plugin URI:		http://www.invenda.com
	Version: 2.0
	Description: Urls redirect logging plugin. Requires "Mysql Content Plugin" to work.
	Author: ConsumerReview
	Author URI: http://www.invenda.com
**/

require(__DIR__.'/../../../wp-config-extra.php');

include(__DIR__.'/../../../wp-reviewconfig.php');
$con = mysqli_connect(DB_RHOST,DB_RUSER,DB_RPASSWORD,DB_RNAME) or die("Some error occurred during connection " . mysqli_error($con));  


class RedirectURL {

    private $debug;

    function __construct($debug) {
        $this->debug = $debug;
    }

    function rdata($url, &$typeOfX, &$idOfX) {
        $splittedUrlByslash = explode('/', $url);
        $urlDataPartRaw = strtolower(end($splittedUrlByslash));
        $urlPostfix = 'crx.aspx';
        if(strpos($urlDataPartRaw, $urlPostfix)===false) {
            if($this->debug) {
                echo "@#no-postfix-crx.aspx,-\n";
            }
            return 0;
        }
        $urlDataPart = substr($urlDataPartRaw, 0, -strlen($urlPostfix));
        $urlData = explode('_', $urlDataPart);
		
		// Modified by Siva on 07th Aug 2017

      /*  if(count($urlData)<2){
            if($this->debug) {
                echo "@#<2,-\n";
            }
            return 0;
        }*/
        $typeOfX = $urlData[0];
        $idOfX = $urlData[1];
        return 1;
    }


    function doIt($GRclass, $type, $idOfX, $aurl,$domainName) {
			$urlparts = explode("/",$url);
			$lastpart = $urlparts[count($urlparts)-1];
			$ltr_type = substr($lastpart,0,3);
			
        switch($type) {
        case 'prd':
        case 'PRD':
        case 'rvf':
        case 'RVF':

			require(__DIR__.'/../../../wp-config-extra.php');
			//Added for trails
			$sqlchannelid = "select channelid from products where productid=$idOfX";
            $rowchannelid = $GRclass->db->get_row($sqlchannelid);
			
			//echo count($rowchannelid);
			if(count($rowchannelid)!=0)
			{
				if($rowchannelid->channelid == "84")
				{
					$sql="select p.Url_Safe_Product_Name AS product_name,c.channelid, c.category_path, (select attribute_value from attribute_values where attributeid = 69 and productid = p.productid) as city FROM products AS p, channel_categories as c WHERE c.categoryid = p.categoryid and ProductId=$idOfX"; 				
					$row = $GRclass->db->get_row($sql);
					if(empty($row)) {
						if($this->debug) {
							echo "@#no-record-found,-\n";
						}
						return null;
					}
					
					
					
					
					$categorySeparator = "/";
					$categoryData = explode($categorySeparator, $row->category_path);

					$mainCategoryName = $categoryData[0];
					$subCategoryName = str_replace(array('trails-', '-trails'), array('',''), $categoryData[1]);

					//Special Logic  trails-around-the-world
					if ($mainCategoryName=="trails-around-the-world")
					{
						$mainCategoryName = $categoryData[1];
						$subCategoryName = str_replace(array('trails-', '-trails'), array('',''), $categoryData[2]);
						$mainCategoryName = str_replace("-trails","",$mainCategoryName);
						$subCategoryName = str_replace($mainCategoryName."-","",$subCategoryName);
					}
					
					
					
					//Special Logic for us teritories
					//echo $categoryData[1];
					if ($categoryData[1]=="us-territories")
					{
						$mainCategoryName = $categoryData[2];
						$subCategoryName = str_replace(array('trails-', '-trails'), array('',''), $categoryData[2]);
					}
					
					$mainCategoryName = str_replace("-trails","",$mainCategoryName);
					
					$review = ($type=='rvf') ? '-review':'';
					//replace space, . / ' " & _
					$url =  sprintf("/%s/%s/%s/%s.html", "trails/".$mainCategoryName, $subCategoryName, str_replace(array(' ', '.', '/', ','), array('-', '-', '-', '-'), $row->city), $row->product_name);
					//die();
				}
				else if($rowchannelid->channelid == "90")
				{
					$sql="select p.Url_Safe_Product_Name AS product_name,c.channelid, c.category_path, (select attribute_value from attribute_values where attributeid = 69 and productid = p.productid) as city FROM products AS p, channel_categories as c WHERE c.categoryid = p.categoryid and ProductId=$idOfX"; 				
					$row = $GRclass->db->get_row($sql);
					if(empty($row)) {
						if($this->debug) {
							echo "@#no-record-found,-\n";
						}
						return null;
					}
					$categorySeparator = "/";
					$categoryData = explode($categorySeparator, $row->category_path);

					$mainCategoryName = $categoryData[0];
					//$subCategoryName = str_replace(array('trails-', '-trails'), array('',''), $categoryData[1]);
					$subCategoryName = $categoryData[1];

					//Special Logic  for bikeshops around-the-world
					if ($mainCategoryName=="around-the-world")
					{
						$mainCategoryName = $categoryData[1];
						//$subCategoryName = str_replace(array('trails-', '-trails'), array('',''), $categoryData[2]);
						$subCategoryName = $categoryData[2];
						$subCategoryName = str_replace($mainCategoryName."-","",$subCategoryName);
					}
				   //Special Logic for us teritories
					if ($categoryData[1]=="us-territories")
					{
						$mainCategoryName = $categoryData[0];
						$subCategoryName = str_replace(array('trails-', '-trails'), array('',''), $categoryData[2]);
					}
					$review = ($type=='rvf') ? '-review':'';
					//replace space, . / ' " & _
					$url =  sprintf("/%s/%s/%s/%s.html", "bikeshops/".$mainCategoryName, $subCategoryName, str_replace(array(' ', '.', '/', ','), array('-', '-', '-', '-'), $row->city), $row->product_name);
					//die();
				}
				else
				{
					$sql="select p.Url_Safe_Product_Name AS product_name,c.channelid, m.Url_Safe_Manufacturer_Name AS manufactorer_name, c.category_path  FROM products AS p, manufacturers AS m, channel_categories as c WHERE m.ManufacturerID=p.ManufacturerID AND c.categoryid = p.categoryid and ProductId=$idOfX"; 
					$row = $GRclass->db->get_row($sql);
					if(empty($row)) {
						if($this->debug) {
							echo "@#no-record-found,-\n";
						}
						return null;
					}
					$categorySeparator = "/";
					$categoryData = explode($categorySeparator, $row->category_path);
					if(count($categoryData)<2) {
						if($this->debug) {
							echo "@#!=2,-\n";
						}
						return null;
					}
					$mainCategoryName = $categoryData[0];
					$subCategoryName = $categoryData[1];
					$review = ($type=='rvf') ? '-review':'';
					
					//echo $DEEPCATEGORIES;
					if($DEEPCATEGORIES == '1')
					{
						$url =  sprintf("/product/%s/%s/%s.html", $row->category_path, $row->manufactorer_name, $row->product_name);
					}
					else
					{
						$url =  sprintf("/%s/%s/%s/%s.html", $mainCategoryName, $row->manufactorer_name, $subCategoryName, $row->product_name);
					}
					//die();
				}
			}
			else
			{
				$url = "/";
			}
		   break;

		case 'apl':
        case 'plr':
        case 'pls':
        case 'CAT':
        case 'cat':
        case 'PLS':
            $sql="select category_path, channelid  from channel_categories where CategoryId=$idOfX"; 
            $row = $GRclass->db->get_row($sql);
			if(count($row)!=0)
			{
				if(empty($row)) {
					if($this->debug) {
						echo "@#no-record-found,-\n";
					}
					return null;
				}
				if($row->channelid == "84")
				{
					$newpath = explode("/",$row->category_path);
					//print_r($newpath);
					if($newpath[1]=="california-trails" || $newpath[1]=="colorado-trails" || $newpath[1]=="texas-trails" || $newpath[1]=="trails-colorado")
					{		
						$secondpath=str_replace(array("trails","-"),array("",""),$newpath[1]);
						$mainCategoryName = "trails/".str_replace("-trails","",$newpath[0])."/".$secondpath;
					}
					else if($newpath[1]=="us-territories")
					{
						$mainCategoryName = "trails/".str_replace("us-territories/","",$row->category_path);
					}
					else if(str_replace("-trails","",$newpath[1])=="canada" || str_replace("-trails","",$newpath[1])=="europe" || str_replace("-trails","",$newpath[1])=="asia-and-pacific")
					{
						$mainCategoryName1 = "trails/".str_replace("-trails","",$newpath[1])."/".str_replace("trails-","",$newpath[2]);
						$mainCategoryName = str_replace(str_replace("-trails","",$newpath[1])."-","",$mainCategoryName1);
					}
					else
					{
						$mainCategoryName = "trails/".str_replace(array("trails-","-trails"),array("",""),$row->category_path);					
					}
				}
				else if($row->channelid == "90")
				{
					$newpath = explode("/",$row->category_path);
					if($newpath[1]=="california" || $newpath[1]=="colorado" || $newpath[1]=="texas") 
					{		
						$secondpath=$newpath[1];
						$mainCategoryName = "bikeshops/".$newpath[0]."/".$secondpath;
					}
					else if($newpath[1]=="canada" || $newpath[1]=="europe" || $newpath[1]=="asia-and-pacific")
					{
						$mainCategoryName1 = "bikeshops/".$newpath[1]."/".$newpath[2];
						$mainCategoryName = str_replace($newpath[1]."-","",$mainCategoryName1);
					}
					else
					{
						$mainCategoryName = "bikeshops/".$row->category_path;					
					}
				}
				else
				{
					$mainCategoryName = $row->category_path;
				}
				$mainCategoryName = str_replace("trails-around-the-world/","",$mainCategoryName);
							//For bikeshops remove around-the-world prefix
				$mainCategoryName = str_replace("around-the-world/","",$mainCategoryName);
				$url =  sprintf("/%s.html", $mainCategoryName);
				//die();
			}
			else
			{
				$url="/";
			}
		   break;
        case 'amc':
        case 'mpr':
        case 'mcl':
            $sql=" select Url_Safe_Manufacturer_Name from manufacturers where ManufacturerID=$idOfX"; 
            $row=$GRclass->db->get_row($sql);
            if(empty($row)) {
                if($this->debug) {
                    echo "@#no-record-found,-\n";
                }
                return null;
            }
            $url =  sprintf("/brands/%s.html", $row->Url_Safe_Manufacturer_Name);
            break;
			
        case 'mpl':
            $sql=" select Url_Safe_Manufacturer_Name from manufacturers where ManufacturerID=$idOfX"; 
            $row=$GRclass->db->get_row($sql);
            if(empty($row)) {
                if($this->debug) {
                    echo "@#no-record-found,-\n";
                }
                return null;
            }
            $url =  sprintf("/brands/%s.html", $row->Url_Safe_Manufacturer_Name);
            break;
			
		// Added by Siva 07th Aug 2017	
		case 'mer' :
            $sql="select Partner_Name from commerce_partners where PartnerID=$idOfX"; 
            $row=$GRclass->db->get_row($sql);
            if(empty($row)) {
                if($this->debug) {
                    echo "@#no-record-found,-\n";
                }
                return null;
            }
			$res2=str_replace(' ', '-', $row->Partner_Name);
			$more=str_replace('.', '_', $res2);
            $url =  sprintf("/hot-deals/%s.html", $more);
			break;					
			
		// Modified by Siva on 07th Aug 2017
		case 'mls' :
            $url =  sprintf("/%s.html", "brands");
			break;					
			
		// Modified by Siva on 07th Aug 2017
		case 'hotdeals' :
            $url =  sprintf("/%s.html", "hot-deals");
			break;					
			
		// Modified by Siva on 07th Aug 2017
		case 'merlist' :
            $url =  sprintf("/%s.html", "partners");
			break;					
			
		// Modified by Siva on 09th Aug 2017
		case 'partners' :
            $url =  sprintf("/%s.html", "partners");
			break;					

		// Modified by Siva on 11th Jan 2018
		case 'photoglossary' :
            $url =  sprintf("/%s", "reviews/reviewsglossary#a");
			break;					
				
		// Modified by Siva on 26th Jan 2018
		case 'latestproducts' :
            $url =  sprintf("/%s.html", "latestproducts");
			break;					
		case 'default' :
            $url =  "/";
			break;					
				
		// Modified by Siva on 23rd Oct 2017
		case 'reviews' :
            $url =  sprintf("/", "partners");
			break;					
			
        default:
			require(__DIR__.'/../../../wp-config-extra.php');
			if($SITE_NAME == "photographyreview" || $SITE_NAME == "outdoorreview" )
			{
				$pos = strpos(strtolower($redirect_to_home), $type);
				if($pos === false)
				{
					if($this->debug) 
					{
						echo "@#no-type-{$type},-\n";
					}
					return null;
				}
				else
				{
					$url = "/";
					break;
				}
			}
			else
			{
				if($this->debug) {
					echo "@#no-type-{$type},-\n";
				}
				return null;
			}
			
        }

        if($this->debug) {
            echo "$type,$url\n";
        }
        return sprintf("%s%s", $domainName, $url);
    }


    function get($GRclass, $url, $domainName) {

        if($this->rdata($url, $typeOfX, $idOfX)) {
            return $this->doIt($GRclass, $typeOfX, $idOfX, $url, $domainName);
        }
        return null;
    }
}


	function site_freview_redirects_activate() {
		
		$options = array(
		
			'enable_redirects'		=> 2,
			'redirect_domain'		=> 'reviews.golfreview.com',
			'redirect_list'			=> '',
			'redirect_list_go'		=> '',
			'redirect_test_file'	=> 'redirectlist.txt',
			'redirect_test_output'	=> 'redirectout.txt'
		);
		$indexed_options = array(
		
			'indexed_redirect_list'			=> '',
			'indexed_redirect_list_go'		=> '',
		);
				
		if(!get_option('golfreview_redirects')) {
	
			add_option('golfreview_redirects', $options);
		}
		if(!get_option('golfreview_indexed_redirects')) {
	
			add_option('golfreview_indexed_redirects', $indexed_options);
		}
	}

	function site_review_redirects_options_page() {
		
		global $wpdb;
	
		if($_POST['save_site_review_redirects']) {
			
			$new_options['enable_redirects']	= $_POST['enable_redirects'];
			$new_options['redirect_domain']		= htmlspecialchars($_POST['redirect_domain']);
			$new_options['redirect_list']		= htmlspecialchars($_POST['redirect_list']);
			$new_options['redirect_list_go']	= htmlspecialchars($_POST['redirect_list_go']);
			$new_options['redirect_test_file']	= htmlspecialchars($_POST['redirect_test_file']);
			$new_options['redirect_test_output']= htmlspecialchars($_POST['redirect_test_output']);

			//<!-- Added by siva for Already indexed link redirect -->
			$new_indexed_options['indexed_redirect_list']		= htmlspecialchars($_POST['indexed_redirect_list']);
			$new_indexed_options['indexed_redirect_list_go']	= htmlspecialchars($_POST['indexed_redirect_list_go']);

			
			update_option('golfreview_redirects',$new_options);
			//<!-- Added by siva for Already indexed link redirect -->
			update_option('golfreview_indexed_redirects',$new_indexed_options);
			
			echo '<div id="message" class="updated fade"><p>Options have been saved.</p></div>';
		}
	
		$options = get_option("golfreview_redirects");
		//<!-- Added by siva for Already indexed link redirect -->
		$indexed_options = get_option("golfreview_indexed_redirects");
?>
<style>
	textarea {width:370px;padding:2px;}
	.tbloptions td {padding:5px 0;}
</style>

	<div class="wrap">
		<div id="poststuff">	
		<h2>Urls Redirect Settings</h2>
		<form method="post" name="site_review_redirect_settings" action="?page=site-review-redirects.php">
			<table class="tbloptions">	
				<tr>
					<td width="145" valign="top"><b>Redirects:</b></td>
					<td><label><input type="radio" name="enable_redirects" value="2"<?php if($options['enable_redirects'] == 2) echo " checked='checked'"; ?> /> On</label><br />
						<label><input type="radio" name="enable_redirects" value="0"<?php if($options['enable_redirects'] == 0) echo " checked='checked'"; ?> /> Off</label><br />
						<label><input type="radio" name="enable_redirects" value="1"<?php if($options['enable_redirects'] == 1) echo " checked='checked'"; ?> /> Test</label>				
						<br /><br />
						If "Test" Mode, set input/output files. Files should be in the WordPress Plugin directory under Sparkle-Site.<br />
						Input: <input type="text" name="redirect_test_file" value="<?php echo $options['redirect_test_file']; ?>" style="width:120px;" /> &nbsp; 
						Output: <input type="text" name="redirect_test_output" value="<?php echo $options['redirect_test_output']; ?>" style="width:120px;" /> &nbsp; &nbsp;
						<?php if($options['enable_redirects'] == 1) { ?><a target="_blank" href="/testurlredirects">run test</a><?php } ?>
					</td>
				</tr>
				<tr>
					<td width="145" valign="top"><b>Domain:</b></td>
					<td><input type="text" name="redirect_domain" value="<?php echo $options['redirect_domain']; ?>" style="width:250px;" /></td>
				</tr>
				<tr>
					<td colspan="2"><b>One to One Redirect List</b><br />
					One URL per line, URLs should not include domain name, redirect will use "Domain" setting above.<br />
					<table>
						<tr><td>URL:<br /><textarea name="redirect_list" rows="10"><?php echo $options['redirect_list']; ?></textarea></td>
							<td>Redirect To:<br /><textarea name="redirect_list_go" rows="10"><?php echo $options['redirect_list_go']; ?></textarea></td>
						</tr>
					</table>
					</td>
				</tr>
			</table>
			
			<!-- Added by siva for Already indexed link redirect -->
			<table class="tbloptions">	
				<tr>
					<td colspan="2"><b>Indexed Redirect List</b><br />
					One URL per line, URLs should not include domain name, redirect will use "Domain" setting above.<br />
					<table>
						<tr><td>Indexed Category Path:<br /><textarea name="indexed_redirect_list" rows="10"><?php echo $indexed_options['indexed_redirect_list']; ?></textarea></td>
							<td>Redirect Path:<br /><textarea name="indexed_redirect_list_go" rows="10"><?php echo $indexed_options['indexed_redirect_list_go']; ?></textarea></td>
						</tr>
					</table>
					</td>
				</tr>
			</table>
			<!-- Added by siva for Already indexed link redirect -->
			
			
			
			<p class="submit"><input type="submit" name="save_site_review_redirects" value="Save Options" /></p>		
		</form>
		</div>
	</div>	
	<?php }
	
	
	
	
	
	
	
	
	
	function site_review_do_redirects() {
		
		$options = get_option('golfreview_redirects');
		$indexed_options = get_option('golfreview_indexed_redirects');
		if($options['enable_redirects']) 
		{
			
			$url = $_SERVER['REQUEST_URI'];	
			
			if($_SERVER['REQUEST_URI'] == '/testurlredirects') 
			{
				
				if($options['enable_redirects'] != 1) {
					
					echo 'Test mode disabled';
					die();
				}
				$file = @file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/wp-content/plugins/sparkle-site/' . $options['redirect_test_file']);
				if($file) {
					
					$url_array = explode("\n",trim($file));
					if($url_array) {
						
						$output = "";
						for($i=0;$i<count($url_array);$i++) {
							
							$parse_url	= site_review_sanitize_redirect_url(trim($url_array[$i]));						
							$redirect	= site_review_parse_redirect_url($parse_url,$options);
							$output		.= $url_array[$i] . "		" . $redirect . "\n";


						}					
						
						//echo $output;
						$fh = fopen($_SERVER['DOCUMENT_ROOT'] . '/wp-content/plugins/sparkle-site/' . $options['redirect_test_output'], 'w') or die("Can't find output file: ".$options['redirect_test_output']);
						fwrite($fh, $output);
						fclose($fh);
						
						echo 'URL redirects logged. <a href="/'.$options['redirect_test_output'].'">view file</a>';
					} 
					else {
						
						echo 'Input file empty: ' .$options['redirect_test_file'];
					}
				} else {
					
					echo 'No input file found: ' .$options['redirect_test_file'];
				}
				die();
			} 
			else if($options['enable_redirects'] == 2) 
			{				
				$redirect = site_review_parse_redirect_url($url,$options);
				$indexed_redirect = site_review_parse_indexed_redirect_url($url,$indexed_options);
				if($redirect) 
				{
					header("Location: $redirect", true, 301);
					die();
				}
				if($indexed_redirect) 
				{
					header("Location: $indexed_redirect", true, 301);
					die();
				}
			}
		}
	}

	function site_review_parse_redirect_url($url,$options) {
		global $GRclass;	
		require(__DIR__.'/../../../wp-config-extra.php');
		$redirect_list_array = explode("\r\n",$options['redirect_list']);
		$redirect_id = 0;

		if(in_array($url,$redirect_list_array)) 
		{
		
			for($i=0;$i<count($redirect_list_array);$i++) {
			
				if($url == $redirect_list_array[$i]) $redirect_id = $i;
			}
			$redirect_list_go_array = explode("\r\n",$options['redirect_list_go']);
			return $options['redirect_domain'] . $redirect_list_go_array[$redirect_id];
		}
        else if(substr($url,-8) == 'crx.aspx') 
		{		
            global $SITE_NAME;
            if($SITE_NAME != 'golfreview') {
                $rurl = new RedirectURL(false);
                $redurl = $rurl->get($GRclass, $url, $options['redirect_domain']);
                return ($redurl ? $redurl : '/404');
            }

			$urlparts = explode("/",$url);
			$lastpart = $urlparts[count($urlparts)-1];
			$ltr_type = substr($lastpart,0,3);
	
			switch(strtolower($ltr_type)) {
				 case 'rvf' :
				 case 'RVF' :
				 case 'prd' :
				 case 'PRD' :
				
					// get product id
					$product = explode("_",substr($lastpart,0,-8));
					$product_id = $product[1];
					$subcat_array = array('balls','bags','shoes','accessories');
					if(is_numeric($product_id)) {
					
					
					
					 $query = "SELECT a.url_safe_product_name, a.manufacturerid, c.url_safe_category_name, m.url_safe_manufacturer_name, 
						( Select attribute_value From attribute_values av Where av.productid = a.productid 
						And av.attributeid = 69) As City, c.url_safe_category_name As state, cc.category_path as country_path From products a 
						LEFT JOIN categories c ON a.categoryid = c.categoryid LEFT JOIN channel_categories cc on a.categoryid = cc.categoryid LEFT JOIN manufacturers m ON a.manufacturerid = m.manufacturerid 
						WHERE a.productid = '$product_id'";	
						
						$Product = $GRclass->db->get_row($query, object);						
						
						if($Product) {
						
							if($Product->manufacturerid == 8051) 
							{

//Add country to the course URL
                                                        if (strpos($Product->country_path,'south-atlantic/') !== false || strpos($Product->country_path,'new-england/') !== false || strpos($Product->country_path,'mid-west/') !== false || strpos($Product->country_path,'pacific/') !== false || strpos($Product->country_path,'south-central/') !== false || strpos($Product->country_path,'mountain/') !== false) {
                                                         $country = 'united-states';
                                                         }
                                                         else {
                                                         $country1 = explode("/",$Product->country_path);
                                                         $country = $country1[0];
                                                         }
														 
									 	$redirect = '/golf-courses/' . $country .'/' . $Product->state . '/' . str_replace(" ","-",$Product->City) . '/' . $Product->url_safe_product_name . '.html';


							} 
							else 
							{
							
								$cat		= (in_array($Product->url_safe_category_name,$subcat_array)) ? 'golf-equipment' : 'golf-clubs';
								if($DEEPCATEGORIES == '1')
								{
									 $redirect	= "/product/".$Product->country_path."/".str_replace(" ","-",$Product->url_safe_manufacturer_name)."/".$Product->url_safe_product_name.".html";
								}
								else
								{									
									$redirect	= '/' . $cat . '/' . $Product->url_safe_manufacturer_name . '/' . $Product->url_safe_category_name . '/' . $Product->url_safe_product_name . '.html';								
								}

							}
						}
						else
						{
							$redirect = '/';
						}
					}
					//die();
					break;
					
				case 'plr' :
				case 'pls' :
				case 'PLS' :
					
					// get category id
					$category		= explode("_",substr($lastpart,0,-8));					
					$category_id	= $category[1];
					
					$subcat_array = array('balls','bags','shoes','accessories');
					
					if(is_numeric($category_id)) {

						$get_category = "SELECT c.url_safe_category_name, cc.channelid, cc.category_path as country_path FROM categories c JOIN channel_categories cc on c.categoryid = cc.categoryid WHERE c.categoryid = '$category_id'";
						$Category	  = $GRclass->db->get_row($get_category);

						if($Category) {
					                $country2 = "";	
							if(in_array($Category->url_safe_category_name,$subcat_array))
							{
								$cat = "golf-equipment";
							}
							else
							{
								if($Category->channelid == '82')
								{
									$cat = 'golf-courses';

//Add country to the course URL
                                                        if (strpos($Category->country_path,'south-atlantic/') !== false || strpos($Category->country_path,'new-england/') !== false || strpos($Category->country_path,'mid-west/') !== false || strpos($Category->country_path,'pacific/') !== false || strpos($Category->country_path,'south-central/') !== false || strpos($Category->country_path,'mountain/') !== false) {
                                                         $country = 'united-states';
                                                         }
                                                         else {
                                                         $country1 = explode("/",$Category->country_path);
                                                         $country = $country1[0];
                                                         }
                                                                $country2 = $country . '/';

								}
								else
								{
									$cat = 'golf-clubs';
								}
							}
							
								$redirect = '/' . $cat . '/' . $country2 . uc_first_word($Category->url_safe_category_name) . '.html';	 // capitalize first letter in statename
						}
						else
						{
							$redirect = '/';
						}
					}
					//die();
					break;
					
				case 'mpr' :				
				case 'mcl' :
				case 'MCL' :
				
					// get brand id
					$brand = explode("_",substr($lastpart,0,-8));
					$brand_id = $brand[1];
					
					if(is_numeric($brand_id)) 
					{
					
						$get_brand	= "Select url_safe_manufacturer_name From manufacturers WHERE manufacturerid = '$brand_id'";
						$Brand		= $GRclass->db->get_row($get_brand);
						
						if($Brand) 
						{
							
							$redirect = '/brands/' .	$Brand->url_safe_manufacturer_name . '.html';
						}
						else
						{
							$redirect = '/';
						}

					}
					die();
					break;
					
				case 'mpl' :
				
					// get category+brand ids
					$str = explode("_",substr($lastpart,0,-8));
					$brand_id	 = $str[1];
					$category_id = $str[2];
					
					if(is_numeric($brand_id) || is_numeric($category_id)) {
						
						$get_values	= "Select c.url_safe_category_name, m.url_safe_manufacturer_name FROM categories c, manufacturers m WHERE c.categoryid = '$category_id' AND m.manufacturerid  = '$brand_id'";						
						$values		= $GRclass->db->get_row($get_values);
						$categoryname = $values->url_safe_category_name;
						if($categoryname == "bags" || $categoryname == "balls" || $categoryname == "shoes" || $categoryname == "accessories" )
						{
							$maincat = "/golf-equipment/";
						}
						else
						{
							$maincat = "/golf-clubs/";							
						}
						
						if($values) 
						{
							if($DEEPCATEGORIES == '1')
							{
								$redirect = "/brand". $maincat . $values->url_safe_category_name . '/' .	$values->url_safe_manufacturer_name . '.html';
							}
							else
							{
								$redirect = $maincat . $values->url_safe_manufacturer_name . '/' .	$values->url_safe_category_name . '.html';
							//$redirect = $options['redirect_domain'] . $values->url_safe_category_name '/brands/' . $values->url_safe_manufacturer_name . '.html';
							}
						}
						else
						{
							$redirect = '/';
						}

					}
					die();
					break;

				default:
					// Modified by Siva on 07th Aug 2017
					$redirect = "/404";
					
			}
			return $redirect;
		} 
		else 
		{
			return false;
		}
	}

	function site_review_parse_indexed_redirect_url($url,$indexed_options) 
	{
		global $GRclass;	
		require(__DIR__.'/../../../wp-config-extra.php');
		$indexed_redirect_list_array = explode("\r\n",$indexed_options['indexed_redirect_list']);
		$indexed_redirect_id = 0;

        if (count($indexed_redirect_list_array) > 1)
        {
			//print_r($indexed_redirect_list_array);
			for($i=0;$i<count($indexed_redirect_list_array);$i++) 
			{
				if($indexed_redirect_list_array[$i] != "")
				{
					if(strpos($url,$indexed_redirect_list_array[$i]) !== false)
					{
						$indexed_redirect_id = $i;
						$indexed_redirect_list_go_array = explode("\r\n",$indexed_options['indexed_redirect_list_go']);
						return $options['redirect_domain'] . str_replace($indexed_redirect_list_array[$i], $indexed_redirect_list_go_array[$indexed_redirect_id], $url);
					}
				}
			}
        }
		if($indexed_redirect_id == 0)
		{
			return false;
		}
	}

	function site_review_sanitize_redirect_url($url) {
		
		// sanitize url before processing
		// remove http:// from url
		if(strpos($url,'http://')) {
			
			$urlparts = explode("/",$url);
			for($j=0;$j<count($urlparts);$j++) {
				
				if($j > 2) $new_url[] = $urlparts[$j];
			}
			$parse_url = "/".implode("/",$new_url);
		} 
		else
		
		// remove domain name from url
		if(strpos($url,strtolower("golfreview.com"))) {
			$urlparts = explode("/",$url);
			for($j=0;$j<count($urlparts);$j++) {
				
				if($j > 0) $new_url[] = $urlparts[$j];
			}
			
			$parse_url = "/".implode("/",$new_url);
			
		} else {
			
			$parse_url = $url;
		}
		return $parse_url;
	}

	function site_review_redirects_add_options_page() {
		
		add_options_page( 'Urls Redirects', 'Urls Redirects', 'manage_options', basename(__FILE__), 'site_review_redirects_options_page');
	}

	// if state name is seperated with - 
	// then split the state name by - 
	// capitalize the first words of state name
	// append - back in state name

	function uc_first_word($str) {
		
		if(strpos($str, "-") == true) {
			
			$exp_state = explode("-", $str);
			foreach ($exp_state as $key => $value)
			
				$exp_state[$key] = ucfirst($exp_state[$key]);
				$uc_state = implode("-",$exp_state);
		}
		else {
			
			$uc_state = ucfirst($str);
		}
		return $uc_state;
	}

	add_action('admin_menu', 'site_review_redirects_add_options_page');
	add_filter('init', 'site_review_do_redirects');
	register_activation_hook( __FILE__, 'site_freview_redirects_activate');
?>
