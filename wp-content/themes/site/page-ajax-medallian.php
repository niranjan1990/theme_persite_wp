<?php /* Template Name: Ajax Medallian Widget Page */ 
	//Created by Siva on 27th Feb 2018

error_reporting(-1);
header("Content-Type: application/json; charset=UTF-8");

//$content .=json_encode($_GET);

if($_GET['snippet'] == 'on')
{
	$snippet = "on";
	$float = "float:left;";
}
else
{
	$snippet = "off";
	$float = "";
} 

$rand= rand(0,100);



if($_GET['size']=='M'){
	$agent = $_SERVER['HTTP_USER_AGENT'];
	if(preg_match('/Linux/',$agent)) $os = 'Linux';
	elseif(preg_match('/Win/',$agent)) $os = 'Windows';
	elseif(preg_match('/iPhone/',$agent)) $osm = 'iPhone';
	elseif(preg_match('/iPad/',$agent)) $osm = 'iPad';
	elseif(preg_match('/Mac/',$agent)) $os = 'Mac';
	else $os = 'UnKnown';

	if($os == "Mac" && $osm != "iPhone" && $osm != "iPad")
	{ 
		$starwidth="85";
		$unicodestarsize = "17";
		$height ="18";
		$margintop = "24";
		$marginleft = "17";
		$ratingfontsize="22";
		$ratingfontmargin="2";
		$reviewdatefontsize="11";
		$ratingmargintopscore="3";
	}
	else if($os == "Windows")
	{ 
		$starwidth="90";
		$unicodestarsize = "21.5";
		$height ="18";
		$margintop = "22";
		$marginleft = "15.5";
		$ratingfontsize="23";
		$ratingfontmargin="1";
		$reviewdatefontsize="11";
		$ratingmargintopscore="5";
	}else{
		$starwidth="90";
		$unicodestarsize = "20";
		$height ="18";
		$margintop = "23";
		$marginleft = "16";
		$ratingfontsize="23";
		$ratingfontmargin="2.5";
		$reviewdatefontsize="11";
	}
	$reviewtext="width: 203px;    margin: 6px;    height: 100px;    float: left;    text-align: center;    background-color: white;    border-radius: 5px;
width:200px !important;";
	$reviewdate="font-weight: bold;text-align: center; padding-top: 17px; font-size: 9px;color: black;";
	$reviewcontent=" line-height: 16px;
font-family:Helvetica;text-align: left;padding-left: 2px; color: black; padding-top: 1px; font-size: 11px;";
	$reviewproductscore="font-family:Helvetica;text-align: center; color: black; font-weight: bolder; font-size: ".$ratingfontsize."px;margin-left: ".$ratingfontmargin."px;margin-top:".$ratingmargintopscore."px;";
	$medallian ="background-image: url(http://".$_SERVER['HTTP_HOST']."/wp-content/themes/site/images/medallian.svg);
    background-repeat: no-repeat;    background-position: center top;   height: 108px; ".$float."text-align: center;width: 120px; ";


	$mobilestylecss="<style>
@media screen and (max-width: 600px) and (min-width: 300px){
#medallian".$rand." .star-ratings-css {
margin-top:21px !important;
}
.reviewproductscore".$rand."{
margin-left:1.5px !important;margin-top:2px !important;
}
.reviewdate".$rand."{
font-weight: bold;text-align: center; padding-top: 10px !important; font-size: 9px;color: black;
}

.reviewtext".$rand."{
 margin: 6px;    height: 100px;    float: left;    text-align: center;    background-color: white;    border-radius: 5px;
width:200px !important;
}

}
</style>";

}else if($_GET['size']=='L'){


$agent = $_SERVER['HTTP_USER_AGENT'];
if(preg_match('/Linux/',$agent)) $os = 'Linux';
elseif(preg_match('/Win/',$agent)) $os = 'Windows';
elseif(preg_match('/iPhone/',$agent)) $osm = 'iPhone';
elseif(preg_match('/iPad/',$agent)) $osm = 'iPad';
elseif(preg_match('/Mac/',$agent)) $os = 'Mac';
else $os = 'UnKnown';

if($os == "Mac" && $osm != "iPhone" && $osm != "iPad")
{ 
	$starwidth="125";
	$unicodestarsize = "24.5";
	$height =18*1.5;
	$margintop = "33";
	$marginleft = "27.5";
	$ratingfontsize=22*1.5;
	$ratingfontmargin="1";
	$reviewdatefontsize="13";
	$ratingmargintopscore="3";
}
else if($os == "Windows")
{ 
	$starwidth="126";
	$unicodestarsize = "31";
	$height =18*1.5;
	$margintop = "30";
	$marginleft = "26";
	$ratingfontsize=23*1.5;
	$ratingfontmargin="2";
	$reviewdatefontsize="13";
	$ratingmargintopscore="4";
}else{
	$starwidth="130";
	$unicodestarsize = "29";
	$height =18*1.5;
	$margintop ="34";
	$marginleft = "25.5";
	$ratingfontsize=23*1.5;
	$ratingfontmargin=1.5*1.5;
	$reviewdatefontsize="13";
}

$reviewtext="width: 203px;    margin: 6px;    height: 100px;    float: left;    text-align: center;    background-color: white;    border-radius: 5px;
width:200px !important;";
	$reviewdate="font-weight: bold;text-align: center; padding-top: 25px; font-size:11px;color: black;";
	$reviewcontent=" line-height: 16px;
font-family: Helvetica;text-align: left;padding-left: 2px; color: black; padding-top: 1px; font-size: 11px;";
	$reviewproductscore="font-family:Helvetica;text-align: center; color: black; font-weight: bolder; font-size: ".$ratingfontsize."px;margin-left: ".$ratingfontmargin."px;margin-top:".$ratingmargintopscore."px;";
	$medallian ="background-image: url(http://".$_SERVER['HTTP_HOST']."/wp-content/themes/site/images/medallian.svg);
    background-repeat: no-repeat;    background-position: center top;   height: 154px; ".$float."text-align: center;width: 180px; ";

	
	$mobilestylecss="<style>
@media screen and (max-width: 600px) and (min-width: 300px){
#medallian".$rand." .star-ratings-css {
margin-top:37px !important;
}
.reviewproductscore".$rand."{
margin-left:1.5px !important;margin-top:2px !important;
}
.reviewdate".$rand."{
font-weight: bold;text-align: center; padding-top: 19px !important; font-size: 9px;color: black;
}

.reviewtext".$rand."{
 margin: 6px;    height: 100px;    float: left;    text-align: center;    background-color: white;    border-radius: 5px;
width:200px !important;
}

}
</style>";


}else if($_GET['size']=='XL'){


$agent = $_SERVER['HTTP_USER_AGENT'];
if(preg_match('/Linux/',$agent)) $os = 'Linux';
elseif(preg_match('/Win/',$agent)) $os = 'Windows';
elseif(preg_match('/iPhone/',$agent)) $osm = 'iPhone';
elseif(preg_match('/iPad/',$agent)) $osm = 'iPad';
elseif(preg_match('/Mac/',$agent)) $os = 'Mac';
else $os = 'UnKnown';

if($os == "Mac" && $osm != "iPhone" && $osm != "iPad")
{ 
	$starwidth="175";
	$unicodestarsize = "35";
	$height =18*2;
	$margintop = "50";
	$marginleft = "33.5";
	$ratingfontsize=22*2;
	$ratingfontmargin="1";
	$reviewdatefontsize="16";
	$ratingmargintopscore="3";
}
else if($os == "Windows")
{ 
	$starwidth="176";
	$unicodestarsize = "42";
	$height =18*2;
	$margintop = "42";
	$marginleft = "33";
	$ratingfontsize=23*2;
	$ratingfontmargin="2";
	$reviewdatefontsize="16";
	$ratingmargintopscore="8";

}else{
	$starwidth="184";
	$unicodestarsize = "41";
	$height =18*2;
	$margintop = "49";
	$marginleft = "29";
	$ratingfontsize=23*2;
	$ratingfontmargin=1.5*2;
	$reviewdatefontsize="16";
	
}
	$reviewtext="width: 203px;    margin: 6px;    height: 100px;    float: left;    text-align: center;    background-color: white;    border-radius: 5px;
width:200px !important;";
	$reviewdate="font-weight: bold;text-align: center; padding-top: 32px; font-size:".$reviewdatefontsize."px;color: black;";
	$reviewcontent=" line-height: 16px;
font-family: Helvetica;text-align: left;padding-left: 2px; color: black; padding-top: 1px; font-size: 11px;";
	$reviewproductscore="font-family: Helvetica;text-align: center; color: black; font-weight: bolder; font-size: ".$ratingfontsize."px;margin-left: ".$ratingfontmargin."px;margin-top:".$ratingmargintopscore."px;";
	$medallian ="background-image: url(http://".$_SERVER['HTTP_HOST']."/wp-content/themes/site/images/medallian.svg);
    background-repeat: no-repeat;    background-position: center top;   height: 216px; ".$float."text-align: center;width: 240px; ";

	
	$mobilestylecss="<style>
@media screen and (max-width: 600px) and (min-width: 300px){
#medallian".$rand." .star-ratings-css {
line-height:30px !important;
margin-top:54px !important;
}
.reviewproductscore".$rand."{
margin-left:1.5px !important;margin-top:10px !important;
}

.reviewdate".$rand."{
font-weight: bold;text-align: center; padding-top: 33px !important; font-size: 9px;color: black;
}

.reviewtext".$rand."{
 margin: 6px;    height: 100px;    float: left;    text-align: center;    background-color: white;    border-radius: 5px;
width:200px !important;
}

}
</style>";
}


