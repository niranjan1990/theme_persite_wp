<?php /* Template Name: User Profile Page */ ?>
<?php 
session_start();
require_once(__DIR__.'/../../../wp-config.php');
if(isset($_COOKIE['bb_userid']))
{
	
}
else
{
	header('Location: /');
}
get_header('noad'); 

$con = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME) or die("Some error occurred during connection " . mysqli_error($con));  

?> 

<!-- New Ajax Search -->

		<!-- Load CSS -->
		<link href="/ajaxsearch/style/style.css" rel="stylesheet" type="text/css" />
		<!-- Load Fonts -->
		<!-- Load jQuery library -->
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
		<!-- Load custom js -->
		<script type="text/javascript" src="/ajaxsearch/scripts/custom.js"></script>

		<script type="text/javascript" src="/ajaxsearch1/scripts/custom.js"></script>

<script>
jQuery(document).ready(function(){
    jQuery('#hideshow').live('click', function(event) {        
         jQuery('#autosearchbar').toggle('show');
		 jQuery("body").removeClass("shiftnav-open");
		 jQuery(".shiftnav-left-edge").removeClass("shiftnav-open-target");
		 
    });
});
</script>



<!-- New Ajax Search -->

<?php
/**
 * Timezones list with GMT offset
 *
 * @return array
 * @link http://stackoverflow.com/a/9328760
 */
function tz_list() {
  $zones_array = array();
  $timestamp = time();
  foreach(timezone_identifiers_list() as $key => $zone) {
    date_default_timezone_set($zone);
    $zones_array[$key]['zone'] = $zone;
    $zones_array[$key]['diff_from_GMT'] = 'UTC/GMT ' . date('P', $timestamp);
  }
  return $zones_array;
}



