<?php 
include "wp-config.php";
define ('DBSERVER', 'localhost');
define ('DBUSER', 'root');
define ('DBPASS','');
define ('DBNAME','golf_venice_new_feb_ver1');

		global $GRclass;
		$PSite = explode(":", get_option( 'PSiteName' ) );
		$Products = $GRclass->get_product_brand_list(['brand'=>$_POST['brand']], $PSite[0] );
		//echo "<pre>";print_r($Products);echo "<pre>";
		
		if($Products) {
			
			foreach($Products as $Product) {
		
				if( $Product->product_name[0]!= 1 ) {
				
					$letters1[] = strtoupper($Product->product_name[0]);
				}
			}
			
			$let1 = array_unique($letters1);
			echo "Or choose from the bikes below";
			echo "<div id='#content-right' class='golf-club-list-items'>";			
			//$alpha = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
			$c = 0;
			echo '<div id="'. $let1[$c] .'xyz"><ul>';
			foreach($Products as $b1) {
				
			
				while(strtoupper($b1->product_name[0]) != $let1[$c] && $b1->product_name[0] != '1') {
					$c++;
					echo '</ul></div>';
					echo '<div id="'.$let1[$c].'xyz" ><ul>';
				}
				if(strtoupper($b1->product_name[0]) == $let1[$c] || $b1->product_name[0] == '1') {
					
					//echo '<li><a href="';
					//if($all == 1) //echo cr_brand_url_search($b,1);
					//print_r($_REQUEST['brandurl']);
					
					$parts	= explode('/',$_REQUEST['brandurl']);
					$catry	= explode('.', $parts[1]);
					$sri= get_bloginfo('url') . '/' . $catry[0] . '/brand/' . $b->url_safe_manufacturer_name . '.html';	
										
					
					
					echo '<li><a href="#" onclick="abcd123(\''.$b1->url_safe_product_name.'\');">'.$b1->product_name.'</a></li>';
					//else echo cr_brand_category_url_wcat_search($b1,$parts['category'],1);
					//echo '">'.$b->manufacturer_name.'</a></li>';
						
				}
			}			
			echo '</ul></div>';
			echo "Or find bike alphabetically";
			echo "<div class='golf-club-list-nav'><ul style='margin-left:10px;'>";
			//print_r($let1);
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

?>