require $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php';
require $_SERVER['DOCUMENT_ROOT'] . '/wp-reviewconfig.php';

$con = mysqli_connect(DB_RHOST,DB_RUSER,DB_RPASSWORD,DB_RNAME) or die("Some error occurred during connection " . mysqli_error($con));  
$sql = "SELECT date_created,excerpt,title,rating,url,product_name from editorial_reviews where productID='".$_GET['articleid']."'";

$query=mysqli_query($con,$sql); 
$link_res=mysqli_fetch_assoc($query);

$productname=$link_res['product_name'];

$productreviewdate= date('m/d/y', strtotime($link_res['date_created']));

$productrating=$link_res['rating']*100/5;

$productscore=$link_res['rating'];

$productcontent=$link_res['excerpt'];

$productcontentlink=$link_res['url'];



$content .='
<link href="https://fonts.googleapis.com/css?family=Dosis:800" rel="stylesheet">
'.$mobilestylecss.'
<style>
@import url(https://fonts.googleapis.com/css?family=Open+Sans:400,600,700);

#medallian'.$rand.' .star-ratings-css {
	unicode-bidi: bidi-override;
    color: #c5c5c5;
    font-size: '.$unicodestarsize.'px;
    height: 20px;
    width: 140px;
    margin: 0 auto;
    position: relative;
    padding: 0;
    /* text-shadow: 0px 1px 0 #a2a2a2; */
}
#medallian'.$rand.' .star-ratings-css-top {
 height:45px;
  color: #c70700;
  padding: 0;
  position: absolute;
  z-index: 1;
  display: block;
  top: 0;
  left: 0;
  overflow: hidden;
}
#medallian'.$rand.' .star-ratings-css-bottom {
 height:45px;
  padding: 0;
  display: block;
  z-index: 0;
}
</style>
';