$countries = array("United States" => "United States",
"Afghanistan" => "Afghanistan",
"Åland Islands" => "Åland Islands",
"Albania" => "Albania",
"Algeria" => "Algeria",
"American Samoa" => "American Samoa",
"Andorra" => "Andorra",
"Angola" => "Angola",
"Anguilla" => "Anguilla",
"Antarctica" => "Antarctica",
"Antigua and Barbuda" => "Antigua and Barbuda",
"Argentina" => "Argentina",
"Armenia" => "Armenia",
"Aruba" => "Aruba",
"Australia" => "Australia",
"Austria" => "Austria",
"Azerbaijan" => "Azerbaijan",
"Bahamas" => "Bahamas",
"Bahrain" => "Bahrain",
"Bangladesh" => "Bangladesh",
"Barbados" => "Barbados",
"Belarus" => "Belarus",
"Belgium" => "Belgium",
"Belize" => "Belize",
"Benin" => "Benin",
"Bermuda" => "Bermuda",
"Bhutan" => "Bhutan",
"Bolivia" => "Bolivia",
"Bosnia and Herzegovina" => "Bosnia and Herzegovina",
"Botswana" => "Botswana",
"Bouvet Island" => "Bouvet Island",
"Brazil" => "Brazil",
"British Indian Ocean Territory" => "British Indian Ocean Territory",
"Brunei Darussalam" => "Brunei Darussalam",
"Bulgaria" => "Bulgaria",
"Burkina Faso" => "Burkina Faso",
"Burundi" => "Burundi",
"Cambodia" => "Cambodia",
"Cameroon" => "Cameroon",
"Canada" => "Canada",
"Cape Verde" => "Cape Verde",
"Cayman Islands" => "Cayman Islands",
"Central African Republic" => "Central African Republic",
"Chad" => "Chad",
"Chile" => "Chile",
"China" => "China",
"Christmas Island" => "Christmas Island",
"Cocos (Keeling) Islands" => "Cocos (Keeling) Islands",
"Colombia" => "Colombia",
"Comoros" => "Comoros",
"Congo" => "Congo",
"Congo, The Democratic Republic of The" => "Congo, The Democratic Republic of The",
"Cook Islands" => "Cook Islands",
"Costa Rica" => "Costa Rica",
"Cote D'ivoire" => "Cote D'ivoire",
"Croatia" => "Croatia",
"Cuba" => "Cuba",
"Cyprus" => "Cyprus",
"Czech Republic" => "Czech Republic",
"Denmark" => "Denmark",
"Djibouti" => "Djibouti",
"Dominica" => "Dominica",
"Dominican Republic" => "Dominican Republic",
"Ecuador" => "Ecuador",
"Egypt" => "Egypt",
"El Salvador" => "El Salvador",
"Equatorial Guinea" => "Equatorial Guinea",
"Eritrea" => "Eritrea",
"Estonia" => "Estonia",
"Ethiopia" => "Ethiopia",
"Falkland Islands (Malvinas)" => "Falkland Islands (Malvinas)",
"Faroe Islands" => "Faroe Islands",
"Fiji" => "Fiji",
"Finland" => "Finland",
"France" => "France",
"French Guiana" => "French Guiana",
"French Polynesia" => "French Polynesia",
"French Southern Territories" => "French Southern Territories",
"Gabon" => "Gabon",
"Gambia" => "Gambia",
"Georgia" => "Georgia",
"Germany" => "Germany",
"Ghana" => "Ghana",
"Gibraltar" => "Gibraltar",
"Greece" => "Greece",
"Greenland" => "Greenland",
"Grenada" => "Grenada",
"Guadeloupe" => "Guadeloupe",
"Guam" => "Guam",
"Guatemala" => "Guatemala",
"Guernsey" => "Guernsey",
"Guinea" => "Guinea",
"Guinea-bissau" => "Guinea-bissau",
"Guyana" => "Guyana",
"Haiti" => "Haiti",
"Heard Island and Mcdonald Islands" => "Heard Island and Mcdonald Islands",
"Holy See (Vatican City State)" => "Holy See (Vatican City State)",
"Honduras" => "Honduras",
"Hong Kong" => "Hong Kong",
"Hungary" => "Hungary",
"Iceland" => "Iceland",
"India" => "India",
"Indonesia" => "Indonesia",
"Iran, Islamic Republic of" => "Iran, Islamic Republic of",
"Iraq" => "Iraq",
"Ireland" => "Ireland",
"Isle of Man" => "Isle of Man",
"Israel" => "Israel",
"Italy" => "Italy",
"Jamaica" => "Jamaica",
"Japan" => "Japan",
"Jersey" => "Jersey",
"Jordan" => "Jordan",
"Kazakhstan" => "Kazakhstan",
"Kenya" => "Kenya",
"Kiribati" => "Kiribati",
"Korea, Democratic People's Republic of" => "Korea, Democratic People's Republic of",
"Korea, Republic of" => "Korea, Republic of",
"Kuwait" => "Kuwait",
"Kyrgyzstan" => "Kyrgyzstan",
"Lao People's Democratic Republic" => "Lao People's Democratic Republic",
"Latvia" => "Latvia",
"Lebanon" => "Lebanon",
"Lesotho" => "Lesotho",
"Liberia" => "Liberia",
"Libyan Arab Jamahiriya" => "Libyan Arab Jamahiriya",
"Liechtenstein" => "Liechtenstein",
"Lithuania" => "Lithuania",
"Luxembourg" => "Luxembourg",
"Macao" => "Macao",
"Macedonia, The Former Yugoslav Republic of" => "Macedonia, The Former Yugoslav Republic of",
"Madagascar" => "Madagascar",
"Malawi" => "Malawi",
"Malaysia" => "Malaysia",
"Maldives" => "Maldives",
"Mali" => "Mali",
"Malta" => "Malta",
"Marshall Islands" => "Marshall Islands",
"Martinique" => "Martinique",
"Mauritania" => "Mauritania",
"Mauritius" => "Mauritius",
"Mayotte" => "Mayotte",
"Mexico" => "Mexico",
"Micronesia, Federated States of" => "Micronesia, Federated States of",
"Moldova, Republic of" => "Moldova, Republic of",
"Monaco" => "Monaco",
"Mongolia" => "Mongolia",
"Montenegro" => "Montenegro",
"Montserrat" => "Montserrat",
"Morocco" => "Morocco",
"Mozambique" => "Mozambique",
"Myanmar" => "Myanmar",
"Namibia" => "Namibia",
"Nauru" => "Nauru",
"Nepal" => "Nepal",
"Netherlands" => "Netherlands",
"Netherlands Antilles" => "Netherlands Antilles",
"New Caledonia" => "New Caledonia",
"New Zealand" => "New Zealand",
"Nicaragua" => "Nicaragua",
"Niger" => "Niger",
"Nigeria" => "Nigeria",
"Niue" => "Niue",
"Norfolk Island" => "Norfolk Island",
"Northern Mariana Islands" => "Northern Mariana Islands",
"Norway" => "Norway",
"Oman" => "Oman",
"Pakistan" => "Pakistan",
"Palau" => "Palau",
"Palestinian Territory, Occupied" => "Palestinian Territory, Occupied",
"Panama" => "Panama",
"Papua New Guinea" => "Papua New Guinea",
"Paraguay" => "Paraguay",
"Peru" => "Peru",
"Philippines" => "Philippines",
"Pitcairn" => "Pitcairn",
"Poland" => "Poland",
"Portugal" => "Portugal",
"Puerto Rico" => "Puerto Rico",
"Qatar" => "Qatar",
"Reunion" => "Reunion",
"Romania" => "Romania",
"Russian Federation" => "Russian Federation",
"Rwanda" => "Rwanda",
"Saint Helena" => "Saint Helena",
"Saint Kitts and Nevis" => "Saint Kitts and Nevis",
"Saint Lucia" => "Saint Lucia",
"Saint Pierre and Miquelon" => "Saint Pierre and Miquelon",
"Saint Vincent and The Grenadines" => "Saint Vincent and The Grenadines",
"Samoa" => "Samoa",
"San Marino" => "San Marino",
"Sao Tome and Principe" => "Sao Tome and Principe",
"Saudi Arabia" => "Saudi Arabia",
"Senegal" => "Senegal",
"Serbia" => "Serbia",
"Seychelles" => "Seychelles",
"Sierra Leone" => "Sierra Leone",
"Singapore" => "Singapore",
"Slovakia" => "Slovakia",
"Slovenia" => "Slovenia",
"Solomon Islands" => "Solomon Islands",
"Somalia" => "Somalia",
"South Africa" => "South Africa",
"South Georgia and The South Sandwich Islands" => "South Georgia and The South Sandwich Islands",
"Spain" => "Spain",
"Sri Lanka" => "Sri Lanka",
"Sudan" => "Sudan",
"Suriname" => "Suriname",
"Svalbard and Jan Mayen" => "Svalbard and Jan Mayen",
"Swaziland" => "Swaziland",
"Sweden" => "Sweden",
"Switzerland" => "Switzerland",
"Syrian Arab Republic" => "Syrian Arab Republic",
"Taiwan, Province of China" => "Taiwan, Province of China",
"Tajikistan" => "Tajikistan",
"Tanzania, United Republic of" => "Tanzania, United Republic of",
"Thailand" => "Thailand",
"Timor-leste" => "Timor-leste",
"Togo" => "Togo",
"Tokelau" => "Tokelau",
"Tonga" => "Tonga",
"Trinidad and Tobago" => "Trinidad and Tobago",
"Tunisia" => "Tunisia",
"Turkey" => "Turkey",
"Turkmenistan" => "Turkmenistan",
"Turks and Caicos Islands" => "Turks and Caicos Islands",
"Tuvalu" => "Tuvalu",
"Uganda" => "Uganda",
"Ukraine" => "Ukraine",
"United Arab Emirates" => "United Arab Emirates",
"United Kingdom" => "United Kingdom",
"United States Minor Outlying Islands" => "United States Minor Outlying Islands",
"Uruguay" => "Uruguay",
"Uzbekistan" => "Uzbekistan",
"Vanuatu" => "Vanuatu",
"Venezuela" => "Venezuela",
"Viet Nam" => "Viet Nam",
"Virgin Islands, British" => "Virgin Islands, British",
"Virgin Islands, U.S." => "Virgin Islands, U.S.",
"Wallis and Futuna" => "Wallis and Futuna",
"Western Sahara" => "Western Sahara",
"Yemen" => "Yemen",
"Zambia" => "Zambia",
"Zimbabwe" => "Zimbabwe");

