<?php
	include "wp-config.php";
	mysql_connect("localhost",'root','');
	mysql_select_db('golf_venice_new_feb_ver1');

	$val=explode('/',$_POST['brandurl']);
	$actual_link = "http://$_SERVER[HTTP_HOST]/";
	$result=$actual_link.$val[3];
	
	

	if($_POST['brandurl']!='' and $_POST['brands']!='' and $_POST['cate']!=''){
		$qry = mysql_query("select p.categoryid,p.Url_Safe_Product_Name,c.categoryid,c.url_safe_category_name  from products p left join categories c on p.categoryid=c.categoryid AND c.url_safe_category_name IN (".stripslashes($_POST['category']).") where p.Url_Safe_Product_Name='".$_POST['cate']."'");
		
		$row = mysql_fetch_array($qry);
		
		
		$url = $result.'/'.$_POST['brands'].'/'.$row['url_safe_category_name'].'/'.$_POST['cate'].'.html';

	}
	
	if($_POST['brandurl']!='' and $_POST['brands']!='' and $_POST['cate']==''){
		
		$url = $_POST['brandurl'];
	}
	if($_POST['brandurl']=='' and $_POST['brands']=='' and $_POST['cate']==''){
		
		$trailsorbike = $_POST['trailsorbike'];
		if($trailsorbike=='golf-courses'){
		
			$channelid=82;
			
			$qry1=mysql_query("select * from products where channelid ='".$channelid."' and Product_Name='".$_POST['searchkey']."'");
			$row1=mysql_fetch_array($qry1);
			$pid=$row1['productid'];
			$purlsafe=$row1['url_safe_product_name'];
			
			$qry2=mysql_query("select * from attribute_values where productid='".$pid."' and attributeid IN (69,70)");
			while($row2=mysql_fetch_array($qry2)){
				$attvalues[]=$row2['attribute_value'];
			}
			if($attvalues[1]=='AL'){$city='Alabama';}if($attvalues[1]=='AK'){$city='Alaska';}if($attvalues[1]=='AZ'){$city='Arizona';}if($attvalues[1]=='AR'){$city='Arkansas';}if($attvalues[1]=='CA'){$city='California';}if($attvalues[1]=='CO'){$city='Colorado';}if($attvalues[1]=='CT'){$city='Connecticut';}if($attvalues[1]=='DE'){$city='Deleware';}if($attvalues[1]=='DC'){$city='District-of-Columbia';}if($attvalues[1]=='FL'){$city='Florida';}if($attvalues[1]=='GA'){$city='Georgia';}if($attvalues[1]=='HI'){$city='Hawaii';}if($attvalues[1]=='ID'){$city='Idaho';}if($attvalues[1]=='IL'){$city='Illinois';}if($attvalues[1]=='IN'){$city='Indiana';}if($attvalues[1]=='IA'){$city='Iowa';}if($attvalues[1]=='KS'){$city='Kansas';}if($attvalues[1]=='KY'){$city='Kentucky';}if($attvalues[1]=='LA'){$city='Louisiana';}if($attvalues[1]=='ME'){$city='Maine';}if($attvalues[1]=='MD'){$city='Maryland';}if($attvalues[1]=='MA'){$city='Massachusetts';}if($attvalues[1]=='MI'){$city='Michigan';}if($attvalues[1]=='MN'){$city='Minnesota';}if($attvalues[1]=='MS'){$city='Mississippi';}if($attvalues[1]=='MO'){$city='Missouri';}if($attvalues[1]=='MT'){$city='Montana';}if($attvalues[1]=='NE'){$city='Nebraska';}if($attvalues[1]=='NV'){$city='Nevada';}if($attvalues[1]=='NH'){$city='New-Hampshire';}if($attvalues[1]=='NJ'){$city='New-Jersey';}if($attvalues[1]=='NM'){$city='New-Mexico';}if($attvalues[1]=='NY'){$city='New-York';}if($attvalues[1]=='NC'){$city='North-Carolina';}if($attvalues[1]=='ND'){$city='North-Dakota';}if($attvalues[1]=='OH'){$city='Ohio';}if($attvalues[1]=='OK'){$city='Oklahoma';}if($attvalues[1]=='OR'){$city='Oregon';}if($attvalues[1]=='PA'){$city='Pennsylvania';}if($attvalues[1]=='RI'){$city='Rhode-Island';}if($attvalues[1]=='SC'){$city='South-Carolina';}if($attvalues[1]=='SD'){$city='South-Dakota';}if($attvalues[1]=='TN'){$city='Tennessee';}if($attvalues[1]=='TX'){$city='Texas';}if($attvalues[1]=='UT'){$city='Utah';}if($attvalues[1]=='VT'){$city='Vermont';}if($attvalues[1]=='VA'){$city='Virginia';}if($attvalues[1]=='WA'){$city='Washington';}if($attvalues[1]=='WV'){$city='West-Virginia';}if($attvalues[1]=='WI'){$city='Wisconsin';}if($attvalues[1]=='WY'){$city='Wyoming';}if($attvalues[1]=='PR'){$city='Puerto-Rico';}if($attvalues[1]=='VI'){$city='Virgin-Islands';}

			$url = $result.'golf-courses/'.$city.'/'.$attvalues[0].'/'.$purlsafe.'.html';	
				
				
		}
		else
		{
			$channelid=7;
			$term = array("brand: ", "Brand: ", "BRAND: ");
				if (preg_match('#Brand:#i', $_POST['searchkey']) === 1) 
				{
					$trailsorbike1 = str_replace($term,"",$_POST['searchkey']);
					$url = $actual_link."".$_POST['trailsorbike']."/brand/".$trailsorbike1.".html";
				}
				else
				{
					$qry = mysql_query("select p.categoryid,p.Url_Safe_Product_Name,p.manufacturerid,p.channelid,c.categoryid,c.url_safe_category_name,m.manufacturerid,url_safe_manufacturer_name from products p left join categories c on p.categoryid=c.categoryid left join manufacturers m on p.manufacturerid=m.manufacturerid where p.channelid ='".$channelid."' and p.Product_Name='".$_POST['searchkey']."'");
				
					$row = mysql_fetch_array($qry);	
					if($trailsorbike=='')
					{
						$url = $result.'bikes'.'/'.$row['url_safe_manufacturer_name'].'/'.$row['url_safe_category_name'].'/'.$row['Url_Safe_Product_Name'].'.html';
					}
					else
					{
						$url = $result.$trailsorbike.'/'.$row['url_safe_manufacturer_name'].'/'.$row['url_safe_category_name'].'/'.$row['Url_Safe_Product_Name'].'.html';	
					}					
				}
				
			
				
		}
		
		

	

	}	

		echo $url;
?>