if($snippet == "on")
{
	$content .='<center><div id="reviewAppOO7-header" style="background-color: #ccc; width: 335px !important; height:135px !important;text-align:center; border-radius: 5px;  border: 1px solid #969595;">';
}
else
{
	$content .= "<center>";
}





if($snippet == "on")
{


$content .= '<!-- Top part --><div style="text-align: center;  color: black;  font-weight: bolder;    font-size: 19px;">
'.$productname.'</div>';
}
else
{
}

$content .= '	
	<!-- Left part -->
	<div id="medallian'.$rand.'" style="'.$medallian.'">

		<div class="reviewdate'.$rand.'" style="'.$reviewdate.'">'.$productreviewdate.'</div>

<div class="star-ratings-css" style="margin-left: '.$marginleft.'px;text-align: left;height: '.$height.'px;width: '.$starwidth.'px;margin-top: '.$margintop.'px;">
				<div class="star-ratings-css-top" style="width: '.$productrating.'%">
<span>★</span><span>★</span><span>★</span><span>★</span><span>★</span></div>
				<div class="star-ratings-css-bottom"><span>★</span><span>★</span><span>★</span><span>★</span><span>★</span></div>
		</div>


		<div class="reviewproductscore'.$rand.'" style="'.$reviewproductscore.'">'.$productscore.'</div>
		
	</div>';
	
if($snippet == "on")
{
	$content .='	
	<!-- Right Part -->
	<div id="medallian'.$rand.'" class="reviewtext'.$rand.'" style="'.$reviewtext.'">

		<div class="reviewcontent" style="'.$reviewcontent.'">'.$productcontent.'
			 <a style="color:black !important;font-weight:bold;text-decoration:underline;" href="'.$productcontentlink.'">Read more...</a>
		</div>
		
	</div>';
}
else
{
}	
	
if($snippet == "on")
{
	$content .='</div></center>';
}
else
{
	$content .= "</center>";
}
	echo 'document.write('.json_encode($content).');';

?>