?>




<?php 
//print_r($_SESSION);
if(isset($_GET['logout']))
{
	unset($_SESSION['checkuser']);
	header('location: /user-profile.html');
}
if(isset($_SESSION['checkuser']))
{
	
}
else
{
	include('checkuser.php'); 	
}
?>



<script>
$(document).ready(function() {
    $("input:text").focus(function() { $(this).select(); } );
});
</script>



 <style>
 .leftalign
{
	text-align:left!important;
	margin-left:80px;
}
@media screen and (max-width: 601px)
{
	.leftalign
	{
		text-align:left!important;
		margin-left: 5px;
	}
}


.change::-webkit-input-placeholder {
    /* WebKit, Blink, Edge */
    color: black;
}
.change:-moz-placeholder {
    /* Mozilla Firefox 4 to 18 */
    color: black;
    opacity: 1;
}
.change::-moz-placeholder {
    /* Mozilla Firefox 19+ */
    color: black;
    opacity: 1;
}
.change:-ms-input-placeholder {
    /* Internet Explorer 10-11 */
    color: black;
}

.labelheading{
    color: #000000;
display:block;
text-align:center;
font-color:rgba(65,117,5,1);
font-size:24px;
font-weight:bold;

}
.labeltext{
    color: #000000;
display:block;
text-align:center;
font-color:rgba(65,117,5,1);
font-size:14px


}
.submit{
	margin-top: 10px;
	font-family: Lucida Sans, sans-serif !important;
	font-size: 16px;
	width:209px;
height:44px;
	background-image: linear-gradient(-180deg, #167c00 14%, #167c00 100%);
	border-radius: 8px;
	color:#fff;
}
.submitblock{
margin-top: 10px;
	font-family: Lucida Sans, sans-serif !important;
	font-size: 16px;
	width:209px;
height:44px;
	border-radius: 8px;
	color:#fff;
	cursor:not-allowed;
	 background-image: linear-gradient(-180deg, #9B9A9B 14%, #9B9A9B 100%);
}

.form-group{
	 padding:5px ! important;
   margin-bottom: 0rem ! important;
}
      
.block {
  height: 550px; 
}  
.block2 {
  min-height: 160px;
} 
.center {
  position: absolute;
/*  top: 0;
  bottom: 0; */
  left: 0;
  right: 0;
  margin: auto;  
}

.input_text{
	    margin-bottom: 10px;
	font-family: Lucida Sans, sans-serif !important;
	padding-left:10px;
	 width: 309px;
  height: 40px;
  border-radius: 8px;
  background-color: #f7f8fa;
  border: solid 1px #979797;	
font-size: 13px;
    color: #000000;
	
}

#input_container {
    position:relative;
    padding:0;
    margin:0;
}
.label{
	margin-top:10px;
}
@media screen  and (max-width: 601px) {
.input_text{
	   width:100%;
	    margin-bottom: 0px;
}
.labeltext {
	font-size:13px !important;
}
label{
margin-bottom:0px !important;
	margin-top:0px;
}
.form-group {
    padding:5px ! important;
   margin-bottom: 0rem ! important;
}
#email,#password{
	width:100% ! important;
}
}
</style>
<div id="content-left" style="background-color:#F0EFDA;" class="col-md-12">
	 <div class="inner"> 
	 <div class="main-content">
<div class='col-md-3'>
</div>

 <div class='container col-sm-8 col-md-6 block'>
        
        <div class='block2  center'>
					<div class="form-header">
					<div class="col-sm-8 col-md-12">  
					<label style="width:100%;margin-bottom:0px !important;">
						<a name="skip" style="margin-right: 0px !important;float:right;margin:10px;display:block;text-align:center;color: #3b7adb;text-decoration: underline;font-size:13px; cursor: pointer;" id="skip" onclick="location.href='user-profile-1.html';">Skip</a>
						<a name="skip" style="margin-left: 0px !important;float:left;margin:10px;display:block;text-align:center;color: #3b7adb;text-decoration: underline;font-size:13px; cursor: pointer;" id="skip" onclick="location.href='/';">Close</a>
					</label>
					</div>
					</div>
			 <label class="labelheading" style="line-height: 23px;">Welcome <?php echo $_COOKIE['bb_username']; ?></label></br>
           <label class="labeltext">Please tell us a little about yourself</label>
		   
		   
		   <?php 
		   if(isset($_POST['submit'])) 
			{
				$user_id=$_POST['userid'];
				$user_name=$_POST['username'];
				$country=$_POST['country'];
				$zip=$_POST['zipcode'];
				$gender=$_POST['gender'];
				$timezone=$_POST['timezone'];	
				if(isset($_POST['daylight_time']))
				{
					$daylight_time='1';	
				}
				else
				{
					$daylight_time='0';	
				}
					
					
				$qry = mysqli_query($con, "select * from user_profile where user_id = '".$user_id."'");
				
				$count = mysqli_num_rows($qry);
				if($count == 0)
				{
					$query1 = mysqli_query($con,"insert into user_profile(user_id,user_name,country,zip,gender,time_zone,daylight_time)values('".$user_id."','".$user_name."','".$country."','".$zip."','".$gender."','".$timezone."','".$daylight_time."')");
					$message = "You have registered successfully!";	
					if($query1)
					{
						header("location: /user-profile-1.html");				
					}
					
				}
				else
				{
					$query2 = mysqli_query($con, "UPDATE user_profile SET `user_name` = '".$user_name."', `country` = '".$country."', `zip` = '".$zip."', `gender` = '".$gender."', `time_zone` = '".$timezone."', `daylight_time` = '".$daylight_time."' WHERE user_id = '".$user_id."'");
					
					$message = "You have registered successfully!";	
					if($query2)
					{
						header("location: /user-profile-1.html");				
					}					
				}
			}		   
		   else
		   {
			   
		   }

		   
		   		$result = mysqli_query($con, "select * from user_profile where user_id = '".$_COOKIE['bb_userid']."'");
				//echo "select * from user_profile where user_id = '".$_COOKIE['userid']."'";
				$row=mysqli_fetch_assoc($result);

		   ?>
				<form action='' method='post' class="form-horizontal" role="form" align="center">
					<div class="form-header"> 
						<div class="col-sm-8 col-md-12">  
							<span id='successerrormessage'></span>
						</div>
					</div>
					
					<div class="form-group">
						<div id="input_container" class="col-sm-8 col-md-12">
							
							<input type="hidden" name="userid" value="<?php echo $_COOKIE['bb_userid']; ?>">
							<input type="text" name="username" id="username" placeholder="Name" required="true" class="input_text" value="<?php echo $row['user_name'];?>"/>
							
						</div>
					</div>
					<div class="form-group leftalign" >
						<div id="input_container" class="col-sm-8 col-md-12">
						
							
								<input type="radio" style="  border: 0px;   " name="gender" id="male" value="male" <?php if ($row['gender']=="male") { echo "checked"; } ?>>
							   <label for="male" style="font-size: 13px;">Male</label>&nbsp;&nbsp;&nbsp;&nbsp;
        						  <input type="radio" style="  border: 0px;   " name="gender" id="female" value="female" <?php if ($row['gender']=="female") { echo "checked"; } ?>>

							   <label for="female" style="font-size: 13px;">Female</label>
							
						</div>
					</div>

					<div class="form-group" >
						<div id="input_container" class="col-sm-8 col-md-12">
							<select class="input_text" name="country" id="country">
							    <?php if($row['country']!=''){ ?>
								 <option value="<?php echo $row['country'] ?>" selected="selected"><?php echo $row['country']; ?></option> 
								<?php } ?>
							<?php
							foreach($countries as $key => $value) {
								if($row['country'] == $value)
								{
									
								}
								else
								{
									
								
							?>
							<option value="<?= $key ?>" title="<?= htmlspecialchars($value) ?>"><?= htmlspecialchars($value) ?></option>
							<?php
							}}
							?>


							</select>
							
							
						</div>
					</div>
					<div class="form-group" >
						<div id="input_container" class="col-sm-8 col-md-12">
							<input class="input_text" name="zipcode" id="zipcode" placeholder="ZipCode" value="<?php echo $row['zip'];?>">
						</div>
					</div> 
						
					
					<div class="form-group" >
						<div id="input_container" class="col-sm-8 col-md-12">
							
							<select class="input_text" name="timezone" id="timezone" required>
							  <option value="">Time Zone</option>
							    <?php if($row['time_zone']!=''){ ?>
								 <option value="<?php echo $row['time_zone'] ?>" selected="selected"><?php echo $row['time_zone']; ?></option> 
								<?php } ?>
								<?php foreach(tz_list() as $t) { 
								if ($row['time_zone']==$t['zone']." - ".$t['diff_from_GMT'])
								{
									
								}
								else
								{
									
								?>									
								  <option value="<?php print $t['zone']." - ".$t['diff_from_GMT'] ?>"><?php print $t['zone']." - ".$t['diff_from_GMT'] ?>
								  </option>
								<?php 
								}
								}
								?>
							</select>
						</div>
					</div> 
					<div class="form-group">
						<div id="input_container" class="col-sm-8 col-md-12">
							
							
     								<label for="checkid" class="labeltext leftalign" style="word-wrap:break-word">
      								  <input id="checkid" style="    width: 5%;    border: 0px;    height: 1em;"  type="checkbox" name="daylight_time" <?php if ($row['daylight_time']=="1") { echo "checked"; } ?>/>Use Dayight Savings Time
     								</label>
														
						</div>
					</div>
					<div class="form-group" > 
						<div class="col-sm-8 col-md-12">  
							<input type="submit" class="submit" name="submit" id="submit" value="Next" />
							
						</div>
					</div>  
				</form>

		</div>
</div>
<div class='col-md-3'>
</div>




 </div>
	 </div>
</div>


	
<?php   get_footer(); ?>
